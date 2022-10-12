export class Step2 {
    galleryImagesChanged(e: { detail: { files: File[] } }) {
        const { files } = e.detail;

        console.log(files);
    }
}