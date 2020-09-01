<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>News Feed</strong>
                    </div>
                    <input type="search" id="table_search" v-model="newsFeedObj.search"
                           v-on:change="searching()" placeholder="Search here...">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <router-link to="/dashboard/newsfeed-create">Create News Feed</router-link>
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
                            </ul>
                        </div>
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags indicators">
                                <li style="padding: 2px 0;" v-for="section in newsFeedObj.newsFeedFilter">
                                    <a class="newsfeed_arrow_opener opener anchor_pointer">{{section.column}}<i
                                            class="fas fa-sort-down"></i></a>
                                    <ul style="margin-top: 8px; width: 150px" class="list_none side_tags inner_tags">
                                        <li style="width: 100%" v-for="child in section.children"
                                            v-bind:class="JSON.stringify(child) == JSON.stringify(dataTable.newsFeedFilterProp) ? 'active': ''"
                                            v-on:click="newsFeedSideFilter(child)"><a
                                                class="anchor_pointer sideBar-filter"
                                        >{{section.column.toLowerCase() ==
                                            'tags' ? child.value: child.value.charAt(0).toUpperCase() +
                                            child.value.slice(1)}}
                                            <i v-if="JSON.stringify(child) == JSON.stringify(dataTable.newsFeedFilterProp)"
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
                        <listing v-bind:tableColumn="dataTable.column"
                                 v-bind:tableFilterable="dataTable.filterable"
                                 v-bind:tableSortable="dataTable.sortable"
                                 v-bind:tableHeadings="dataTable.headings"
                                 v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                 v-bind:apiPayLoad="dataTable.payLoad"
                                 v-bind:tableMapColumns="dataTable.mapColumns"
                                 v-bind:tableSearch="dataTable.searchProp"
                                 v-bind:tableSideBarSearch="dataTable.newsFeedFilterProp"
                                 v-bind:componentName="dataTable.componentName"
                                 v-bind:tableReloading="dataTable.reloadProp"
                                 v-bind:footerComponent="dataTable.isFooterComponent">
                            <template slot="listing" slot-scope="data">
                                <i class="fas fa-cog" v-on:click.stop="newsFeedAction(data.actionlisting)"></i>
                                <i class="fas fa-sort-down" v-on:click.stop="newsFeedAction(data.actionlisting)"></i>
                                <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id + '_action'">
                                    <li>
                                        <router-link
                                                v-bind:to="'/dashboard/newsfeed-edit/' + data.actionlisting.row.id"
                                                data-toggle="modal"
                                                data-target="#add_lookup"><i
                                                class="fas fa-edit"></i> Edit
                                        </router-link>
                                    </li>
                                    <li v-if="data.actionlisting.row['nf1-status'] != 'draft'">
                                        <router-link
                                                v-bind:to="'/dashboard/newsfeed-stats/' + data.actionlisting.row.id"><i
                                                class="fas fa-list"></i>View Stats
                                        </router-link>
                                    </li>
                                    <li v-if="data.actionlisting.row['nf1-status'] == 'active'">
                                        <a class="anchor_pointer"
                                           v-on:click="newsFeedOperation(data.actionlisting.row.id, 'suspend')"
                                           data-toggle="modal"
                                           data-target="#add_lookup"><i class="fa fa-times"></i> Suspend
                                        </a>
                                    </li>
                                    <li>
                                        <a class="anchor_pointer"
                                           v-on:click="newsFeedOperation(data.actionlisting.row.id, 'delete')"
                                           data-toggle="modal"
                                           data-target="#add_lookup"><i class="fa fa-times"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </template>
                            <template slot="newsFeedName" slot-scope="data">

                                {{data.rowObj.row['nf1-name']}}
                                <div class="inner_table_info">
                                    <ul class="list_none">
                                        <li>
                                            <em>{{data.rowObj.row.stats.targetedUsers}}</em>
                                            <span>Targeted Users</span>
                                        </li>
                                        <li>
                                            <em>{{data.rowObj.row.stats.segment}}</em>
                                            <span>Segment</span>
                                        </li>
                                        <li>
                                            <em>{{data.rowObj.row.stats.click}}</em>
                                            <span>Clicks (Unique)</span>
                                        </li>
                                        <li>
                                            <em>{{data.rowObj.row.stats.newsFeedImpression}}</em>
                                            <span>Impressions (Unique)</span>
                                        </li>
                                        <li>
                                            <em>{{data.rowObj.row.stats.clickThrough}}</em>
                                            <span>Clickthrough Rate (Unique)</span>
                                        </li>
                                    </ul>
                                </div>
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

    export default {
        name: "newsfeed-Listing",
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    componentName: "newsFeedListing",
                    column: ['nf1-name', 'l1-name', 's1-name', 'nf1-start_time', 'nf1-end_time', 'nf1-status', 'Action'],
                    mapColumns: ['id', 'nf1-name', 'l1-name', 's1-name', 'nf1-start_time', 'nf1-end_time', 'nf1-status', 'stats'],
                    filterable: ['nf1-name', 'l1-name', 's1-name', 'nf1-start_time', 'nf1-end_time', 'nf1-status'],
                    sortable: ['nf1-name', 'l1-name', 's1-name', 'nf1-start_time', 'nf1-end_time', 'nf1-status'],
                    isFooterComponent: true,
                    headings: {
                        'nf1-name': 'Name',
                        'l1-name': 'Location',
                        's1-name': 'Segment',
                        'nf1-start_time': 'Start Date',
                        'nf1-end_time': 'End Date',
                        'nf1-status': 'Status',
                        Action: 'Action'
                    },
                    /*columnsClasses: {
                        'nf1-name': 'col-sm-2',
                        'l1-name': 'col-sm-2',
                        's1-name': 'col-sm-2',
                        'nf1-start_time': 'col-sm-2',
                        'nf1-end_time': 'col-sm-2',
                        'nf1-is_active': 'col-sm-2',
                        Action: 'col-sm-2'
                    },*/
                    payLoad: {
                        "resource": "newsfeeds",
                        "action": "get"
                    },
                    searchProp: '',
                    newsFeedFilterProp: {},
                },
                newsFeedObj: {
                    search: '',
                    newsFeedFilter: []
                },
            };
        },
        mounted() {
            this.getNewsFeedSideFilters();
        },
        methods: {
            getNewsFeedSideFilters() {
                const payLoad = {
                    resource: "newsfeed/filters",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {
                    if (response.data.meta.code == 200) {
                        this.newsFeedObj.newsFeedFilter = response.data.data;
                    }
                }).catch((error) => {

                });
            },
            newsFeedAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            newsFeedSideFilter(obj) {
                this.dataTable.newsFeedFilterProp = obj;
            },
            removeActive(obj) {
                this.dataTable.newsFeedFilterProp = {};
            },
            searching() {
                this.dataTable.searchProp = this.newsFeedObj.search;
            },
            newsFeedOperation(id, status) {

                this.$swal({
                    title: "Are you sure ?  You want to " + status + " this newsfeed !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {
                    if (result.value) {
                        const payLoad = {
                            resource: "newsfeeds",
                            action: "update",
                            id,
                            data: {
                                step: "confirm",
                                status
                            }
                        };

                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payLoad,
                            this.authHeaders,
                            true,
                        ).then((response) => {
                            if (response.data.meta.code === 200) {
                                $(".table_drop").hide();
                                this.dataTable.reloadProp = !this.dataTable.reloadProp;
                            }
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

    .inner_table_info {
        width: 145% !important;
    }

    .inner_table_info ul li span {
        float: left;
    }
</style>

<style scoped>
    .newsfeed_arrow_opener i.active {
        margin-top: 13px;
    }
</style>