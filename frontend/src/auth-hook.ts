import { lifecycleHooks } from 'aurelia';
import { IRouter, Navigation, Parameters, RoutingInstruction } from '@aurelia/router';
import { IAuth } from './services/auth';

@lifecycleHooks()
export class AuthHook {
    constructor(@IAuth readonly auth: IAuth, @IRouter readonly router: IRouter) {

    }

    load() {
        console.log(...arguments);
    }

    canLoad(viewModel, params: Parameters, instruction: RoutingInstruction, navigation: Navigation) {
        const isAuth = instruction.route?.match.data?.isAuth ?? false;

        if (!isAuth || this.auth.isLoggedIn && isAuth) {
            return true;
        }

        return this.router.load('/login');
    }
}