<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class ChangelogController extends Controller
{
    /**
     * Show the changelog for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $changelogFile = Jetstream::localizedMarkdownPath('../../CHANGELOG.md');

        return view('changelog', [
            'changelog' => Str::markdown(file_get_contents($changelogFile)),
        ]);
    }
}
