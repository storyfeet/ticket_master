
export default function Ticket({ ticket, isByUser, canEdit, pager }) {

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
            {!isByUser && <td>
                <a onClick={() => {
                    let path = `/users/${ticket.email}/tickets`;
                    console.log("GetUser clicked : ", path);
                    pager({ newPath: path, byUser: true });
                }}>Get User's Tickets</a>
            </td>}
            {canEdit && <td></td>}

        </tr >
    );
}
