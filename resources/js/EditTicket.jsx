import { useEffect, useState, useRef } from "react";
import { postCsrfJson } from "./loader";
import { ErrTextArea } from "./ErrView";
import Ticket from "./Ticket";

export function EditTicket({ user, ticket }) {
    let [messages, messagesSetter] = useState([]);
    let [errs, errSetter] = useState(null);

    async function loadMessages() {
        let dat = await postCsrfJson("/user/get_ticket_messages", {
            "ticket_id": ticket.ticket_id,
        });

        if (dat.errors) {
            errSetter(dat.errors);
            return;
        }
        messagesSetter(dat);

    }
    useEffect(() => {
        loadMessages();
    }, [ticket]);

    let messageBox = messages.map((m, index) => {
        return (
            <TicketMessage user={user} message={m} key={index} />
        );
    });
    return (
        <div>
            <h2>Ticket Editor</h2>
            <table><tbody><Ticket ticket={ticket} /></tbody></table>
            {messageBox}
            {!ticket.status && <NewTicketMessage errs={errs} loadMessages={loadMessages} ticket={ticket} />}
        </div>
    );



}

function TicketMessage({ user, message }) {
    return (
        <div className={user.id === message.author_id ? "ticket_message user" : "ticket_message"
        }>
            <span className="top_left">{message.author_name}</span>
            <span className="top_right">{message.created_at}</span>
            <p>{message.message}</p>
        </div >
    );
}

function NewTicketMessage({ loadMessages, ticket }) {
    //export function ErrTextArea({ label, name, rows, cols, inRef, err, value }) {
    let mesRef = useRef();
    let [errs, errSetter] = useState(null);

    function handleSubmit(e) {
        e.preventDefault();
        (async () => {
            let res = await postCsrfJson("/user/new_ticket_message", { ticket_id: ticket.ticket_id, message: mesRef.current.value });

            if (res.errors) {
                errSetter(res.errors);
                return;
            }
            errSetter(null)
            loadMessages();
            mesRef.current.value = "";
        })();
        return true;
    }
    return (
        <form onSubmit={handleSubmit}>
            <ErrTextArea label="Message" name="message"
                rows={4} cols={50} inRef={mesRef} err={errs?.message} />
            <input type="submit" value="Send Message" />

        </form>
    );
}
