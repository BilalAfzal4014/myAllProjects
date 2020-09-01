<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Company</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search"
                           type="text" v-model="lookupobj.search"
                           v-on:change="companySearchlisting"/>
                    <div class="align_right">
                        <strong class="actions_droper">Actions
                            <i class="fas fa-sort-down"></i>
                        </strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <a v-on:click="addCompany">Add Company</a>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="add_company" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">Add Company</h4>
                            </div>
                            <div class="modal-body">
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Name:</h4>
                                        <input type="text" v-model="companyParamObj.name">
                                        <!--v-on:keyup="codeRegularExpression">-->
                                        <input id="lookupid" type="hidden" v-model="companyParamObj.companyid">
                                        <p class="error" v-if="errors.name">{{ errors.name }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Email:</h4>
                                        <input type="text" v-model="companyParamObj.email">
                                        <p class="error" v-if="errors.email">{{ errors.email }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Password</h4>
                                        <input class="pw" type="text" v-model="companyParamObj.password">
                                        <p class="error" v-if="errors.password">{{ errors.password }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Confirm Password</h4>
                                        <input class="pw" type="text" v-model="companyParamObj.confirmPassword">
                                        <p class="error" v-if="errors.confirmPassword">{{ errors.confirmPassword }}</p>
                                    </div>

                                </div>
                                <div class="modal_row">
                                    <div class="modal_col"></div>
                                    <div class="modal_col">
                                        <h4 class="modal-title inline">Image:</h4><br>
                                        <a>
                                            <label class="bordered_label">
                                                <img alt="#" class="app_logo" v-bind:src=" companyParamObj.logo"
                                                     v-if="companyParamObj.logo"/>
                                                <img v-else alt="#" class="app_logo"
                                                     src="../../../../assets/images/logo.png"
                                                />
                                                <button v-on:click="activateGalleryModal" class="choose_file_btn">Choose
                                                    File
                                                </button>
                                            </label>
                                        </a>
                                        <p class="error" v-if="errors.logo">{{ errors.logo }}</p>
                                        <input style="margin-top: 15px" class="submitButton" type="submit"
                                               v-on:click="submitCompany"
                                               value="ADD">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags indicators">
                                <li style="padding: 2px 0;" v-for="section in sideFilters">
                                    <a class="newsfeed_arrow_opener opener anchor_pointer">{{section.column}}<i
                                            class="fas fa-sort-down"></i></a>
                                    <ul style="margin-top: 8px; width: 150px" class="list_none side_tags inner_tags">
                                        <li style="width: 100%" v-for="child in section.children"
                                            v-bind:class="JSON.stringify(child) == JSON.stringify(dataTable.lookupProp) ? 'active': ''"
                                            v-on:click="fn_companyFilter(child)"><a
                                                class="anchor_pointer sideBar-filter">{{child.label}}
                                            <i v-if="JSON.stringify(child) == JSON.stringify(dataTable.lookupProp)"
                                               style="margin-right: 4px; margin-top: 3px;" class="fa fa-times"
                                               aria-hidden="true"
                                               v-on:click.stop="removeActive"></i></a>
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
                                     v-bind:tableSideBarSearch="dataTable.lookupProp"
                                     v-bind:tableSortable="dataTable.sortable">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click="segmentAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down" v-on:click.stop="segmentAction(data.actionlisting)"></i>
                                    <ul class="table_drop list_none" style="width: 135px;"
                                        v-bind:id="data.actionlisting.row.id+'_action'">
                                        <li>
                                            <router-link
                                                    data-target="#add_lookup"
                                                    data-toggle="modal"
                                                    v-bind:to="'/dashboard/company-edit/' + data.actionlisting.row.id">
                                                <i
                                                        class="fas fa-edit"></i> Edit
                                            </router-link>
                                        </li>
                                        <li v-if="data.actionlisting.row.status">
                                            <a v-on:click="deleteCompany(data.actionlisting.row.id,0)"><i
                                                    class="fas fa-trash"></i> Inactive </a>
                                        </li>
                                        <li v-else>
                                            <a v-on:click="deleteCompany(data.actionlisting.row.id,1)"><i
                                                    class="fas fa-trash"></i> Active </a>
                                        </li>
                                        <li v-if="!data.actionlisting.row.status">
                                            <a v-on:click="removeCompany(data.actionlisting.row.id,1)"><i
                                                    class="fas fa-trash"></i> Remove </a>
                                        </li>
                                        <li v-if="data.actionlisting.row.cache_status === 'completed'">
                                            <a v-on:click="rebuildCache(data.actionlisting.row.id)"><i
                                                    class="fas fa-refresh"></i> Rebuild Cache </a>
                                        </li>
                                    </ul>
                                </template>
                                <template slot="logo" slot-scope="data">
                                    <img style="max-width: 70px;" v-bind:src="data.imagelisting.row.logo"/>
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
        name: "LookupListing",
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['logo', 'name', 'email', 'app_user_count', 'packageName', 'is_active', 'Action'],
                    mapColumns: ['id', 'logo', 'name', 'email', 'app_user_count', 'packageName', 'is_active', 'created_at', 'status', 'Action', 'cache_status'],
                    filterable: ['logo', 'name', 'email', 'app_user_count', 'packageName', 'is_active'],
                    sortable: ['logo', 'name', 'email', 'app_user_count', 'packageName', 'is_active'],
                    componentName: "companyListing",
                    isFooterComponent: true,
                    headings: {
                        logo: 'Logo',
                        name: 'Name',
                        email: 'Email',
                        app_user_count: 'Users',
                        is_active: 'Status',
                        packageName: 'Package',
                        Action: 'Action'
                    },
                    columnsClasses: {
                        id: 'width_5',
                        logo: 'width_10',
                        name: 'width_20',
                        email: 'width_20',
                        app_user_count: 'width_10',
                        packageName: 'width_10',
                        is_active: 'width_10',
                        Action: 'width_10'
                    },
                    payLoad: {
                        resource: "company",
                        action: "get",
                    },
                    searchProp: '',
                    lookupProp: {},

                },
                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
                lookupobj: {
                    search: '',
                    lookupFilter: {}
                },
                companyParamObj: {
                    companyid: '0',
                    name: '',
                    email: '',
                    password: '',
                    confirmPassword: '',
                    logo: ''

                },
                errors: {
                    name: '',
                    email: '',
                    password: '',
                    confirmPassword: '',
                    logo: ''
                },
                sideFilters: []
            }
        },
        mounted() {
            $("#footer").css({display: 'block'});
            this.listenForCustomEvents();
            this.getCompanySideFilters();
        },
        beforeDestroy() {
            $("#footer").css({display: 'none'});
        },
        methods: {
            getCompanySideFilters() {
                const payLoad = {
                    resource: "company/side-filters",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.sideFilters = response.data.data;
                }).catch((error) => {
                })
            },
            companySearchlisting() {
                this.dataTable.searchProp = this.lookupobj.search;
            },
            segmentAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive() {
                this.dataTable.lookupProp = {}
            },
            fn_companyFilter(obj) {
                this.dataTable.lookupProp = obj;
            },
            submitCompany() {
                var payload = {};
                var name = this.companyParamObj.name;
                var companyid = this.companyParamObj.companyid;
                var email = this.companyParamObj.email;
                var password = this.companyParamObj.password;
                var confirmpassword = this.companyParamObj.confirmPassword;
                var logoPath = this.companyParamObj.logo;
                if (name == '') {
                    this.errors.name = 'Please Enter Name';
                }
                if (email == '') {
                    this.errors.email = 'Please Enter Email';
                }
                if (password == '') {
                    this.errors.password = 'Please Enter Password';
                }
                if (confirmpassword == '') {
                    this.errors.confirmPassword = 'Please Enter Confirm Password';
                }
                if (password != confirmpassword) {
                    this.errors.confirmPassword = 'Password does not matched';
                }
                if (email != "") {
                    (this.reg.test(email)) ? this.errors.email = '' : this.errors.email = 'Email address not valid';
                }

                if (name != "" && (email != "" && this.reg.test(email)) && password != "" && confirmpassword != "") {
                    if (password == confirmpassword) {
                        this.api("post",
                            this.constants.getUrl("companySignUp"),
                            this.companyParamObj,
                            this.authHeaders
                        ).then((response) => {
                            if (response.data.status == 200) {
                                $('#add_company').modal('hide');
                                if (companyid == 0) {
                                    this.$swal({
                                        position: 'top-end',
                                        type: 'success',
                                        title: 'Company has been saved',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                } else {
                                    this.$swal({
                                        position: 'top-end',
                                        type: 'success',
                                        title: 'Company has been updated',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }
                            } else {
                                this.errors.email = response.data.message;
                            }
                            console.log('response', response);
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                            if (error.response.status == 422) {
                                this.errors.code = error.response.data.error.error;
                            }
                        })
                    }
                } else {
                    // alert('please add value');
                }
            },
            deleteCompany(id, status) {
                var data = {
                    'id': id,
                    'is_active': status
                };

                this.$swal({
                    title: "Are you sure ?",
                    text: status == 0 ? "You want to Inactive this company" : "You want to Activate this company",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {
                    if (result.value) {
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "companyStatus",
                                action: "create",
                                data: data
                            },
                            this.authHeaders
                        ).then((response) => {
                            this.$swal(status == 0 ? "Inactive" : "Activated",
                                status == 0 ? "company is Inactive" : "company is Activated",
                                'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {

                        });
                    }
                });
            },
            addCompany() {
                this.companyParamObj.name = '';
                this.companyParamObj.companyid = '0';
                this.companyParamObj.email = '';
                this.companyParamObj.password = '';
                this.companyParamObj.confirmPassword = '';
                this.companyParamObj.logo = '';
                this.errors.name = '';
                this.errors.companyid = '';
                this.errors.email = '';
                this.errors.password = '';
                this.errors.confirmPassword = '';
                this.errors.logo = '';
                $("#add_company").modal("show");
            },
            rebuildCache(id) {
                this.$swal({
                    title: "Rebuild Cache?",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {
                    if (result.value) {
                        var payload = {
                            resource: "company/rebuild/cache",
                            action: "post",
                            data: {id: id}
                        };
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payload,
                            this.authHeaders
                        ).then((response) => {
                            if (response.data.status == 400 || response.data.status == 422) {
                                this.$swal('Failed', response.data.message, 'failed');
                                return
                            }
                            this.$swal('Success', 'Rebuild cache successfully.', 'success');
                            this.dataTable.reloadProp = true
                        }).catch((error) => {
                            console.log(error.response.data);
                            this.$swal('Failed', error.response.data, 'failed');
                        });
                    }
                });
            },
            removeCompany(id) {
                this.$swal({
                    title: "Delete this company?",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => { // <--
                    console.log('result', result);
                    // alert(result);
                    if (result.value) {
                        var payload = {
                            resource: "company/" + id,
                            action: "delete",
                            data: {id: id}
                        };
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payload,
                            this.authHeaders
                        ).then((response) => {
                            if (response.data.status == 400 || response.data.status == 422) {
                                this.$swal('Failed', response.data.message, 'failed');
                                return
                            }
                            this.$swal('Success', 'Company removed successfully.', 'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                            console.log(error.response.data);
                            this.$swal('Failed', error.response.data, 'failed');
                        });
                    }
                });
            },
            activateGalleryModal() {
                this.companyParamObj.logo = '';
                this.bus.$emit('galleryModalOpen', false);
            },
            listenForCustomEvents() {
                this.bus.$on("galleryModalReturn", (url) => {
                    this.companyParamObj.logo = url;
                });
            }
        },
        watch: {
            companyParamObj: {
                handler(companyParamObj) {
                    if (companyParamObj.name != "") {
                        this.errors.name = '';
                    }
                    if (companyParamObj.email != "") {
                        (this.reg.test(companyParamObj.email)) ? this.errors.email = '' : this.errors.email = 'Email address not valid';
                    }
                    if (companyParamObj.password != "") {
                        this.errors.password = '';
                    }
                    if (companyParamObj.confirmPassword != "") {
                        this.errors.confirmPassword = '';
                    }
                    if (companyParamObj.password && companyParamObj.confirmPassword) {
                        if (companyParamObj.password == companyParamObj.confirmPassword) {
                            this.errors.confirmPassword = ' '
                        } else {
                            this.errors.confirmPassword = 'Password does not matched';
                        }
                    }
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
    .img_label {
        display: inline-block;
        vertical-align: middle;
        padding: 6px 4px;
    }

    input.pw {
        -webkit-text-security: disc;
    }
</style>