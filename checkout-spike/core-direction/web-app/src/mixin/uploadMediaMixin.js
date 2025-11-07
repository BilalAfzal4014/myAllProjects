import { uploadMedia } from "@/apiManager/media";

export const DEFAULT_IMAGES = [
    {
        name: "user-avatar-01",
        path: "/assets/images/avatar/user-avatar-01.png",
        gender: "Male",
    },
    {
        name: "user-avatar-02",
        path: "/assets/images/avatar/user-avatar-02.png",
        gender: "Female",
    },
    {
        name: "user-avatar-03",
        path: "/assets/images/avatar/user-avatar-05.png",
        gender: "Unlisted",
    },
];
const uploadMediaMixin = {
    methods: {
        async convertImagePathToFileObject(gender, shouldEmitEvent = false) {
            const filteredImage = DEFAULT_IMAGES.find(
                (image) => image.gender === gender
            );
            const { name, path } = filteredImage;
            const base64ImageData = await this.fetchImagePathAsBase64(path);
            return this.convertBase64ToFileAndUpload(
                base64ImageData,
                name,
                shouldEmitEvent
            );
        },
        uploadUserAvatar(e) {
            const file = e.target.files[0];
            this.userAvatar = URL.createObjectURL(file);
            this.isShowAvatarViewModal = true;
            this.resetFileInput();
        },
        resetFileInput() {
            this.$refs.file.value = null;
        },

        async fetchImagePathAsBase64(imagePath) {
            const response = await fetch(imagePath);
            const imageBlob = await response.blob();
            const reader = new FileReader();
            const base64Promise = new Promise((resolve) => {
                reader.onloadend = () => resolve(reader.result);
            });
            reader.readAsDataURL(imageBlob);
            return base64Promise;
        },

        async convertBase64ToFileAndUpload(
            base64ImageData,
            imageName,
            shouldEmitEvent
        ) {
            const imageFile = await this.convertBase64ToImageFile(
                base64ImageData,
                imageName,
                "image/png"
            );
            return this.uploadImageFileToS3Bucket(imageFile, shouldEmitEvent);
        },

        async convertBase64ToImageFile(base64Data, filename, mimeType) {
            const res = await fetch(base64Data);
            const buf = await res.arrayBuffer();
            return new File([buf], filename, { type: mimeType });
        },

        async uploadImageFileToS3Bucket(imageFile, shouldEmitEvent = false) {
            try {
                let formData = new FormData();
                formData.append("file", imageFile);
                const response = await uploadMedia(formData);
                if (shouldEmitEvent) {
                    this.previewImage = URL.createObjectURL(imageFile);
                    this.$emit("changePicture", response.data.key);
                    this.isShowCropImageModal = false;
                }
                return response.data.key;
            } catch (error) {
                throw new Error(error);
            }
        },
    },
};

export default uploadMediaMixin;
