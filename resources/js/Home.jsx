import { useState } from "react";
import TicketList from "./TicketList";
import LoginForm from "./Login";

export default function Home() {
    let [user, setUser] = useState(null)

    return (
        <>
            <h1>Ticket Master</h1>
            <LoginForm user={user} userSetter={setUser} />
            <TicketList />
        </>
    );
}
