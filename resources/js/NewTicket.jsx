import { useRef, useState } from "react";
import { postCsrfJson } from "./loader";
import { ErrListView, ErrInput, ErrTextArea } from "./ErrView";


export function NewTicket({ goTicketsC }) {
    let [errs, errSetter] = useState(null);
    let messBox = useRef();
    let subBox = useRef();

    async function handleSubmit(e) {

        e.preventDefault();
        let newErrs = {};
        if (!messBox.current.value) {
            newErrs.content = ["Please provide some content/context for your ticket"];
        }
        if (!subBox.current.value) {
            newErrs.subject = ["Please provide a subject for your ticket"];
        }
        if (Object.keys(newErrs).length > 0) {
            errSetter(newErrs)
            return true;
        } else {
            errSetter(null)
        }

        let res = await postCsrfJson("/user/new_ticket", {
            subject: subBox.current.value,
            content: messBox.current.value,
        });

        if (res.errors) {
            errSetter(res.errors);
        }
        goTicketsC("/user/get_open")();

        return true;

    }

    return (
        <form onSubmit={handleSubmit}>
            <h2>New Ticket</h2>
            {errs && <ErrListView errs={errs} errSetter={errSetter} />}
            <ErrInput label="Subject" name="subject" type="text" inRef={subBox} err={errs?.subject} /><br />

            <ErrTextArea label="Content" name="content" inRef={messBox} cols={50} rows={7} err={errs?.content} /><br />
            <input type="submit" value="Create Ticket" />
        </form>
    );
}
