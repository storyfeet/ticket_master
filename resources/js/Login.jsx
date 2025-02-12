
export default function LoginForm() {
    return (
        <form >
            <label>Email : <input type="text" name="email" /></label><br />
            <label>Password : <input type="password" name="password" /></label>
            <input type="submit" value="Login" />
        </form>
    );
}
