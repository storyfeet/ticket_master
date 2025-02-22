import Ticket from "./Ticket";
import { useState, useEffect } from "react";
import * as loader from "./loader";
import { useTranslation } from "react-i18next";

export default function TicketList({ basePath, canGetUser, goTicketsC, goEditTicket }) {
    let [ticketList, setTicketList] = useState([]);
    let { t } = useTranslation();

    async function setPage({ newPath }) {
        console.log("pageGetter : path = ", newPath);
        let jsres = await loader.loadJson(newPath);
        console.log(jsres);
        console.log("setTicketList", setTicketList);
        setTicketList(jsres);
    }
    useEffect(() => {
        setPage({ newPath: basePath });
    }, [basePath])



    function PageButton({ dir, dpath }) {
        return (
            <button onClick={() => setPage({ newPath: dpath })} disabled={dpath === null}>{t(dir)}</button>
        );
    }

    let tlist = ticketList.data?.map((ticket, index) => (
        <Ticket key={index} ticket={ticket}
            goTicketsC={goTicketsC} canGetUser={canGetUser} goEditTicket={goEditTicket} />
    ));
    return (
        <div className="ticket_list">
            <h2>Tickets</h2>
            <PageButton dir="dir-first" dpath={ticketList.first_page_url} />
            <PageButton dir="dir-prev" dpath={ticketList.prev_page_url} />
            <PageButton dir="dir-next" dpath={ticketList.next_page_url} />
            <PageButton dir="dir-last" dpath={ticketList.last_page_url} />
            <table>
                <tbody>
                    {tlist || <tr><td>{t("stat-no_tickets")}</td></tr>}
                </tbody>
            </table>
        </div>
    );
}



