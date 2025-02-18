import { useState, useRef } from "react";
import TicketList from "./TicketList";
import { ErrInput, ErrListView } from "./ErrView";
import { NewTicket } from "./NewTicket";
import { DISPLAY_MODE } from "./util";



export function Panel({ user }) {



    let [basePath, basePathSetter] = useState(null);
    let [canGetUser, canGetUserSetter] = useState(false);
    let [errs, errSetter] = useState(null)
    let [displayMode, displaySetter] = useState(DISPLAY_MODE.NONE)


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

    return (
        <>
            {errs && <ErrListView errs={errs} errSetter={errSetter} />}
            {user.isAdmin && <AdminPanel goTicketsC={goTicketsC} errs={errs} errSetter={errSetter} />}
            < UserPanel user={user} goTicketsF={goTicketsC} goNewTicket={goNewTicket} />
            {basePath && displayMode === DISPLAY_MODE.TICKETS &&
                <TicketList basePath={basePath} goTicketsC={goTicketsC} canGetUser={canGetUser} />}
            {displayMode === DISPLAY_MODE.NEW_TICKET &&
                <NewTicket goTicketsC={goTicketsC} />}

        </>
    );
}

export function AdminPanel({ goTicketsC, errs, errSetter }) {
    let emailRef = useRef();

    function handleTicketsByEmail() {
        let email = emailRef.current.value;
        if (!email) {
            errSetter({ "email": ["Please provide Email Address"] });
            return;
        }
        goTicketsC(`/admin/get_user_tickets/${email}`)();

    }
    return (
        <div className="admin_panel">
            <button onClick={goTicketsC("/admin/get_open", true)}>Open Tickets</button>
            <button onClick={goTicketsC("/admin/get_closed", true)}>Closed Tickets</button>
            <div>
                <ErrInput label="Email:" type="text"
                    name="email" inRef={emailRef} err={errs?.email} />
                <button onClick={handleTicketsByEmail} >Tickets By Email</button>
            </div>
        </div >
    );
}


export function UserPanel({ goTicketsF, goNewTicket }) {
    return (
        <div className="user_panel">
            <button onClick={goTicketsF("/user/get_all")}>My Tickets</button>
            <button onClick={goTicketsF("/user/get_open")}>My Open Tickets</button>
            <button onClick={goTicketsF("/user/get_closed")}>My Closed Tickets</button>
            <button onClick={goNewTicket}>Create New Ticket</button>
        </div>
    );
}
