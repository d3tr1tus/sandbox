import React from "react";
import {Input, Form, Select, Switch} from "antd";
import countries from "./../core/countries";

export default class BaseFormSchema {

    constructor(form) {
        this.form = form;
        this.getFieldDecorator = form.getFieldDecorator;

        const countriesOptions = countries.map(country => {
            return <Select.Option key={country.code} value={country.code}>
                {country.name}
            </Select.Option>;
        });

        this.items = {
            name: {
                id: "name",
                label: "Název",
                requiredMessage: "Prosím vyplňte název",
                options: {},
                element: <Input placeholder="Název" />,
            },
            firstName: {
                id: "firstName",
                label: "Jméno",
                requiredMessage: "Prosím vyplňte jméno",
                options: {},
                element: <Input placeholder="Jméno" />,
            },
            lastName: {
                id: "lastName",
                label: "Příjmení",
                requiredMessage: "Prosím vyplňte příjmení",
                options: {},
                element: <Input placeholder="Příjmení" />,
            },
            email: {
                id: "email",
                label: "E-mail",
                requiredMessage: "Prosím vyplňte email",
                options: {},
                element: <Input placeholder="E-mail" />,
            },
            phone: {
                id: "phone",
                label: "Telefon",
                requiredMessage: "Prosím vyplňte telefon",
                options: {},
                element: <Input placeholder="Telefon" />,
            },
            isPartner: {
                id: "isPartner",
                label: "Partner ano / ne",
                options: {},
                element: <Switch />,
            },
            isActive: {
                id: "isActive",
                label: "Aktivní ano / ne",
                options: {},
                element: <Switch />,
            },
            street: {
                id: "street",
                label: "Ulice",
                requiredMessage: "Prosím vyplňte ulici a ČP",
                options: {},
                element: <Input placeholder="Ulice a ČP" />,
            },
            city: {
                id: "city",
                label: "Město",
                requiredMessage: "Prosím vyplňte město",
                options: {},
                element: <Input placeholder="Město" />,
            },
            zip: {
                id: "zip",
                label: "PSČ",
                requiredMessage: "Prosím vyplňte PSČ",
                options: {},
                element: <Input placeholder="PSČ" />,
            },
            country: {
                id: "country",
                label: "Země",
                requiredMessage: "Prosím vyplňte zemi",
                options: {},
                element: <Select showSearch optionFilterProp="children" style={{width: 200}} placeholder="Vyberte zemi">{countriesOptions}</Select>,
            },
            identificationNumber: {
                id: "identificationNumber",
                label: "IČ",
                requiredMessage: "Prosím vyplňte IČ",
                options: {},
                element: <Input placeholder="IČ" />,
            },
            taxIdentificationNumber: {
                id: "taxIdentificationNumber",
                label: "DIČ",
                requiredMessage: "Prosím vyplňte DIČ",
                options: {},
                element: <Input placeholder="DIČ" />,
            },
        };
    }

    render(id, options = {}) {

        if (!this.items[id]) {
            console.error(`Item ${id} does not exists!`); // eslint-disable-line no-console
            return null;
        }

        if (!this.items[id].options.rules) {
            this.items[id].options.rules = [];
        }

        if (options.initialValue) {
            this.items[id].options.initialValue = options.initialValue;
        }

        const requiredMessage = this._getRequiredMessage(id, options);
        if (requiredMessage) {
            this.items[id].options.rules.push({required: true, message: requiredMessage});
        }

        const label = this._getLabel(id, options);

        return <Form.Item label={label}>
            {this.getFieldDecorator(id, this.items[id].options)(this.items[id].element)}
        </Form.Item>;
    }

    _getLabel(id, options) {

        let label = false;
        if (options.label && options.label.length > 0) {
            label = options.label;
        } else if (options.label && options.label === true) {
            label = this.items[id].label;
        }

        return label;
    }

    _getRequiredMessage(id, options) {
        if (options.required && options.required.length > 0) {
            return options.required;
        }

        if (options.required && options.required === true) {
            const requiredMessage = this.items[id].requiredMessage;
            if (requiredMessage && requiredMessage.length > 0) {
                return requiredMessage;
            }
            console.error(`No require message for item ${id}`); // eslint-disable-line no-console
        }

        return null;
    }

}