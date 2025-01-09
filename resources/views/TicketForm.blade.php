<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>New Ticket</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>
        <h1>New Ticket : {{$user->name}}</h1>
        @if ($errors->any())
            <div class="error_list">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            </div>
        @endif
        <form action="/tickets/new" method="post">
            @csrf
            Subject:<br>
            <input type="text" name="subject"/><br>
            Content: <br>
            <textarea name="content" cols="40" rows="5"></textarea><br>
            <input type="submit" value="Raise Ticket"/>

        </form>
    </body>

</html>
