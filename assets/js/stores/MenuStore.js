import {observable} from "mobx";

class MenuStore {

    @observable isCollapsed = false;
    @observable activeKey = null;

    toggleCollapses() {
        this.isCollapsed = !this.isCollapsed;
    }

    onPathnameChange(pathname) {
        this.activeKey = pathname;
    }

}

export default new MenuStore();
