import Ticket from "./Ticket";
import { useState, useEffect } from "react";
import * as loader from "./loader";

export default function TicketList() {
    let [ticketList, setTicketList] = useState([]);
    let [path, setPath] = useState("/tickets/open");


    useEffect(() => {
        (async () => {
            console.log("pageGetter : path = ", path);
            let jsres = await loader.loadJson(path);
            console.log(jsres);
            console.log("setTicketList", setTicketList);
            setTicketList(jsres);
        })();
    }, [path])

    let tlist = ticketList.data?.map((ticket, index) => (
        <Ticket key={index} ticket={ticket} pager={(p) => setPath(p)} />
    ));
    return (
        <div className="ticket_list">
            <h2>Tickets</h2>
            <table>
                <tbody>
                    {tlist || <tr><td>No Tickets</td></tr>}
                </tbody>
            </table>
        </div>
    );
}
