<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>{{__('messages.new_ticket')}}</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>
        @include('LanguageSelect')
        <h1>{{__('messages.new_ticket')}} : {{$user->name}}</h1>
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
            <label>{{__('messages.subject')}}:</label><br>
            <input type="text" name="subject"/><br>
            <label>{{__('messages.content')}}:</label> <br>
            <textarea name="content" cols="40" rows="5"></textarea><br>
            <input type="submit" value="{{__('messages.raise_new_ticket')}}"/>

        </form>
    </body>

</html>
