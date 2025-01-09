<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
    </head>
    <body>

        <h1>Ticket Master</h1>
        @if (isset($user))
            <p>Welcome : <strong>{{$user->name}}</strong></p>
            <p><a href="/logout">Log Out</a></p>
        @else
            <p><a href="/loginhome">Login</a> to create tickets</p>
        @endif

        <div>
            <p>View Tickets</p>
            <button id="btn_open_tickets">Open Tickets</button>
            <button id="btn_closed_tickets">Closed Tickets</button>
            &nbsp;&nbsp; <input type="text" id="txt_email" />
            <button id="btn_tickets_by_email">Tickets By Email</button>
            <div id="next_prev_view"></div>
            <table id="ticket_table">
            </div>

        </div>

    </body>

<script type="module">
import * as loader from "/js/loader.js";

function drawTickets(location,tickets){
    location.innerHTML = "";
    for(let i in tickets){
        let t = tickets[i];

        let row = document.createElement("tr");
        loader.drawTicket(row,t);
        loader.drawUserLink(row,t,async()=>{
            console.log("Loading user tickets");
            loadTickets(`users/${t.email}/tickets`);
        });
        location.appendChild(row);
    }
}

function setNextPrev(location,data){
    location.innerHTML = "";
    if (data.prev_page_url) {
        let btn = loader.elem("button", {
            onclick: async ()=>{
                await loadTickets(data.prev_page_url);
            },
            innerHTML:"Previous"
        });
        location.appendChild(btn);
    }
    if (data.next_page_url) {
        let btn = loader.elem("button", {
            onclick: async ()=>{
                await loadTickets(data.next_page_url);
            },
            innerHTML:"Next"
        });
        location.appendChild(btn);
    }
}


document.getElementById("btn_open_tickets").onclick = async function(){
    await loadTickets("/tickets/open");
}

document.getElementById("btn_closed_tickets").onclick = async function(){
    await loadTickets("/tickets/closed");
}

document.getElementById("btn_tickets_by_email").onclick = async function(){
    let email = document.getElementById("txt_email").value;
    await loadTickets(`/users/${email}/tickets`);
}



async function loadTickets(path){
    let fResult = await loader.loadJson(path);
    let location = document.getElementById("ticket_table");
    drawTickets(location,fResult.data);

    let nextPrev = document.getElementById("next_prev_view");
    setNextPrev(nextPrev,fResult);

}


</script>

</html>
