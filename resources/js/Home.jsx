import { useState } from "react";
import LoginForm from "./Login";
import { Panel } from "./Panels";
import { useTranslation } from "react-i18next";

export default function Home() {
    let [user, setUser] = useState(window.USER_INFO)
    let { t } = useTranslation();

    return (
        <>
            <h1>{t("ticket_slave")}</h1>
            <LoginForm user={user} userSetter={setUser} />
            {user && <Panel user={user} />}
        </>
    );
}
