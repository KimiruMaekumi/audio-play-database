@extends('layout')

@section('title'){{ __('forms.search_title') }}@stop

@section('content')
    @isset ($audio_plays)
        @if (count($audio_plays) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th class="text-end" scope="col">{{__('forms.title')}}</th>
                    <th scope="col">{{__('forms.voice_actors')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($audio_plays as $audio_play)
                    <tr>
                        <td class="audio-play-title text-end">
                            <a href="{{$audio_play['id'].'/edit'}}">{{$audio_play['title']}}</a>
                        </td>
                        <td class="voice-actor-cell">
                            @foreach($audio_play['voice_actors'] as $voice_actor)
                                {{$voice_actor['name']}}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-primary" role="alert">
                {{__('forms.search_results_empty')}}
            </div>
        @endif
    @endisset

    @isset($search_terms)
        @if (count($audio_plays) > 0)
            <script>
                function highlightSearchTerms() {

                    //Separate and trim each search term
                    var search_terms = document.getElementById("searchTermsInput").value;
                    search_terms = search_terms.split(",");
                    for (let i = 0; i < search_terms.length; i++) {
                        search_terms[i] = search_terms[i].trim();
                    }

                    //Go through each voice actor cell and highlight the search terms
                    var voice_actor_cells = document.getElementsByClassName("voice-actor-cell");
                    for (let i = 0; i < search_terms.length; i++) {
                        for (let j = 0; j < voice_actor_cells.length; j++) {

                            var inner_html = voice_actor_cells[j].innerHTML;
                            inner_html = inner_html.replace(search_terms[i],"<mark>"+search_terms[i]+"</mark>");
                            voice_actor_cells[j].innerHTML = inner_html;
                        }
                    }
                }
            </script>
        @endif
    @endisset
@stop
