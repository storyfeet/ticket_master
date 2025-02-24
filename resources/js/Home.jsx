import {useEffect, useState} from "react";
import LoginForm from "./Login";
import { Panel } from "./Panels";
import { useTranslation } from "react-i18next";
import { LanguageSelector } from "./LanguageSelect";

export default function Home() {
    let [user, setUser] = useState(window.USER_INFO)
    let [updates,setUpdates] = useState([]);
    let { t } = useTranslation();

    useEffect(()=> {
        if (!user) {
            console.log("No User");
            return;
        }
        console.log("Try listening user : ",user);
        window.Echo.private(`my_tickets.${user.id}`)
            .listen(".updated",(dat)=>{
                console.log("Updated: ",dat);
                let nUpdates = [...updates];
                nUpdates.push(dat);
                setUpdates(nUpdates);
            });
    },[user]);

    return (
        <>
            <LanguageSelector />
            <h1>{t("ticket_slave")}</h1>
            <LoginForm user={user} userSetter={setUser} />
            {user && <Panel user={user} />}
        </>
    );
}
