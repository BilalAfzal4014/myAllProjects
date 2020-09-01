<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content">
                <h1>Edit Company</h1>
                <div class="new_padding_div">
                    <ul class="tabs stats_tabs list_none inline_list">
                        <li v-bind:class="[ activetab === 1 ? 'active' : '' ]" v-on:click="activetab=1"><a>General</a>
                        </li>
                        <li v-bind:class="[ activetab === 2 ? 'active' : '' ]" v-on:click="activetab=2"><a>Password</a>
                        </li>
                        <!--<li v-bind:class="[ activetab === 3 ? 'active' : '' ]" v-on:click="activetab=3"><a>Package
                            Details</a>
                        </li>-->
                    </ul>
                    <div class="stats_tab_content" id="tab-content">
                        <!--modal starts-->
                        <div class="modal fade" id="packages_list" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                                        <h4 class="modal-title">Available Packages</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table_holder default_scroll">
                                            <table class="auto_tds" style="table-layout: auto;">
                                                <thead>
                                                <tr class="overflow_tr" role="row">
                                                    <th style="text-align:center;">
                                                        Name
                                                    </th>
                                                    <th>
                                                        Type
                                                    </th>
                                                    <th>
                                                        Push Limit
                                                    </th>
                                                    <th>
                                                        InApp Limit
                                                    </th>
                                                    <th>
                                                        Email Limit
                                                    </th>
                                                    <th>
                                                        NFC Limit
                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="currPackage in availablePackages">
                                                    <td>{{currPackage.name}}</td>
                                                    <td>{{currPackage.type}}</td>
                                                    <td>{{currPackage.push_limit}}</td>
                                                    <td>{{currPackage.inapp_limit}}</td>
                                                    <td>{{currPackage.email_limit}}</td>
                                                    <td>{{currPackage.nfc_limit}}</td>
                                                    <td>
                                                        <input type="checkbox" class="select_package_checkbox"
                                                               v-bind:checked="JSON.stringify(currPackage) == JSON.stringify(selectedPackage)"
                                                               v-on:change="addRemove($event, currPackage)">
                                                    </td>
                                                </tr>
                                                <tr v-if="availablePackages.length == 0">
                                                    <td colspan="5">
                                                        No record found
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="input_row">
                                            <input type="button" value="Change Package" class="right package_input"
                                                   v-on:click="changePackage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--modal ends-->
                        <div class="tab active" id="overview" v-if="activetab === 1">
                            <div class="state_cols">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Name:</label>
                                            <input autocomplete="off" class="form-control" required type="text"
                                                   v-model="companyObj.name"/>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Email:</label>
                                            <input autocomplete="off" class="form-control" disabled="disabled"
                                                   type="text" v-model="companyObj.email"/>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Logo:</label>
                                            <!--<input type="file" class="form-control" id="file" ref="file"-->
                                            <!--v-on:change="handleFileUpload()"/>-->
                                            <!--<img width="100px" height="100px" v-bind:src="companyObj.logo"/>-->
                                            <a v-on:click="activateGalleryModal">
                                                <label class="bordered_label">
                                                    <img alt="#" class="app_logo" height="100px"
                                                         v-bind:src="companyObj.logo" v-if="companyObj.logo"
                                                         width="100px"/>
                                                    <img alt="#" class="app_logo"
                                                         height="100px"
                                                         src="../../../../assets/images/app-image.png" v-else
                                                         width="100px"/>
                                                </label>
                                            </a>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Company key:</label>
                                            <input autocomplete="off" class="form-control" readonly type="text"
                                                   v-model="companyObj.key"/>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Status:</label>
                                            <select autocomplete="off" class="form-control"
                                                    v-model="companyObj.is_active">
                                                <option value="">select status</option>
                                                <option value="1">Active</option>
                                                <option value="0">In Active</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="row text-center">
                                    <button class="btn btn-primary" v-on:click="updateCompany">Update</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab" id="engagement" v-if="activetab === 2">
                            <div class="state_cols">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Password:</label>
                                            <input autocomplete="off" class="form-control pw" required
                                                   type="text" v-model="companyObj.password" value="2018/12/19"/>
                                            <p class="error" v-if="errors.password">{{ errors.password }}</p>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8">
                                            <label>Confirm Password:</label>
                                            <input autocomplete="off" class="form-control pw"
                                                   required type="text" v-model="companyObj.confirmPassword"/>
                                            <p class="error" v-if="errors.confirmPassword">{{ errors.confirmPassword
                                                }}</p>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="row text-center">
                                    <button class="btn btn-primary" v-on:click="updatePassword">Update</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab" id="package_details" v-if="activetab === 3">
                            <div class="state_cols">
                                <div class="package_info">
                                    <div class="package_col">
                                        <span><b>Package Name:</b> {{packageDetails.name}}</span><br/>
                                        <span><b>Package Type:</b> {{packageDetails.type}}</span>
                                    </div>
                                    <div class="package_col">
                                        <span><b>Package Start Date:</b> {{packageDetails.startDate}}</span><br/>
                                        <span><b>Package End Date:</b> {{packageDetails.endDate}}</span>
                                    </div>
                                </div>
                                <div style="height: 280px; padding-bottom: 20px !important;"
                                     class="table_holder default_scroll">
                                    <table class="auto_tds" style="table-layout: auto;">
                                        <thead>
                                        <tr class="overflow_tr" role="row">
                                            <th style="text-align:center;">
                                                Feature
                                            </th>
                                            <th>
                                                Consume
                                            </th>
                                            <th style="font-size: 15px; width:auto">
                                                Total
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="companyPackage in packageDetails.details">
                                            <td>{{companyPackage.feature}}</td>
                                            <td>{{companyPackage.used}}</td>
                                            <td>{{companyPackage.total}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="input_row">
                                    <input type="button" value="Choose Package" class="right package_input"
                                           v-on:click="changePackageListing">
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
    export default {
        name: "editCompany",
        data() {
            return {
                activetab: 1,
                companyObj: {
                    id: '',
                    name: '',
                    email: '',
                    key: '',
                    logo: '',
                    is_active: '',
                    password: '',
                    confirmPassword: '',
                },
                errors: {
                    name: '',
                    logo: '',
                    is_active: '',
                    password: '',
                    confirmPassword: ''
                },
                packageDetails: {
                    name: "",
                    type: "",
                    startDate: "",
                    endDate: "",
                    details: []
                },
                availablePackages: [],
                selectedPackage: {}
            }
        },
        mounted() {
            $("#footer").css({display: "none"});
            this.companyObj.id = this.$route.params.id;
            this.getCompanylist();
            this.listenForCustomEvents();
            this.getPackageDetails();
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
        },
        methods: {
            getCompanylist() {
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "company/edit",
                        action: "get",
                        id: this.companyObj.id
                    },
                    this.authHeaders
                ).then((response) => {
                    console.log('res', response);
                    if (response.data.meta.code != 200) {
                        if (response.data.data.check_is_admin == 0) {
                            this.$router.push("/dashboard/company-userListing");
                        } else {
                            this.$router.push("/dashboard/company-listing");
                        }
                    }
                    this.companyObj.email = response.data.data.email;
                    this.companyObj.name = response.data.data.name;
                    this.companyObj.key = response.data.data.company_key;
                    this.companyObj.is_active = response.data.data.is_active;
                    this.companyObj.logo = response.data.data.logo;
                    this.companyObj.password = '';
                    this.companyObj.confirmPassword = '';
                    // this.$swal('Deleted', 'You successfully deleted this file', 'success');
                    // this.dataTable.reloadProp = !this.dataTable.reloadProp;
                }).catch((error) => {
                    console.log('error', error);
                });
            },
            updateCompany() {
                var name = this.companyObj.name;
                var is_active = this.companyObj.is_active;
                var logo = this.companyObj.logo;
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "company",
                        action: "update",
                        id: this.companyObj.id,
                        data: this.companyObj
                    },
                    this.authHeaders
                ).then((response) => {
                    console.log('response', response);
                    if (response.data.status == 200) {
                        this.$swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Company has been Updated',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else {
                        this.$swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Failed',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    this.$store.dispatch('companyImageUrlAction', response.data.user.logo);

                }).catch((error) => {
                    console.log('error', error);
                })
            },
            updatePassword() {
                var password = this.companyObj.password;
                var confirmPassword = this.companyObj.confirmPassword;
                if (password == '') {
                    this.errors.password = 'Please add password';
                }
                if (confirmPassword == '') {
                    this.errors.confirmPassword = 'Please add  confirm password';
                }
                if (password != confirmPassword) {
                    this.errors.confirmPassword = 'Password does not matched';
                }
                if (password != '' && confirmPassword != '') {
                    if (password == confirmPassword) {

                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "company/updatePassword",
                                action: "update",
                                id: this.companyObj.id,
                                data: this.companyObj
                            },
                            this.authHeaders
                        ).then((response) => {
                            console.log('update', response);
                            if (response.data.status == 200) {
                                this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Password has been Updated',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            } else {
                                this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Failed',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        }).catch((error) => {
                            console.log('error', error);
                        })
                    }
                }
            },
            activateGalleryModal() {
                this.bus.$emit('galleryModalOpen', false);
            },
            listenForCustomEvents() {
                this.bus.$on("galleryModalReturn", (url) => {
                    this.companyObj.logo = url;
                });
            },
            getPackageDetails() {

                const payLoad = {
                    resource: "company/package-details/" + this.$route.params.id,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.packageDetails = response.data.data;
                }).catch((error) => {
                })
            },
            changePackageListing() {
                const payLoad = {
                    resource: "company/package-listing/" + this.$route.params.id,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.availablePackages = response.data.data;
                    $("#packages_list").modal("show");
                }).catch((error) => {
                })
            },
            addRemove($event, choosedPackage) {
                if ($event.target.checked) {
                    this.selectedPackage = JSON.parse(JSON.stringify(choosedPackage));
                } else {
                    this.selectedPackage = {};
                }
            },
            changePackage() {

                if (JSON.stringify(this.selectedPackage) != "{}") {

                    const payLoad = {
                        resource: "company/change-package",
                        action: "post",
                        data: {
                            selectedPackage: this.selectedPackage,
                            companyId: this.companyObj.id
                        }
                    };

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders
                    ).then((response) => {

                        if (response.data.meta.status.toLowerCase() == "success") {
                            this.$swal({
                                position: 'top-right',
                                type: 'success',
                                title: response.data.data,
                                showConfirmButton: false,
                                timer: 2500
                            });
                            this.getPackageDetails();
                            $("#packages_list").modal("hide");
                        } else {
                            this.$swal({
                                position: 'top-right',
                                type: 'error',
                                title: response.data.data,
                                showConfirmButton: false,
                                timer: 2500
                            });
                        }

                    }).catch((error) => {

                    });

                } else {
                    this.$swal({
                        position: 'top-right',
                        type: 'info',
                        title: "No package Selected",
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            }
        },
        watch: {
            '$route.params.id'(currVal, oldVal) {
                this.companyObj.id = this.currVal;
                this.getCompanylist();
            },
            companyObj: {
                handler(companyObj) {
                    console.log('companyObj', companyObj);
                    if (companyObj.password != "") {
                        this.errors.password = '';
                    }
                    if (companyObj.confirmPassword != "") {
                        this.errors.confirmPassword = '';
                    }
                    if (companyObj.password && companyObj.confirmPassword) {
                        if (companyObj.password == companyObj.confirmPassword) {
                            this.errors.confirmPassword = 'Password matched'
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

    .package_input {
        float: right;
        background: #2a8689;
        color: #fff;
    }

    input.pw {
        -webkit-text-security: disc;
    }

    .package_info {
        padding: 5px 2px 2px;
        overflow: hidden;
    }

    .package_info .package_col {
        float: left;
        max-width: 49%;
    }

    .package_info .package_col span {
        color: #4c9597;
        font-size: 14px;
    }

    .package_info .package_col:last-child {
        float: right;
    }

    .select_package_checkbox {
        -webkit-appearance: checkbox;
        height: auto !important;
    }
</style>