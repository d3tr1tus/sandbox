import React, {Component} from "react";
import PropTypes from "prop-types";
import {observer} from "mobx-react";
import {Card, Icon, Spin} from "antd";

@observer
export default class UsersList extends Component {

    static propTypes = {
        id: PropTypes.number.isRequired,
        name: PropTypes.string.isRequired,
        identificationNumber: PropTypes.string.isRequired,
        taxIdentificationNumber: PropTypes.string.isRequired,
    }

    render() {

        const style = {margin: "0 0 20px 0"};

        const description = <div>
            IČ: {this.props.identificationNumber} DIČ: {this.props.taxIdentificationNumber}
        </div>;

        return <Card style={style}>
            <Card.Meta description={description} title={this.props.name} />
        </Card>;

    }
}
