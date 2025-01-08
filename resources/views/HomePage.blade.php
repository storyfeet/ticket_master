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
            &nbsp;&nbsp; <input type="text" id="txt_email" />
            <button id="btn_tickets_by_email">Tickets By Email</button>
            <div id="ticket_view">
            </div>

        </div>

    </body>

<script type="module">
import * as loader from "/js/loader.js";

function drawTickets(location,tickets){
    location.innerHTML = "";
    for(let i in tickets){
        let t = tickets[i];
        let status = t.status ? "Closed" : "Open";

        let infoline = `${t.ticket_id} (${status}): ${t.subject} `;
        let userline = `${t.user_id}: ${t.name} - ${t.email}`;

        let dateline = `Opened : ${t.created_at}. Last Update: ${t.updated_at} `;




        let div = loader.elem('div',{className:"ticket"});
        location.appendChild(div);
        div.appendChild(loader.elem('p',{innerText:infoline}))
        div.appendChild(loader.elem('p',{innerText:userline}))
        div.appendChild(loader.elem('p',{innerText:t.content}))
        div.appendChild(loader.elem('p',{innerText:dateline}))


        //TODO
    }
}

function setNextPrev(location,data){
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
    let location = document.getElementById("ticket_view");
    drawTickets(location,fResult.data);
    setNextPrev(fResult);
}


</script>

</html>
