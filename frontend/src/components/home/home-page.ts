import { IApi } from "../../services/api";

export class HomePage {
    private products = [];

    constructor(@IApi readonly api: IApi) {}

    async loading() {
        try {
            this.products = await this.api.getProducts();
        } catch (e) {
            console.error(e);
        }
    }
}