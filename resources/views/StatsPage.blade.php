
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master - Stats</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>
        <h1>Ticket Master - Stats</h1>

        <a href="/">Home</a>

        <h2>Totals</h2>

        <table>
        <tr><td>Total Tickets</td><td>{{$total_tickets}}</td></tr>
        <tr><td>Total Unprocessed Tickets</td><td>{{$total_unprocessed_tickets}}</td></tr>
        </table>

        <h2>Ticket Champion</h2>
        User with the most tickets was <strong>{{$most_tickets?->name ?: "NO_ONE"}}</strong> : {{$most_tickets?->email ?: NO_ADDRESS}}.
        <br>With : <strong>{{$most_tickets?->total ?:0}}</strong> tickets

        <h2>Most Recent</h2>
        The most recent ticket processed was <strong>"{{$last_processed?->subject ?: "NO_ITEM"}}"</strong> at <strong>{{$last_processed?->updated_at ?: "NEVER"}}.</strong>


    </body>
</html>

