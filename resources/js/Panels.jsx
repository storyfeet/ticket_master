import { useState } from "react";
import TicketList from "./TicketList";
export function Panel({ user }) {

    let [basePath, basePathSetter] = useState(null);

    function baseSet(newPath) {
        return () => {
            basePathSetter(newPath);
        }
    }

    return (
        <>
            {user.isAdmin && <AdminPanel baseSetter={baseSet} />}
            < UserPanel user={user} baseSetter={baseSet} />
            {basePath && <TicketList basePath={basePath} />}
        </>
    );
}

export function AdminPanel({ baseSetter }) {


    return (
        <div className="admin_panel">
            <button onClick={baseSetter("/admin/get_open")}>Open Tickets</button>
            <button onClick={baseSetter("/admin/get_closed")}>Closed Tickets</button>
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
