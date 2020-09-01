<template>
    <div v-bind:id="componentName">
        <v-server-table :columns="datatableObj.columns"
                        :options="datatableObj.options"
                        @loaded="onLoaded"
                        ref="segmentListingTable">
            <template slot="Action" slot-scope="props">
                <slot name="listing" v-bind:actionlisting="props"></slot>
            </template>
            <template slot="email" slot-scope="props">
                 <span class="pull-left" v-if="componentName == 'campaignTracking'">
                    <slot name="email-slot" v-bind:nameColumn="props"></slot>
                </span>
                <span v-if="componentName != 'campaignTracking'">
                     {{props.row.email}}
                </span>
            </template>
            <template slot="image" slot-scope="props">
                <slot name="image" v-bind:imagelisting="props"></slot>
            </template>
            <template slot="logo" slot-scope="props">
                <slot name="logo" v-bind:imagelisting="props"></slot>
            </template>
            <template slot="image_url" slot-scope="props">
                <slot name="image_url" v-bind:imagelisting="props"></slot>
            </template>
            <template slot="user_id" slot-scope="props">
                <slot name="user_id" v-bind:userList="props"></slot>
            </template>
            <template slot="name" slot-scope="props">
                <span class="pull-left" v-if="componentName == 'campaignListing'">
                    <slot name="campaignName" v-bind:nameColumn="props"></slot>
                </span>
                <em class="txt">
                    {{props.row.name}}
                </em>
            </template>
            <template slot="dimension" slot-scope="props">
                <slot name="dimension" v-bind:galleryList="props"></slot>
            </template>
            <template slot="nf1-name" slot-scope="props">
                <slot name="newsFeedName" v-bind:rowObj="props"></slot>
            </template>
            <template slot="status" slot-scope="data">
                <slot name="status-slot" v-bind:data="data" v-if="componentName == 'campaignTracking'"></slot>
                <span v-if="componentName != 'campaignTracking'">
                    {{data.row.status}}
                </span>
            </template>
            <template slot="flag" slot-scope="props">
                {{props.row.flag}}
                <slot name="flag-slot"
                      v-bind:data="props.row.reason"
                      v-if="componentName == 'importListing' && props.row.flag.toLowerCase() == 'failed'"></slot>
            </template>
            <template slot="message" slot-scope="props">
                <slot name="message" v-bind:data="props"></slot>
            </template>
            <template slot="variant" slot-scope="props">
                <slot name="variant" v-bind:data="props"></slot>
            </template>
        </v-server-table>
    </div>
