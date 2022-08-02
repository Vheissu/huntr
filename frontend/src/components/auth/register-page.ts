import { wait } from './../../helpers';
import { ToastMessage } from './../../services/toast-service';
import { newInstanceForScope } from '@aurelia/kernel';
import { IValidationRules } from '@aurelia/validation';
import { IValidationController } from '@aurelia/validation-html';
import { IRouter } from '@aurelia/router';

import { IApi } from '../../services/api';
import { IAuth } from '../../services/auth';
import { IToastService } from '../../services/toast-service';

export class RegisterPage {
    private email: string;
    private username: string;
    private password: string;

    constructor(
        @IApi readonly api: IApi,
        @IAuth readonly auth: IAuth,
        @newInstanceForScope(IValidationController) private validationController: IValidationController,
        @IValidationRules validationRules: IValidationRules,
        @IToastService readonly toastService: IToastService,
        @IRouter readonly router: IRouter
    ) {
        validationRules.on(this)
            .ensure('username').required()
            .ensure('email').required()
            .ensure('password').required();
    }

    async handleForm() {
        const result = await this.validationController.validate();

        if (result.valid) {
            const register = await this.auth.register(this.email, this.username, this.password);

            if (register.success) {
                localStorage.setItem('token', register.jwt);

                this.toastService.success(new ToastMessage('Your account has been created. Logging you in...', 'Successfully registered!'));

                await(wait(1500));

                this.router.load('/');
            } else {
                this.toastService.error(new ToastMessage(register.data.message, 'Something went wrong'));
            }
        }
    }
}