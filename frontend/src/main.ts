import Aurelia, { StyleConfiguration } from 'aurelia';
import { RouterConfiguration } from '@aurelia/router';
import { MyApp } from './my-app';

import bootstrap from 'bootstrap/dist/css/bootstrap.css';
import shared from './shared.css';

Aurelia
  .register(StyleConfiguration.shadowDOM({
    sharedStyles: [bootstrap, shared]
  }))
  .register(RouterConfiguration.customize({ useUrlFragmentHash: false }))
  .app(MyApp)
  .start();
