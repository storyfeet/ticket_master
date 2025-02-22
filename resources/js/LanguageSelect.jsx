import { useTranslation } from "react-i18next";

const languages = [
    { code: "en", name: "English", native: "English" },
    { code: "fr", name: "French", native: "Français" },
]


export function LanguageSelector() {
    let { t, i18n } = useTranslation();

    function selectLanguage(e) {
        i18n.changeLanguage(e.target.value);
    }

    let options = languages.map((lan) => {
        let tx = t(lan.name);
        if (tx !== lan.native) {
            tx += "/" + lan.native;
        }
        return (<option value={lan.code}>{tx}</option>);
    });

    return (
        <select className="language_select" value={i18n.language} onChange={selectLanguage}>
            {options}
        </select>
    );
}

