import { IApi } from "../../services/api";
import { IAuth } from "../../services/auth";

export class TopicsPage {
    private topics   = [];
    private products = [];

    private isTopic = false;

    constructor(@IApi readonly api: IApi, @IAuth readonly auth: IAuth) {}

    async load(params) {
        if ( !params.topic ) {
            this.topics = await this.api.getTopics();
        } else {
            this.products = await this.api.getProductsByTopic(params.topic);
            this.isTopic = true;
        }
    } 
}