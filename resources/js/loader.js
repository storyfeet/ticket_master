


export async function postCsrfJson(path, data) {
    let res = await fetch(path, {
        method: "post",
        headers: {
            "X-CSRF-Token": window.CSRF_TOKEN,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
        credentials: "same-origin",
    });
    return await res.json();
}

export async function loadJson(path,data) {
    let res = await fetch(path, {
        method: "get",
    });
    return await res.json();
}

export async function closeTicket(ticketId, csrf_token) {
    let formData = new FormData();
    formData.append('ticket_id', ticketId);
    console.log("Posting ticket_id as :", ticketId);
    let res = await fetch("tickets/close_ticket", {
        method: "post",
        headers: {
            "X-CSRF-Token": csrf_token,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ ticket_id: ticketId }),
        credentials: "same-origin",
    });
    let json = await res.json();
    return json;
}


export function ticketBuilder(messages) {

    let result = {}


    let elem = (tagname, values) => {
        let res = document.createElement(tagname);
        for (let k in values) {
            res[k] = values[k];
        }
        return res;
    }
    result.elem = elem;

    result.drawTicket = (parent, tk) => {
        let status = tk.status ? messages.status_closed : messages.status_open;
        let tClass = tk.status ? "closed_ticket" : "open_ticket";
        let infoline = `${tk.ticket_id} (${status}): <strong>${tk.subject}</strong> `;
        let userline = `${tk.user_id}: ${tk.name} - ${tk.email}`;

        let dateline = `${messages.created_at} : ${tk.created_at}. ${messages.last_update}: ${tk.updated_at} `;

        let div = elem('td', { className: tClass });
        div.appendChild(elem('p', { innerHTML: infoline }))
        div.appendChild(elem('p', { innerText: userline }))
        div.appendChild(elem('p', { innerText: tk.content }))
        div.appendChild(elem('p', { innerText: dateline }))
        parent.appendChild(div);
        return div;
    }

    result.drawUserLink = (parent, tk, callback) => {
        let tClass = tk.status ? "closed_ticket" : "open_ticket";
        let td = elem('td', { className: tClass });
        let btn = elem("button", {
            innerText: messages.users_tickets,
        });
        btn.onclick = callback;
        td.appendChild(btn);

        parent.appendChild(td);
        return td;
    }

    result.drawCloser = (parent, callback) => {
        let td = elem('td', { className: "open_ticket" });
        let btn = elem("button", {
            innerText: messages.close_ticket,
        });
        btn.onclick = callback;
        td.appendChild(btn);

        parent.appendChild(td);
        return td;
    }
    return result;
}

