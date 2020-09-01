<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Campaign Queue</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search here..."
                           type="search"
                           v-model="queueObj.search"
                           v-on:change="searching111">
                </header>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" id="mCSB_1"
                             style="max-height: none;" tabindex="0">
                            <div class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" dir="ltr"
                                 id="mCSB_1_container" style="position:relative; top:0; left:0;">
                                <div class="side_widget">
                                    <strong class="title colored">Filters:</strong>
                                    <ul class="list_none side_tags">
                                        <li>
                                            <a class="anchor_pointer opener">Company
                                                <i class="fas fa-sort-down">

                                                </i>
                                            </a>
                                            <ul class="list_none inner_tags side_tags indicators" style="width: 150px"
                                                v-if="companies.length > 0">
                                                <li style="width: 94%"
                                                    v-bind:class="dataTable.lookupProp == company.id ? 'active': ''"
                                                    v-for="(company, index) in companies">
                                                    <a class="sideBar-filter" style="cursor: pointer"
                                                       v-bind:id="'active'+index"
                                                       v-on:click="queuesFilter(company.id, index)">{{company.name}}
                                                        <i aria-hidden="true"
                                                           class="fa fa-times" v-if="dataTable.lookupProp == company.id"
                                                           v-on:click.stop="removeActive(index)">

                                                        </i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mCSB_scrollTools mCSB_1_scrollbar mCS-light mCSB_scrollTools_vertical"
                                 id="mCSB_1_scrollbar_vertical"
                                 style="display: none;">
                                <div class="mCSB_draggerContainer">
                                    <div class="mCSB_dragger" id="mCSB_1_dragger_vertical"
                                         style="position: absolute; min-height: 130px; height: 0px; top: 0px; display: block; max-height: 42px;">
                                        <div class="mCSB_dragger_bar" style="line-height: 130px;"></div>
                                    </div>
                                    <div class="mCSB_draggerRail"></div>
                                </div>
                            </div>
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
                                    <i class="fas fa-sort-down"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id+'_action'">

                                        <li v-if="data.actionlisting.row.status == 'Complete' || data.actionlisting.row.status == 'Failed'">
                                            <a v-on:click="changeStatus(data.actionlisting.row.id, 'Available')"><i
                                                    class="fas fa-bookmark"></i> Set to Available
                                            </a>
                                        </li>
                                        <li v-if="data.actionlisting.row.status == 'Available'">
                                            <a v-on:click="changeStatus(data.actionlisting.row.id, 'Processing')"><i
                                                    class="fas fa-play"></i> Execute
                                            </a>
                                        </li>

                                        <li>
                                            <a v-on:click="editlanguage(data.actionlisting.row.id)"><i
                                                    class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a v-on:click="deletLookup(data.actionlisting.row.id)"><i
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
        name: "queueListing",
        components: {
            listing
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['id', 'campaign_id', 'company_name', 'status', 'details', 'error_message', 'created_at', 'Action'],
                    mapColumns: ['id', 'campaign_id', 'company_name', 'status', 'details', 'created_at', 'error_message'],
                    filterable: ['id', 'campaign_id', 'company_name', 'status', 'details', 'created_at', 'error_message'],
                    sortable: ['id', 'campaign_id', 'company_name', 'status', 'details', 'created_at', 'error_message'],
                    componentName: "queueListing",
                    isFooterComponent: true,
                    headings: {
                        id: 'ID',
                        campaign_id: 'Campaign ID',
                        company_name: 'Company Name',
                        status: 'Status',
                        details: 'Detail',
                        error_message: 'Error Message',
                        created_at: 'Created At',
                        Action: 'Action'

                    },
                    // columnsClasses: {
                    //     id: 'width_20',
                    //     campaign_id: 'width_60',
                    //     company_name: 'width_60',
                    //     status: 'width_100',
                    //     details: 'width_60',
                    //     error_message: 'width_60',
                    //     created_at: 'width_60',
                    //     Action: 'width_60'
                    // },
                    payLoad: {
                        resource: "campaign/queues",
                        action: "get",
                    },
                    searchProp: '',
                    lookupProp: {},
                },
                queueObj: {
                    search: '',
                    lookupFilter: []
                },
                companies: {}
            }

        },
        mounted() {
            $("#footer").css({display: 'block'});
            this.Filter();
            this.getCompanies();
        },
        beforeDestroy() {
            $("#footer").css({display: 'none'});
        },
        methods: {
            searching111() {
                this.dataTable.searchProp = this.queueObj.search;
            },
            removeActive(id) {
                $("#active" + id).removeClass("active");
                this.dataTable.lookupProp = {}
            },
            Filter() {
                this.dataTable.lookupProp = [];
            },
            deletLookup(id) {
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
                            this.$swal('Deleted', 'You successfully deleted this file', 'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                            console.log('error', error);
                        });
                    }
                });
            },
            getCompanies() {
                var payload = {
                    resource: "companies",
                    action: "get",
                    data: {}
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    this.companies = response.data
                }).catch((error) => {
                });
            },
            queuesFilter(id, index) {
                $(".sideBar-filter").removeClass("active");
                $("#active" + index).addClass("active");
                this.dataTable.lookupProp = id
            },
            changeStatus(id, status) {
                this.$swal({
                    title: "Update Status?",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {
                    if (result.value) {
                        var payload = {
                            resource: "campaign/queues",
                            action: "post",
                            data: {id: id, status: status}
                        };
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payload,
                            this.authHeaders
                        ).then((response) => {
                            this.$swal('Success', 'Queue job executed successfully.', 'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {
                        });
                    }
                });
            },
            segmentAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            }
        }
    }
</script>

<style>

</style>