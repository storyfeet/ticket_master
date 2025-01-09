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
            Email : <input name="email" type="text" value="{{old('email')}}"/><br>
            Password : <input name="password" type="password"/><br>
            <input type="submit" value="login"/>
        </form>


        <div>
            <p>This is a dummy webpage, the two available logins are :</p>
            <p>normal : normal@tickets.com : normalnormal</p>
            <p>admin : admin@tickets.com : adminadmin</p>

        </div>

    </body>
</html>

