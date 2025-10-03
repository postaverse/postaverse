<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportForm extends Component
{
    protected $listeners = ['openReport' => 'open'];
    public $postId;
    public $reason = 'other';
    public $description;
    public $show = false;

    public function mount($postId = null)
    {
        $this->postId = $postId;
    }

    public function open($postId)
    {
        $this->postId = $postId;
        $this->show = true;
    }

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'reason' => 'required|string',
            'description' => 'nullable|string|max:2000',
        ]);

        $post = Post::findOrFail($this->postId);

        Report::create([
            'reporter_id' => Auth::id(),
            'reportable_id' => $post->id,
            'reportable_type' => Post::class,
            'reason' => $this->reason,
            'description' => $this->description,
            'status' => 'pending',
        ]);

        $this->show = false;
        $this->description = null;
        $this->reason = 'other';

        session()->flash('message', 'Report submitted. Our moderation team will review it.');
        $this->emitUp('reportSubmitted');
    }

    public function render()
    {
        return view('livewire.report-form');
    }
}

