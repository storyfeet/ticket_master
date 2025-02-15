
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
    return (
        <div>
            <label>{etype}</label>
            {err.map((e, index) => <p key={index}>{e}</p>)}
        </div>
    )
}

export function ErrInput({ label, name, type, inRef, err, value }) {
    return (
        <>
            {
                (err?.map(
                    (val, index) => (<p key={index} className="error">{val}</p>)))
            }
            <label className={err && "error"}>
                {label} : <input ref={inRef} type={type} name={name} value={value} />
            </label>
        </>
    );
}

