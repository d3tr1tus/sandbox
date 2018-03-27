import BaseFormSchema from "./../BaseFormSchema";
import CompanyModalStore from "./../../stores/Company/CompanyModalStore";

export default class EditCompanyFormSchema extends BaseFormSchema {

    onSubmit() {
        this.form.validateFields((err, values) => {
            if (!err) {
                CompanyModalStore.saveCompany({
                    name: values.name,
                    isActive: values.isActive,
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
