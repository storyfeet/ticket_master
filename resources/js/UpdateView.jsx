

export function UpdateView({updates,setUpdates}){
    if (updates.length == 0){
        return <></>;
    }
    function closerC(n){
        return ()=>{
          setUpdates(updates.filter((_,i)=> i !== n));
        };
    }

    let upList = updates.map((up,index) =>
          <UpdatedTicket key={index} ticket={up} closer={closerC(index)} />
        );

    return <div className="fixed_top">
        {upList}
    </div>;
}


function UpdatedTicket({ticket,closer}){
    let tString = shortenDotDot(ticket.subject,25 );
    return (
        <div className="updated">
            <button className="x_close" onClick={closer}>X</button>
            <h2>Ticket Updated : {ticket.id}</h2>
            <p>{tString}</p>
        </div>
    );
}

function shortenDotDot(s,len){
    if (s.length < len) return s;
    return s.substring(0,len-3) + "...";
}
