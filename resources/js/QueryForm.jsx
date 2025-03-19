import {loadJson} from "./loader";
import {useRef,useState} from "react";

export function QueryForm(){
    let pathRef = useRef();
    let [content,contentSetter] = useState();

    function handleSubmit(e){
        e.preventDefault();
        (async ()=> {
            let res = await fetch(pathRef.current.value);
            let s = await res.text();
            contentSetter(s);
        })();

    }

    return <>
        <form onSubmit={handleSubmit}>
            <input type="text" ref={pathRef} />
            <input type="submit" />
        </form>
        <div>
            <code>{content}</code>
        </div>
    </>

}
