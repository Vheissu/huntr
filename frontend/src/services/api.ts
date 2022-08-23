import { HttpClient, json, RetryInterceptor } from '@aurelia/fetch-client';
import { DI } from 'aurelia';
import { IAuth } from './auth';

const REST_ENDPOINT_WP        = 'https://api.itemhuntr.com/wp-json/wp/v2/';
const REST_ENDPOINT_UTILITIES = 'https://api.itemhuntr.com/wp-json/utilities/v1/';
const REST_ENDPOINT_USER      = 'https://api.itemhuntr.com/wp-json/user/v1/';

export const IApi = DI.createInterface<IApi>('IApi', x => x.singleton(Api));

// eslint-disable-next-line @typescript-eslint/no-empty-interface
export interface IApi extends Api {}

export interface IWPTaxonomyResponse {
    id: number;
    count: number;
    description: string;
    link: string;
    name: string;
    slug: string;
    taxonomy: string;
    meta: unknown[];
    acf: unknown[];
}

export class Api {
    private http: HttpClient = new HttpClient();

    constructor(@IAuth readonly auth: IAuth) {
        this.http.configure(config => {
            config.withInterceptor({
                request: async (request) => {
                    const decodedJwt = this.auth.token;
                    
                    if (decodedJwt) {
                        const expires = decodedJwt.exp * 1000;
                        const now = Date.now();
                        
                        if (now > expires) {
                            const newToken = await this.auth.refresh(localStorage.getItem('token'));

                            if (newToken?.success) {
                                localStorage.setItem('token', newToken.data.jwt);
                            } else {
                                localStorage.removeItem('token');
                            }
                        }
                    }

                    if (localStorage.getItem('token')) {
                        request.headers.set('Authorization', `Bearer ${localStorage.getItem('token')}`);
                    }

                    return request;
                }
            });
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

    async getProductsByTopic(slug: string): Promise<any[]> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}products?topics_slug=${slug}`);

        return response.json();
    }

    async getProduct(productId: string): Promise<any> {
        try {
            const response = await this.http.fetch(`${REST_ENDPOINT_WP}products?slug=${productId}`);
            const product = await response.json();

            if (product.length) {
                return product[0];
            }
        } catch (e) {
            return null;
        }
    }

    async getTopics(): Promise<IWPTaxonomyResponse[]> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}topics`);

        return response.json();
    }

    async getTopic(topicId): Promise<IWPTaxonomyResponse> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}topics/${topicId}`);

        return response.json();
    }

    async getCollections(): Promise<IWPTaxonomyResponse[]> {
        const response = await this.http.fetch(`${REST_ENDPOINT_WP}collections`);

        return response.json();
    }

    async getCollection(collectionId): Promise<IWPTaxonomyResponse> {
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
                direction
            })
        });

        return response.json();
    }

    async getAvatar(id: string | number): Promise<string> {
        const response = await this.http.fetch(`${REST_ENDPOINT_USER}avatar/${id}`);

        return response.text();
    }
}