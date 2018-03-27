import React, {Component} from "react";
import PropTypes from "prop-types";
import {SketchPicker} from "react-color";

export default class ColorPicker extends Component {

    static propTypes = {
        onChange: PropTypes.func,
        defaultValue: PropTypes.any,
        value: PropTypes.any,
    }

    constructor(props) {
        super(props);
        this.state = {
            isVisible: false,
            color: props.value,
        };
    }

    handleClick = () => {
        this.setState({isVisible: !this.state.isVisible});
    };

    handleClose = () => {
        this.setState({isVisible: false});
    };

    onChange = (color) => {
        this.props.onChange(color.hex);
        this.setState({color: color.hex});
    };

    render() {

        const styles = {
            color: {
                width: "36px",
                height: "21px",
                borderRadius: "2px",
            },
            swatch: {
                marginTop: 4,
                padding: "5px",
                background: "#fff",
                borderRadius: "1px",
                boxShadow: "0 0 0 1px rgba(0,0,0,.1)",
                display: "inline-block",
                cursor: "pointer",
            },
            popover: {
                // position: "absolute",
                zIndex: "2",
            },
            cover: {
                position: "fixed",
                top: "0px",
                right: "0px",
                bottom: "0px",
                left: "0px",
            },
        };

        styles.color.background = `${this.state.color}`;

        return (
            <div>
                <div style={styles.swatch} onClick={this.handleClick}>
                    <div style={styles.color} />
                </div>
                {this.state.isVisible ? <div style={styles.popover}>
                    <div style={styles.cover} onClick={this.handleClose} />
                    <SketchPicker color={this.state.color} onChange={this.onChange} />
                </div> : null}

            </div>
        );
    }
}
