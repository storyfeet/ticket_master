
let lang_files = {};

let lang = "en";

async function loadLang(code) {
    let path = `/languages/${code}`;
    let langData = await fetch({});
}

async function getLang(code) {
    if (lang_files[code]) return lang_files[code];
    let res = loadLang(code);
    if
    return loadLang(code);
}

export function setLang(lang, fallbacks) {
}

export function lang() {
}




