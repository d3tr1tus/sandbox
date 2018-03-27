import React, {Component} from "react";
import {observer} from "mobx-react";
import PropTypes from "prop-types";
import {Alert} from "antd";
import NotificationStore from "./../stores/NotificationStore";

@observer
export default class Notification extends Component {

    static propTypes = {
        type: PropTypes.string.isRequired,
    }

    render() {

        if (NotificationStore.message.length === 0 || NotificationStore.type !== this.props.type) {
            return null;
        }

        return <Alert
            style={{marginBottom: 30}}
            type={NotificationStore.isError ? "error" : "success"}
            showIcon={true}
            message={NotificationStore.message} />;
    }
}
