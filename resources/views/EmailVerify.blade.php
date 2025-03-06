<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Email Verification</title>
</head>
<body>
Dear {{$user->name}} <br><br>

Please follow the link below to verify your email:<br><br>

<a href="http://localhost/verify/{{$user->email}}/{{$code}}" >http://localhost/verify/{{$user->email}}/{{$code}}</a>
<br><br>

Thanks
<br><br>

Ticket Slave
<br><br>

</body>
</html>
