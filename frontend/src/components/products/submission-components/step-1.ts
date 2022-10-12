import { bindable, BindingMode, customElement } from "aurelia";

@customElement({
    name: 'step-1',
    template: `<smart-input label="Name of the product" value.bind="productName"></smart-input>`
})
export class Step1 {
    @bindable({ mode: BindingMode.twoWay }) productName = '';
}