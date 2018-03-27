import BaseFormSchema from "./../BaseFormSchema";
import UserModalStore from "./../../stores/User/UserModalStore";

export default class EditUserFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                UserModalStore.saveUser({
                    firstName: values.firstName,
                    lastName: values.lastName,
                    phone: values.phone,
                    isActive: values.isActive,
                    isPartner: values.isPartner,
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
