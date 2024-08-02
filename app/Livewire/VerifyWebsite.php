<?php
// app/Livewire/VerifyWebsite.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Site;
use App\Models\Badge;

class VerifyWebsite extends Component
{
    public $verificationCode;
    public $domain;
    public $site;
    public $isVerified;

    public function mount()
    {
        $this->domain = $this->normalizeDomain($this->domain);
        $this->site = Site::where('user_id', Auth::id())->first();

        if ($this->site) {
            Log::info('Site found', ['site' => $this->site]);
            $this->isVerified = $this->site->is_verified;
        } else {
            $this->site = new Site([
                'user_id' => Auth::id(),
                'domain' => $this->domain,
                'is_verified' => false,
            ]);
            $this->isVerified = false;
            Log::info('New site instance created', ['site' => $this->site]);
        }

        // Generate and store the verification code in the session
        if (Session::has('verification_code')) {
            $this->verificationCode = Session::get('verification_code');
        } else {
            $this->verificationCode = $this->generateVerificationCode();
            Session::put('verification_code', $this->verificationCode);
        }
    }

    public function removeSite()
    {
        $this->site->delete();
        Log::info('Site removed', ['site' => $this->site]);
        $this->site = new Site([
            'user_id' => Auth::id(),
            'domain' => $this->domain,
            'is_verified' => false,
        ]);
        $this->isVerified = false;
    }

    public function verify()
    {
        // Retrieve the verification code from the session
        $this->verificationCode = Session::get('verification_code');
        Log::info('Starting verification process', ['domain' => $this->domain, 'verificationCode' => $this->verificationCode]);

        $this->isVerified = $this->verifyMetaTag($this->domain, $this->verificationCode);

        Log::info('Verification result', ['isVerified' => $this->isVerified]);

        if ($this->isVerified) {
            $this->site->is_verified = true;
            $this->site->user_id = Auth::id();
            $this->site->domain = $this->domain;
            $this->site->save();
            Log::info('Site verification status updated', ['site' => $this->site]);
            // Create a badge for the user
            if (!$this->site->user->badges->contains(5)) {
                $this->giveBadge(Auth::id(), 5);
            }

        } else {
            Log::warning('Verification failed', ['domain' => $this->domain, 'verificationCode' => $this->verificationCode]);
        }
    }

    private function verifyMetaTag($domain, $verificationCode)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $domain)) {
            $domain = "http://" . $domain;
        }

        $htmlContent = @file_get_contents($domain);

        if ($htmlContent === FALSE) {
            Log::error('Failed to fetch HTML content', ['domain' => $domain]);
            return false;
        }

        $metaTag = '<meta name="postaverse-web-verification" content="' . $verificationCode . '">';
        $isMetaTagPresent = strpos($htmlContent, $metaTag) !== false;

        Log::info('Meta tag verification', ['metaTag' => $metaTag, 'isMetaTagPresent' => $isMetaTagPresent]);

        return $isMetaTagPresent;
    }

    private function generateVerificationCode()
    {
        return bin2hex(random_bytes(16));
    }

    private function normalizeDomain($domain)
    {
        // Remove protocol (http, https) from the domain
        return preg_replace("~^(?:f|ht)tps?://~i", "", $domain);
    }

    public function render()
    {
        return view('livewire.verify-website', [
            'isVerified' => $this->isVerified,
            'verificationCode' => $this->verificationCode,
        ]);
    }
}