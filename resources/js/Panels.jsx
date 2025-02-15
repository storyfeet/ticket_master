import { useState } from "react";
import TicketList from "./TicketList";
export function Panel({ user }) {

    let [basePath, basePathSetter] = useState(null);
    let [canGetUser, canGetUserSetter] = useState(false);

    function baseSet(newPath, canGetUser = false) {
        return () => {
            basePathSetter(newPath);
            canGetUserSetter(canGetUser);
        }
    }

    return (
        <>
            {user.isAdmin && <AdminPanel baseSetter={baseSet} />}
            < UserPanel user={user} baseSetter={baseSet} />
            {basePath && <TicketList basePath={basePath} baseSetter={baseSet} canGetUser={canGetUser} />}
        </>
    );
}

export function AdminPanel({ baseSetter }) {


    return (
        <div className="admin_panel">
            <button onClick={baseSetter("/admin/get_open", true)}>Open Tickets</button>
            <button onClick={baseSetter("/admin/get_closed", true)}>Closed Tickets</button>
            <button >Tickets By Email</button>
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
