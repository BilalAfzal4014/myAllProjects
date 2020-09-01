<template>
    <div id="wrapper">
        <headerComponent></headerComponent>
        <main id="main">
            <div class="page_content">
                <sideBarComponent
                        v-bind:listingApp="listingApp"
                        v-on:editAppGroup="editAppGroup"
                        v-on:updateAppGroup="updateAppGroup"
                ></sideBarComponent>

                <div class="ajax_loader">
                    <img src="../../assets/images/loader.gif">
                </div>
                <galleryListingModal v-bind:closeModal="galleryListingModal.off"
                                     v-bind:openModal="galleryListingModal.on"
                                     v-on:getImgUrl="pasteImgUrl"
                                     v-on:getImgUrlForCrop="cropImg">
                </galleryListingModal>
                <cropperModal v-bind:closeModal="cropperModal.off"
                              v-bind:imgSrc="cropperModal.imgSrc"
                              v-bind:openModal="cropperModal.on"
                              v-on:getImgUrl="pasteImgUrl">
                </cropperModal>
                <router-view></router-view>
            </div>
        </main>
        <footer-component></footer-component>
        <div class="modal fade" id="add_app_group" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4 class="modal-title" v-if="appGroupObj.title">{{appGroupObj.title}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="rows_holder">
                            <div class="app_tab active" id="app_step1">
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">App Group Name:</h4>
                                        <input type="text" v-model="appGroupObj.name">
                                        <p class="error" v-if="errors.name">{{ errors.name }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title inline">App Logo:</h4><br>
                                        <a>
                                            <label class="bordered_label">
                                                <img alt="#" class="app_logo" v-bind:src=" appGroupObj.logo"
                                                     v-if="appGroupObj.logo"/>
                                                <img v-else alt="#" class="app_logo" src="../../assets/images/logo.png"
                                                />
                                                <button v-on:click="activateGalleryModal" class="choose_file_btn">Choose
                                                    File
                                                </button>
                                            </label>
                                        </a>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col fluid">
                                        <h4 class="modal-title">Description:</h4>
                                        <textarea v-model="appGroupObj.description"></textarea>
                                        <p class="error" v-if="errors.description">{{ errors.description }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col fluid steps_btns">
                                        <a class="anchor_pointer" v-on:click="submitAppGroup">{{appGroupObj.buttontitle}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import headerComponent from "./partials/header";
    import sideBarComponent from "./partials/sideBar";
    import footerComponent from "./partials/footer";
    import galleryListingModal from '../../components/otherComponents/galleryListingModal/galleryListingModal';
    import cropperModal from '../../components/otherComponents/cropperModal/cropperModal';


    export default {
        name: "dashboard",
        components: {
            headerComponent,
            sideBarComponent,
            footerComponent,
            galleryListingModal,
            cropperModal
        },
        mounted() {
            this.galleryModal();
            this.listenForChangeAppGroup();
        },
        data() {
            return {
                appGroupObj: {
                    'title': 'Add App Group',
                    'buttontitle': 'Save',
                    'name': '',
                    'description': '',
                    'logo': '',
                    'id': 0,
                },
                appGroupObjCopy: {
                    'name': '',
                    'description': '',
                    'logo': '',
                    'id': 0,
                },
                locationObj: {
                    'id': '',
                    'name': '',
                    'code': '',
                    'description': ''

                },
                errors: {
                    'name': '',
                    'description': '',
                    'logo': ''
                },
                errorlocationObj: {
                    'name': '',
                    'code': '',
                    'description': ''
                },
                listingApp: [],
                locationListing: [],
                user: [],
                attributeListingModal: {
                    on: false,
                    off: false
                },
                galleryListingModal: {
                    on: false,
                    off: false
                },
                cropperModal: {
                    on: false,
                    off: false,
                    imgSrc: 'data:image/png;base64,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',
                },
                forDashboardUser: true
            }
        },
        methods: {
            listenForChangeAppGroup() {
                this.bus.$on('appGroupChanged', () => {
                    if (this.$route.path == "/dashboard/dashboard-stats") {
                        this.bus.$emit("callDashboardStatsApis");
                    } else {
                        this.$router.push("/dashboard/dashboard-stats");
                    }
                });
            },
            submitAppGroup() {
                var name = this.appGroupObj.name;
                var description = this.appGroupObj.description;
                var image = this.appGroupObj.logo;
                var id = this.appGroupObj.id;
                if (name == '') {
                    this.errors.name = 'Please enter a name';
                }
                if (name != "") {
                    var payload = {
                        resource: "groups",
                        action: "post",
                        data: this.appGroupObj
                    };
                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payload,
                        this.authHeaders
                    ).then((response) => {
                        console.log('response', response);
                        if (id == 0) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'App Group Added',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'App Group Updated',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }

                        this.listingApp = response.data.data;
                    }).catch((error) => {
                        console.log('error', error);
                    });
                    $("#add_app_group").modal("hide");
                }
            },
            handleFileUpload() {
                //  console.log('imagepath', this.$route);
                //console.log('file', this.$refs.file.files[0]);
                let formData = new FormData();
                formData.append('file', this.$refs.file.files[0]);
                // console.log('data', formData);
                this.api("post",
                    this.constants.getUrl("fileuploadUrl"),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    },
                    true
                ).then((response) => {
                    if (response.data.status == 200) {
                        this.$swal({
                            position: 'top-end',
                            type: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.appGroupObj.logo = response.data.imagePath;
                    }
                    console.log('response', response);
                }).catch((error) => {
                    console.log('error', error);
                })

            },
            editAppGroup(data, callback) {
                this.errors.name = '';
                this.appGroupObj.title = 'Update App Group';
                this.appGroupObj.buttontitle = 'Update';
                this.appGroupObj.logo = data.logo;
                this.appGroupObj.id = data.id;
                this.appGroupObj.name = data.name;
                this.appGroupObj.description = data.description;
                $("#add_app_group").modal("show");
                callback('testing');
            },
            updateAppGroup() {
                this.appGroupObj.title = 'Add App Group';
                this.appGroupObj.buttontitle = 'Save';
                this.appGroupObj.logo = '';
                this.appGroupObj.id = '';
                this.appGroupObj.name = '';
                this.appGroupObj.description = '';

                this.errors.name = '';
                this.errors.description = '';
            },
            pasteImgUrl(url) {
                this.galleryListingModal.off = !this.galleryListingModal.off;
                this.cropperModal.off = !this.cropperModal.off;
                if (this.forDashboardUser) {
                    this.appGroupObj.logo = url;
                } else {
                    this.bus.$emit("galleryModalReturn", url);
                }
                this.forDashboardUser = true;
            },
            cropImg(url) {
                this.cropperModal.imgSrc = url;
                this.cropperModal.on = !this.cropperModal.on;
                this.galleryListingModal.off = !this.galleryListingModal.off;
            },
            activateGalleryModal() {
                //this.appGroupObj.logo = '';
                this.galleryListingModal.on = !this.galleryListingModal.on;
            },
            galleryModal() {
                this.bus.$on('galleryModalOpen', (res) => {
                    this.forDashboardUser = res;
                    this.galleryListingModal.on = !this.galleryListingModal.on;
                });

            }
        }
    }
</script>

<style scoped>
    .ajax_loader {
        display: none;
        position: fixed;
        top: 68px;
        left: 96px;
        height: 100%;
        right: 0;
        background: rgba(255, 255, 255, 0.7);
        z-index: 100;
    }

    .ajax_loader img {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 110px;
        transform: translate(-60%, -70%);
    }
</style>