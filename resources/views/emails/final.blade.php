<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body>
<p>Hello Mr/Mrs. {{ $data['person']['name'] }},</p>
<p>
    Mr/Mrs. {{ $data['owner'] }} wanted to share this information with you.
</p>

<h1>All Notes</h1>
<hr>
<h2>{{ $data['title'] }}</h2>
{!! $data['body'] !!}

<div class="images">
    @isset ($data['files'])
        <h4>All Images</h4>
        <hr>
        @foreach ($data['files'] as $file)
            <img src="{{ $file  }}" alt="">
        @endforeach
    @endisset
</div>

<br>
<br>
Thanks,
<br>
The End Team
</body>
</html>