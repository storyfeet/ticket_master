
export default function Ticket({ index, ticket, isByUser, canEdit }) {

    return (
        <tr key={index} className={ticket.status ? "closed_ticket" : "open_ticket"} >
            <td><b>{ticket.name}</b>
                <br />{ticket.email}
                <br />{ticket.status ? "Closed" : "Open"}
            </td>
            <td className="ticket_content"><b>{ticket.subject}</b><br />{ticket.content}</td>
            <td>
                {ticket.created_at}<br />
                {ticket.updated_at}
            </td>
            {!isByUser && <td><button>Get User's Tickets</button></td>}
            {canEdit && <td></td>}

        </tr>
    );
}
