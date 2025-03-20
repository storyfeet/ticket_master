import {useState} from "react";
export function TreeFold({data}){
    switch (typeof data){
        case "object":
            return <ObjectFold data={data}/>
        case "string":
            if (data.startsWith("<!DOCTYPE html>"))
                return <StringFold s={data}/>
            return data;
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

const ST_MODE = {
    RAW:"raw",
    FRAME:"frame",
    BODY:"body",
}

function StringFold({s}){
    let [stMode,stModeSetter] = useState(ST_MODE.RAW);

    return <div>
    <select defaultValue={stMode} onChange={(e)=>stModeSetter(e.target.value)}>
        <option value={ST_MODE.RAW}>Raw</option>
        <option value={ST_MODE.FRAME}>Frame</option>
        <option value={ST_MODE.BODY}>Body</option>
    </select><br/>
        {stMode === ST_MODE.RAW && s}
        {stMode === ST_MODE.FRAME && <iframe sandbox="" srcDoc={s}></iframe>}
        {stMode === ST_MODE.BODY &&
            "<body" + s.split(/<body(.*)<\/body>(.*)/s)[1]+"</body>"
        }
    </div>;

}
