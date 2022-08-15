import { IRouter } from '@aurelia/router';
import { IAuth } from "../../services/auth";

export class LogoutPage {
    constructor(@IAuth readonly auth: IAuth, @IRouter readonly router: IRouter) {

    }

    async canLoad() {
        this.auth.logout();

        window.location.href = '/login';
    }
}