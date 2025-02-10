import Ticket from "./Ticket";
import { useState, useEffect } from "react";
import * as loader from "./loader";

export default function TicketList() {
    let [ticketList, setTicketList] = useState([]);

    useEffect(async () => {
        let jsres = await loader.loadJson("/tickets/open");
        console.log(jsres);
        setTicketList(jsres.data);
    }, [])

    let tlist = ticketList.data?.map((ticket, index) => (
        <Ticket ticket={ticket} index={index} />
    ));
    return (
        <div className="ticket_div">
            <h2>Tickets</h2>
            {tlist || "No Tickets"}
        </div>
    );
}
