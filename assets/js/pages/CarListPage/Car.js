import React, {Component} from "react";
import PropTypes from "prop-types";
import {observer} from "mobx-react";
import {Card, Icon, Spin} from "antd";

@observer
export default class Car extends Component {

    static propTypes = {
        id: PropTypes.number.isRequired,
        name: PropTypes.string.isRequired,
        kilometres: PropTypes.number.isRequired,
        color: PropTypes.string.isRequired,
    }

    render() {
        const style = {margin: "0 0 20px 0"};

        const description = <div>
            Najeto: {this.props.kilometres}
            <br/>
            <div style={{width: 70}}>
                Barva: <div style={{float: "right", width: 15, height: 15, background: this.props.color}}></div>
            </div>

        </div>;

        return <Card style={style}>
            <Card.Meta description={description} title={this.props.name} />
        </Card>;

    }
}
