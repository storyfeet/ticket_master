import { useEffect, useState, useRef } from "react";
import { postCsrfJson ,loadJson} from "./loader";
import { ErrTextArea } from "./ErrView";
import Ticket from "./Ticket";
import { useTranslation } from "react-i18next";

export function EditTicket({ user, ticket, ticketSetter }) {
    let [messages, messagesSetter] = useState([]);
    let [errs, errSetter] = useState(null);

    async function loadMessages() {
        let tid = ticket.ticket_id || ticket.id;
        let dat = await loadJson(`/user/get_ticket_messages/${tid}`,
            {});

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
            {!ticket.status && <NewTicketMessage errs={errs} loadMessages={loadMessages} ticket={ticket} ticketSetter={ticketSetter} />}
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

function NewTicketMessage({ loadMessages, ticket, ticketSetter }) {
    //export function ErrTextArea({ label, name, rows, cols, inRef, err, value }) {
    let mesRef = useRef();
    let [errs, errSetter] = useState(null);
    let { t } = useTranslation();

    function handleSubmitC(close) {
        return (e) => {
            e.preventDefault();
            (async () => {
                let res = await postCsrfJson("/user/new_ticket_message", { ticket_id: ticket.ticket_id, message: mesRef.current.value, close: close });

                if (res.errors) {
                    errSetter(res.errors);
                    return;
                }
                if (res.ticket) {
                    ticketSetter(res.ticket);
                }
                errSetter(null)
                loadMessages();
                mesRef.current.value = "";
            })();
            return true;
        }
    }
    return (
        <form >
            <ErrTextArea label="Message" name="message"
                rows={4} cols={50} inRef={mesRef} err={errs?.message} />
            <button onClick={handleSubmitC(false)}>{t("send_message")}</button>
            <button onClick={handleSubmitC(true)}>{t("close_with_message")}</button>

        </form>
    );
}
