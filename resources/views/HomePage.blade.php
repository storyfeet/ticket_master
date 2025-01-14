<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
        @vite('resources/js/app.js')
    </head>
    <body>
        @include('LanguageSelect')
        <h1>Ticket Master</h1>
        @isset($message)
            <p>{{$message}}</p>
        @endisset
        @if (isset($user))
            <p>Welcome : <strong>{{$user->name}}</strong></p>
            <p><a href="/ticketform">Raise New Ticket</a>
            &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
            <a href="stats_page">View Stats</a>
            &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
            <a href="/logout">Log Out</a></p>
        @else
            <p><a href="/loginhome">Login</a> to create tickets.
            &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
            Or <a href="/stats_page">View Stats</a></p>
        @endif

        <div>
            <p>View Tickets</p>
            <button id="btn_open_tickets">Open Tickets</button>
            <button id="btn_closed_tickets">Closed Tickets</button>
            @isset($user)
            <button id="btn_my_tickets">My Tickets</button>
            @endisset
            &nbsp;&nbsp; <input type="text" id="txt_email" />
            <button id="btn_tickets_by_email">Tickets By Email</button>
            <div id="next_prev_view"></div>
            <table id="ticket_table">
            </div>

        </div>

    </body>

<script type="module">


import * as loader from "/js/loader.js";



const CSRF_TOKEN = '{{csrf_token()}}';

let LastRequest = {
    path : "/tickets/open",
    isByUser :  false,
};

function drawTickets(location,tickets,isByUser,path){
    location.innerHTML = "";
    for(let i in tickets){
        let t = tickets[i];

        let row = document.createElement("tr");
        loader.drawTicket(row,t);
        if (!isByUser){
            loader.drawUserLink(row,t,async()=>{
                console.log("Loading user tickets");
                loadTickets(`users/${t.email}/tickets`,true);
            });
        }
        if (t.status == 0 && (t.user_id == USER_ID || IS_ADMIN) ){
            loader.drawCloser(row,async()=>{
                let r = await loader.closeTicket(t.ticket_id,CSRF_TOKEN);
                if (r.error) {
                    console.log(r)
                }else {
                    loadTickets(path,isByUser);
                }

            })
        }
        location.appendChild(row);
    }
}

function setNextPrev(location,data,byUser){
    location.innerHTML = "";
    if (data.prev_page_url) {
        let btn = loader.elem("button", {
            onclick: async ()=>{
                await loadTickets(data.prev_page_url,byUser);
            },
            innerHTML:"Previous"
        });
        location.appendChild(btn);
    }
    if (data.next_page_url) {
        let btn = loader.elem("button", {
            onclick: async ()=>{
                await loadTickets(data.next_page_url,byUser);
            },
            innerHTML:"Next"
        });
        location.appendChild(btn);
    }
}

@if(isset($is_admin))
const IS_ADMIN = {{$is_admin}};
@else
const IS_ADMIN = false;
@endif
@if( isset($user))
const USER_ID = {{$user->id}};
const USER_NAME = "{{$user->name}}";
const USER_EMAIL = "{{$user->email}}";
document.getElementById("btn_my_tickets").onclick =  async function(){
    await loadTickets("/users/{{$user->email}}/tickets",true);
}
@else
const USER_ID = null;
const USER_NAME = null;
const USER_EMAIL = null;
@endif



document.getElementById("btn_open_tickets").onclick = async function(){
    await loadTickets("/tickets/open",false);
}

document.getElementById("btn_closed_tickets").onclick = async function(){
    await loadTickets("/tickets/closed",false);
}

document.getElementById("btn_tickets_by_email").onclick = async function(){
    let email = document.getElementById("txt_email").value;
    await loadTickets(`/users/${email}/tickets`,true);
}



async function loadTickets(path,isByUser){
    let fResult = await loader.loadJson(path);
    let location = document.getElementById("ticket_table");

    LastRequest.path = path;
    LastRequest.isByIser = isByUser;


    drawTickets(location,fResult.data,isByUser,path);

    let nextPrev = document.getElementById("next_prev_view");
    setNextPrev(nextPrev,fResult,isByUser);

}

@if(isset($user))
loadTickets(`/users/${USER_EMAIL}/tickets`,true);
@else
loadTickets(`/tickets/open`,true);

@endif

window.Echo.channel("tickets").listen(".updated",()=>{
    console.log("Update recieved");
    loadTickets(LastRequest.path,LastRequest.isByUser);
});


</script>

</html>
