import { wait } from './../../helpers';
import { newInstanceForScope } from '@aurelia/kernel';
import { IValidationRules } from '@aurelia/validation';
import { IValidationController } from '@aurelia/validation-html';
import { IRouter } from '@aurelia/router';

import { IApi } from '../../services/api';
import { IAuth } from '../../services/auth';
import { IToastService, ToastMessage } from '../../services/toast-service';

import hotkeys from 'hotkeys-js';

export class LoginPage {
    private showLogin = true;
    private email: string;
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
            .ensure('email').required()
            .ensure('password').required().when(() => this.showLogin);
    }

    attached() {
        hotkeys('ctrl+shift+u', (event, handler) => {
            this.email = 'testing@fdslkfjsd.com';
            this.password = 'password';
            this.handleForm();
            
            event.preventDefault();
        });
    }

    async handleForm() {
        const result = await this.validationController.validate();

        if (result.valid) {
            const login = await this.auth.login(this.email, this.password);

            if (login.success) {
                localStorage.setItem('token', login.data.jwt);

                this.toastService.success(new ToastMessage('You are now logged in. Welcome back!', 'Successfully logged in!'));

                await wait(1500);

                window.location.href = '/';
            }
        }
    }
}
