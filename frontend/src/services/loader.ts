import { DI } from 'aurelia';

export const ILoader = DI.createInterface<ILoader>('ILoader', x => x.singleton(Loader));

// eslint-disable-next-line @typescript-eslint/no-empty-interface
export interface ILoader extends Loader {}

export class Loader {
    private showLoading = false;

    show() {
        this.showLoading = true;
    }

    hide() {
        this.showLoading = false;
    }
}