<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class MarkdownController extends Controller
{
    /**
     * Show a markdown page with the application layout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $page)
    {
        $markdownFile = resource_path("markdown/{$page}.md");
        $title = ucfirst($page);
        
        if (!file_exists($markdownFile)) {
            abort(404);
        }

        return view('markdown', [
            'content' => Str::markdown(file_get_contents($markdownFile)),
            'title' => $title
        ]);
    }
    
    /**
     * Show the changelog for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function changelog(Request $request)
    {
        $changelogFile = Jetstream::localizedMarkdownPath('../../CHANGELOG.md');

        return view('markdown', [
            'content' => Str::markdown(file_get_contents($changelogFile)),
            'title' => 'Changelog'
        ]);
    }
}