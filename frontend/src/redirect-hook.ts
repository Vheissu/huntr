import { lifecycleHooks } from 'aurelia';
import { IRouter, Navigation, Parameters, RoutingInstruction } from '@aurelia/router';
import { IAuth } from './services/auth';

@lifecycleHooks()
export class RedirectHook {
    constructor(@IAuth readonly auth: IAuth, @IRouter readonly router: IRouter) {

    }

    canLoad(viewModel, params: Parameters, instruction: RoutingInstruction, navigation: Navigation) { 
        const canProceed = instruction.route?.match.data?.public && !this.auth.isLoggedIn;
        
        if (canProceed) {
            return true;
        }

        this.router.load('/');
    }
}