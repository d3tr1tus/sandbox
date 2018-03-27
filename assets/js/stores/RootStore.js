import {observable, action} from "mobx";
import Api from "./../core/Api";

class RootStore {

    @observable location = null;
    history = null;

    /** @type {Api} */
    api = null;

    cookies = null;
    @observable title = "";

    constructor() {
        this.api = new Api();
    }

    @action _updateLocation(newState) {
        this.location = newState;
    }

}

export default new RootStore();
