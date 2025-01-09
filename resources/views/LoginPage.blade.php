<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master - Login</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>
        <h1>Ticket Master - Login</h1>
        @if ($errors->any())
            <div class="error_list">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            </div>
        @endif
        <form action="login" method="post">
            @csrf;
            <input name="email" type="text" value="{{old('email')}}"/>
            <input name="password" type="password"/>
            <input type="submit" value="login"/>
        </form>

    </body>
</html>

