import {observable} from "mobx";
import RootStore from "./RootStore";
import NotificationStore from "./NotificationStore";

class UserStore {

    @observable token = null;
    @observable user = null;

    @observable isLoggingIn = false;

    loadUser() {
        this.token = RootStore.cookies.get("token");
        this.user = RootStore.cookies.get("user");
    }

    storeToken(token) {
        this.token = token;
        RootStore.cookies.set("token", token, {path: "/"});
    }

    storeUserData(user) {
        this.user = user;
        RootStore.cookies.set("user", user, {path: "/"});
    }

    login(values) {
        this.isLoggingIn = true;
        NotificationStore.reset();

        return RootStore.api.post("/login", values)
            .then((response) => {
                this.storeUserData({email: values.email});
                this.storeToken(response.token);
                RootStore.history.push("/admin/dashboard/");
                this.isLoggingIn = false;
            }).catch((e) => {
                this.isLoggingIn = false;
                NotificationStore.processException(e, "login");
            });
    }

    logout() {
        this.user = null;
        this.token = null;
        RootStore.cookies.remove("token", {path: "/"});
        RootStore.cookies.remove("user", {path: "/"});
        RootStore.history.push("/admin/login/");
    }

}

export default new UserStore();
