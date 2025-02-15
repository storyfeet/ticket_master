import { useState } from "react";
import TicketList from "./TicketList";
import LoginForm from "./Login";
import { Panel } from "./Panels";

export default function Home() {
    let [user, setUser] = useState(window.USER_INFO)

    return (
        <>
            <h1>Ticket Master</h1>
            <LoginForm user={user} userSetter={setUser} />
            {user && <Panel user={user} />}
        </>
    );
}
