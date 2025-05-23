import {useState,useRef} from "react";
import {useTranslation} from "react-i18next";
import {ErrInput,ErrSelect} from "./ErrView.jsx";

const ORDER_OPTIONS = {
    "user": "lab-order_by_user",
    'email' : 'lab-order_by_email',
    'subject' : 'lab-order_by_subject',
    'content' : 'lab-order_by_content',
    'created' : 'lab-order_by_created',
    'updated' : 'lab-order_by_updated',
};

const STATUS_OPTIONS = {
    "open"   : "lab-status_open",
    "closed" : "lab-status_closed",
    "any"    : "lab-status_any",
    "error" : "Error",
}

export function AdvancedFilter({goTickets,errs,errSetter}){
    let userRef = useRef();
    let contentRef = useRef();
    let perPageRef = useRef();
    let orderByRef = useRef();
    let ascendRef = useRef();
    let statusRef = useRef();

    let {t} = useTranslation();
    function handleSubmit(e){
        e.preventDefault();
        let data = {};
        data.user_like = userRef.current.value;
        data.content_like = contentRef.current.value;
        data.per_page = perPageRef.current.value;
        data.order_by = orderByRef.current.value;
        data.ascending = ascendRef.current.checked;
        data.status = statusRef.current.value;

        let qdata = new URLSearchParams(data).toString();
        let path = "/admin/get_advanced_tickets?"+qdata;
        console.log("Advanced Tickets Path:",path);
        goTickets(path,true,errSetter);

    }

    return <form onSubmit={handleSubmit}>
        <h2>{t("title-advanced_filter")}</h2>
        <ErrInput label="lab-by_user" name="user_like"
                  type="text" inRef={userRef} err={errs?.user_like} /><br />
        <ErrInput label="lab-by_content" name="content_like"
                  type="text" inRef={contentRef} err={errs?.content_like}/><br />
        <ErrSelect label="lab-status" name={"status"} err={errs?.status}
                   inRef={statusRef} defValue="any" options={STATUS_OPTIONS} /><br />

        <ErrSelect label="lab-order_by" name="order_by" err={errs?.order_by}
                   inRef={orderByRef} defValue={"updated"} options={ORDER_OPTIONS} />
        &nbsp;<ErrInput label="lab-ascending_order" name="ascending" err={errs?.ascending}
                   inRef={ascendRef} defValue={true} type="checkbox"  /><br />
        <ErrInput label="lab-tickets_per_page" name="per_page" type="number" inRef={perPageRef} err={errs?.per_page} defValue={3} /><br />
        <input type="submit" value={t("btn-get_tickets")} />
    </form>;
}
