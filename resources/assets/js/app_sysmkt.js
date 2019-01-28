class Errors {
    constructor() {
        this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any() {
        return Object.keys(this.errors).length > 0;
    }

    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    record(errors) {
        this.errors = errors;
    }

    clear(field) {
        if (field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};
    }
}

class Form {
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
        this.submitting = false;
    }

    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }

    reset() {
        for (let field in this.originalData) {
            this[field] = "";
        }

        this.errors.clear();
    }

    post(url) {
        return this.submit("post", url);
    }

    put(url) {
        return this.submit("put", url);
    }

    patch(url) {
        return this.submit("patch", url);
    }

    delete(url) {
        return this.submit("delete", url);
    }

    submit(requestType, url) {
        this.submitting = true;
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then(response => {
                    this.submitting = false;
                    this.onSuccess(response.data);

                    resolve(response.data);
                })
                .catch(error => {
                    this.submitting = false;
                    console.log(error.response.data.message);
                    this.onFail(error.response.data.errors);

                    reject(error.response.data);
                });
        });
    }

    onSuccess(data) {
        console.log(data.message); // temporary

        this.reset();
    }

    onFail(errors) {
        this.errors.record(errors);
    }
}

new Vue({
    el: "#appSysMkt",

    data: {
        form: new Form({
            codice: "",
            descrizione: ""
        })
    },

    methods: {
        onSubmit() {
            this.form.post("/sysMkt").then(response => {
                window.location.reload();
            });
        }
    }
});