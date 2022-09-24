<?php

namespace App\Http\Controllers;

use App\Models\AudioPlay;
use App\Services\AudioPlayService;
use Illuminate\Http\Request;

class AudioPlayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function search(Request $request)
    {
        $plays_with_actors = AudioPlay::with('voice_actors')->get();
        return view('search_audio_play',['audio_plays'=>$plays_with_actors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_audio_play');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required',
            'voice_actors' => 'required',
        ]);

        $audio_play_service = new AudioPlayService();
        $audio_play_service->store($validated);

        return redirect('audio_plays/create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AudioPlay  $audio_play
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(AudioPlay $audio_play)
    {
        $audio_play_voice_actors = $audio_play->voice_actors()->get();
        $voice_actors_string = $audio_play_voice_actors
            ->pluck('name')
            ->join(',');

        return view('edit_audio_play', [
            'audio_play' => $audio_play,
            'voice_actors' => $voice_actors_string
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AudioPlay  $audio_play
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, AudioPlay $audio_play)
    {
        $validated = $request->validate([
            'title' => 'required',
            'voice_actors' => 'required',
        ]);

        $audio_play_service = new AudioPlayService();
        $audio_play_service->update($audio_play, $validated);

        return redirect('home');
    }
}
