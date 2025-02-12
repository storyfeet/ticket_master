import Ticket from "./Ticket";
import { useState, useEffect } from "react";
import * as loader from "./loader";

export default function TicketList() {
    let [ticketList, setTicketList] = useState([]);
    let [byUser, setByUser] = useState(false);
    let [path, setPath] = useState("/tickets/open");


    async function setPage({ newPath, byUser }) {
        console.log("pageGetter : path = ", newPath);
        let jsres = await loader.loadJson(newPath);
        console.log(jsres);
        console.log("setTicketList", setTicketList);
        setByUser(Boolean(byUser));
        setTicketList(jsres);
        setPath(newPath);
    }

    useEffect(() => {
        setPage({ newPath: path, byUser: false });
    }, [])

    function PageButton({ dir, dpath }) {
        return (
            <button onClick={() => setPage({ newPath: dpath, byUser: byUser })} disabled={dpath === null}>{dir}</button>
        );
    }

    let tlist = ticketList.data?.map((ticket, index) => (
        <Ticket key={index} ticket={ticket} pager={setPage} isByUser={byUser} />
    ));
    return (
        <div className="ticket_list">
            <h2>Tickets</h2>
            <PageButton dir="First" dpath={ticketList.first_page_url} />
            <PageButton dir="Previous" dpath={ticketList.prev_page_url} />
            <PageButton dir="Next" dpath={ticketList.next_page_url} />
            <PageButton dir="Last" dpath={ticketList.last_page_url} />
            <table>
                <tbody>
                    {tlist || <tr><td>No Tickets</td></tr>}
                </tbody>
            </table>
        </div>
    );
}



