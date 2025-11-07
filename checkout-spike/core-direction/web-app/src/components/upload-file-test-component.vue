<template>
  <div>
    <a href="#test">Test</a>
    <h1>UPload Test File</h1>
    <input id="profile_image" ref="profileImage" accept="image/*" class="file-upload" type="file"
           @change="uploadProfileImage"
    >
    <set-user-interest id="test" />
  </div>
</template>

<script>
import {uploadMedia} from "@/apiManager/media";
import SetUserInterest from "@/components/signup/set-user-profile/set-user-interest";

export default {
    name: "UploadFileTestComponent",
    components: {SetUserInterest},
    methods: {
        uploadProfileImage(e) {
            (async () => {
                const response = await fetch("/assets/images/avatar/user-avatar-05.png");
                const imageBlob = await response.blob();
                const reader = new FileReader();
                reader.readAsDataURL(imageBlob);
                reader.onloadend = () => {
                    const base64data = reader.result;
                    let image = this.urlToFile(base64data);
                };
            })();
            const file = e.target.files[0];
            this.profileImage = URL.createObjectURL(file);
            let formData = new FormData();
            formData.append("file", e.target.files[0]);
            uploadMedia(formData);
        },
        urlToFile(base64) {
            const convertUrlToFile = async (url, filename, mimeType) => {
                const res = await fetch(url);
                const buf = await res.arrayBuffer();
                return new File([buf], filename, {type: mimeType});
            };

            (async () => {
                await convertUrlToFile(
                    base64,
                    "user-avatar-05.png",
                    "image/png"
                );
            })();
        }

    }
};
</script>

<style scoped>

</style>