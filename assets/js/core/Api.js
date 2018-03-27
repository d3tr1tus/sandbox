import RootStore from "./../stores/RootStore";

export default class Api {

    get(endpoint, queryParams = {}) {
        const ops = {
            method: "GET",
            headers: this._getHeaders(),
        };

        let url = `/api${endpoint}`;
        if (Object.keys(queryParams).length > 0) {
            url += (url.indexOf("?") === -1 ? "?" : "&") + this._queryParams(queryParams);
        }
        return this._fetchJson(url, ops);
    }

    post(endpoint, data = {}) {
        const ops = {
            method: "POST",
            headers: this._getHeaders(data),
            body: this._getData(data),
        };

        return this._fetchJson(`/api${endpoint}`, ops);
    }

    put(endpoint, data = {}) {
        const ops = {
            method: "PUT",
            headers: this._getHeaders(data),
            body: this._getData(data),
        };

        return this._fetchJson(`/api${endpoint}`, ops);
    }

    remove(endpoint) {
        const ops = {
            method: "DELETE",
            headers: this._getHeaders(),
        };
        return this._fetchJson(`/api${endpoint}`, ops);
    }

    _fetchJson(endpoint, options = {}) {
        return new Promise((resolve, reject) => {
            fetch(`${process.env.API_URL}${endpoint}`, options).then(result => {
                if (result.status === 200) {
                    result.json().then(json => resolve(json));
                } else {
                    result.json().then(json => reject(json));
                }
            });
        });
    }

    _getHeaders(data) {
        const headers = {};

        const token = RootStore.cookies.get("token");
        if (token) {
            headers["Authorization"] = `Bearer ${token}`;
        }

        if (data instanceof FormData) {
            return headers; // multipart/form-data
        }

        headers["Content-Type"] = "application/json";
        return headers;
    }

    _getData(data) {
        if (data instanceof FormData) {
            return data;
        }

        return JSON.stringify(data);
    }

    _queryParams(params) {
        return Object.keys(params)
            .map(k => `${encodeURIComponent(k)}=${encodeURIComponent(params[k])}`)
            .join("&");
    }

}
