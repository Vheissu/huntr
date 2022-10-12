import { IAuth } from "../services/auth";

import { Dropdown } from "bootstrap";
import { IEventAggregator, INode } from "aurelia";

export class AppHeader {
  private productDropdown;
  private profileDropdown;
  private avatar = "";

  constructor(
    @IAuth readonly auth: IAuth,
    @INode readonly element: HTMLElement,
    @IEventAggregator readonly ea: IEventAggregator
  ) {
    this.avatar = auth?.token?.avatar_image ?? "";
  }

  bound() {
    // this.ea.subscribe('au:router:navigation-start', payload => {
    //     this.productDropdown.hide();
    //     this.profileDropdown.hide();
    // });
  }

  attached() {
    // const dropdownProductElement = this.element.shadowRoot.querySelector(
    //   ".dropdown-toggle-product"
    // );

    // const dropdownProfileElement = this.element.shadowRoot.querySelector(
    //   ".dropdown-toggle-profile"
    // );

    // this.productDropdown = new Dropdown(dropdownProductElement);
    // this.profileDropdown = new Dropdown(dropdownProfileElement);

    // this.element.shadowRoot.addEventListener('click', event => {
    //     if ((event.target as HTMLElement).tagName !== 'A') {
    //         this.productDropdown.hide();
    //         this.profileDropdown.hide();
    //     }
    // });
  }
}
