<template>
    <div class="modal fade" id="gallery_listing" role="dialog">
        <div class="modal-dialog" style="width: 775px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title">Gallery Listing</h4>
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
                    <div>
                        <div style="overflow: hidden;">
                            <p style="float: right; margin: 0">{{fileName}}</p>
                        </div>
                        <div class="search_gallery_imgs">
                            <input placeholder="Search here..."
                                   type="search"
                                   v-model="listingProps.lookupobj.search"
                                   v-on:change="listingProps.lookupProp = listingProps.lookupobj.search">
                        </div>
                        <div class="gall_btns">
                            <input type="file" id="gall_file" class="hidden" ref="file"
                                   v-on:change="reactiveValidate($event)">
                            <label for="gall_file">Browse</label>
                            <a class="btn-primary anchor_pointer gall_uploader" v-bind:disabled="uploading"
                               v-on:click="uploadImage">
                                <i class="fa fa-refresh fa-spin"
                                   style="margin-left: 0px;"
                                   v-bind:class="uploading ? '': 'visibility'">

                                </i>
                                Upload
                            </a>
                            <p class="error">{{errors.img.message}}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table_holder default_scroll"> <!--mCustomScrollbar-->
                        <galleryListing
                                v-bind:column="listingProps.column1"
                                v-bind:columnsClasses="listingProps.columnsClasses"
                                v-bind:cropAction="true"
                                v-bind:filterable="listingProps.filterable"
                                v-bind:headings="listingProps.headings"
                                v-bind:lookupProp="listingProps.lookupProp"
                                v-bind:mapColumns="listingProps.mapColumns"
                                v-bind:payLoad="listingProps.payLoad"
                                v-bind:reloadProp="listingProps.reloadProp"
                                v-bind:searchProp="listingProps.searchProp"
                                v-bind:sortable="listingProps.sortable"
                                v-bind:isThisFooterComponent="false"
                                v-bind:componentName="'galleryListingModal'"
                                v-on:getUrlForCrop="cropImgUrl"
                                v-on:getUrlForImage="passImg">
                        </galleryListing>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import galleryListing from "../galleryListing/subGalleryListing";

    export default {
        name: "gallery_listing",
        props: ['openModal', 'closeModal'],
        components: {
            galleryListing
        },
        data() {
            return {
                locked: true,
                uploading: false,
                listingProps: {
                    reloadProp: false,
                    lookupobj: {
                        search: '',
                        lookupFilter: []
                    },
                    galleryParamObj: {
                        image_url: '',
                        image_name: '',
                        image_width: '',
                        image_height: '',
                        image_size: ''
                    },
                    column1: ['image_url', 'image_name', 'image_width', 'image_height', 'image_size', 'Action'],
                    mapColumns: ['id', 'image_url', 'image_name', 'image_width', 'image_height', 'image_size'],
                    filterable: ['image_url', 'image_name', 'image_width', 'image_height', 'image_size'],
                    sortable: ['image_url', 'image_name', 'image_width', 'image_height', 'image_size'],
                    headings: {
                        image_url: 'Image',
                        image_name: 'Name',
                        image_width: 'Width',
                        image_height: 'Height',
                        image_size: 'Size',
                        Action: 'Action'
                    },
                    columnsClasses: {
                        image_url: 'width_60',
                        image_name: 'width_120',
                        image_width: 'width_60',
                        image_height: 'width_60',
                        image_size: 'width_60',
                        Action: 'width_60',
                    },
                    payLoad: {
                        resource: "gallery",
                        action: "get",
                    },
                    searchProp: '',
                    lookupProp: {}
                },
                errors: {
                    img: {
                        message: ''
                    }
                },
                fileName: ''
            };
        },
        methods: {
            passImg(url) {
                this.$emit("getImgUrl", url);
            },
            cropImgUrl(url) {
                this.$emit("getImgUrlForCrop", url);
            },
            uploadImage() {
                this.locked = false;
                if (this.validate()) {
                    this.uploading = true;
                    let formData = new FormData();
                    formData.append('file', this.$refs.file.files[0]);
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
                            this.resetErrorsState();
                        } else {
                            this.uploading = false;
                            this.$swal({
                                position: 'top-end',
                                type: 'error',
                                title: 'Uploading Failed',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }).catch((error) => {
                        this.uploading = false;
                    })
                }
            },
            uploadLogo(message, obj) {
                var payload = {
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
                    this.fileName = "";
                    this.listingProps.reloadProp = !this.listingProps.reloadProp;
                    this.uploading = false;
                }).catch((error) => {
                    this.uploading = false;
                });
            },
            validate() {
                let success = true;
                let errors = {
                    img: {
                        message: ''
                    }
                };
                this.errors = JSON.parse(JSON.stringify(errors));

                if (this.$refs.file.files[0] == undefined) {
                    success = false;
                    this.errors.img.message = 'Please select image';
                } else {
                    let extension = this.$refs.file.files[0].name.split(".");
                    extension = extension[extension.length - 1].toLowerCase();

                    if (extension != 'jpg' && extension != 'jpeg' && extension != 'png' && extension != 'gif' && extension != 'bmp') {
                        success = false;
                        this.errors.img.message = 'jpg | jpeg | png | gif | bmp extensions are allowed only';
                    }
                }

                return success;
            },
            reactiveValidate(e) {
                if (e.target.files[0] != undefined) {
                    this.fileName = e.target.files[0].name;
                } else {
                    this.fileName = "";
                }
                if (!this.locked) {
                    this.validate();
                }
            },
            resetErrorsState() {
                this.locked = true;
                let errors = {
                    img: {
                        message: ''
                    }
                };
                this.errors = JSON.parse(JSON.stringify(errors));
                this.$refs.file.value = '';
            }
        },
        watch: {
            openModal(currVal, oldVal) {
                this.fileName = "";
                this.resetErrorsState();
                this.listingProps.reloadProp = !this.listingProps.reloadProp;
                $("#gallery_listing").modal("show");
            },
            closeModal(currVal, oldVal) {
                $("#gallery_listing").modal("hide");
            },
        }
    }

</script>

<style>
    .width_120 {
        word-break: break-all;
    }
</style>

<style scoped>
    #uploadImage {
        margin: 0px;
        padding: 0px;
        width: 150px;
        position: relative;
        border: none;
        overflow: hidden;
        display: inline-block;
    }

    input#uploadImage:before {
        position: absolute;
        top: 0px;
        left: 0px;
        content: 'Browse File';
        background: #2a8689;
        width: 100%;
        height: 100%;
        text-align: center;
        color: #fff;
        padding: 10px 0;
        font-size: 14px;
    }

    .upload_div button {
        border-radius: 4px;
        color: white;
        border: 1px solid #2a8689;
        padding: 9px 22px;
        background: #2a8689;
    }

    .visibility {
        display: none;
    }

    .table_holder {
        overflow: hidden !important;
        height: 100vh !important;
        padding-right: 23px !important;
        padding-bottom: 50px !important;
    }

    .modal-body .table_holder {
        padding-right: 0 !important;
    }

    .modal-body {
        max-height: none;
        overflow: hidden;
    }

    .table_holder {
        max-height: 524px;
        overflow: auto !important;
    }

    .search_gallery_imgs {
        float: left;
        margin: 7px 0 0 0;
        width: 64%;
    }

    .search_gallery_imgs input {
        height: 37px !important;
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