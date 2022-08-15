import { IAuth } from '../services/auth';

export class AppHeader {
    private avatar = '';

    constructor(@IAuth readonly auth: IAuth) {
        this.avatar = auth?.token?.avatar_image ?? '';
    }
}