import BaseFormSchema from "./../BaseFormSchema";
import CreateUserStore from "./../../stores/User/CreateUserStore";

export default class CreateUserFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CreateUserStore.createUser({
                    firstName: values.firstName,
                    lastName: values.lastName,
                    isPartner: values.isPartner,
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
