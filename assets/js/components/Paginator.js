import React, {Component} from "react";
import PropTypes from "prop-types";
import {Pagination} from "antd";

export default class Paginator extends Component {

    static propTypes = {
        store: PropTypes.object.isRequired,
    }

    render() {

        if (this.props.store.paginator.itemsCount === 0) {
            return null;
        }

        return <div style={{textAlign: "center", margin: "25px 0 10px 0"}}>
            <Pagination
                onChange={(page) => {this.props.store.changePage(page);}}
                current={this.props.store.paginator.page}
                showTotal={(total, range) => `${range[0]}-${range[1]} z ${total} položek`}
                pageSize={this.props.store.paginator.itemsPerPage}
                total={this.props.store.paginator.itemsCount} />
        </div>;
    }
}
