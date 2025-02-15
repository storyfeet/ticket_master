import { useRef, useState } from "react";
import { postCsrfJson } from "./loader";
import { ErrInput, ErrTextArea } from "./ErrView";


export function NewTicket() {
    let [errs, errSetter] = useState(null);
    let messBox = useRef();
    let subBox = useRef();

    function handleSubmit(e) {

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
        //TODO
        return true;

    }

    return (
        <form onSubmit={handleSubmit}>
            <h2>New Ticket</h2>
            <ErrInput label="Subject" name="subject" type="text" inRef={subBox} err={errs?.subject} />

            <ErrTextArea label="Content" name="content" inRef={messBox} cols={50} rows={7} err={errs?.content} />
            <input type="submit" />
        </form>
    );
}
