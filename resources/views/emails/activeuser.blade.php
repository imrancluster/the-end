<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Total number of registered users is: {{ $count }}</h1>

<p>Hello Mr/Mrs. {{ $living->user->name }},</p>
<p>
    This is your boring email. We are just checking your are living in earth :P.
    Please click on the following button to make sure your are live.
</p>
<p><a href="{{ $living->token_url }}">Click Here!</a></p>

<br>
<br>
Thanks,
<br>
The End Team
</body>
</html>