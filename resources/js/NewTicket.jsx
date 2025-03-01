import { useRef, useState } from "react";
import { postCsrfJson } from "./loader";
import { ErrListView, ErrInput, ErrTextArea } from "./ErrView";
import { useTranslation } from "react-i18next";


export function NewTicket({ goTickets }) {
    let [errs, errSetter] = useState(null);
    let messBox = useRef();
    let subBox = useRef();
    let { t } = useTranslation();

    async function handleSubmit(e) {

        e.preventDefault();
        let newErrs = {};
        if (!messBox.current.value) {
            newErrs.content = ["err-ticket_content_required"];
        }
        if (!subBox.current.value) {
            newErrs.subject = ["err-ticket_subject_required"];
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
        goTickets("/user/get_open");

        return true;

    }

    return (
        <form onSubmit={handleSubmit}>
            <h2>{t("new_ticket")}</h2>
            {errs && <ErrListView errs={errs} errSetter={errSetter} />}
            <ErrInput label="lab-subject" name="subject" type="text" inRef={subBox} err={errs?.subject} /><br />

            <ErrTextArea label="lab-content" name="content" inRef={messBox} cols={50} rows={7} err={errs?.content} /><br />
            <input type="submit" value={t("create_ticket")} />
        </form>
    );
}
