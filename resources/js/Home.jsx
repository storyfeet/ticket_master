import {useEffect, useState} from "react";
import LoginForm from "./Login";
import { Panel } from "./Panels";
import { useTranslation } from "react-i18next";
import { LanguageSelector } from "./LanguageSelect";
import {UpdateView} from "./UpdateView.jsx";

export default function Home() {
    let [user, setUser] = useState(window.USER_INFO)
    let [newUpdate,setNewUpdate] = useState(null);
    let [updates,setUpdates] = useState([]);
    let { t } = useTranslation();

    useEffect(()=>{
        if (!newUpdate) return;
        console.log("oldUpdates",updates);
        let nUpdates = [...updates];
        console.log("Updated: ",newUpdate);
        nUpdates.push(newUpdate);
        console.log("update_list:",nUpdates)
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

    return (
        <>
            <LanguageSelector />
            {updates && <UpdateView updates={updates} setUpdates={setUpdates}/>}
            <h1>{t("ticket_slave")}</h1>
            <LoginForm user={user} userSetter={setUser} />
            {user && <Panel user={user} />}
        </>
    );
}