</template>
<script>
    import Vue from 'vue';
    import datatable, {Event} from 'vue-tables-2';

    Vue.use(datatable.ServerTable);

    export default {
        name: "data-table",
        props: ['tableColumn', 'tableFilterable', 'tableSortable',
            'tableHeadings', 'tableColumnsClasses', 'apiPayLoad',
            'tableMapColumns', 'tableSearch', 'tableSideBarSearch', 'tableReloading', 'componentName', 'customFiltersProp', 'footerComponent', 'tableActionType'],
        data() {
            return {
                table: {
                    selectedRows: 0,
                    totalRows: 0
                },
                datatableObj: {
                    reloadProp: false,
                    columns: this.tableColumn, //props
                    options: {
                        initialPage: 1,
                        perPage: 20,
                        perPageValues: [25],
                        filterByColumn: false,
                        footerHeadings: false,
                        requestKeys: {
                            filter: 'filter',
                            filterBy: 'filterBy',
                            query: 'query',
                            limit: 'limit',
                            orderBy: 'orderBy',
                            ascending: 'ascending',
                            page: 'page',
                            byColumn: 'byColumn',

                        },
                        customFilters: this.customFiltersProp != undefined ? this.customFiltersProp : ['query', 'sideFilters'],
                        pagination: {chunk: 10, dropdown: false},
                        headings: this.tableHeadings, // props
                        filterable: this.tableFilterable, //props
                        sortable: this.tableSortable, //props
                        skin: 'table-striped table-bordered table-hover',
                        sortIcon: {
                            base: 'glyphicon',
                            up: 'glyphicon-chevron-up',
                            down: 'glyphicon-chevron-down',
                            is: 'glyphicon-sort'
                        },
                        orderBy: {ascending: true},
                        columnsClasses: this.tableColumnsClasses, //props
                        requestFunction: (data) => {// data for network call : props
                            this.authHeaders.headers.Authorization = this.getToken();
                            data.columns = this.tableFilterable;
                            this.apiPayLoad.data = data;
                            return this.api("post",
                                this.constants.getUrl("backendApiUrl"),
                                this.apiPayLoad,
                                this.authHeaders,
                                true,
                            ).catch(function (e) {
                            }.bind(this));
                        },
                        responseAdapter: (response) => { //props
                            var finalResponse = [];
                            if (response.data.meta.code === 200) {

                                var arr = response.data.data;
                                for (var i = 0; i < arr.length; i++) {
                                    var obj = {};
                                    for (var j = 0; j < this.tableMapColumns.length; j++) {
                                        obj[this.tableMapColumns[j]] = arr[i][this.tableMapColumns[j]];
                                    }
                                    finalResponse.push(obj);
                                }
                            }


                            this.table.selectedRows = finalResponse.length;
                            this.table.totalRows = response.data.meta.total;

                            return {
                                data: finalResponse,
                                count: response.data.meta.total
                            }

                        },
                        rowClassCallback: (row) => {
                            if (this.componentName == "campaignListing") {
                                if (row.status == 'active') {
                                    return 'green';
                                } else if (row.status == 'draft') {
                                    return 'blue';
                                } else if (row.status == 'expired') {
                                    return 'black';
                                } else if (row.status == 'suspended') {
                                    return 'red';
                                }
                            } else if (this.componentName == "newsFeedListing") {
                                if (row['nf1-status'] == 'active') {
                                    return 'green';
                                } else if (row['nf1-status'] == 'draft') {
                                    return 'blue';
                                } else if (row['nf1-status'] == 'suspend') {
                                    return 'red';
                                }
                            } else if (this.componentName == "galleryListing") {
                                if (row.is_active == 'Active') {
                                    return 'green';
                                } else {
                                    return 'blue';
                                }
                            }
                        }
                    }
                }
            }
        },
        methods: {
            onLoaded() {
                let setTimeOut = false;
                if (this.footerComponent) {
                    if (this.table.selectedRows == this.table.totalRows) {
                        $("#footer").text("Showing " + (this.table.totalRows > 0 ? '1' : '0') + " to " + this.table.totalRows + " of " + this.table.totalRows + " records");
                    } else {
                        $("#footer").text($("#" + this.componentName + " .VuePagination__count").text());
                    }
                    $(".VuePagination__count").css({display: "none"});
                    setTimeOut = true;
                } else {
                    if (this.table.selectedRows == this.table.totalRows) {
                        $(".VuePagination__count").css({display: "block"});
                        $("#" + this.componentName + " .VuePagination__count").text("Showing " + (this.table.totalRows > 0 ? '1' : '0') + " to " + this.table.totalRows + " of " + this.table.totalRows + " records");
                    }
                }

                if (setTimeOut) {
                    setTimeout(() => {
                        $(".VuePagination__count").css({display: "none"});
                    }, 50);
                }

                setTimeOut = false;
                $('[data-toggle="popover"]').popover();
            }
        },
        watch: {
            tableSearch(currVal, OldVal) {
                console.log('search', currVal);
                Event.$emit('vue-tables.filter::' + this.datatableObj.options.customFilters[0], currVal);
            },
            tableSideBarSearch: {
                handler(currVal, OldVal) {
                    Event.$emit('vue-tables.filter::' + this.datatableObj.options.customFilters[1], currVal);
                },
                deep: true,
            },
            tableReloading(currVal, OldVal) {
                this.$refs.segmentListingTable.refresh();
            },
            tableActionType(currVal, OldVal) {
                Event.$emit('vue-tables.filter::' + this.datatableObj.options.customFilters[1], currVal);
            }
        },

    }
</script>

<style scoped>
    td .txt {
        overflow: hidden;
        font-style: normal;
    }
</style>