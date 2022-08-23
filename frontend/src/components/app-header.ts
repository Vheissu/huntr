import { IAuth } from "../services/auth";

import { Dropdown } from "bootstrap";
import { INode } from "aurelia";

export class AppHeader {
  private productDropdown;
  private profileDropdown;
  private avatar = "";

  constructor(
    @IAuth readonly auth: IAuth,
    @INode readonly element: HTMLElement
  ) {
    this.avatar = auth?.token?.avatar_image ?? "";
  }

  attached() {
    const dropdownProductElement = this.element.shadowRoot.querySelector(
      ".dropdown-toggle-product"
    );

    const dropdownProfileElement = this.element.shadowRoot.querySelector(
      ".dropdown-toggle-profile"
    );

    this.productDropdown = new Dropdown(dropdownProductElement);
    this.profileDropdown = new Dropdown(dropdownProfileElement);
  }
}
