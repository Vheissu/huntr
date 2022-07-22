import { IAuth } from '../services/auth';

export class AppHeader {
    constructor(@IAuth readonly auth: IAuth) {

    }
}