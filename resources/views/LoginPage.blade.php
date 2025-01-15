<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master - Login</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>
        @include('LanguageSelect')
        <h1>{{__('messages.ticket_master')}} - {{__('messages.login')}}</h1>
        @if ($errors->any())
            <div class="error_list">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            </div>
            <br>
        @endif
        <form action="login" method="post">
            @csrf
            <label>{{__('messages.email_address')}}</label> : <input name="email" type="text" value="{{old('email')}}"/><br>
            <label>{{__('messages.password')}}</label> : <input name="password" type="password"/><br>
            <input type="submit" value="login"/>
        </form>

<br><br>
        <div>
            <p>{{__('messages.dummy_site')}}</p>
            <p>normal : normal@tickets.com : normalnormal</p>
            <p>admin : admin@tickets.com : adminadmin</p>

        </div>

    </body>
</html>

