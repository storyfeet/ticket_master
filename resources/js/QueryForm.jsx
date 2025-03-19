import {loadSafeJson} from "./loader";
import {useRef,useState} from "react";
import {TreeFold} from "./TreeFold.jsx";
export function QueryForm(){
    let pathRef = useRef();
    let [content,contentSetter] = useState();

    function handleSubmit(e){
        e.preventDefault();
        (async ()=> {
            let res = await loadSafeJson(pathRef.current.value);
            //let s = await res.text();
            contentSetter(res);
        })();

    }

    return <>
        <form onSubmit={handleSubmit}>
            <input type="text" ref={pathRef} />
            <input type="submit" />
        </form>
        <div>
            <TreeFold data={content}/>
        </div>
    </>

}
