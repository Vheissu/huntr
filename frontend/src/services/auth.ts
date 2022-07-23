import { HttpClient, json } from '@aurelia/fetch-client';
import { DI } from 'aurelia';

const REST_ENDPOINT_WP   = 'https://api.itemhuntr.com/wp-json/wp/v2/';
const REST_ENDPOINT_AUTH = 'https://api.itemhuntr.com/wp-json/auth/v1/';

const AUTH_KEY = 'jdlaksjdlaksjdalks5353xcv#!@#!@';

export const IAuth = DI.createInterface<IAuth>('IAuth', x => x.singleton(Auth));

// eslint-disable-next-line @typescript-eslint/no-empty-interface
export interface IAuth extends Auth {}

export interface IAuthRegisterResponse {
    token_type: string;
    iat: number;
    expires_in: number;
    jwt_token: string;
    code: number;
}

export interface IAuthResponse {
    success: boolean;
    data: {
        jwt: string;
    };
}

export interface IRegisterResponse {
    success: boolean;
    id?: number;
    message?: string;
    user?: {
        ID: number;
        user_login: string;
        user_nicename: string;
        user_email: string;
        user_url: string;
        user_registered: string;
        user_activation_key: string;
        user_status: string;
        display_name: string;
    };
    roles?: string[];
    jwt?: string;
    data?: {
        message: string;
        errorCode: number;
    };
}

export class Auth {
    private http: HttpClient = new HttpClient();

    constructor() {
        this.http.configure(config => {
            config.useStandardConfiguration();
            return config;
        });
    }

    async register(email: string, username: string, password: string): Promise<IRegisterResponse> {
        const response = await this.http.fetch(`${REST_ENDPOINT_AUTH}users`, {
            method: 'POST',
            body: json({
                email,
                username,
                password,
                AUTH_KEY: AUTH_KEY
            })
        });

        return response.json();
    }

    async login(email: string, password: string): Promise<IAuthResponse> {
        const response = await this.http.fetch(`${REST_ENDPOINT_AUTH}auth`, {
            method: 'POST',
            body: json({
                email,
                password,
            })
        });

        return response.json();
    }

    async resetPassword(email: string): Promise<IAuthResponse> {
        const response = await this.http.fetch(`${REST_ENDPOINT_AUTH}user/reset_password`, {
            method: 'POST',
            body: json({
                email,
            })
        });

        return response.json();
    }

    async refresh(token: string) {
        const response = await this.http.fetch(`${REST_ENDPOINT_AUTH}auth/refresh`, {
            method: 'POST',
            body: json({
                JWT: token
            })
        });

        return response.json();
    }

    get isLoggedIn(): boolean {
        return localStorage.getItem('token') !== null;
    }
}