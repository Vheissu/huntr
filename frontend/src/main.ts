import Aurelia, { StyleConfiguration } from 'aurelia';
import { ValidationHtmlConfiguration } from '@aurelia/validation-html';
import { RouterConfiguration } from '@aurelia/router';
import { MyApp } from './my-app';

import { AuthHook } from './auth-hook';
import { RedirectHook } from './redirect-hook';

import bootstrap from 'bootstrap/dist/css/bootstrap.css';
import shared from './shared.css';
import variables from './variables.css';

Aurelia
  .register(StyleConfiguration.shadowDOM({
    sharedStyles: [bootstrap, variables, shared]
  }))
  .register(
      ValidationHtmlConfiguration, 
      RouterConfiguration.customize({ useUrlFragmentHash: false }),
      AuthHook,
      RedirectHook
  )
  .app(MyApp)
  .start();
