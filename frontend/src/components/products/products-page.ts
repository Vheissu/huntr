import { IRouteableComponent } from '@aurelia/router';

import { IAuth } from '../../services/auth';
import { IApi } from './../../services/api';

export class ProductsPage implements IRouteableComponent {
    private product = null;

    constructor(@IApi readonly api: IApi, @IAuth readonly auth: IAuth) {}

    async load(params) {
        this.product = await this.api.getProduct(params.product);
    } 
    
    async castVote() {
        let direction = 'up';

        if (this.product.user_voted) {
            this.product.vote_count--;
            direction = 'down';
        } else {
            this.product.vote_count++;
            direction = 'up';
        }

        await this.api.castVote(this.product.id, direction);
    }
}