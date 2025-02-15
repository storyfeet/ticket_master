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
            {!user.isAdmin && < UserPanel user={user} baseSetter={baseSet} />}
            {basePath && <TicketList basePath={basePath} />}
        </>
    );
}

export function AdminPanel({ baseSetter }) {


    return (
        <div className="admin_panel">
            <button onClick={baseSetter("/tickets/open")}>Open Tickets</button>
            <button onClick={baseSetter("/tickets/closed")}>Closed Tickets</button>
            <button >Tickets By Email</button>
        </div >
    );
}


export function UserPanel({ user }) {
    return (
        <div className="user_panel">
            <button >My Open Tickets</button>
            <button >My Closed Tickets</button>
        </div>
    );
}
