<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Package</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search here..."
                           type="search" v-on:change="dataTable.searchProp = $event.target.value">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li><a v-on:click="openModal">Add Package</a></li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="associate_companies" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">Associated Companies</h4>
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
                                                Email
                                            </th>
                                            <th>
                                                Package Start Date
                                            </th>
                                            <th style="font-size: 15px; width:auto">
                                                Package End Date
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="company in associatedCompanies">
                                            <td>{{company.name}}</td>
                                            <td>{{company.email}}</td>
                                            <td>{{company.start_time}}</td>
                                            <td>{{company.end_time}}</td>
                                        </tr>
                                        <tr v-if="associatedCompanies.length == 0">
                                            <td colspan="5">
                                                No record found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="add_package" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">{{packageObj.id == '' || packageObj.id ==
                                    null ? 'Add': 'Update'}} Package</h4>
                            </div>
                            <div class="modal-body" style="max-height: 690px">

                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Name:</h4>
                                        <input type="text" v-model="packageObj.name">
                                        <p class="error">{{packageErrors.name}}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Type:</h4>
                                        <select v-model="packageObj.type">
                                            <option v-bind:value="'monthly'">Monthly</option>
                                            <option v-bind:value="'yearly'">Yearly</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal_row">
                                    <div class="modal_col direction_col" style="width: 100%">
                                        <h4 class="modal-title">Description:</h4>
                                        <textarea v-model="packageObj.description"></textarea>
                                        <p class="error">{{packageErrors.description}}</p>
                                    </div>
                                </div>

                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Push Limit:</h4>
                                        <input type="text" v-model="packageObj.pushLimit">
                                        <p class="error">{{packageErrors.pushLimit}}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">InApp Limit:</h4>
                                        <input type="text" v-model="packageObj.inAppLimit">
                                        <p class="error">{{packageErrors.inAppLimit}}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Email Limit:</h4>
                                        <input type="text" v-model="packageObj.emailLimit">
                                        <p class="error">{{packageErrors.emailLimit}}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">NFC Limit:</h4>
                                        <input type="text" v-model="packageObj.nfcLimit">
                                        <p class="error">{{packageErrors.nfcLimit}}</p>
                                    </div>
                                </div>

                                <div class="modal_row" v-if="packageObj.packageSubscriber == null">
                                    <div class="modal_col" style="float: right; width: auto;">
                                        <input type="submit"
                                               v-bind:value="packageObj.id == '' || packageObj.id == null ? 'ADD': 'UPDATE'"
                                               v-on:click="recordInsertion">
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
                                <li style="padding: 2px 0;" v-for="section in sideFiltersArr">
                                    <a class="newsfeed_arrow_opener opener anchor_pointer">{{section.column}}<i
                                            class="fas fa-sort-down"></i></a>
                                    <ul style="margin-top: 8px; width: 150px" class="list_none side_tags inner_tags">
                                        <li style="width: 100%" v-for="child in section.children"
                                            v-bind:class="JSON.stringify(child) == JSON.stringify(dataTable.sideFilters) ? 'active': ''"
                                            v-on:click="sideFilterFunction(child)"><a
                                                class="anchor_pointer sideBar-filter"
                                        >{{section.column.toLowerCase() ==
                                            'tags' ? child.label: child.label.charAt(0).toUpperCase() +
                                            child.label.slice(1)}}
                                            <i v-if="JSON.stringify(child) == JSON.stringify(dataTable.sideFilters)"
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
                                     v-bind:tableColumn="dataTable.column"
                                     v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                     v-bind:tableFilterable="dataTable.filterable"
                                     v-bind:tableHeadings="dataTable.headings"
                                     v-bind:tableMapColumns="dataTable.mapColumns"
                                     v-bind:tableReloading="dataTable.reloadProp"
                                     v-bind:tableSearch="dataTable.searchProp"
                                     v-bind:tableSideBarSearch="dataTable.sideFilters"
                                     v-bind:tableSortable="dataTable.sortable"
                                     v-bind:componentName="dataTable.componentName"
                                     v-bind:footerComponent="dataTable.isFooterComponent">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click.stop="dropDownAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down"
                                       v-on:click.stop="dropDownAction(data.actionlisting)"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id + '_action'">
                                        <li>
                                            <a v-on:click="getRecord(data.actionlisting.row.id)"><i
                                                    class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <!--<li>
                                            <a v-on:click="getAssociateCompanies(data.actionlisting.row.id)"><i
                                                    class="fas fa-edit"></i> Associated CO
                                            </a>
                                        </li>-->
                                        <li>
                                            <a v-on:click="ChangeStatus(data.actionlisting.row.id, data.actionlisting.row.is_active == 1 ?
                                                'Inactive': 'Active')"><i
                                                    class="fas fa-edit"></i> {{data.actionlisting.row.is_active == 1 ?
                                                'Inactive': 'Active'}}
                                            </a>
                                        </li>
                                    </ul>
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
        name: "PackageListing",
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['name', 'type', 'push_limit', 'email_limit', 'inapp_limit', 'nfc_limit', 'status', 'Action'],
                    mapColumns: ['id', 'name', 'type', 'push_limit', 'email_limit', 'inapp_limit', 'nfc_limit', 'is_active', 'status'],
                    filterable: ['name', 'type', 'push_limit', 'email_limit', 'inapp_limit', 'nfc_limit'],
                    sortable: ['name', 'type', 'push_limit', 'email_limit', 'inapp_limit', 'nfc_limit'],
                    componentName: "packageListing",
                    isFooterComponent: true,
                    headings: {
                        name: 'Name',
                        type: 'Type',
                        push_limit: 'Push Limit',
                        email_limit: 'Email Limit',
                        inapp_limit: 'InApp Limit',
                        nfc_limit: 'NFC Limit',
                        status: 'Status'
                    },
                    payLoad: {
                        resource: "packages",
                        action: "get",
                    },
                    searchProp: '',
                    sideFilters: {},
                },
                packageObj: {
                    id: "",
                    name: "",
                    type: "monthly",
                    description: "",
                    pushLimit: 0,
                    inAppLimit: 0,
                    emailLimit: 0,
                    nfcLimit: 0,
                    packageSubscriber: null
                },
                packageErrors: {
                    name: "",
                    type: "",
                    description: "",
                    pushLimit: "",
                    inAppLimit: "",
                    emailLimit: "",
                    nfcLimit: "",
                },
                sumbitPressed: false,
                sideFiltersArr: [
                    {
                        column: "Status",
                        children: [
                            {
                                parent: "is_active",
                                value: "1",
                                label: "Active"
                            },
                            {
                                parent: "is_active",
                                value: "0",
                                label: "Inactive"
                            }
                        ]
                    },
                    {
                        column: "Type",
                        children: [
                            {
                                parent: "type",
                                value: "monthly",
                                label: "monthly"
                            },
                            {
                                parent: "type",
                                value: "yearly",
                                label: "yearly"
                            }
                        ]
                    }
                ],
                associatedCompanies: [],
            }
        },
        mounted() {
            $("#footer").css({display: 'block'});
        },
        beforeDestroy() {
            $("#footer").css({display: 'none'});
        },
        methods: {
            openModal() {
                this.packageObj = {
                    id: "",
                    name: "",
                    type: "monthly",
                    description: "",
                    pushLimit: 0,
                    inAppLimit: 0,
                    emailLimit: 0,
                    nfcLimit: 0,
                    packageSubscriber: null
                };
                this.removeErrors();
            },
            recordInsertion() {
                this.sumbitPressed = true;
                if (this.validateRecord()) {
                    const payLoad = {
                        resource: "packages",
                        action: "post",
                        data: this.packageObj
                    };

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders
                    ).then((response) => {
                        this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        $("#add_package").modal("hide");

                        this.$swal({
                            position: 'top-right',
                            type: 'success',
                            title: 'Package ' + (this.packageObj.id == null || this.packageObj.id == "" ? 'Created' : 'updated') + ' Successfully',
                            showConfirmButton: false,
                            timer: 2500
                        });

                    }).catch((error) => {

                    });
                }

            },
            validateRecord() {
                let success = true;
                let expression = /^\+?(0|[1-9]\d*)$/;
                this.packageErrors = {
                    name: "",
                    type: "",
                    description: "",
                    pushLimit: "",
                    inAppLimit: "",
                    emailLimit: "",
                    nfcLimit: "",
                };


                if (this.packageObj.name == "") {
                    this.packageErrors.name = "Name is required";
                    success = false;
                }


                if (this.packageObj.description == "") {
                    this.packageErrors.description = "Description is required";
                    success = false;
                }

                if (!expression.test(this.packageObj.pushLimit)) {
                    this.packageErrors.pushLimit = "Push limit can only be a positive integer";
                    success = false;
                }

                if (!expression.test(this.packageObj.inAppLimit)) {
                    this.packageErrors.inAppLimit = "In App limit can only be a positive integer";
                    success = false;
                }

                if (!expression.test(this.packageObj.emailLimit)) {
                    this.packageErrors.emailLimit = "Email limit can only be a positive integer";
                    success = false;
                }

                if (!expression.test(this.packageObj.nfcLimit)) {
                    this.packageErrors.nfcLimit = "NFC limit can only be a positive integer";
                    success = false;
                }

                return success;
            },
            getRecord(packageId) {
                const payLoad = {
                    resource: "packages/" + packageId,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.packageObj = response.data.data;
                    this.removeErrors();
                }).catch((error) => {

                });
            },
            sideFilterFunction(obj) {
                this.dataTable.sideFilters = obj;
            },
            removeActive() {
                this.dataTable.sideFilters = {};
            },
            dropDownAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            getAssociateCompanies(packageId) {
                const payLoad = {
                    resource: "associate-companies/" + packageId,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.associatedCompanies = response.data.data;
                    $("#associate_companies").modal("show");
                }).catch((error) => {

                });
            },
            removeErrors() {
                this.sumbitPressed = false;
                this.packageErrors = {
                    name: "",
                    type: "",
                    description: "",
                    pushLimit: "",
                    inAppLimit: "",
                    emailLimit: "",
                    nfcLimit: "",
                };
                $("#add_package").modal("show");
            },
            ChangeStatus(packageId, todoStatus) {

                this.$swal({
                    title: "Are you sure ?",
                    text: todoStatus.toLowerCase() == "inactive" ? "You want to Inactive this Package" : "You want to Activate this Package",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {
                    if (result.value) {
                        const payLoad = {
                            resource: "package/change/status/" + packageId + "/" + todoStatus,
                            action: "get",
                        };

                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payLoad,
                            this.authHeaders
                        ).then((response) => {

                            if (response.data.meta.status.toLowerCase() == "error") {
                                this.$swal({
                                    position: 'top-right',
                                    type: 'error',
                                    title: response.data.data,
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            } else {
                                this.$swal({
                                    position: 'top-right',
                                    type: 'success',
                                    title: response.data.data,
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                                this.dataTable.reloadProp = !this.dataTable.reloadProp;
                            }
                        }).catch((error) => {

                        });
                    }
                });

            }
        },
        watch: {
            packageObj: {
                handler(currVal, oldVal) {
                    if (this.sumbitPressed) {
                        this.validateRecord();
                    }
                },
                deep: true
            }
        }
    }
</script>

<style>

</style>