import { IRouteableComponent } from '@aurelia/router';

import { IApi } from './../../services/api';

export class ProductsPage implements IRouteableComponent {
    private product = null;

    constructor(@IApi readonly api: IApi) {}

    async load(params) {
        this.product = await this.api.getProduct(params.product);
        console.log(this.product);
    }  
}