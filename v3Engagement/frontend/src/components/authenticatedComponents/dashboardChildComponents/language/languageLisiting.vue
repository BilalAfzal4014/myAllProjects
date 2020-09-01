<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Language</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search here..."
                           type="search"
                           v-model="lookupobj.search"
                           v-on:change="searching111">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li><a v-on:click="addlanguage">Add Language</a></li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="add_language" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">{{ mode == 'add'  ? "Add Language" : "Edit Language" }}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Name:</h4>
                                        <input type="text" v-model="languageParamObj.name">
                                        <input id="lookupid" type="hidden" v-model="languageParamObj.id">
                                        <p class="error" v-if="errors.name">{{ errors.name }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Code:</h4>
                                        <input type="text" v-model="languageParamObj.code">
                                        <p class="error" v-if="errors.code">{{ errors.code }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col direction_col">
                                        <h4 class="modal-title">Direction:</h4>
                                        <input type="radio" id="ltr" value="ltr" v-model="languageParamObj.dir">
                                        <label for="ltr">ltr</label>
                                        <input type="radio" id="rtl" value="rtl" v-model="languageParamObj.dir">
                                        <label for="rtl">rtl</label>
                                        <p class="error" v-if="errors.dir">{{ errors.dir }}</p>

                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Image:</h4>
                                        <!--<input id="file" class="no_border" ref="file" type="file"-->
                                               <!--v-on:change="handleFileUpload()"/>-->
                                        <a>
                                            <label class="bordered_label">
                                                <img alt="#" class="app_logo" v-bind:src="languageParamObj.image"
                                                     v-if="languageParamObj.image"/>
                                                <img v-else alt="#" class="app_logo" src="../../../../assets/images/logo.png"
                                                />
                                                <button v-on:click="activateGalleryModal" class="choose_file_btn">Choose
                                                    File
                                                </button>
                                            </label>
                                        </a>
                                        <p class="error" v-if="errors.image">{{ errors.image }}</p>
                                    </div>

                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title m_top">Actions</h4>
                                        <input type="submit" v-on:click="submitLanguage" v-bind:value="mode == 'add'  ? 'ADD' : 'Update'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="side_widget">
                            <div v-for="filter in lookupobj.lookupFilter">
                                <strong class="title colored">{{filter.columns}}:</strong>
                                <ul class="list_none side_tags">
                                    <li v-for="(child, index) in filter.children">
                                        <a class="sideBar-filter" style="cursor: pointer" v-bind:id="index"
                                           v-on:click="fn_lookupFilter(child, index)">{{child.parentAlias}}
                                            <i aria-hidden="true" class="fa fa-times"
                                               v-on:click.stop="removeActive(index)"></i>

                                        </a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table_holder mCustomScrollbar">
                        <div class="dataTables_wrapper no-footer" id="example_wrapper">
                            <listing v-bind:apiPayLoad="dataTable.payLoad"
                                     v-bind:tableColumn="dataTable.column"
                                     v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                     v-bind:tableFilterable="dataTable.filterable"
                                     v-bind:tableHeadings="dataTable.headings"
                                     v-bind:tableMapColumns="dataTable.mapColumns"
                                     v-bind:tableReloading="dataTable.reloadProp"
                                     v-bind:tableSearch="dataTable.searchProp"
                                     v-bind:tableSideBarSearch="dataTable.lookupProp"
                                     v-bind:tableSortable="dataTable.sortable"
                                     v-bind:componentName="dataTable.componentName"
                                     v-bind:footerComponent="dataTable.isFooterComponent">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click="segmentAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id+'_action'">
                                        <li>
                                            <a v-on:click="editlanguage(data.actionlisting.row.id)"><i
                                                    class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a v-on:click="deleteLanguage(data.actionlisting.row.id)"><i
                                                    class="fas fa-trash"></i> Delete </a>
                                        </li>
                                    </ul>
                                </template>
                                <template slot="image" slot-scope="data">
                                    <img v-bind:src="data.imagelisting.row.image"/>
                                </template>
                            </listing>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    import listing from '../../../otherComponents/datatable/datatableComponent';

    export default {
        name: "LanguageListing",
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['id', 'code', 'image', 'name', 'created_at', 'dir', 'Action'],
                    mapColumns: ['id', 'code', 'image', 'name', 'dir', 'created_at'],
                    filterable: ['id', 'code', 'image', 'name', 'dir', 'created_at'],
                    sortable: ['id', 'code', 'image', 'name', 'dir', 'created_at'],
                    componentName: "languageListing",
                    isFooterComponent: true,
                    headings: {
                        id: 'Sr#',
                        code: 'Code',
                        image: 'Thumbnail',
                        name: 'Name',
                        dir: 'Dir',
                        created_at: 'Created At',
                        Action: 'Action'

                    },
                    // columnsClasses: {
                    //     id: 'width_20',
                    //     code: 'width_60',
                    //     image: 'width_60',
                    //     name: 'width_100',
                    //     created_at: 'width_60',
                    //     Action: 'width_60'
                    // },
                    payLoad: {
                        resource: "language",
                        action: "get",
                    },
                    searchProp: '',
                    lookupProp: {},

                },
                lookupobj: {
                    search: '',
                    lookupFilter: []
                },
                languageParamObj: {
                    languageid: '0',
                    name: '',
                    code: '',
                    image: '',
                    dir: ''

                },
                errors: {
                    name: '',
                    code: '',
                    image: '',
                    dir: ''
                },
                mode: "add"
            }
        },
        mounted() {
            $("#footer").css({display: 'block'});
            this.listenForCustomEvents();
            this.Filter();
        },
        beforeDestroy() {
            $("#footer").css({display: 'none'});
        },
        methods: {
            searching111() {
                this.dataTable.searchProp = this.lookupobj.search;
            },
            segmentAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive(id) {
                console.log('remove', id);
                $("#" + id).removeClass("active");
            },
            editlanguage(editId) {
                this.mode = 'edit';
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "language/current",
                        action: "get",
                        id: editId
                    },
                    this.authHeaders
                ).then((response) => {
                    console.log('editResponse', response);
                    this.languageParamObj.languageid = response.data.data.id;
                    this.languageParamObj.name = response.data.data.name;
                    this.languageParamObj.code = response.data.data.code;
                    this.languageParamObj.image = response.data.data.image;
                    this.languageParamObj.dir = response.data.data.dir;
                    $("#add_language").modal("show");
                }).catch((error) => {
                    console.log('error', error);
                })

            },
            Filter() {
                this.dataTable.lookupProp = [];
            },
            submitLanguage() {
                var payload;
                var name = this.languageParamObj.name;
                var code = this.languageParamObj.code;
                var id = this.languageParamObj.languageid;
                var image = this.languageParamObj.image;
                var dir = this.languageParamObj.dir;
                if (name == '') {
                    this.errors.name = 'Please Enter Name';
                }
                if (code == '') {
                    this.errors.code = 'Please Enter Code';
                }
                if (image == '') {
                    this.errors.image = 'Please Enter Image';
                }
                if (dir == '') {
                    this.errors.dir = 'Please Select Direction';
                }
                console.log('dir', dir);
                if (name != "" && code != "" && id == 0 && dir != "" && image != "") {
                    payload = {
                        resource: "language",
                        action: "create",
                        data: this.languageParamObj
                    };
                    this.saveLanguage(payload, id);
                } else if (name != "" && code != "" && id != 0 && dir != "" && image != "") {
                    payload = {
                        resource: "language",
                        action: "update",
                        id: id,
                        data: this.languageParamObj
                    };
                    this.saveLanguage(payload, id);
                }

            },
            deleteLanguage(id) {
                this.$swal({
                    title: "Delete this order status?",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => { // <--
                    console.log('result', result);
                    // alert(result);
                    if (result.value) {
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "language",
                                action: "delete",
                                id: id
                            },
                            this.authHeaders
                        ).then((response) => {
                            console.log('deleteResponse', response);
                            if (response.data.meta.code == '200') {
                                this.$swal('Deleted', 'You successfully deleted this file', 'success');
                            } else {
                                this.$swal('error', response.data.error[0], 'error');
                            }
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                            console.log('error', error);
                        });
                    }
                });
            },
            addlanguage() {
                this.mode = 'add';
                this.languageParamObj.languageid = 0;
                this.languageParamObj.name = '';
                this.languageParamObj.code = '';
                this.languageParamObj.image = '';
                this.languageParamObj.dir = '';
                // this.$refs.file.value = '';
                $("#add_language").modal("show");
            },
            handleFileUpload() {
                console.log('imagepath', this.$route);
                console.log('file', this.$refs.file.files[0]);
                let formData = new FormData();
                formData.append('file', this.$refs.file.files[0]);
                console.log('data', formData);
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

                        this.languageParamObj.image = response.data.imagePath;
                    }
                    console.log('response', response);
                }).catch((error) => {
                    console.log('error', error);
                })

            },
            saveLanguage(payload, id) {
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    console.log('saveResponse', response);
                    $('#add_language').modal('hide');
                    if (response.status == 200) {
                        if (id == 0) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Language has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Language has been updated',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }

                    }
                    this.dataTable.reloadProp = !this.dataTable.reloadProp;
                }).catch((error) => {
                    console.log('error', error);
                    // if (error.response.status == 422) {
                    //     this.errors.code = error.response.data.error.error;
                    // }
                })
            },
            activateGalleryModal() {
                this.languageParamObj.image = '';
                this.bus.$emit('galleryModalOpen', false);
            },
            listenForCustomEvents() {
                this.bus.$on("galleryModalReturn", (url) => {
                    this.languageParamObj.image = url;
                });
            }
        }
    }
</script>

<style>

</style>