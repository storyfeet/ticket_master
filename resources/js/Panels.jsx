import { useState, useRef } from "react";
import TicketList from "./TicketList";
import { ErrInput, ErrListView } from "./ErrView";
export function Panel({ user }) {


    let [basePath, basePathSetter] = useState(null);
    let [canGetUser, canGetUserSetter] = useState(false);
    let [errs, errSetter] = useState(null)

    function baseSet(newPath, canGetUser = false) {
        return () => {
            errSetter(null);

            basePathSetter(newPath);
            canGetUserSetter(canGetUser);
        }
    }

    return (
        <>
            {errs && <ErrListView errs={errs} errSetter={errSetter} />}
            {user.isAdmin && <AdminPanel baseSetter={baseSet} errs={errs} errSetter={errSetter} />}
            < UserPanel user={user} baseSetter={baseSet} />
            {basePath && <TicketList basePath={basePath} baseSetter={baseSet} canGetUser={canGetUser} />}
        </>
    );
}

export function AdminPanel({ baseSetter, errs, errSetter }) {
    let emailRef = useRef();

    function handleTicketsByEmail() {
        let email = emailRef.current.value;
        if (!email) {
            errSetter({ "email": ["Please provide Email Address"] });
            return;
        }
        baseSetter(`/admin/get_user_tickets/${email}`)();

    }
    return (
        <div className="admin_panel">
            <button onClick={baseSetter("/admin/get_open", true)}>Open Tickets</button>
            <button onClick={baseSetter("/admin/get_closed", true)}>Closed Tickets</button>
            <div>
                <ErrInput label="Email:" type="text"
                    name="email" inRef={emailRef} err={errs?.email} />
                <button onClick={handleTicketsByEmail} >Tickets By Email</button>
            </div>
        </div >
    );
}


export function UserPanel({ baseSetter }) {
    return (
        <div className="user_panel">
            <button onClick={baseSetter("/user/get_all")}>My Tickets</button>
            <button onClick={baseSetter("/user/get_open")}>My Open Tickets</button>
            <button onClick={baseSetter("/user/get_closed")}>My Closed Tickets</button>
        </div>
    );
}
