import {useRef,useState} from "react";
import {ErrInput,ErrListView} from "./ErrView.jsx";
import {postCsrfJson} from "./loader.js";
import {useTranslation} from "react-i18next";

export function CreateUser(){
    let {t} = useTranslation();
    let [errs,errSetter] = useState(null);
    let nameRef = useRef();
    let emailRef = useRef();
    let passRef = useRef();
    function handleSubmit(e){
        e.preventDefault();
        (async () =>{
            let dat = await postCsrfJson(
                "/admin/create_user",
                {
                    name:nameRef.current.value,
                    email:emailRef.current.value,
                    password:passRef.current.value,
                }
            );
            if (dat.errors) {
                errSetter(dat.errors);
                return;
            }
            console.log("User Created", dat);
        })();
    }
    return <>
    {errs && <ErrListView errs={errs} errSetter={errSetter} /> }
        <form onSubmit={handleSubmit}>
        <ErrInput label="lab-name" type="text" name="name"
            inRef={nameRef} err={errs?.name}/>
        <ErrInput label="lab-email" type="email" name="email"
            inRef={emailRef} err={errs?.email} />
        <ErrInput label="lab-password" type="password" name="password"
             inRef={passRef} err={errs?.password} />
        <input type="submit" value={t("btn-create_user")}/>
        </form>
    </>;
}
