import {useState,useRef} from "react";
import {useTranslation} from "react-i18next";
import {ErrInput} from "./ErrView.jsx";

export function AdvancedFilter({goTicketsC}){
    let [errs,errSetter] = useState(null);
    let userRef = useRef();
    let contentRef = useRef();
    let perPageRef = useRef();

    let {t} = useTranslation();
    function handleSubmit(e){
        e.preventDefault();
        let data = {};
        data.user_like = userRef.current.value;
        data.content_like = contentRef.current.value;
        data.per_page = perPageRef.current.value;

        let qdata = new URLSearchParams(data).toString();
        let path = "/admin/get_advanced_tickets?"+qdata;
        console.log("Advanced Tickets Path:",path);
        goTicketsC(path)();

    }

    return <form onSubmit={handleSubmit}>
        <h2>{t("title-advanced_filter")}</h2>
        <ErrInput label="lab-by_user" name="user_like"
                  type="text" inRef={userRef} err={errs?.user_like} /><br />
        <ErrInput label="lab-by_content" name="content_like"
                  type="text" inRef={contentRef} err={errs?.content_like}/><br />

        <ErrInput label="lab-tickets_per_page" name="per_page" type="number" inRef={perPageRef} err={errs?.per_page} defValue={3} /><br />
        <input type="submit" value={t("btn-get_tickets")} />
    </form>;
}
