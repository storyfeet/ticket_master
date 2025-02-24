import { useState, useRef } from "react";
import TicketList from "./TicketList";
import { ErrInput, ErrListView } from "./ErrView";
import { NewTicket } from "./NewTicket";
import { DISPLAY_MODE } from "./util";
import { EditTicket } from "./EditTicket";
import { useTranslation } from "react-i18next";



export function Panel({ user }) {



    let [basePath, basePathSetter] = useState(null);
    let [canGetUser, canGetUserSetter] = useState(false);
    let [errs, errSetter] = useState(null)
    let [displayMode, displaySetter] = useState(DISPLAY_MODE.NONE)
    let [currentTicket, currentTicketSetter] = useState(null)


    //ends in C to mark closure
    function goTicketsC(newPath, canGetUser = false) {
        return () => {
            errSetter(null);

            basePathSetter(newPath);
            canGetUserSetter(canGetUser);
            displaySetter(DISPLAY_MODE.TICKETS);
        }
    }
    function goNewTicket() {
        displaySetter(DISPLAY_MODE.NEW_TICKET);
    }

    function goEditTicket(ticket) {
        console.log("goEditTicket", ticket);
        currentTicketSetter(ticket);
        displaySetter(DISPLAY_MODE.EDIT_TICKET);
    }

    return (
        <>
            {errs && <ErrListView errs={errs} errSetter={errSetter} />}
            {user.isAdmin && <AdminPanel goTicketsC={goTicketsC} errs={errs} errSetter={errSetter} />}
            < UserPanel user={user} goTicketsF={goTicketsC} goNewTicket={goNewTicket} />
            {basePath && displayMode === DISPLAY_MODE.TICKETS &&
                <TicketList basePath={basePath} goEditTicket={goEditTicket}
                    goTicketsC={goTicketsC} canGetUser={canGetUser} />}
            {displayMode === DISPLAY_MODE.NEW_TICKET &&
                <NewTicket goTicketsC={goTicketsC} />}
            {currentTicket && displayMode === DISPLAY_MODE.EDIT_TICKET &&
                <EditTicket ticket={currentTicket} ticketSetter={currentTicketSetter} user={user} />}

        </>
    );
}

export function AdminPanel({ goTicketsC, errs, errSetter }) {
    let emailRef = useRef();
    let { t } = useTranslation();

    function handleTicketsByEmail() {
        let email = emailRef.current.value;
        if (!email) {
            errSetter({ "email": ["err-email_required"] });
            return;
        }
        goTicketsC(`/admin/get_user_tickets/${email}`)();

    }
    return (
        <div className="admin_panel">
            <button onClick={goTicketsC("/admin/get_open", true)}>{t("open_tickets")}</button>
            <button onClick={goTicketsC("/admin/get_closed", true)}>{t("closed_tickets")}</button>
            <div>
                <ErrInput label="email" type="text"
                    name="email" inRef={emailRef} err={errs?.email} />
                <button onClick={handleTicketsByEmail} >{t("tickets_by_email")}</button>
            </div>
        </div >
    );
}


export function UserPanel({ goTicketsF, goNewTicket }) {
    let { t } = useTranslation();

    return (
        <div className="user_panel">
            <button onClick={goTicketsF("/user/get_all")}>{t("my_tickets")}</button>
            <button onClick={goTicketsF("/user/get_open")}>{t("my_open_tickets")}</button>
            <button onClick={goTicketsF("/user/get_closed")}>{t("my_closed_tickets")}</button>
            <button onClick={goNewTicket}>{t("create_new_ticket")}</button>
        </div>
    );
}
