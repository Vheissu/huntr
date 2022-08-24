import { IRouter } from "@aurelia/router";

export class SubmitProduct {
    private url = '';

    constructor(@IRouter readonly router: IRouter) {

    }

    save() {
        if (this.url.length > 0) {
            const newProduct = {
                url: this.url,
            };

            localStorage.setItem('product_submission', JSON.stringify(newProduct));

            this.router.load('/new/product/submission');
        }
    }
}