<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('forms.search_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body @isset($search_terms) onload="highlightSearchTerms()" @endisset>
<div class="row h-100">
    <div class="col my-auto">
        <div class="row"></div>
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <form method="GET" action="/audio_plays/search">
                    <div class="row">
                        <div class="mb-3">
                            <label for="searchTermsInput" class="form-label">{{ __('forms.search_terms') }}</label>
                            <input type="text" class="form-control" id="searchTermsInput" name="search_terms" value="@isset($search_terms){{ $search_terms }}@endisset">
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <button type="submit" class="btn btn-primary">{{ __('forms.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col"></div>
        </div>
        @isset ($audio_plays)
                <div class="row">
                    <div class="col"></div>
                    <div class="col-10">
                        @if (count($audio_plays) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('forms.title')}}</th>
                                    <th scope="col">{{__('forms.voice_actors')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($audio_plays as $audio_play)
                                    <tr>
                                        <td>{{$audio_play['title']}}</td>
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
                    </div>
                    <div class="col"></div>
                </div>
        @endisset
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

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

</body>
</html>
