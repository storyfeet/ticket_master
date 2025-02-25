import {useEffect, useState} from "react";


export function UpdateView({user,goEditTicket}){
    let [newUpdate,setNewUpdate] = useState(null);
    let [updates,setUpdates] = useState([]);

    // While the listener can't access the changing update list
    // in a long term closure, it can access a setter.
    // This effect listens for a new update and adds it to the list.
    useEffect(()=>{
        if (!newUpdate) return;
        let nUpdates = [...updates];
        nUpdates.push(newUpdate);
        setUpdates(nUpdates);
    },[newUpdate]);


    useEffect(()=> {
        if (!user) {
            console.log("No User");
            return;
        }
        console.log("Try listening user : ",user);
        window.Echo.private(`my_tickets.${user.id}`)
            .listen(".updated",(dat)=>{
                setNewUpdate(dat);
            });
    },[user]);

    if (updates.length === 0){
        return <></>;
    }
    function closerC(n){
        return ()=>{
          setUpdates(updates.filter((_,i)=> i !== n));
        };
    }
    if (updates.length === 0) return <></>;

    let upList = updates.map((up,index) =>
          <UpdatedTicket key={index}
                         ticket={up}
                         closer={closerC(index)}
                         goEditTicket={goEditTicket} />
        );

    return <div className="fixed_top">
        {upList}
    </div>;
}


function UpdatedTicket({ticket,closer,goEditTicket}){
    let tString = shortenDotDot(ticket.subject,25 );
    return (
        <div className="updated">
            <button className="x_close" onClick={closer}>X</button>
            <h2>Ticket Updated : {ticket.id}</h2>
            <p>{tString}</p>
            <button className="x_close" onClick={()=>{
                closer();
                goEditTicket(ticket);
            }}>⭆</button>

        </div>
    );
}

function shortenDotDot(s,len){
    if (s.length < len) return s;
    return s.substring(0,len-3) + "...";
}
