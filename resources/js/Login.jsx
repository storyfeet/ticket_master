
export default function LoginForm({ user, userSetter }) {

    async function handleSubmit() {
        login = await fetch();
        //TODO

    }

    if (user === null) {

        return (
            <form onSubmit={handleSubmit}>
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
