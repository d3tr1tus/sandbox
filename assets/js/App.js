import React from "react";
import PropTypes from "prop-types";
import {observer} from "mobx-react";
import {Route, Switch, withRouter} from "react-router";
import {Layout} from "antd";

import UserStore from "./stores/UserStore";
import MenuStore from "./stores/MenuStore";
import Header from "./components/Header/Header";

import CompanyListPage from "./pages/CompanyListPage/CompanyListPage";
import CarListPage from "./pages/CarListPage/CarListPage";
import DashboardPage from "./pages/DashboardPage";

import NotFoundPage from "./pages/NotFoundPage";

@withRouter
@observer
export default class App extends React.Component {

    static propTypes = {
        history: PropTypes.object.isRequired,
    }

    componentWillMount() {
        UserStore.loadUser();

        this.props.history.listen(location => {
            MenuStore.onPathnameChange(location.pathname);
        });

        MenuStore.onPathnameChange(this.props.history.location.pathname);
    }

    render () {
        return <Layout style={{ minHeight: "100vh" }}>

            <Layout>

                <Header />

                <br/>

                <Layout.Content style={{ margin: "0 16px" }}>

                    <div style={{ padding: 24, background: "#fff", minHeight: 360 }}>

                        <Switch>

                            <Route exact path="/" component={DashboardPage} />
                            <Route exact path="/cars/" component={CarListPage} />
                            <Route exact path="/companies/" component={CompanyListPage} />

                            <Route component={NotFoundPage} />
                        </Switch>

                    </div>
                </Layout.Content>

                <Layout.Footer style={{ textAlign: "center" }}>
                    Atreo sandbox © 2018 - Vytvořilo Atreo digital s.r.o.
                </Layout.Footer>
            </Layout>
        </Layout>;
    }
}
