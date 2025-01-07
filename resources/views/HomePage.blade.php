<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>

        <h1>Ticket Master</h1>
        <div>
            <p>View Tickets</p>
            <button id="btn_open_tickets">Open Tickets</button>
            <button id="btn_closed_tickets">Closed Tickets</button>

            <div id="ticket_view">
            </div>

        </div>

    </body>

<script type="module">
import * as loader from "/js/loader";

function drawTickets(location,tickets){
    location.innerHTML = "";
    for(let i in tickets){
        let t = tickets[i];

    }
}


function setNextPrev(location,data){
}


document.getElementById("btn_open_tickets").onclick = async function(){
    let fResult = await loader.loadTickets(true);
    let location = document.getElementById("ticket_view");
    drawTickets(fResult.data);
    setNextPrev(fResult);
}








</script>

</html>
