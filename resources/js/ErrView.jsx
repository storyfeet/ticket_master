import { useTranslation } from "react-i18next";
export function ErrListView({ errs, errSetter }) {
    let rErrs = Object.keys(errs).reduce((prev, curr, index) => {
        prev.push(<ErrView key={index} etype={curr} err={errs[curr]} />);
        return prev;
    }, []);

    return (
        <>
            {rErrs}
            <button onClick={() => { errSetter(null) }} >Clear</button>
        </>
    );
}


function ErrView({ etype, err }) {
    let { t } = useTranslation();
    return (
        <div>
            <label>{t(etype)}</label>
            {err.map((e, index) => <p key={index}>{t(e)}</p>)}
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

