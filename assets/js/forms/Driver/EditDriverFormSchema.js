import BaseFormSchema from "./../BaseFormSchema";
import DriverModalStore from "./../../stores/Driver/DriverModalStore";

export default class EditDriverFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                DriverModalStore.saveDriver({
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
