import React, {Component} from "react";
import PropTypes from "prop-types";
import {Button} from "antd";

export default class Loader extends Component {

    static propTypes = {
        message: PropTypes.string,
    }

    render() {
        return <Button type="primary" loading style={{marginBottom: 20}}>
            {this.props.message || "Načítám"}
        </Button>;
    }
}
