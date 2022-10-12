import { bindable, BindingMode } from "aurelia";

export function generateId(): string {
    return Math.random().toString(36).substring(2, 9);
}

export class SmartInput {
    @bindable id = generateId();
    @bindable type = 'text';
    @bindable label = '';
    @bindable placeholder = null;
    @bindable({ mode: BindingMode.twoWay }) value = '';
}