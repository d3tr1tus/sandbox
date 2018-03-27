import {observable} from "mobx";
import UserStore from "./../stores/UserStore";

class NotificationStore {

    @observable message = "";
    @observable type = "";
    @observable isError = false;

    reset() {
        this.message = "";
        this.type = "";
    }

    processSuccess(message, type) {
        this.message = message;
        this.type = type;
        this.isError = false;
    }

    processException(e, type) {

        if (e.message === "Expired JWT Token" || e.message === "Invalid JWT Token") {
            UserStore.logout();
            return;
        }

        this.isError = true;
        this.message = e.message;
        this.type = type;
    }
}

export default new NotificationStore();
