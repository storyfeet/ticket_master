import { useState, useRef } from "react";
import {TicketList} from "./TicketList";
import { ErrInput, ErrListView } from "./ErrView";
import { NewTicket } from "./NewTicket";
import { DISPLAY_MODE } from "./util";
import { EditTicket } from "./EditTicket";
import { useTranslation } from "react-i18next";
import {UpdateView} from "./UpdateView.jsx";
import {CreateUser} from "./CreateUser.jsx";
import {AdvancedFilter} from "./AdvancedFilter";
import {postCsrfJson} from "./loader";
import {QueryForm} from "./QueryForm";




export function Panel({ user }) {

    let [basePath, basePathSetter] = useState(null);
    let [refreshTickets,refreshTicketsSetter] = useState(new Date().getTime());
    let [canGetUser, canGetUserSetter] = useState(false);
    let [errs, errSetter] = useState(null)
    let [displayMode, displaySetter] = useState(DISPLAY_MODE.NONE)
    let [adminMode , adminModeSetter] = useState(ADMIN_DISPLAY.NONE);
    let [currentTicket, currentTicketSetter] = useState(null)

    function refresher(){
        errSetter(null);
        refreshTicketsSetter(new Date().getTime());
    }

    //ends in C to mark closure
    function goTickets(newPath, canGetUser = false) {
            errSetter(null);
            basePathSetter(newPath);
            refreshTicketsSetter(new Date().getTime());
            canGetUserSetter(canGetUser);
            displaySetter(DISPLAY_MODE.TICKETS);
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
            <UpdateView user={user} goEditTicket={goEditTicket}/>
            {errs && <ErrListView errs={errs} errSetter={errSetter} refresher={refresher} />}
            {user.isAdmin &&
                <AdminPanel goTickets={goTickets} errs={errs} errSetter={errSetter}
                mode={adminMode} modeSetter={adminModeSetter} displaySetter={displaySetter}/>}
            {(!user.isAdmin || adminMode === ADMIN_DISPLAY.ME) &&
                < UserPanel user={user} goTickets={goTickets} goNewTicket={goNewTicket} />}
            {basePath && displayMode === DISPLAY_MODE.TICKETS &&
                <TicketList basePath={basePath} goEditTicket={goEditTicket}
                    goTickets={goTickets} canGetUser={canGetUser}
                    refreshTickets={refreshTickets} refresher={refresher}
                    errs={errs} errSetter={errSetter}/>}
            {displayMode === DISPLAY_MODE.NEW_TICKET &&
                <NewTicket goTickets={goTickets} />}
            {currentTicket && displayMode === DISPLAY_MODE.EDIT_TICKET &&
                <EditTicket ticket={currentTicket} ticketSetter={currentTicketSetter} user={user} />}

        </>
    );
}

const ADMIN_DISPLAY = {
    NONE :0,
    CREATE_USER :1,
    EMAIL_FILTER : 2,
    ADVANCED_FILTER : 3,
    ME : 4,
    QUERY:5,

};

export function AdminPanel({ goTickets, errs, errSetter,mode,modeSetter,displaySetter }) {
    let { t } = useTranslation();

    function toggleMode(newMode){
        if (mode === newMode){
            modeSetter(ADMIN_DISPLAY.NONE);
        }else {
            modeSetter(newMode);
        }
    }
    function qGoTickets(path){
        return ()=>{
            modeSetter(ADMIN_DISPLAY.NONE);
            goTickets(path,true);
        }
    }
    function modeAndClear(newMode){
        return function(){
            displaySetter(DISPLAY_MODE.NONE);
            toggleMode(newMode);
        }
    }
    return (
        <div className="admin_panel">
            <button onClick={modeAndClear(ADMIN_DISPLAY.ME)}>
                {t(mode === ADMIN_DISPLAY.ME ? "btn-close_me_as_user": "btn-me_as_user")}
            </button>
            <button onClick={qGoTickets("/admin/get_duty_tickets")}>
                {t("btn-duty_tickets")}
            </button>
            <button onClick={qGoTickets("/admin/get_open" )}>
                {t("open_tickets")}</button>
            <button onClick={qGoTickets("/admin/get_closed")}>
                {t("closed_tickets")}</button>
            <button onClick={modeAndClear(ADMIN_DISPLAY.EMAIL_FILTER)}>{
                t(mode === ADMIN_DISPLAY.EMAIL_FILTER ? "btn-close_email_filter":"btn-go_email_filter")
            }</button>
            <button onClick={modeAndClear(ADMIN_DISPLAY.ADVANCED_FILTER)}>{
                t(mode=== ADMIN_DISPLAY.ADVANCED_FILTER ? "btn-close_advanced_filter":"btn-go_advanced_filter")
            }</button>
            <button onClick={modeAndClear(ADMIN_DISPLAY.CREATE_USER)}>{
                t(mode=== ADMIN_DISPLAY.CREATE_USER ? "btn-close_create_user":"btn-go_create_user")
            }</button>
            <button onClick={modeAndClear(ADMIN_DISPLAY.QUERY)}>{
                t(mode=== ADMIN_DISPLAY.QUERY ? "btn-close_free_query":"btn-free_query")
            }</button>
            {mode === ADMIN_DISPLAY.CREATE_USER && <CreateUser />}
            {mode === ADMIN_DISPLAY.EMAIL_FILTER && <EmailFilter goTickets={goTickets} />}
            {mode === ADMIN_DISPLAY.ADVANCED_FILTER &&
                <AdvancedFilter goTickets={goTickets} errs={errs} errSetter={errSetter}/>}
            {mode == ADMIN_DISPLAY.QUERY && <QueryForm/>}
        </div >
    );
}

export function EmailFilter({goTickets}){
    let emailRef = useRef();
    let [errs,errSetter] = useState(null);
    let {t} = useTranslation();
    function handleTicketsByEmail() {
        let email = emailRef.current.value;
        if (!email) {
            errSetter({ "email": ["err-email_required"] });
            return;
        }
        goTickets(`/admin/get_user_tickets/${email}`);

    }

    return <div>
        <ErrInput label="email" type="text"
                  name="email" inRef={emailRef} err={errs?.email} />
        <button onClick={handleTicketsByEmail} >{t("tickets_by_email")}</button>
    </div>;
}

export function UserPanel({user, goTickets, goNewTicket }) {
    let { t } = useTranslation();
    function qGoTickets(path){
        return ()=>{
            goTickets(path);
        }
    }

    async function verify(){
        let res = await postCsrfJson("/user/request_verification_email",{});
        console.log(res);
    }

    return (
        <div className="user_panel">
            <button onClick={qGoTickets("/user/get_all")}>{t("my_tickets")}</button>
            <button onClick={qGoTickets("/user/get_open")}>{t("my_open_tickets")}</button>
            <button onClick={qGoTickets("/user/get_closed")}>{t("my_closed_tickets")}</button>
            <button onClick={goNewTicket}>{t("create_new_ticket")}</button>
            {!user.verified && <button onClick={verify}>{t("btn-verify_email")}</button>}
        </div>
    );
}
