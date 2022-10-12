import { watch } from '@aurelia/runtime-html';

export class NewSubmission {
    private currentSection = 1;
    private productName = 'Dwayne';

    setSection(section: number) {
        this.currentSection = section;
    }

    @watch('productName')
    productNameChanged(newVal, oldVal) {
        console.log(newVal, oldVal);
    }
}
