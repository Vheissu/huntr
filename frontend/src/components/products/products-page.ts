import { IRouteableComponent } from '@aurelia/router';

import { IAuth } from '../../services/auth';
import { IApi } from './../../services/api';

export class ProductsPage implements IRouteableComponent {
    private product = null;

    constructor(@IApi readonly api: IApi, @IAuth readonly auth: IAuth) {}

    async load(params) {
        this.product = await this.api.getProduct(params.product);
        console.log(this.product);
    }  
}