import { bindable, INode } from 'aurelia';
import { SortableEvent } from 'sortablejs';

type ImageType = 'thumbnail' | 'gallery';

interface IImagePreview {
    image: string;
    ready: boolean;
}

export class ImageUpload {
    private imagePreviews: IImagePreview[] = [];
    private imageReferences: File[] = [];

    @bindable type: ImageType = 'thumbnail';

    constructor(@INode readonly element: HTMLElement) {}

    private removeImage(index: number) {
        this.imagePreviews.splice(index, 1);
        this.imageReferences.splice(index, 1);
    }

    private fileSelected(event: Event) {
        const files = (event.target as HTMLInputElement).files;

        // Populate imagePreviews array with files.length number of empty strings
        this.imagePreviews = [...Array(files.length)].map(() => {
            return { image: '', ready: false };
        });

        for (const [i, file] of Array.from(files).entries()) {
            const reader = new FileReader();

            this.imageReferences.push(file);

            reader.onload = () => {
                this.imagePreviews.splice(i, 1, {
                    image: reader.result as string,
                    ready: true
                });
            };

            reader.readAsDataURL(file);
        }

        const filesChangedEvent = new CustomEvent('files-changed', {
            bubbles: true,
            cancelable: false,
            detail: {
                files: this.imageReferences,
            },
        });

        this.element.dispatchEvent(filesChangedEvent);
    }

    private orderChanged($event: CustomEvent) {
        const { newIndex, oldIndex } = $event.detail;
    
        if (newIndex !== oldIndex) {
          const item = this.imagePreviews[oldIndex];
    
          this.imagePreviews.splice(oldIndex, 1);
          this.imagePreviews.splice(newIndex, 0, item);
        }
      };
}
