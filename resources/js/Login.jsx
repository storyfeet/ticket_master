import { useRef, useState } from "react";
import { postCsrfJson } from "./loader";
import { ErrInput } from "./ErrView";
import { useTranslation } from "react-i18next";

export default function LoginForm({ user, userSetter }) {
    let [errors, setErrors] = useState(null);
    let rEmail = useRef();
    let rPass = useRef();
    let { t } = useTranslation();
    async function handleSubmitLogin(e) {
        e.preventDefault();

        let newErrors = {};

        if (rEmail.current.value == "") {
            newErrors.email = ["err-email_required"];
        }
        if (rPass.current.value == "") {
            newErrors.password = ["err-password_required"];
        }
        if (Object.keys(newErrors).length > 0) {
            setErrors(newErrors)
            return true;
        }

        let login = await postCsrfJson("/login", {
            "email": rEmail.current.value,
            "password": rPass.current.value,
        });
        if (login?.errors) {
            setErrors(login.errors);
            return true;
        }

        if (!login?.errors) {
            userSetter(login);
        }

        return true;
    }



    if (user === null) {

        console.log("CSRF :", window.CSRF_TOKEN);
        return (
            <form onSubmit={handleSubmitLogin} >
                <input type="hidden" name="_token" value={window.CSRF_TOKEN} />
                <ErrInput name="email" label="Email" type="text"
                    inRef={rEmail} err={errors?.email} /><br />
                <ErrInput name="password" label="Password" type="password"
                    inRef={rPass} err={errors?.password} /><br />
                <ErrInput value="Login" type="submit" err={errors?.credentials}
                /><br />
            </form>
        );
    }
    return (
        <h2>{t("welcome")} {user.name} </h2>
    );
}
