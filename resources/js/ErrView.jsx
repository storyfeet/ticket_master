
export default function ErrListView({ errs, errSetter }) {
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
