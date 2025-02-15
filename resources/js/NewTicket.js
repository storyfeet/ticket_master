import { useRef } from "react";
import { postCsrfJson } from "./loader";


export default function NewTicket() {
    let messBox = useRef();
    let subBox = useRef();

    function handleSubmit() {
    }

    return (
        <form onSubmit={handleSubmit}>
            <label>New Ticket</label>
            <label>Subject <input name="subject" ref={subBox} /></label>

            <textarea name="message" ref={messBox} cols={50} rows={7} />
            <input type="submit" />
        </form>
    );
}
