import React, {} from "react";
import {LocaleProvider} from "antd";
import {Router} from "react-router-dom";
import {render} from "react-dom";
import Cookies from "universal-cookie";
import createBrowserHistory from "history/createBrowserHistory";
import csCZ from "antd/lib/locale-provider/cs_CZ";
import "moment/locale/cs";
import {syncHistoryWithStore} from "./core/syncHistoryWithStore";
import RootStore from "./stores/RootStore";
import App from "./App";

RootStore.cookies = new Cookies();
const browserHistory = createBrowserHistory();
const history = syncHistoryWithStore(browserHistory, RootStore);

class Application extends React.Component {
    render () {
        return <LocaleProvider locale={csCZ}>
            <Router history={history}>
                <App/>
            </Router>
        </LocaleProvider>;
    }
}

render(<Application/>, document.getElementById("app"));
