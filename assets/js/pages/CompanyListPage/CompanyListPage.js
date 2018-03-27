import React, {Component} from "react";
import {observer} from "mobx-react";
import {Icon} from "antd";
import CompanyList from "./CompanyList";

@observer
export default class CompanyListPage extends Component {

    render() {

        return (
            <div>

                <h1><Icon type="home" /> Firmy</h1>
                <CompanyList />

            </div>
        );
    }
}
