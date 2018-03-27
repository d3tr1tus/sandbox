import React, {Component} from "react";
import PropTypes from "prop-types";
import UserStore from "./../stores/UserStore";
import RootStore from "./../stores/RootStore";

export default class LoggedInContainer extends Component {

    static propTypes = {
        children: PropTypes.any,
    }

    componentDidMount() {
        const okPathNames = [
            "/login",
        ];

        if (!UserStore.user && okPathNames.indexOf(RootStore.location.pathname) === -1) {
            RootStore.history.replace("/admin/login/");
        }
    }

    render() {
        return UserStore.user ? this.props.children : null;
    }

}
