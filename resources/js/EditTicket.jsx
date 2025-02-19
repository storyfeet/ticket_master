import { useEffect, useState } from "react";
import { postCsrfJson } from "./loader";

export default function EditTicket({ user, ticket }) {
    let [messages, messagesSetter] = useState([]);

    useEffect(() => {
        async(() => {
            let dat = await postCsrfJson("/users/get_ticket_messages", {
                "ticket_id": ticket.id,
            })

        })();
    }, [ticket]);

    let messageBox = messages.map((m, index) => {
        return (
            <TicketMessage user={user} message={m} key={index} />
        );
    });
    return (
        <div>
            {messageBox}
        </div>
    );



}

function TicketMessage({ user, message }) {
    return (
        <div className={user.id === message.user_id ? "ticket_message user" : "ticket_message"
        }>
            <p>message.message</p>
        </div >
    );
}
