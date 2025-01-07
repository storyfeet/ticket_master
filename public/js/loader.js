//(Yes I know about arrow functions)

export async function LoadTickets(open) {
    let path = open ? "/open" : "/closed";
    let res = await fetch(path, {
        method: "get",
    });
    let json = await res.json();
    return json;
}




