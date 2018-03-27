import CreateCarModelFormSchema from "./CreateCarModelFormSchema";
import CarModelModalStore from "./../../stores/CarModel/CarModelModalStore";

export default class EditCarModelFormSchema extends CreateCarModelFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CarModelModalStore.saveCarModel(values);
            }
        });
    }

}
