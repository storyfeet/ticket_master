
import { useTranslation } from "react-i18next";

export default function Ticket({ ticket, canGetUser, goTickets, goEditTicket }) {
    let { t } = useTranslation();

    return (
        <tr className={ticket.status ? "closed_ticket" : "open_ticket"} >
            <td><b>{ticket.name}</b>
                <br />{ticket.email}
                <br />{ticket.status ? t("stat-closed") : t("stat-open")}
            </td>
            <td className="ticket_content"><b>{ticket.subject}</b><br />{ticket.content}</td>
            <td>
                {ticket.created_at}<br />
                {ticket.updated_at}
            </td>
            {canGetUser && goTickets && <td>
                <a onClick={()=>{goTickets(`/admin/get_user_tickets/${ticket.email}`)}} >
                    {t("btn-get_users_tickets")}
                </a>
            </td>}
            {goEditTicket && <td>
                <button onClick={() => { goEditTicket(ticket) }} >{t("btn-view_ticket")}</button>
            </td>}

        </tr >
    );
}
