import { useRef } from "react";
import { postCsrfJson } from "./loader";

export default function LoginForm({ user, userSetter }) {

    let rEmail = useRef();
    let rPass = useRef();
    async function handleSubmitLogin(e) {
        e.preventDefault();

        if (rEmail.current.value == "") {
            alert("No Email for login");
            return true;
        }
        if (rPass.current.value == "") {
            alert("No Password for login");
            return true;
        }

        let login = await postCsrfJson("/loginjson", {
            "email": rEmail.current.value,
            "password": rPass.current.value,
        });

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
                <label>Email : <input ref={rEmail} type="text" name="email" /></label><br />
                <label>Password : <input ref={rPass} type="password" name="password" /></label>
                <input type="submit" value="Login" />
            </form>
        );
    }
    return (
        <h2>Welcome {user.name}  <a href="/logout" >Logout</a></h2>
    );
}
