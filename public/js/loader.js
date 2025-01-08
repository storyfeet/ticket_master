//(Yes I know about arrow functions)

export async function loadJson(path) {
    let res = await fetch(path, {
        method: "get",
    });
    let json = await res.json();
    return json;
}

export function elem(tagname, values) {
    let res = document.createElement(tagname);
    for (let k in values) {
        res[k] = values[k];
    }
    return res;
}

export function drawTicket(parent, tk) {
    let status = tk.status ? "Closed" : "Open";
    let tClass = tk.status ? "closed_ticket" : "open_ticket";
    let infoline = `${tk.ticket_id} (${status}): ${tk.subject} `;
    let userline = `${tk.user_id}: ${tk.name} - ${tk.email}`;

    let dateline = `Opened : ${tk.created_at}. Last Update: ${tk.updated_at} `;

    let div = elem('td', { className: tClass });
    div.appendChild(elem('p', { innerText: infoline }))
    div.appendChild(elem('p', { innerText: userline }))
    div.appendChild(elem('p', { innerText: tk.content }))
    div.appendChild(elem('p', { innerText: dateline }))
    parent.appendChild(div);
    return div;
}

export function drawUserLink(parent, tk, callback) {
    let tClass = tk.status ? "closed_ticket" : "open_ticket";
    let td = elem('td', { className: tClass });
    let btn = elem("button", {
        innerText: "User Tickets",
    });
    btn.onclick = callback;
    td.appendChild(btn);

    parent.appendChild(td);
    return td;

}




