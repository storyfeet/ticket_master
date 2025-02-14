
export default function LoginForm({ user, userSetter }) {

    async function handleSubmit() {
        login = await fetch();
        //TODO

    }

    if (user === null) {

        console.log("CSRF :", window.CSRF_TOKEN);
        return (
            <form onSubmit={handleSubmit} action="/loginjson" method="post">
                <input type="hidden" name="_token" value={window.CSRF_TOKEN} />
                <label>Email : <input type="text" name="email" /></label><br />
                <label>Password : <input type="password" name="password" /></label>
                <input type="submit" value="Login" />
            </form>
        );
    }
    return (
        <h2>Welcome {user.name}</h2>
    );
}
