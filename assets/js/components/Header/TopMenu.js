import React, {Component} from "react";
import {observer} from "mobx-react";
import {Link} from "react-router-dom";
import PropTypes from "prop-types";
import {Menu, Icon, Layout} from "antd";
import MenuStore from "./../../stores/MenuStore";

@observer
export default class TopMenu extends Component {

    static propTypes = {
        message: PropTypes.string,
    }

    render() {
        return <Menu style={{ lineHeight: '64px' }} theme="dark" selectedKeys={[MenuStore.activeKey]} mode="horizontal">

            <Menu.Item key="/">
                <Link to="/">
                    <Icon type="dashboard" />
                    <span>Nástěnka</span>
                </Link>
            </Menu.Item>

            <Menu.Item key="/companies/">
                <Link to="/companies/">
                    <Icon type="home" />
                    <span>Firmy</span>
                </Link>
            </Menu.Item>

            <Menu.Item key="/cars/">
                <Link to="/cars/">
                    <Icon type="car" />
                    <span>Auta</span>
                </Link>
            </Menu.Item>
        </Menu>;
    }
}
