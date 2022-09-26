<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <style>
        html, body, .full-height {
            height: 100%;
        }
        .audio-play-title{
            font-weight: bold;
            font-size: 110%;
        }
        th {
            font-size: 120%;
        }
    </style>
</head>
<body @isset($search_terms) onload="highlightSearchTerms()" @endisset>

<div class="row full-height">
    <div class="col bg-light"></div>
    <div class="col-2 bg-light pt-3">
        <div class="row px-2">
            <div class="card px-0">
                <h6 class="card-header">{{ __('forms.search_terms') }}</h6>
                <div class="card-body">
                    <form method="GET" action="/audio_plays/search">
                        <div class="row">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="searchTermsInput" name="search_terms" value="@isset($search_terms){{ $search_terms }}@endisset">
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-primary">{{ __('forms.search') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <nav class="nav nav-pill">
                <li class="nav-item">
                    <a class="nav-link active" href="/audio_plays/create">{{ __('forms.create_audio_play_title') }}</a>
                </li>
            </nav>
        </div>
    </div>
    <div class="col-8 bg-secondary">
        <div class="card m-3 px-0">
            <h5 class="card-header">@yield('title')</h5>
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="col bg-light"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</body>
</html>
