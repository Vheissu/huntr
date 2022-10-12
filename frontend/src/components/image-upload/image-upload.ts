import { bindable } from "aurelia";

type ImageType = 'thumbnail' | 'gallery';

export class ImageUpload {
    private image: File;
    private imagePreview: string;
    private imagePreviews: string[] = [];
    private imageReferences: File[] = [];

    @bindable type: ImageType = 'thumbnail';

    private fileSelected(event: Event) {
        const files = (event.target as HTMLInputElement).files;

        for (const file of files) {
            const reader = new FileReader();

            this.imageReferences.push(file);

            reader.onload = () => {
                this.imagePreviews.push(reader.result as string);
            };
    
            reader.readAsDataURL(file);
        }
    }
}