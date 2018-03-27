import React, {Component} from "react";
import PropTypes from "prop-types";
import {Alert} from "antd";
import {observer} from "mobx-react";

@observer
export default class UsersList extends Component {

    static propTypes = {
        message: PropTypes.string.isRequired,
        loading: PropTypes.bool,
        count: PropTypes.number.isRequired,
    }

    render() {

        const loading = this.props.loading || false;

        if (loading || this.props.count > 0) {
            return null;
        }

        return <Alert style={{fontSize: 20, padding: 30}} message={this.props.message} type="info" />;

    }
}
