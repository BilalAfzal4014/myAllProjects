<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Campaigns</strong>
                    </div>
                    <input type="search" id="table_search" v-model="campaignObj.search"
                           v-on:change="searching()" placeholder="Search here...">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <router-link to="/dashboard/campaign-create">Create Campaign</router-link>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1">
                        <div class="side_widget">
                            <strong class="title">Status</strong>
                            <ul class="list_none indicators">
                                <li><i class="active"></i> <span>Active</span></li>
                                <li><i class="draft"></i> <span>Draft</span></li>
                                <li><i class="suspend"></i> <span>Suspend</span></li>
                                <li><i class="expired"></i> <span>Expired</span></li>
                            </ul>
                        </div>
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags">
                                <li v-for="section in campaignObj.campaignFilter">
                                    <a class="opener anchor_pointer">{{section.column}}<i class="fas fa-sort-down"></i></a>
                                    <ul style="width: 150px" class="list_none side_tags inner_tags indicators">
                                        <li style="width: 100%" v-for="child in section.children"
                                            v-on:click="campaignSideFilter(child)"
                                            v-bind:class="JSON.stringify(child) == JSON.stringify(dataTable.campaignFilterProp) ? 'active': ''">
                                            <a class="anchor_pointer sideBar-filter">{{section.column.toLowerCase() ==
                                                'tags' ? child.value: child.value.charAt(0).toUpperCase() +
                                                child.value.slice(1)}}
                                                <i style="margin-right: 4px; margin-top: 3px;" class="fa fa-times"
                                                   aria-hidden="true"
                                                   v-if="JSON.stringify(child) == JSON.stringify(dataTable.campaignFilterProp)"
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
                        <listing v-bind:tableColumn="dataTable.column"
                                 v-bind:tableFilterable="dataTable.filterable"
                                 v-bind:tableSortable="dataTable.sortable"
                                 v-bind:tableHeadings="dataTable.headings"
                                 v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                 v-bind:apiPayLoad="dataTable.payLoad"
                                 v-bind:tableMapColumns="dataTable.mapColumns"
                                 v-bind:tableSearch="dataTable.searchProp"
                                 v-bind:tableSideBarSearch="dataTable.campaignFilterProp"
                                 v-bind:componentName="dataTable.componentName"
                                 v-bind:tableReloading="dataTable.reloadProp"
                                 v-bind:footerComponent="dataTable.isFooterComponent">
                            <template slot="listing" slot-scope="data">
                                <i class="fas fa-cog" v-on:click.stop="campaignAction(data.actionlisting)"></i>
                                <i class="fas fa-sort-down" v-on:click.stop="campaignAction(data.actionlisting)"></i>
                                <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id + '_action'">
                                    <li v-if="data.actionlisting.row.status == 'draft'">
                                        <router-link
                                                v-bind:to="'/dashboard/campaign-edit/edit/' + data.actionlisting.row.id"
                                                data-toggle="modal"
                                                data-target="#add_lookup"><i
                                                class="fas fa-edit"></i> Edit
                                        </router-link>
                                    </li>
                                    <li v-else>
                                        <router-link
                                                v-bind:to="'/dashboard/campaign-edit/view/' + data.actionlisting.row.id"
                                                data-toggle="modal"
                                                data-target="#add_lookup"><i
                                                class="fas fa-edit"></i> View
                                        </router-link>
                                    </li>
                                    <li v-if="data.actionlisting.row.status != 'draft'">
                                        <router-link
                                                v-bind:to="'/dashboard/campaign-stats/' + data.actionlisting.row.id"><i
                                                class="fas fa-list"></i>View Stats
                                        </router-link>
                                    </li>
                                    <li v-if="data.actionlisting.row.status == 'active'">
                                        <a class="anchor_pointer"
                                           v-on:click="suspendCampaign(data.actionlisting.row.id)"
                                           data-toggle="modal"
                                           data-target="#add_lookup"><i class="fa fa-times"></i> Suspend
                                        </a>
                                    </li>
                                    <li>
                                        <a class="anchor_pointer" v-on:click="exportUsers(data.actionlisting.row.id)"
                                           data-toggle="modal"
                                           data-target="#add_lookup"><i class="fas fa-edit"></i> Export
                                        </a>
                                    </li>
                                </ul>
                            </template>
                            <template slot="campaignName" slot-scope="data">
                                <i v-if="data.nameColumn.row.campaign_type.toLowerCase() == 'inapp'"
                                   class="fas fa-mobile-alt"></i>
                                <i v-else-if="data.nameColumn.row.campaign_type.toLowerCase() == 'email'"
                                   class="far fa-envelope"></i>
                                <i v-else="data.nameColumn.row.campaign_type.toLowerCase() == 'push'"
                                   class="fas fa-address-book"></i>
                            </template>
                        </listing>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import listing from '../../../otherComponents/datatable/datatableComponent';
    import csvDownload from "../../../../mixins/csvDownload/csvDownload";

    export default {
        name: "Campaign-Listing",
        mixins: [csvDownload],
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    componentName: "campaignListing",
                    column: ['name', 'campaign_type', 'targeted_users', 'total', 'sent', 'failed', 'view', 'updated_at', 'Action'],
                    mapColumns: ['id', 'name', 'campaign_type', 'status', 'targeted_users', 'total', 'sent', 'failed', 'view', 'updated_at'],
                    filterable: ['name', 'campaign_type', 'total', 'sent', 'failed', 'view', 'updated_at'],
                    sortable: ['name', 'campaign_type', 'total', 'sent', 'failed', 'view', 'updated_at'],
                    isFooterComponent: true,
                    headings: {
                        name: 'Name',
                        campaign_type: 'Type',
                        targeted_users: 'Target Users',
                        total: 'Total',
                        sent: 'Sent',
                        failed: 'Failed',
                        view: 'View',
                        updated_at: 'Last Edited',
                        Action: 'Action'
                    },
                    columnsClasses: {
                        name: 'col-sm-2 widdth_20',
                        campaign_type: 'col-sm-3 width_10',
                        targeted_users: 'col-sm-1 widdth_20',
                        total: 'col-sm-1',
                        sent: 'col-sm-1',
                        failed: 'col-sm-1',
                        view: 'col-sm-1',
                        updated_at: 'col-sm-2',
                        Action: 'width_100'
                    },
                    payLoad: {
                        "resource": "campaigns",
                        "action": "get"
                    },
                    searchProp: '',
                    campaignFilterProp: {},
                },
                campaignObj: {
                    search: '',
                    campaignFilter: []
                },
            };
        },
        mounted() {
            this.getCampaignSideFilters();
        },
        methods: {
            getCampaignSideFilters() {
                const payLoad = {
                    resource: "campaign/filters",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {
                    if (response.data.meta.code == 200) {
                        this.campaignObj.campaignFilter = response.data.data;
                    }
                }).catch((error) => {
                    console.clear();
                    console.log(error);
                });
            },
            campaignAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            exportUsers(campaignId) {
                const payLoad = {
                    resource: "campaign/export/" + campaignId,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        this.downloadCSV("campaign - " + new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString() + ".csv", response.data.data);
                    }
                }).catch((error) => {

                });
            },
            campaignSideFilter(obj) {
                this.dataTable.campaignFilterProp = obj;
            },
            removeActive(obj) {
                this.dataTable.campaignFilterProp = {};
            },
            searching() {
                this.dataTable.searchProp = this.campaignObj.search;
            },
            suspendCampaign(campaignId) {


                this.$swal({
                    title: "Are you sure ?  You want to suspend this campaign !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {

                    if (result.value) {
                        const payLoad = {
                            resource: "campaigns",
                            action: "update",
                            id: campaignId,
                            data: {
                                step: "preview",
                                status: 'suspended'
                            }
                        };

                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payLoad,
                            this.authHeaders
                        ).then((response) => {
                            $(".table_drop").hide();
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {

                        });
                    }

                });
            }
        }
    }
</script>

<style>
    .green {
        border-left: 4px solid #adff2f;
    }

    .blue {
        border-left: 4px solid #0000ff;
    }

    .red {
        border-left: 4px solid #ff0000;
    }

    .black {
        border-left: 4px solid #000000;
    }

    tr td:first-child {
        text-align: left;
        padding-left: 7px;
    }

    .table_holder table th {
        padding: 5px 5px;
        font-size: 14px;
    }

    th .pull-right {
        font-size: 12px;
    }
</style>