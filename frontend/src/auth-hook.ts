import { lifecycleHooks } from 'aurelia';
import { IRouter, Navigation, Parameters, RoutingInstruction } from '@aurelia/router';
import { IAuth } from './services/auth';

@lifecycleHooks()
export class AuthHook {
    constructor(@IAuth readonly auth: IAuth, @IRouter readonly router: IRouter) {

    }
    
    canLoad(viewModel, params: Parameters, instruction: RoutingInstruction, navigation: Navigation) {
        const isAuth = instruction.route?.match.data?.isAuth ?? false;

        if ( !isAuth ) {
            return true;
        }

        console.log(this.auth.isLoggedIn);

        if (this.auth.isLoggedIn && isAuth) {
            return true;
        }

        this.router.load('/login');

        return false;
    }
}