import React, {Component} from "react";
import {Spin, Col, Row} from "antd";
import {observer} from "mobx-react";
import CompanyListStore from "./../../stores/Company/CompanyListStore";
import Company from "./Company";
import Paginator from "./../../components/Paginator";
import NoContent from "./../../components/NoContent";

@observer
export default class CompanyList extends Component {

    componentDidMount() {
        CompanyListStore.fetch();
    }

    render() {

        return <div>

            <Spin size={"large"} spinning={CompanyListStore.isLoading}>
                <NoContent message="Nebyla nalezena žádná firma" loading={CompanyListStore.isLoading} count={CompanyListStore.companies.length} />

                <Row gutter={20}>
                    {CompanyListStore.companies.map((company) => {
                        return <Col key={company.id} md={12} lg={6}>
                            <Company {...company} />
                        </Col>;
                    })}
                </Row>
            </Spin>

            <Paginator store={CompanyListStore} />

        </div>;

    }
}
