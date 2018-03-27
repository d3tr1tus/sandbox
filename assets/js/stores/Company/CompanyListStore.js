import {observable} from "mobx";
import RootStore from "./../RootStore";
import NotificationStore from "./../NotificationStore";

class CompanyListStore {

    @observable isLoading = false;
    @observable paginator = {page: 1, itemsPerPage: 20, itemsCount: 0};
    @observable companies = [];

    changePage(page) {
        this.paginator.page = page;
        this.load();
    }

    fetch() {
        this.companies = [];
        this.paginator.itemsCount = 0;

        this.isLoading = true;
        NotificationStore.reset();

        const query = {page: this.paginator.page, itemsPerPage: this.paginator.itemsPerPage};

        RootStore.api.get("/companies/", query).then(response => {
            this.paginator = response.paginator;
            this.companies = response.items;
            this.isLoading = false;
        }).catch((e) => {
            this.isLoading = false;
            NotificationStore.processException(e, "company-list");
        });
    }
}

export default new CompanyListStore();
