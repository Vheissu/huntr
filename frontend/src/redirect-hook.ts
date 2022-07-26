import { lifecycleHooks } from 'aurelia';
import { IRouter, Navigation, Parameters, RoutingInstruction } from '@aurelia/router';
import { IAuth } from './services/auth';

@lifecycleHooks()
export class RedirectHook {
    constructor(@IAuth readonly auth: IAuth, @IRouter readonly router: IRouter) {

    }

    canLoad(viewModel, params: Parameters, instruction: RoutingInstruction, navigation: Navigation) { 
        const isPublic = instruction.route?.match.data?.public ?? false;

        if (isPublic && !this.auth.isLoggedIn || !isPublic) {
            return true;
        }

        return this.router.load('/');
    }
}