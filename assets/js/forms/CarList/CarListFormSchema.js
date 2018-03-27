import React from "react";
import {Input, Select} from "antd";
import BaseFormSchema from "./../BaseFormSchema";
import CarListStore from "../../stores/Car/CarListStore";
import CarModelsListStore from "./../../stores/CarModel/CarModelsListStore";
import ColorPicker from "./../../components/ColorPicker";

export default class CarListFormSchema extends BaseFormSchema {

    constructor(form, options) { // eslint-disable-line max-params
        super(form);

        this.options = options;

        const onChange = () => {
            CarListStore.onChange(options.index);
        };

        const carModels = CarModelsListStore.carModels.map(carModel => {
            return <Select.Option key={carModel.id} value={carModel.id}>
                {carModel.name}
            </Select.Option>;
        });

        const markings = [
            {id: "magnets", name: "Magnety"},
            {id: "stickers", name: "Samolepky"},
        ];

        const markingsOptions = markings.map(marking => {
            return <Select.Option key={marking.id} value={marking.id}>
                {marking.name}
            </Select.Option>;
        });

        this.items.carModelId = {
            id: "carModelId",
            label: "Model",
            requiredMessage: "Prosím vyberte model vozidla",
            options: {initialValue: options.car.carModel.id, rules: [{required: true, message: "Prosím vyberte model vozidla"}]},
            element: <Select onChange={onChange} optionFilterProp="children" placeholder="Vyberte model vozdila" type="number">{carModels}</Select>,
        };

        this.items.marking = {
            id: "marking",
            label: "Označení",
            requiredMessage: "Prosím vyberte označení vozidla",
            options: {initialValue: options.car.marking, rules: [{required: true, message: "Prosím vyberte označení vozidla"}]},
            element: <Select onChange={onChange} optionFilterProp="children" placeholder="Vyberte označení vozdila" type="number">{markingsOptions}</Select>,
        };

        this.items.licensePlate = {
            id: "licensePlate",
            label: "Značka",
            options: {initialValue: options.car.licensePlate, rules: [{required: true, message: "Prosím vyplňte poznávací značku"}]},
            element: <Input onChange={onChange} placeholder="Poznávací značka" />,
        };

        this.items.yearOfManufacture = {
            id: "yearOfManufacture",
            label: "Rok výroby",
            options: {initialValue: options.car.yearOfManufacture, rules: [{required: true, message: "Prosím vyplňte rok výroby"}]},
            element: <Input onChange={onChange} placeholder="Rok výroby" />,
        };

        this.items.color = {
            id: "color",
            label: "Barva",
            options: {initialValue: options.car.color, rules: [{required: true, message: "Prosím vyberte barvu"}]},
            element: <ColorPicker onChange={onChange} />,
        };

    }

    onSubmit() {
        this.form.validateFields((err, values) => {
            const vals = {...values};
            vals.id = this.options.car.id;
            vals.index = this.options.index;
            vals.driverId = this.options.driverId;
            vals.companyId = this.options.companyId;

            if (!err) {
                CarListStore.createCar(vals);
            }
        });
    }

}
