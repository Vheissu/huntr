import { HttpClient, json } from '@aurelia/fetch-client';
import { DI } from 'aurelia';

const REST_ENDPOINT_WP        = 'https://api.itemhuntr.com/wp-json/wp/v2/';
const REST_ENDPOINT_UTILITIES = 'https://api.itemhuntr.com/wp-json/utilities/v1/';

export const IApi = DI.createInterface<IApi>('IApi', x => x.singleton(Api));

// eslint-disable-next-line @typescript-eslint/no-empty-interface
export interface IApi extends Api {}

export class Api {
    private http: HttpClient = new HttpClient();

    constructor() {
        this.http.configure(config => {
            config.useStandardConfiguration();
            return config;
        });
    }

    async createProduct(title: string): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}products`, {
            method: 'POST',
            body: json({
                title,
                status: 'draft',
                content: '',
                date: new Date().toISOString(), // YYYY-MM-DDTHH:MM:SS
            })
        });

        return response.json();
    }

    async updateProduct(productId, body): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}products/${productId}`, {
            method: 'POST',
            body: json(body)
        });

        return response.json();
    }

    async publishProduct(productId, body): Promise<any> {
        await this.updateProduct(productId, body);

        body.status = 'publish';

        const response = await this.http.fetch(`${REST_ENDPOINT_WP}products/${productId}`, {
            method: 'POST',
            body: json(body)
        });

        return response.json();
    }

    async getProducts(): Promise<any[]> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}products`);

        return response.json();
    }

    async getProduct(productId: string): Promise<any> {
        try {
            const response = await this.http.fetch(`${REST_ENDPOINT_WP}products?post_name=${productId}`);
            const product = await response.json();

            if (product.length) {
                return product[0];
            }
        } catch (e) {
            return null;
        }
    }

    async getTopics(): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}topics`);

        return response.json();
    }

    async getTopic(topicId): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}topics/${topicId}`);

        return response.json();
    }

    async getCollections(): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}collections`);

        return response.json();
    }

    async getCollection(collectionId): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}collections/${collectionId}`);

        return response.json();
    }

    async getMediaItem(id: string | number): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}media/${id}`);

        return response.json();
    }

    async uploadFile(files: FileList, postId: string): Promise<any> {
        const formData = new FormData();
        const file = files[0];

        formData.append('file', file);
        formData.append('title', file.name);
        formData.append('post', postId);

        const headers = {};
        headers['Content-Disposition'] = 'form-data; filename=\''+file.name+'\'';

        const response = await this.http.fetch(`${REST_ENDPOINT_WP}media`, {
            method: 'post',
            body: formData,
            headers
        });

        return response.json();
    }

    async getComments(productId: number): Promise<any[]> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}comments?post=${productId}`);

        return response.json();
    }

    async createComment(title: string): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}comments`, {
            method: 'POST'
        });

        return response.json();
    }

    async castVote(productId, direction): Promise<any> {
        const response = await this.http.fetch(`${REST_ENDPOINT_UTILITIES}vote/${productId}`, {
            method: 'POST',
            body: json({
                authToken: localStorage.getItem('token'),
                direction
            })
        });

        return response.json();
    }
}