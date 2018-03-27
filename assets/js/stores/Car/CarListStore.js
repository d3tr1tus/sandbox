import {observable} from "mobx";
import RootStore from "./../RootStore";
import NotificationStore from "./../NotificationStore";

class CarListStore {

    @observable isLoading = false;
    @observable paginator = {page: 1, itemsPerPage: 20, itemsCount: 0};
    @observable cars = [];
    @observable company = null;

    fetch() {
        this.cars = [];
        this.isLoading = true;
        const params = {};

        if (this.company) {
            params.companyId = this.company.id;
        }

        RootStore.api.get("/cars/", params).then(response => {
            this.paginator = response.paginator;
            this.cars = response.items;
            this.isLoading = false;
        }).catch(() => {
            this.cars = [];
            this.isLoading = false;
            NotificationStore.processException(e, "car-list");
        });
    }


}

export default new CarListStore();
