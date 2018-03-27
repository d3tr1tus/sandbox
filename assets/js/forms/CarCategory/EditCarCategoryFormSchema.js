import BaseFormSchema from "./../BaseFormSchema";
import CarCategoryModalStore from "./../../stores/CarCategory/CarCategoryModalStore";

export default class EditCompanyFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CarCategoryModalStore.saveCarCategory({
                    name: values.name,
                    isActive: values.isActive,
                });
            }
        });
    }

}
