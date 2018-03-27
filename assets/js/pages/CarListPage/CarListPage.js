import React, {Component} from "react";
import {observer} from "mobx-react";
import {Icon, Button} from "antd";
import CarList from "./CarList";

@observer
export default class CarListPage extends Component {

    render() {

        return (
            <div>

                <h1><Icon type="car" /> Vozidla</h1>
                <CarList />

            </div>
        );
    }
}
