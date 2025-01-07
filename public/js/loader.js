//(Yes I know about arrow functions)

export async function loadTickets(open) {
    let path = open ? "/tickets/open" : "/tickets/closed";
    let res = await fetch(path, {
        method: "get",
    });
    let json = await res.json();
    return json;
}

export function elem(tagname, values) {
    let res = document.createElement(tagname);
    for (k in values) {
        res[k] = values[k];
    }
    return res;
}




