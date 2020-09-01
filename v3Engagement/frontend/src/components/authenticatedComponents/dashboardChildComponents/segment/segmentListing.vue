<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Segments</strong>
                    </div>
                    <input type="search" id="table_search" name="table_search"
                           v-model="segmentObj.search"
                           v-on:change="searching()"
                           placeholder="Search here...">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <router-link to="/dashboard/segment-create">Create Segment</router-link>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags">
                                <li v-for="filter in segmentObj.segmentFilter">
                                    <a class="opener anchor_pointer">{{filter.column.charAt(0).toUpperCase() +
                                        filter.column.slice(1)}}<i class="fas fa-sort-down"></i></a>
                                    <ul style="width: 150px" class="list_none side_tags inner_tags indicators">
                                        <li style="width: 100%" v-for="child in filter.children"
                                            v-on:click="segmentSideFilter(child)"
                                            v-bind:class="JSON.stringify(dataTable.segmentFilterProp) == JSON.stringify(child) ? 'active': ''">
                                            <a class="anchor_pointer sideBar-filter">{{filter.column.toLowerCase() ==
                                                'tags' ? child.value: child.value.charAt(0).toUpperCase() +
                                                child.value.slice(1)}}
                                                <i style="margin-right: 4px; margin-top: 3px;" class="fa fa-times"
                                                   aria-hidden="true"
                                                   v-if="JSON.stringify(dataTable.segmentFilterProp) == JSON.stringify(child)"
                                                   v-on:click.stop="removeActive">
                                                </i>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table_holder mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div id="example_wrapper" class="dataTables_wrapper no-footer">
                            <listing v-bind:tableColumn="dataTable.column"
                                     v-bind:tableFilterable="dataTable.filterable"
                                     v-bind:tableSortable="dataTable.sortable"
                                     v-bind:tableHeadings="dataTable.headings"
                                     v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                     v-bind:apiPayLoad="dataTable.payLoad"
                                     v-bind:tableMapColumns="dataTable.mapColumns"
                                     v-bind:tableSearch="dataTable.searchProp"
                                     v-bind:tableSideBarSearch="dataTable.segmentFilterProp"
                                     v-bind:componentName="dataTable.componentName"
                                     v-bind:tableReloading="dataTable.reloadProp"
                                     v-bind:footerComponent="dataTable.isFooterComponent">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click.stop="segmentAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down" v-on:click.stop="segmentAction(data.actionlisting)"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id + '_action'">
                                        <li>
                                            <router-link
                                                    v-bind:to="'/dashboard/segment-edit/' + data.actionlisting.row.id"
                                                    data-toggle="modal"
                                                    data-target="#add_lookup"><i
                                                    class="fas fa-edit"></i> Edit
                                            </router-link>
                                        </li>
                                        <li>
                                            <a class="anchor_pointer"
                                               v-on:click="changeStatus(data.actionlisting.row.id, data.actionlisting.row.is_active == 1 ? 0: 1)"
                                            ><i class="fas fa-edit"></i>
                                                {{data.actionlisting.row.is_active == 1 ? 'In Active': 'Active'}}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="anchor_pointer"
                                               v-on:click="exportUsers(data.actionlisting.row.id)"
                                            ><i class="fas fa-edit"></i> Export
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
    import csvDownload from "../../../../mixins/csvDownload/csvDownload";

    export default {
        name: "Segment-Listing",
        mixins: [csvDownload],
        components: {
            listing,
        },
        mounted() {
            this.getSegmentSideFilters();
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['name', 'targeted_users', 'total_campaigns', 'total_news_feeds', 'updated_at', 'Action'],
                    mapColumns: ['id', 'name', 'targeted_users', 'total_campaigns', 'total_news_feeds', 'is_active', 'updated_at'],
                    filterable: ['name', 'total_campaigns', 'total_news_feeds', 'updated_at'],
                    sortable: ['name', 'total_campaigns', 'total_news_feeds', 'updated_at'],
                    headings: {
                        name: 'Name',
                        targeted_users: 'Target Users',
                        total_campaigns: 'Total Campaigns',
                        total_news_feeds: 'Total NewsFeeds',
                        updated_at: 'Last Edited',
                        Action: 'Action'

                    },
                    isFooterComponent: true,
                    componentName: "segmentListing",
                    payLoad: {
                        resource: "segments",
                        action: "get",
                    },
                    searchProp: '',
                    segmentFilterProp: {},
                },
                segmentObj: {
                    search: '',
                    segmentFilter: []
                },
            }
        },
        methods: {
            getSegmentSideFilters() {
                const payLoad = {
                    resource: "segment/filters",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {
                    this.segmentObj.segmentFilter = response.data.data;
                }).catch((error) => {
                    console.clear();
                    console.log(error);
                });
            },
            searching() {
                this.dataTable.searchProp = this.segmentObj.search;
            },
            segmentSideFilter(row) {
                this.dataTable.segmentFilterProp = row;
            },
            segmentAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive() {
                this.dataTable.segmentFilterProp = {};
            },
            exportUsers(segmentId) {
                const payLoad = {
                    resource: "segment/export/" + segmentId,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    if (response.data.meta.code === 200) {
                        this.downloadCSV("segment - " + new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString() + ".csv", response.data.data);
                    }
                }).catch((error) => {

                });
            },
            changeStatus(segmentId, status) {

                this.$swal({
                    title: "Are you sure ?  You want to " + (status == 1 ? 'active' : 'inactive') + " this !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {

                    if (result.value) {
                        const payLoad = {
                            resource: "segment/change-status/" + segmentId + "/" + status,
                            action: "get",
                        };

                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payLoad,
                            this.authHeaders
                        ).then((response) => {
                            if (response.data.data.status) {
                                this.$swal({
                                    position: 'top-right',
                                    type: 'success',
                                    title: response.data.data.message,
                                    showConfirmButton: false,
                                    timer: 2500
                                });

                                this.dataTable.reloadProp = !this.dataTable.reloadProp;
                            } else {

                                this.$swal({
                                    position: 'top-right',
                                    type: 'error',
                                    title: response.data.data.message,
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            }

                        }).catch((error) => {

                        });
                    }
                });


            }
        },

    }
</script>

<style>

</style>