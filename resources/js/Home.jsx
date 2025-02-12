
import TicketList from "./TicketList";
import LoginForm from "./Login";

export default function Home() {
    return (
        <>
            <h1>Ticket Master</h1>
            <LoginForm />
            <TicketList />
        </>
    );
}
