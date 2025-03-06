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
import {postCsrfJson} from "./loader.js";




export function Panel({ user }) {



    let [basePath, basePathSetter] = useState(null);
    let [refreshTickets,refreshTicketsSetter] = useState(new Date().getTime());
    let [canGetUser, canGetUserSetter] = useState(false);
    let [errs, errSetter] = useState(null)
    let [displayMode, displaySetter] = useState(DISPLAY_MODE.NONE)
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
            {user.isAdmin && <AdminPanel goTickets={goTickets} errs={errs} errSetter={errSetter} />}
            < UserPanel user={user} goTickets={goTickets} goNewTicket={goNewTicket} />
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
};

export function AdminPanel({ goTickets, errs, errSetter }) {
    let { t } = useTranslation();
    let [display,displaySetter ] = useState(ADMIN_DISPLAY.NONE);

    function toggleDisplay(mode){
        if (display === mode){
            displaySetter(ADMIN_DISPLAY.NONE);
        }else {
            displaySetter(mode);
        }
    }
    function qGoTickets(path){
        return ()=>{
            displaySetter(ADMIN_DISPLAY.NONE);
            goTickets(path,true);
        }
    }
    return (
        <div className="admin_panel">
            <button onClick={qGoTickets("/admin/get_open" )}>
                {t("open_tickets")}</button>
            <button onClick={qGoTickets("/admin/get_closed")}>
                {t("closed_tickets")}</button>
            <button onClick={()=>{toggleDisplay(ADMIN_DISPLAY.EMAIL_FILTER)}}>{
                t(display=== ADMIN_DISPLAY.EMAIL_FILTER ? "btn-close_email_filter":"btn-go_email_filter")
            }</button>
            <button onClick={()=>{toggleDisplay(ADMIN_DISPLAY.ADVANCED_FILTER)}}>{
                t(display=== ADMIN_DISPLAY.ADVANCED_FILTER ? "btn-close_advanced_filter":"btn-go_advanced_filter")
            }</button>
            <button onClick={()=>{toggleDisplay(ADMIN_DISPLAY.CREATE_USER)}}>{
                t(display=== ADMIN_DISPLAY.CREATE_USER ? "btn-close_create_user":"btn-go_create_user")
            }</button>
            {display === ADMIN_DISPLAY.CREATE_USER && <CreateUser />}
            {display === ADMIN_DISPLAY.EMAIL_FILTER && <EmailFilter goTickets={goTickets} />}
            {display === ADMIN_DISPLAY.ADVANCED_FILTER &&
                <AdvancedFilter goTickets={goTickets} errs={errs} errSetter={errSetter}/>}
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
