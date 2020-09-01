<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Gallery Listing</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search here..."
                           type="search"
                           v-model="lookupobj.search"
                           v-on:change="gallerySearching">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <div class="file_select">
                                    <input class="hidden" id="file_selector" ref="file" type="file"
                                           v-on:change="handleFileUpload()"/>
                                    <label for="file_selector">Upload Image</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="add_gallery" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                            </div>
                            <div class="modal-body text-center">
                                <img height="200px" v-bind:src="galleryParamObj.image_src" width="200px">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="side_widget">

                        </div>
                    </div>
                    <div class="table_holder mCustomScrollbar">
                        <div class="dataTables_wrapper no-footer" id="example_wrapper">
                            <sub-gallery-listing
                                    v-bind:column="column"
                                    v-bind:columnsClasses="columnsClasses"
                                    v-bind:cropAction="false"
                                    v-bind:filterable="filterable"
                                    v-bind:headings="headings"
                                    v-bind:mapColumns="mapColumns"
                                    v-bind:payLoad="payLoad"
                                    v-bind:reloadProp="reloadProp"
                                    v-bind:searchProp="lookupProp"
                                    v-bind:filterProp="filterProp"
                                    v-bind:isThisFooterComponent="true"
                                    v-bind:componentName="'galleryListing'"
                                    v-bind:sortable="sortable"
                                    v-on:imageSrc="setImageSrc">
                            </sub-gallery-listing>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    import subGalleryListing from '../../../otherComponents/galleryListing/subGalleryListing';

    export default {
        name: "GalleryLisiting",
        components: {
            subGalleryListing
        },
        data() {
            return {
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
                    image_size: '',
                    image_src: ''
                },
                column: ['image_url', 'image_name', 'dimension', 'image_size', 'created_at', 'Action'],
                mapColumns: ['image_url', 'image_name', 'dimension', 'image_size', 'status', 'created_at', 'image_width', 'image_height', 'id'],
                filterable: ['image_url', 'image_name', 'image_size', 'created_at'],
                sortable: ['image_url', 'image_name', 'image_size', 'created_at'],
                headings: {
                    image_url: 'Image',
                    image_name: 'Name',
                    // image_width: 'Width',
                    // image_height: 'Height',
                    dimension: 'Dimension',
                    image_size: 'Size',
                    created_at: 'Created At',
                    Action: 'Action'
                },
                columnsClasses: {},
                payLoad: {
                    resource: "gallery",
                    action: "get",
                },
                searchProp: '',
                lookupProp: {},
                filterProp: {}

            }

        },
        mounted() {

        },
        methods: {
            removeActive(id) {
                console.log('remove', id);
                $("#" + id).removeClass("active");
                this.filterProp = {};
            },
            fn_userFilter(row, status) {
                console.log('row', row);
                console.log('status', status);
                $(".sideBar-filter").removeClass("active");
                $("#" + row).addClass("active");
                this.filterProp = status;
            },
            gallerySearching() {
                this.lookupProp = this.lookupobj.search;
            },
            handleFileUpload() {
                let formData = new FormData();
                formData.append('file', this.$refs.file.files[0]);
                this.api("post",
                    this.constants.getUrl("fileuploadUrl"),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }, true
                ).then((response) => {
                    if (response.data.status == 200) {

                        this.galleryParamObj.image_url = response.data.imagePath;
                        this.galleryParamObj.image_name = response.data.filename;
                        this.galleryParamObj.image_width = response.data.width;
                        this.galleryParamObj.image_height = response.data.height;
                        this.galleryParamObj.image_size = response.data.size;
                        this.uploadLogo(response.data.message);
                    }

                }).catch((error) => {
                })

            },
            uploadLogo(message) {
                var payload = {
                    resource: "gallery",
                    action: "post",
                    data: this.galleryParamObj
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
                    this.reloadProp = !this.reloadProp;
                }).catch((error) => {
                });
            },
            setImageSrc(path) {
                this.galleryParamObj.image_src = path;
            }
        }
    }
</script>

<style>

</style>