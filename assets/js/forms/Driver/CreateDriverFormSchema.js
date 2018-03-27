import BaseFormSchema from "./../BaseFormSchema";
import CreateDriverStore from "./../../stores/Driver/CreateDriverStore";

export default class CreateDriverFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CreateDriverStore.createDriver({
                    firstName: values.firstName,
                    lastName: values.lastName,
                    email: values.email,
                    phone: values.phone,
                    address: {
                        country: values.country,
                        city: values.city,
                        street: values.street,
                        zip: values.zip,
                    },
                    identificationNumber: values.identificationNumber,
                    taxIdentificationNumber: values.taxIdentificationNumber,
                });
            }
        });
    }

}
