@extends('layout')

@section('title'){{ __('forms.create_audio_play_title') }}@stop

@section('content')
    @if (session('message'))
        <div class="alert alert-success">
            {{ __('forms.audio_play_stored',['title'=>session('message')]) }}
        </div>
    @endif
    <form method="POST" action="/audio_plays">
        @csrf
        <div class="row">
            <div class="col">
                <div class="mb-3">

                    @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="titleInput" class="form-label">{{ __('forms.title') }}</label>
                    <input type="text" class="form-control" id="titleInput" name="title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">

                    @error('voice_actors')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="voiceActorsInput" class="form-label">{{ __('forms.voice_actors') }}</label>
                    <input type="text" class="form-control" id="voiceActorsInput" name="voice_actors">
                </div>
            </div>
        </div>
        <div class="d-flex flex-row-reverse">
            <button type="submit" class="btn btn-primary">{{ __('forms.save') }}</button>
        </div>
    </form>
@stop
