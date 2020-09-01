<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Location Listing</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search here..."
                           type="search"
                           v-model="lookupobj.search"
                           v-on:change="locationSearch">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <router-link to="/dashboard/location-create">Add Location</router-link>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <!--<div class="side_widget">-->
                        <!--<div>-->
                        <!--<strong class="title colored">Filter(s):</strong>-->
                        <!--<ul class="list_none side_tags indicators">-->
                        <!--<li v-bind:class="dataTable.lookupProp == label ? 'active': ''"-->
                        <!--v-for="(label, index) in actives">-->
                        <!--<a class="sideBar-filter" style="cursor: pointer" v-bind:id="'active'+index"-->
                        <!--v-on:click="locationFilter(label, index)">{{label}}-->
                        <!--<i aria-hidden="true" class="fa fa-times"-->
                        <!--v-if="dataTable.lookupProp == label"-->
                        <!--v-on:click.stop="removeActive(index)"></i>-->

                        <!--</a>-->
                        <!--</li>-->
                        <!--</ul>-->
                        <!--</div>-->
                        <!--</div>-->
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags">
                                <li>
                                    <a class="opener anchor_pointer">Status <i class="fas fa-sort-down"></i></a>
                                    <ul class="list_none inner_tags side_tags indicators" style="width: 150px">
                                        <li v-bind:class="dataTable.lookupProp == label ? 'active': ''"
                                            v-for="(label, index) in actives">
                                            <a class="sideBar-filter" style="cursor: pointer" v-bind:id="'active'+index"
                                               v-on:click="locationFilter(label, index)">{{label}}
                                                <i aria-hidden="true" class="fa fa-times"
                                                   v-if="dataTable.lookupProp == label"
                                                   v-on:click.stop="removeActive(index)"></i>

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
                                     v-bind:tableSideBarSearch="dataTable.lookupProp"
                                     v-bind:tableSortable="dataTable.sortable">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click.stop="segmentAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down" v-on:click.stop="segmentAction(data.actionlisting)"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id+'_action'">
                                        <li>
                                            <router-link
                                                    v-bind:to="'/dashboard/edit-location/' + data.actionlisting.row.id">
                                                <i
                                                        class="fas fa-edit"></i> Edit
                                            </router-link>
                                        </li>
                                        <li v-if="data.actionlisting.row.is_active==1">
                                            <a v-on:click="ChangeStatus(data.actionlisting.row, 0)"><i
                                                    class="fas fa-trash"></i>In Active </a>
                                        </li>
                                        <li v-else>
                                            <a v-on:click="ChangeStatus(data.actionlisting.row, 1)"><i
                                                    class="fas fa-trash"></i>Active </a>
                                        </li>
                                        <li>
                                            <a v-on:click="deleteLocation(data.actionlisting.row.id)"><i
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
                    column: ['id', 'name', 'description', 'status', 'created_at', 'Action'],
                    mapColumns: ['id', 'name', 'description', 'status', 'is_active', 'created_at'],
                    filterable: ['id', 'name', 'description', 'status', 'is_active', 'created_at'],
                    sortable: ['id', 'name', 'description', 'status', 'is_active', 'created_at'],
                    isFooterComponent: true,
                    componentName: "locationListing",
                    headings: {
                        id: 'Sr#',
                        name: 'Name',
                        description: 'Description',
                        status: 'Status',
                        created_at: 'Created At',
                        Action: 'Action'

                    },
                    columnsClasses: {},
                    payLoad: {
                        resource: "location",
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
                    image: ''

                },
                errors: {
                    name: '',
                    code: ''
                },
                actives: ["Active", "Inactive", "Popular Locations"]
            }
        },
        mounted() {
        },
        methods: {
            locationSearch() {
                console.log('call');
                this.dataTable.searchProp = this.lookupobj.search;
            },
            segmentAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive(id) {
                $("#active" + id).removeClass("active");
                this.dataTable.lookupProp = {}
            },
            Filter() {
                this.dataTable.lookupProp = [];
            },
            deleteLocation(id) {
                this.$swal({
                    title: "Are you sure? You want to delete this !",
                    // text: "Are you sure? You won't be able to revert this!",
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
                                resource: "location/delete",
                                action: "remove",
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
            ChangeStatus(row, status) {
                let activeStatus = 'DeActivate';
                var id = row.id;
                if (status) {
                    activeStatus = 'Activate'
                }

                this.$swal({
                    title: "Are you sure ? you want to " + activeStatus + " " + "Location!",
                    // text: "Are you sure???",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Do it!"
                }).then((result) => {

                    if (result.value != undefined && result.value) {
                        var locationId = id;
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "location",
                                action: "create",
                                data: {id: id, is_active: status}
                            },
                            this.authHeaders
                        ).then((response) => {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Location has been Updated',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $(".sideBar-filter").removeClass("active");
                            this.dataTable.lookupProp = {}
                        }).catch((error) => {
                            console.log('error', error);
                        });
                    }
                });
            },
            locationFilter(label, index) {
                $(".sideBar-filter").removeClass("active");
                $("#active" + index).addClass("active");
                this.dataTable.lookupProp = label
            }
        }
    }

</script>

<style>

</style>