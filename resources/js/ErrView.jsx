import { useTranslation } from "react-i18next";
import {useState,useEffect} from "react";
export function ErrListView({ errs, errSetter ,refresher}) {
    let rErrs = Object.keys(errs).reduce((prev, curr, index) => {
        prev.push(<ErrView key={index} etype={curr} err={errs[curr]} refresher={refresher} />);
        return prev;
    }, []);

    return (
        <>
            {rErrs}
            <button onClick={() => { errSetter(null) }} >Clear</button>
        </>
    );
}


function ErrView({ etype, err,refresher }) {
    let { t } = useTranslation();
    if (etype === "err-wait"){
        return <CountDownView err={err } refresher={refresher}/>;
    }
    return (
        <div className={"ErrView"}>
            <label>{t(etype)}</label>
            {err.map((e, index) => <p key={index}>{t(e)}</p>)}
        </div>
    )
}

function CountDownView({err, refresher}){
    let {t} = useTranslation();
    let [start] = useState( new Date().getTime());
    let [now,nowSetter] = useState(new Date().getTime());

    let toWait = Math.floor(err[0] - (now - start)/1000);

    useEffect(() => {
        setTimeout(()=>{
            if (toWait >= 0)
                nowSetter(new Date().getTime());
        },1000)}
    ,[now] );

    if (toWait < 0) {
        return <div className={"ErrView"}>
            <label>{t("err-wait_over")}</label>
            <p >{t("lab-wait_over")}</p>
            <button onClick={refresher}>{t("btn-refresh")}</button>

        </div>
    }

    return (
        <div className={"ErrView"}>
            <label>{t("err-wait")}</label>
            <p >{t("lab-wait",{count:toWait})}</p>
        </div>
    )
}

export function ErrInput({ label, name, type = "text", inRef, err, value,defValue }) {
    let { t } = useTranslation();
    return (
        <>
            {
                (err?.map(
                    (val, index) => (<p key={index} className="error">{t(val)}</p>)))
            }
            <label className={err && "error"}>
                {t(label)} : <input ref={inRef} type={type} name={name} value={value} defaultValue={defValue}/>
            </label>
        </>
    );
}

export function ErrTextArea({ label, name, rows, cols, inRef, err, value }) {
    let { t } = useTranslation();
    return (
        <>
            {
                (err?.map(
                    (val, index) => (<p key={index} className="error">{t(val)}</p>)))
            }
            <label className={err && "error"}>
                {t(label)} : <textarea ref={inRef} rows={rows} cols={cols} name={name} value={value} />
            </label>
        </>
    )
}

export function ErrSelect({ label, name, inRef, err, value ,options,defValue}) {
    let { t } = useTranslation();

    let opList = Object.keys(options).reduce((acc,k,index)=>{
        acc.push(<option value={k} key={index}>{t(options[k])}</option>);
        return acc;
    },[]);
    return (
        <>
            {
                (err?.map(
                    (val, index) => (<p key={index} className="error">{t(val)}</p>)))
            }
            <label className={err && "error"}>
                {t(label)} :
                <select ref={inRef} name={name} value={value} defaultValue={defValue} >
                    {opList}
                </select>
            </label>
        </>
    );
}
