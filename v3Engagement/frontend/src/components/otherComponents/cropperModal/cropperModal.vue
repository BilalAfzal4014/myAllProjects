<template>
    <div id="cropper_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 775px;">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="overflow: hidden;">
                        <h4 style="float: left" class="modal-title">Crop Image</h4>
                        <div style="float: right">
                            <span style="margin-right: 3px">Height: {{dragger.height}}px</span>
                            <span>Width: {{dragger.width}}px</span>
                        </div>
                    </div>

                    <div class="notice">
                        <!--<img src="http://devengagement.engagiv.com/assets/images/bulb_icon.png">-->
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <!--<i class='far fa-lightbulb'></i>-->
                        <ul>
                            <li>
                                <span>1 - Minimum optimum size for banner image is Width: 640px Height: 1136px</span>
                                <span>2 - For full screen minimum preferred image size is Width: 600px Height: 800px </span>
                            </li>
                            <li>
                                <span>3 - For dialog minimum preferred image size is Width: 100px Height: 100px</span>
                                <span>4 - We allow .PNG, .GIF, .JPG, .JPEG and . BMP image file formats.</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-body">
                    <div style="max-height: 350px; overflow-y: auto">
                        <vue-cropper v-bind:key="imgSrc"
                                     ref="cropper"
                                     v-bind:src="imgSrc"
                                     alt="Source Image"
                                     v-bind:crop="cropperDragger"
                                     v-bind:zoom="zooming">
                        </vue-cropper>
                        <!--v-bind:cropmove="move"-->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="button_crop">
                        <a class="anchor_pointer btn btn-danger" v-on:click="hideModal">Cancel</a>
                        <a v-bind:disabled="uploading" class="anchor_pointer btn btn-success" v-on:click="cropImage">
                            <i style="margin-left: 0px;"
                               v-bind:class="uploading ? '': 'visibility'"
                               class="fa fa-refresh fa-spin"></i>
                            Crop &amp; Insert</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueCropper from 'vue-cropperjs';

    export default {
        name: "cropper_modal",
        props: ['openModal', 'closeModal', 'imgSrc'],
        components: {
            VueCropper,
        },
        data() {
            return {
                dragger: {
                    height: 0,
                    width: 0,
                },
                uploading: false,
                cropImg: ''
            };
        },
        mounted() {
            this.$refs.cropper.rotate(45);
        },
        methods: {
            zooming() {
                this.dragger.width = Math.round(this.$refs.cropper.initCrop().getData().width);
                this.dragger.height = Math.round(this.$refs.cropper.initCrop().getData().height);
            },
            hideModal() {
                $("#cropper_modal").modal("hide");
            },
            cropperDragger() {
                this.dragger.width = Math.round(this.$refs.cropper.initCrop().getData().width);
                this.dragger.height = Math.round(this.$refs.cropper.initCrop().getData().height);
            },
            cropImage() {
                this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
                let block = this.cropImg.split(";");
                let contentType = block[0].split(":")[1];
                let realData = block[1].split(",")[1];
                let blob = this.b64toBlob(realData, contentType);
                this.cropImg = blob;
                this.uploadImage();
            },
            b64toBlob(b64Data, contentType, sliceSize) {
                contentType = contentType || '';
                sliceSize = sliceSize || 512;

                let byteCharacters = atob(b64Data);
                let byteArrays = [];

                for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                    let slice = byteCharacters.slice(offset, offset + sliceSize);

                    let byteNumbers = new Array(slice.length);
                    for (let i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }

                    let byteArray = new Uint8Array(byteNumbers);

                    byteArrays.push(byteArray);
                }

                let blob = new Blob(byteArrays, {type: contentType});
                return blob;
            },
            uploadImage() {
                this.uploading = true;
                let formData = new FormData();
                formData.append('file', this.cropImg);
                this.api("post",
                    this.constants.getUrl("fileuploadUrl"),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((response) => {
                    if (response.data.status == 200) {
                        let obj = {
                            image_url: response.data.imagePath,
                            image_name: response.data.filename,
                            image_width: response.data.width,
                            image_height: response.data.height,
                            image_size: response.data.size
                        };
                        this.uploadLogo(response.data.message, obj);
                    }
                }).catch((error) => {
                    this.uploading = false;
                })
            },
            uploadLogo(message, obj) {
                const payload = {
                    resource: "gallery",
                    action: "post",
                    data: obj
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    this.$swal({
                        position: 'top-end',
                        type: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.$emit("getImgUrl", obj.image_url);
                    this.uploading = false;
                }).catch((error) => {
                    this.uploading = false;
                });
            }
        },
        watch: {
            openModal(currVal, oldVal) {
                $("#cropper_modal").modal("show");
            },
            closeModal(currVal, oldVal) {
                $("#cropper_modal").modal("hide");
            },
            imgSrc(currVal, oldVal) {
                this.$refs.cropper.rotate(45);
            }
        }
    }

</script>


<style scoped>
    .visibility {
        display: none;
    }

    .notice {
        margin: 8px 0 !important;
        width: 100%;
        padding: 3px 0;
        display: inline-block;
        border-radius: 6px;
        vertical-align: top;
        color: black;
        font-size: 11px;
        line-height: 16px;
        background: #dddddd;
        display: none;
    }

    .notice i {
        padding: 12px 7px;
        font-size: 50px;
        float: left;
        /*color: whitesmoke;*/
        color: #989898;
    }

    .notice ul {
        margin: 0;
        overflow: hidden;
        padding: 5px 3px;
        list-style: none;
    }

    .notice ul li span {
        display: block;
        text-align: left;
    }

</style>