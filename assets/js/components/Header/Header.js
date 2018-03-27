import React, {Component} from "react";
import {Layout, Row, Col} from "antd";
import TopMenu from "./TopMenu";

export default class Header extends Component {

    render() {
        return <Layout.Header className="header">
            <Row gutter={30}>
                <Col md={4}>
                    <h1 style={{color: "white"}}>Atreo sandbox</h1>
                </Col>
                <Col md={6}>
                    <TopMenu />
                </Col>
            </Row>
        </Layout.Header>;
    }
}
