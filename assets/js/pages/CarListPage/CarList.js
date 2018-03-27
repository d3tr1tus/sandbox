import React, {Component} from "react";
import {Spin, Col, Row} from "antd";
import {observer} from "mobx-react";
import CarListStore from "./../../stores/Car/CarListStore";
import Car from "./Car";
import Paginator from "./../../components/Paginator";
import NoContent from "./../../components/NoContent";

@observer
export default class CarList extends Component {

    componentDidMount() {
        CarListStore.fetch();
    }

    render() {

        return <div>

            <Spin size={"large"} spinning={CarListStore.isLoading}>
                <NoContent message="Nebylo nalezeno žádné vozdilo" loading={CarListStore.isLoading} count={CarListStore.cars.length} />

                <Row gutter={20}>
                    {CarListStore.cars.map((car) => {
                        return <Col key={car.id} md={12} lg={6}>
                            <Car {...car} />
                        </Col>;
                    })}
                </Row>
            </Spin>

            <Paginator store={CarListStore} />

        </div>;

    }
}
