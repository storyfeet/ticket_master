import {useState} from "react";
export function TreeFold({data}){
    switch (typeof data){
        case "object":
            return <ObjectFold data={data}/>
        default:
            return String(data);
    }
}

function ObjectFold({data}){
    let inner =
        Object.keys(data || {})
        .reduce((acc,k,index)=>{
            acc.push(<KeyFold key={index} k={k} v={data[k]} ></KeyFold>);
            return acc;
    } ,[])
    return <div className="tree_fold_div">
        {inner}
    </div>
}

function KeyFold({k,v}){
    let [show,showSetter] = useState(true);
    return <>
        <input type="checkbox"
               defaultChecked={true}
               onChange={(e)=>{
                   showSetter(e.target.checked);
               }}
               />
        {k}:
        {show && <TreeFold data={v}/>}
        <br/>
    </>;
}
