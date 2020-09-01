<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Apps Listing</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search"
                           type="search"
                           v-model="appobj.search"
                           v-on:change="searchingApp">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li><a v-on:click="addApp">Add App</a></li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="add_app" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">{{mode}} App</h4>
                            </div>
                            <div class="modal-body">
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">App Name:</h4>
                                        <input type="text" v-model="applisitngParamObj.name">
                                        <p class="error" v-if="errors.name">{{ errors.name }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title inline">App Logo:</h4><br>
                                        <a>
                                            <label class="bordered_label">
                                                <img alt="#" class="app_logo" v-bind:src=" applisitngParamObj.logo"
                                                     v-if="applisitngParamObj.logo"/>
                                                <img alt="#" class="app_logo" src="../../../../assets/images/logo.png"
                                                     v-else
                                                />
                                                <button class="choose_file_btn" v-on:click="activateGalleryModal">Choose
                                                    File
                                                </button>
                                            </label>
                                        </a>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">App ID</h4>
                                        <input type="text" v-model="applisitngParamObj.app_id">
                                        <p class="error" v-if="errors.app_id">{{ errors.app_id }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Platform</h4>
                                        <select class="form-control" v-model="applisitngParamObj.platform">
                                            <option value="">Select Platfrom</option>
                                            <option value="android">Android</option>
                                            <option value="ios">IOS</option>
                                            <option value="web">Web</option>
                                        </select>
                                        <span class="cert_info" style="display: none; color: #2a8689">Note: Please upload apns certificate for firebase account</span>
                                        <p class="error" v-if="errors.platform">{{ errors.platform }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col fluid">
                                        <h4 class="modal-title">Description:</h4>
                                        <textarea v-model="applisitngParamObj.description"></textarea>
                                        <p class="error" v-if="errors.description">{{ errors.description }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col fluid">
                                        <h4 class="modal-title">FireBase Key:</h4>
                                        <input type="text" v-model="applisitngParamObj.firebase_api_key">
                                        <p class="error" v-if="errors.firebase_api_key">{{ errors.firebase_api_key
                                            }}</p>
                                    </div>
                                </div>

                                <div class="modal_row">
                                    <div class="modal_col fluid">
                                        <input style="float:right;" type="button" v-on:click="submitappListing"
                                               value="SAVE">
                                        <div style="overflow: hidden;">
                                            <h4 class="modal-title m_top inline">Active:</h4>
                                            <div class="on_off_div">
                                                <div class="an-onoffswitchContainer" id="app_active_switch">
                                                    <input class="abs_input an-onoffswitch-checkbox"
                                                           id="an-onoffswitch0"
                                                           name="check1"
                                                           type="checkbox" v-model="applisitngParamObj.is_active">
                                                    <!--an-switch-->
                                                    <label class="an-onoffswitch-label" for="an-onoffswitch0">
                                                        <span class="an-onoffswitch-inner"></span>
                                                        <span class="an-onoffswitch-switch"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="error" v-if="errors.is_active">{{ errors.is_active }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <!--<div class="side_widget">-->
                        <!--<strong class="title colored">Filter(s):</strong>-->
                        <!--<ul class="list_none side_tags">-->
                        <!--<li>-->
                        <!--<a class="opener anchor_pointer">Platform <i class="fas fa-sort-down"></i></a>-->
                        <!--<ul class="list_none inner_tags side_tags indicators" style="width: 150px">-->
                        <!--<li style="width: 100%"-->
                        <!--v-bind:class="dataTable.appProp == 'iOS' ? 'active': ''">-->
                        <!--<a class="anchor_pointer sideBar-filter" v-on:click="fn_appFilter('iOS')">iOS-->
                        <!--<i aria-hidden="true"-->
                        <!--class="fa fa-times"-->
                        <!--style="margin-right: 4px; margin-top: 3px;"-->
                        <!--v-if="dataTable.appProp == 'iOS'"-->
                        <!--v-on:click.stop="dataTable.appProp = {}">-->
                        <!--</i>-->
                        <!--</a>-->
                        <!--</li>-->
                        <!--<li style="width: 100%"-->
                        <!--v-bind:class="dataTable.appProp == 'Android' ? 'active': ''">-->
                        <!--<a v-on:click="fn_appFilter('Android')">Android-->
                        <!--<i aria-hidden="true"-->
                        <!--class="fa fa-times"-->
                        <!--style="margin-right: 4px; margin-top: 3px;"-->
                        <!--v-if="dataTable.appProp == 'Android'"-->
                        <!--v-on:click.stop="dataTable.appProp = {}">-->
                        <!--</i>-->
                        <!--</a>-->
                        <!--</li>-->
                        <!--&lt;!&ndash;<div class="side_widget">&ndash;&gt;-->
                        <!--&lt;!&ndash;<strong class="title colored">Filter(s):</strong>&ndash;&gt;-->
                        <!--&lt;!&ndash;<ul class="list_none side_tags">&ndash;&gt;-->
                        <!--&lt;!&ndash;<li v-for="section in campaignObj.campaignFilter">&ndash;&gt;-->
                        <!--&lt;!&ndash;<a class="opener anchor_pointer">{{section.column}}<i class="fas fa-sort-down"></i></a>&ndash;&gt;-->
                        <!--&lt;!&ndash;<ul style="width: 150px" class="list_none side_tags inner_tags indicators">&ndash;&gt;-->
                        <!--&lt;!&ndash;<li style="width: 100%" v-for="child in section.children"&ndash;&gt;-->
                        <!--&lt;!&ndash;v-on:click="campaignSideFilter(child)"&ndash;&gt;-->
                        <!--&lt;!&ndash;v-bind:class="JSON.stringify(child) == JSON.stringify(dataTable.campaignFilterProp) ? 'active': ''">&ndash;&gt;-->
                        <!--&lt;!&ndash;<a class="anchor_pointer sideBar-filter">{{section.column.toLowerCase() ==&ndash;&gt;-->
                        <!--&lt;!&ndash;'tags' ? child.value: child.value.charAt(0).toUpperCase() +&ndash;&gt;-->
                        <!--&lt;!&ndash;child.value.slice(1)}}&ndash;&gt;-->
                        <!--&lt;!&ndash;<i style="margin-right: 4px; margin-top: 3px;" class="fa fa-times"&ndash;&gt;-->
                        <!--&lt;!&ndash;aria-hidden="true"&ndash;&gt;-->
                        <!--&lt;!&ndash;v-if="JSON.stringify(child) == JSON.stringify(dataTable.campaignFilterProp)"&ndash;&gt;-->
                        <!--&lt;!&ndash;v-on:click.stop="removeActive">&ndash;&gt;-->
                        <!--&lt;!&ndash;</i>&ndash;&gt;-->
                        <!--&lt;!&ndash;</a>&ndash;&gt;-->
                        <!--&lt;!&ndash;</li>&ndash;&gt;-->
                        <!--&lt;!&ndash;</ul>&ndash;&gt;-->
                        <!--&lt;!&ndash;</li>&ndash;&gt;-->
                        <!--&lt;!&ndash;</ul>&ndash;&gt;-->
                        <!--&lt;!&ndash;</div>&ndash;&gt;-->
                        <!--&lt;!&ndash;<li style="width: 100%"-->
                        <!--v-bind:class="dataTable.appProp == 'Web' ? 'active': ''">-->
                        <!--<a v-on:click="fn_appFilter('Web')">Web-->
                        <!--<i aria-hidden="true"-->
                        <!--class="fa fa-times"-->
                        <!--style="margin-right: 4px; margin-top: 3px;"-->
                        <!--v-if="dataTable.appProp == 'Web'"-->
                        <!--v-on:click.stop="dataTable.appProp = {}"></i>-->
                        <!--</a>-->
                        <!--</li>&ndash;&gt;-->
                        <!--</ul>-->
                        <!--</li>-->
                        <!--</ul>-->
                        <!--</div>-->

                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags">
                                <li v-for="section in deviceFilter">
                                    <a class="opener anchor_pointer">{{section.column}}<i class="fas fa-sort-down"></i></a>
                                    <ul class="list_none side_tags inner_tags indicators" style="width: 150px">
                                        <li style="width: 100%"
                                            v-bind:class="JSON.stringify(child) == JSON.stringify(dataTable.appProp) ? 'active': ''"
                                            v-for="child in section.children"
                                            v-on:click="fn_appFilter(child)">
                                            <a class="anchor_pointer sideBar-filter">{{child.value}}
                                                <i aria-hidden="true" class="fa fa-times"
                                                   style="margin-right: 4px; margin-top: 3px;"
                                                   v-if="JSON.stringify(child) == JSON.stringify(dataTable.appProp)"
                                                   v-on:click.stop="removeActive">
                                                </i>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table_holder mCustomScrollbar">
                        <div class="dataTables_wrapper no-footer" id="example_wrapper">
                            <listing v-bind:apiPayLoad="dataTable.payLoad"
                                     v-bind:componentName="dataTable.componentName"
                                     v-bind:footerComponent="dataTable.isFooterComponent"
                                     v-bind:tableColumn="dataTable.column"
                                     v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                     v-bind:tableFilterable="dataTable.filterable"
                                     v-bind:tableHeadings="dataTable.headings"
                                     v-bind:tableMapColumns="dataTable.mapColumns"
                                     v-bind:tableReloading="dataTable.reloadProp"
                                     v-bind:tableSearch="dataTable.searchProp"
                                     v-bind:tableSideBarSearch="dataTable.appProp"
                                     v-bind:tableSortable="dataTable.sortable">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click.stop="appAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down" v-on:click.stop="appAction(data.actionlisting)"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id + '_action'">
                                        <li>
                                            <a v-on:click="editApplisiting(data.actionlisting.row)"><i
                                                    class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li v-if="data.actionlisting.row.is_active">
                                            <a v-on:click="updateStatus(data.actionlisting.row.id,0)"><i
                                                    class="fas fa-trash"></i> Inactive </a>
                                        </li>
                                        <li v-else>
                                            <a v-on:click="updateStatus(data.actionlisting.row.id,1)"><i
                                                    class="fas fa-trash"></i> Active </a>
                                        </li>
                                        <li>
                                            <a v-on:click="deleteApplisting(data.actionlisting.row.id)"><i
                                                    class="fas fa-trash"></i> Delete </a>
                                        </li>
                                    </ul>
                                </template>
                                <template slot="logo" slot-scope="data">
                                    <div v-if="data.imagelisting.row.logo">
                                        <img v-bind:src="data.imagelisting.row.logo"/>
                                    </div>
                                    <div v-else>
                                        <img src="../../../../assets/images/logo.png"/>
                                        <!--<img src="../../../../assets/images/profile_placeholder.png"/>-->
                                    </div>
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
        name: "AppListing",
        components: {
            listing
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['logo', 'name', 'app_id', 'platform', 'status', 'created_at', 'Action'],
                    mapColumns: ['logo', 'id', 'name', 'app_id', 'description', 'platform', 'firebase_api_key', 'status', 'is_active', 'logo', 'platform', 'created_at'],
                    filterable: ['logo', 'name', 'app_id', 'description', 'platform', 'created_at'],
                    sortable: ['logo', 'name', 'app_id', 'description', 'platform', 'created_at'],
                    isFooterComponent: true,
                    componentName: "appListing",
                    headings: {
                        logo: 'logo',
                        name: 'Name',
                        app_id: 'App ID',
                        description: 'Description',
                        platform: 'Platform',
                        created_at: 'Created At',
                        status: 'Status',
                        Action: 'Action'

                    },
                    columnsClasses: {},
                    payLoad: {
                        resource: "app",
                        action: "get",
                    },
                    searchProp: '',
                    appProp: {},

                },
                appobj: {
                    search: '',
                    lookupFilter: []
                },
                applisitngParamObj: {
                    id: '0',
                    name: '',
                    app_id: '',
                    description: '',
                    firebase_api_key: '',
                    is_active: '',
                    logo: '',
                    platform: ''

                },
                errors: {
                    name: '',
                    app_id: '',
                    description: '',
                    firebase_api_key: '',
                    is_active: '',
                    logo: '',
                    platform: ''
                },
                mode: 'Add',
                galleryListingModal: {
                    on: false,
                    off: false
                },
                cropperModal: {
                    on: false,
                    off: false,
                    imgSrc: 'data:image/png;base64,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',
                },
                deviceFilter: [
                    {
                        column: "Platform",
                        children: [
                            {
                                parent: "platform",
                                value: "Ios"
                            },
                            {
                                parent: "platform",
                                value: "Android",
                            },
                            {
                                parent: "platform",
                                value: "Web",
                            }
                        ],
                    },
                    {
                        column: "Status",
                        children: [
                            {
                                parent: "is_active",
                                value: "Active",
                                subVal: 1,
                            },
                            {
                                parent: "is_active",
                                value: "InActive",
                                subVal: 0,
                            }
                        ],
                    }

                ]
            };
        },
        mounted() {
            this.listenForCustomEvents();
            //this.Filter();
            //this.authHeaders.headers['Content-Type']='multipart/form-data';
            //  alert('call');
        },
        beforeDestroy() {
            this.bus.$off("galleryModalReturn");
        },
        methods: {
            updateStatus(id, status) {
                var data = {
                    'id': id,
                    'is_active': status
                };

                this.$swal({
                    title: "Are you sure ?",
                    text: status == 0 ? "You want to Inactive this app" : "You want to Activate this app",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {
                    if (result.value) {
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "appStatus",
                                action: "create",
                                data: data
                            },
                            this.authHeaders
                        ).then((response) => {
                            console.log('response', response);
                            this.$swal(status == 0 ? "Inactive" : "Activated",
                                status == 0 ? "app is Inactive" : "app is Activated",
                                'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                            console.log('error', error);
                        });
                    }
                });
            },
            searchingApp() {
                this.dataTable.searchProp = this.appobj.search;
            },
            appAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive(id) {
                // console.log('remove', id);
                // $("#" + id).removeClass("active");
                this.dataTable.appProp = {};
            },
            editApplisiting(data) {
                console.log('data', data);
                this.applisitngParamObj.is_active = data.is_active;
                this.applisitngParamObj.id = data.id;
                this.applisitngParamObj.name = data.name;
                this.applisitngParamObj.app_id = data.app_id;
                this.applisitngParamObj.description = data.description;
                this.applisitngParamObj.firebase_api_key = data.firebase_api_key;
                this.applisitngParamObj.is_active = data.is_active;
                this.applisitngParamObj.logo = data.logo;
                this.applisitngParamObj.platform = data.platform;
                // this.$refs.file.value = '';
                this.mode = 'Edit';
                $("#add_app").modal("show");
            },
            fn_appFilter(row) {
                console.log('row', row);
                // $(".sideBar-filter").removeClass("active");
                // $("#" + row).addClass("active");
                this.dataTable.appProp = row;
            },
            // Filter() {
            //     this.dataTable.appProp = [];
            // },
            submitappListing() {
                var payload;
                var id = this.applisitngParamObj.id;
                var name = this.applisitngParamObj.name;
                var appid = this.applisitngParamObj.app_id;
                var description = this.applisitngParamObj.description;
                var firebasekey = this.applisitngParamObj.firebase_api_key;
                var active = this.applisitngParamObj.is_active;
                var image = this.applisitngParamObj.logo;
                var platform = this.applisitngParamObj.platform;
                if (active) {
                    this.applisitngParamObj.is_active = 1;
                } else {

                    this.applisitngParamObj.is_active = 0;
                }

                if (name == '') {
                    this.errors.name = 'Please Enter App Name';
                }
                if (appid == '') {
                    this.errors.app_id = 'Please Enter App ID';
                }
                if (description == '') {
                    this.errors.description = 'Please Enter Description';
                }
                if (firebasekey == '') {
                    this.errors.firebase_api_key = 'Please Enter FireBase key';
                }
                if (active == '') {
                    this.errors.is_active = '';
                }

                if (platform == '') {
                    this.errors.platform = 'Please Select Platform';
                }

                if (name != "" && appid != "" && description != "" && firebasekey != "" && platform != "" && id == 0) {
                    payload = {
                        resource: "app",
                        action: "create",
                        data: this.applisitngParamObj
                    };
                    this.saveAppListing(payload, id);

                } else if (name != "" && appid != "" && description != "" && firebasekey != "" && id != "0" && platform != "") {
                    payload = {
                        resource: "app/current",
                        action: "update",
                        id: id,
                        data: this.applisitngParamObj
                    };
                    this.saveAppListing(payload, id);

                }

            },
            deleteApplisting(id) {
                this.$swal({
                    title: "Delete this order status?",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => {
                    if (result.value) {
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "app",
                                action: "delete",
                                id: id
                            },
                            this.authHeaders
                        ).then((response) => {
                            console.log('deleteResponse', response);
                            this.$swal('Deleted', 'You successfully deleted this file', 'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                            console.log('error', error);
                        });
                    }
                });
            },
            addApp() {
                this.applisitngParamObj.id = 0;
                this.applisitngParamObj.name = '';
                this.applisitngParamObj.app_id = '';
                this.applisitngParamObj.description = '';
                this.applisitngParamObj.firebase_api_key = '';
                this.applisitngParamObj.is_active = '';
                this.applisitngParamObj.logo = '';
                this.applisitngParamObj.platform = '';
                // this.$refs.file.value = '';
                this.mode = 'Add';
                $('.cert_info').css('display', 'none');
                $("#add_app").modal("show");
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
                    }
                ).then((response) => {
                    if (response.data.status == 200) {
                        this.$swal({
                            position: 'top-end',
                            type: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        this.applisitngParamObj.logo = response.data.imagePath;
                    }
                    console.log('response', response);
                }).catch((error) => {
                    console.log('error', error);
                })

            },
            saveAppListing(payload, id) {
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    console.log('res', response);

                    if (response.data.meta.code == 200) {
                        if (id == 0) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'App listing has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'App listing has been updated',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        $('#add_app').modal('hide');
                    } else {
                        this.$swal({
                            position: 'top-end',
                            type: 'error',
                            title: response.data.error,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    this.dataTable.reloadProp = !this.dataTable.reloadProp;
                }).catch((error) => {
                    console.log('error', error);
                })
            },
            activateGalleryModal() {
                // this.applisitngParamObj.logo = '';
                this.bus.$emit('galleryModalOpen', false);
            },
            listenForCustomEvents() {
                this.bus.$on("galleryModalReturn", (url) => {
                    console.log('url', url);
                    this.applisitngParamObj.logo = url;
                    //alert("nannah munnah rahi hoon, daish ka sipahi hoon");
                });
            }
        },
        watch: {
            applisitngParamObj: {
                handler(applistingParamObj) {
                    if (applistingParamObj.app_id != "") {
                        this.errors.app_id = '';
                    }
                    if (applistingParamObj.description != "") {
                        this.errors.description = '';
                    }
                    if (applistingParamObj.firebase_api_key != "") {
                        this.errors.firebase_api_key = '';
                    }
                    if (applistingParamObj.is_active != "") {
                        this.errors.is_active = '';
                    }
                    if (applistingParamObj.logo != "") {
                        this.errors.logo = '';
                    }
                    if (applistingParamObj.name != "") {
                        this.errors.name = '';
                    }
                    if (applistingParamObj.platform != "") {
                        if (applistingParamObj.platform == 'ios') {
                            $('.cert_info').css('display', 'block');
                        } else {
                            $('.cert_info').css('display', 'none');
                        }

                        this.errors.platform = '';
                    }
                    // do stuff
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
    #app_active_switch input {
        height: 0px;
        border: none;
    }

    #app_active_switch label {
        margin-top: -4px;
    }

</style>