import BaseFormSchema from "./../BaseFormSchema";
import CreateCarCategoryStore from "./../../stores/CarCategory/CreateCarCategoryStore";

export default class CreateCarCategoryFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CreateCarCategoryStore.createCarCategory({
                    name: values.name,
                    isActive: values.isActive,
                });
            }
        });
    }

}
