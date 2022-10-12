import { SmartInput } from './components/smart-input/smart-input';
import Aurelia, { StyleConfiguration } from 'aurelia';
import { ValidationHtmlConfiguration } from '@aurelia/validation-html';
import { RouterConfiguration } from '@aurelia/router';
import { MyApp } from './my-app';

import { AuthHook } from './auth-hook';
import { RedirectHook } from './redirect-hook';

import bootstrap from 'bootstrap/dist/css/bootstrap.css';
import shared from './shared.css';
import variables from './variables.css';
import { AuSortable } from './resources/au-sortable';

Aurelia
  .register(StyleConfiguration.shadowDOM({
    sharedStyles: [bootstrap, variables, shared]
  }))
  .register(
      ValidationHtmlConfiguration, 
      RouterConfiguration.customize({ useUrlFragmentHash: false, useDirectRouting: false, useHref: false }),
      SmartInput,
      AuthHook,
      RedirectHook,
      AuSortable
  )
  .app(MyApp)
  .start();
