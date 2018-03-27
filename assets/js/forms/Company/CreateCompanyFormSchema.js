import BaseFormSchema from "./../BaseFormSchema";
import CreateCompanyStore from "./../../stores/Company/CreateCompanyStore";

export default class CreateCompanyFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CreateCompanyStore.createCompany({
                    name: values.name,
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
