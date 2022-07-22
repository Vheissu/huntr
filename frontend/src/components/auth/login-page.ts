import { newInstanceForScope } from '@aurelia/kernel';
import { IValidationRules } from '@aurelia/validation';
import { IValidationController } from '@aurelia/validation-html';
import { IRouter } from '@aurelia/router';

import { IApi } from '../../services/api';
import { IAuth } from '../../services/auth';

export class LoginPage {
    private email: string;
    private password: string;

    constructor(
        @IApi readonly api: IApi,
        @IAuth readonly auth: IAuth,
        @newInstanceForScope(IValidationController) private validationController: IValidationController,
        @IValidationRules validationRules: IValidationRules,
        @IRouter readonly router: IRouter
    ) {
        validationRules.on(this)
            .ensure('email').required()
            .ensure('password').required();
    }

    async login() {
        const result = await this.validationController.validate();

        if (result.valid) {
            const login = await this.auth.login(this.email, this.password);

            if (login.success) {
                localStorage.setItem('token', login.data.jwt);

                this.router.load('/');
            }
        }
    }
}
