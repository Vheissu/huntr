import Aurelia, { StyleConfiguration } from 'aurelia';
import { ValidationHtmlConfiguration } from '@aurelia/validation-html';
import { RouterConfiguration } from '@aurelia/router';
import { Auth } from './services/auth';
import { MyApp } from './my-app';

import { AuthHook } from './auth-hook';
import { RedirectHook } from './redirect-hook';

import bootstrap from 'bootstrap/dist/css/bootstrap.css';
import shared from './shared.css';

Aurelia
  .register(StyleConfiguration.shadowDOM({
    sharedStyles: [bootstrap, shared]
  }))
  .register(
      ValidationHtmlConfiguration, 
      RouterConfiguration.customize({ useUrlFragmentHash: false }),
      Auth,
      AuthHook,
      RedirectHook
  )
  .app(MyApp)
  .start();
