<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit audio play</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body>
<div class="row h-100">
    <div class="col my-auto">
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <form method="POST" action="/audio_plays/{{ $audio_play['id'] }}">
                    @method('PATCH')
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">

                                @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <label for="titleInput" class="form-label">Title</label>
                                <input type="text" class="form-control" id="titleInput" name="title" value="{{ $audio_play['title'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">

                                @error('voice_actors')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <label for="voiceActorsInput" class="form-label">Voice Actors</label>
                                <input type="text" class="form-control" id="voiceActorsInput" name="voice_actors" value="{{ $voice_actors }}">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</body>
</html>
