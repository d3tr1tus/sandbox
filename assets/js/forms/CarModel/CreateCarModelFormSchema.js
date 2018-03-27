import React from "react";
import {Input, Select} from "antd";
import BaseFormSchema from "./../BaseFormSchema";
import CreateCarModelStore from "./../../stores/CarModel/CreateCarModelStore";
import CarCategoriesListStore from "./../../stores/CarCategory/CarCategoriesListStore";

export default class CreateCarCategoryFormSchema extends BaseFormSchema {

    constructor(form) {
        super(form);

        const carCategories = CarCategoriesListStore.carCategories.map(carCategory => {
            return <Select.Option key={carCategory.id} value={carCategory.id}>
                {carCategory.name}
            </Select.Option>;
        });

        this.items.capacity = {
            id: "capacity",
            label: "Kapacita",
            requiredMessage: "Prosím vyplňte kapacitu modelu",
            options: {},
            element: <Input type="number" placeholder="Kapacita" />,
        };

        this.items.carCategoryIds = {
            id: "carCategoryIds",
            label: "Kategorie vozidel",
            requiredMessage: "Prosím vyberte kategorie vozidel",
            options: {},
            element: <Select optionFilterProp="children" mode="multiple" placeholder="Vyberte kategorii vozidel" type="number">{carCategories}</Select>,
        };
    }

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CreateCarModelStore.createCarModel(values);
            }
        });
    }

}
