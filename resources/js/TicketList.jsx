import Ticket from "./Ticket";
import { useState, useEffect } from "react";
import * as loader from "./loader";
import { useTranslation } from "react-i18next";
import {ErrListView} from "./ErrView";

export function TicketList({
                        basePath, canGetUser,
                        goTickets, goEditTicket ,
                        refreshTickets,refresher,
                        errs,errSetter}) {
    let [ticketList, setTicketList] = useState([]);
    let { t } = useTranslation();

    async function setPage( newPath ) {
        console.log("pageGetter : path = ", newPath);
        let jsres = await loader.loadJson(newPath);
        if (jsres.errors) errSetter(jsres.errors);
        console.log(jsres);
        console.log("setTicketList", setTicketList);
        setTicketList(jsres);
    }

    function setPageNum(n){
        let qAmp = basePath.includes("?") ? "&" : "?";
        let newPath = `${basePath}${qAmp}page=${n}`;
        setPage(newPath);
    }

    useEffect(() => {
        setPage( basePath );
    }, [basePath,refreshTickets])



    function PageButton({ dir, pNum , disabled}) {
        return (
            <button onClick={()=>setPageNum(pNum)} disabled={disabled}>{t(dir)} {!disabled && ! isNaN(pNum) && `(${pNum})`}</button>
        );
    }
    function PageSelect({pNum,max}){
        let ops = [];
        for (let i =1; i <= max; i++){
            ops.push(<option value={i} key={i}>{i===pNum && t("lab-current_page")} {i}</option>);
        }
        return <select
                defaultValue={pNum}
                onChange={(e)=>{setPageNum(e.target.value)}}
            >{ops}
            </select>
    }

    let tlist = ticketList.data?.map((ticket, index) => (
        <Ticket key={index} ticket={ticket}
            goTickets={goTickets} canGetUser={canGetUser} goEditTicket={goEditTicket} />
    ));
    return (
        <div className="ticket_list">
            {errs && <ErrListView errs={errs} errSetter={errSetter} refresher={refresher}/>}
            <h2>Tickets</h2>
            <PageButton dir="dir-first" pNum={1}
                        disabled={ticketList.current_page === 1}/>
            <PageButton dir="dir-prev" pNum={ticketList.current_page -1}
                        disabled={ticketList.current_page === 1}/>
            <PageSelect pNum={ticketList.current_page} max={ticketList.last_page}/>
            <PageButton dir="dir-next" pNum={ticketList.current_page +1}
                        disabled={ticketList.current_page === ticketList.last_page}/>
            <PageButton dir="dir-last" pNum={ticketList.last_page}
                        disabled={ticketList.current_page === ticketList.last_page}/>
            <table>
                <tbody>
                    {tlist || <tr><td>{t("stat-no_tickets")}</td></tr>}
                </tbody>
            </table>
        </div>
    );
}



