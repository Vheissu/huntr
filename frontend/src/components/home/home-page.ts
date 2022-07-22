import { IApi } from "../../services/api";

export class HomePage {
    private products = [];

    constructor(@IApi readonly api: IApi) {}

    async load() {
        this.products = await this.api.getProducts();
    }
}