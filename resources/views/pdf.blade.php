<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
</head>
<body>

<h1>{{ $title }}</h1>

{!! $body !!}

<div class="images">
    @isset ($files)
        <h3>All Images</h3>
        <hr>
        @foreach ($files as $file)
            <img src="{{ $file  }}" alt="">
        @endforeach
    @endisset
</div>
</body>
</html>