import './bootstrap';
import '../css/app.css';
import Home from "./Home";

import ReactDOM from 'react-dom/client';
//import Home from './Page/Home';
import "./i18n";

ReactDOM.createRoot(document.getElementById('app')).render(
    <Home />
);


