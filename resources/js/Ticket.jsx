
export default function Ticket({ ticket, canGetUser, goTicketsC }) {

    return (
        <tr className={ticket.status ? "closed_ticket" : "open_ticket"} >
            <td><b>{ticket.name}</b>
                <br />{ticket.email}
                <br />{ticket.status ? "Closed" : "Open"}
            </td>
            <td className="ticket_content"><b>{ticket.subject}</b><br />{ticket.content}</td>
            <td>
                {ticket.created_at}<br />
                {ticket.updated_at}
            </td>
            {canGetUser && <td>
                <a onClick={goTicketsC(`/admin/get_user_tickets/${ticket.email}`)} >
                    Get User's Tickets
                </a>
            </td>}

        </tr >
    );
}
