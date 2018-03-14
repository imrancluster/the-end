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
<h1>Welcome to The End!, {{ $user->name }}</h1>
<p>Please click on the <strong><a style="color: orangered;" href="{{ $user->link }}">Activation</a></strong> link to activate your account.</p>
<p>You can copy and paste the following url on your browser.</p>
<strong>Link: </strong> {{ $user->link }}

<br>
<br>
Thanks,
<br>
The End Team
</body>
</html>