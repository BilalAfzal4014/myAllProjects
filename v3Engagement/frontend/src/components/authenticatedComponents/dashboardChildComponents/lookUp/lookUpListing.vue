<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Lookups</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search"
                           type="search"
                           v-model="lookupobj.search"
                           v-on:change="lookupSearch">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li><a v-on:click="addlookup">Add Lookup</a></li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="add_lookup" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">{{lookup}} Lookup</h4>
                            </div>
                            <div class="modal-body">
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Name:</h4>
                                        <input type="text" v-model="lookupParamObj.name"
                                               v-on:keyup="codeRegularExpression">
                                        <input id="lookupid" type="hidden" v-model="lookupParamObj.id">
                                        <p class="error" v-if="errors.name">{{ errors.name }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Code:</h4>
                                        <input disabled type="text" v-model="lookupParamObj.code">
                                        <p class="error" v-if="errors.code">{{ errors.code }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Parent</h4>
                                        <select v-model="lookupParamObj.parent_id">
                                            <option value="">Please select a parent</option>
                                            <option value="1">Action Triggers</option>
                                            <option value="45">Conversion Types</option>
                                        </select>
                                        <p class="error" v-if="errors.parentId">{{ errors.parentId }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Description:</h4>
                                        <textarea v-model="lookupParamObj.description"></textarea>
                                        <p class="error" v-if="errors.description">{{ errors.description }}</p>
                                        <input type="submit" v-on:click="submitLookup" v-bind:value="lookup"
                                               style="margin-top:10px;" class="right_btn">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="side_widget">
                            <div style="display: none" v-for="filter in lookupobj.lookupFilter">
                                <strong class="title colored">{{filter.columns}}:</strong>
                                <ul class="list_none side_tags">
                                    <li v-for="(child, index) in filter.children">
                                        <a class="sideBar-filter" style="cursor: pointer" v-bind:id="index"
                                           v-on:click="fn_lookupFilter(child, index)">{{child.parentAlias}}
                                            <i aria-hidden="true" class="fa fa-times"
                                               v-on:click.stop="removeActive(index)"></i>

                                        </a>

                                    </li>
                                </ul>
                            </div>
                            <div>
                                <strong class="title colored">Filter:</strong>
                                <ul class="list_none side_tags" v-for="filter in lookupobj.lookupFilter">
                                    <li>
                                        <a href="#" class="opener">Status <i class="fas fa-sort-down"></i></a>
                                        <ul class="list_none inner_tags">
                                            <!--<li><a href="#">Action Triggers</a></li>-->
                                            <!--<li><a href="#">Conversion Types</a></li>-->
                                            <li v-for="(child, index) in filter.children">
                                                <a class="sideBar-filter" style="cursor: pointer" v-bind:id="index"
                                                   v-on:click="fn_lookupFilter(child, index)">{{child.parentAlias}}
                                                    <i aria-hidden="true" class="fa fa-times"
                                                       v-on:click.stop="removeActive(index)"></i>

                                                </a>

                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
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
                                     v-bind:tableSideBarSearch="dataTable.lookupProp"
                                     v-bind:tableSortable="dataTable.sortable">
                                <template slot="listing" slot-scope="data">
                                    <i class="fas fa-cog" v-on:click="lookupAction(data.actionlisting)"></i>
                                    <i class="fas fa-sort-down"></i>
                                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id">
                                        <li>
                                            <a v-on:click="editlookup(row)"><i
                                                    class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a v-on:click="deletLookup(data.actionlisting.row.id)"><i
                                                    class="fas fa-trash"></i> Delete </a>
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
        name: "LookupListing",
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['code', 'name', 'parent', 'created_at', 'Action'],
                    mapColumns: ['id', 'code', 'name', 'parent', 'created_at'],
                    filterable: ['code', 'name', 'parent', 'created_at'],
                    sortable: ['code', 'name', 'parent', 'created_at'],
                    headings: {
                        code: 'Code',
                        name: 'Name',
                        parent: 'Parent Code',
                        created_at: 'Created At',
                        Action: 'Action'

                    },
                    columnsClasses: {
                        code: 'colspan_2',
                        // name: 'width_100',
                        // parent: 'col-sm-2',
                        created_at: 'colspan_2',
                        // Action: 'width_60'
                    },
                    payLoad: {
                        resource: "lookup",
                        action: "get",
                    },
                    searchProp: '',
                    lookupProp: {},

                },
                lookupobj: {
                    search: '',
                    lookupFilter: []
                },
                lookupParamObj: {
                    lookupid: '0',
                    name: '',
                    code: '',
                    parent_id: '',
                    description: ''

                },
                errors: {
                    name: '',
                    code: '',
                    parentId: '',
                    description: ''
                },
                lookup: 'Add'
            }

        },
        mounted() {
            this.fetchLookupFilters();
        },
        methods: {
            lookupSearch() {
                console.log('search', this.lookupobj.search);
                this.dataTable.searchProp = this.lookupobj.search;
            },
            lookupAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + ")").hide();
                $("#" + rowObj.row.id).slideToggle('100');
            },
            removeActive(id) {
                console.log('remove', id);
                $("#" + id).removeClass("active");
                this.dataTable.lookupProp = {};
            },
            editlookup(data) {
                this.errors.code = '';
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "lookup/edit",
                        action: "get",
                        id: editId
                    },
                    this.authHeaders,
                    true
                ).then((response) => {
                    console.log('editResponse', response);
                    this.lookupParamObj.lookupid = response.data.data.id;
                    this.lookupParamObj.name = response.data.data.name;
                    this.lookupParamObj.code = response.data.data.code;
                    this.lookupParamObj.parent_id = response.data.data.parent_id;
                    this.lookupParamObj.description = response.data.data.description;
                    this.lookup = 'Edit';
                    $("#add_lookup").modal("show");
                }).catch((error) => {
                    console.log('error', error);
                })

            },
            fn_lookupFilter(row, id) {
                // console.log('row', row);
                // console.log('id', id);
                // console.log('called');
                // $(".sideBar-filter").removeClass("active");
                // $("#" + id).addClass("active");
                this.dataTable.lookupProp = row;
            },
            fetchLookupFilters() {
                var filterArray = [];
                //alert('call');
                const payLoad = {
                    resource: "lookup",
                    method: "filters",
                    action: "get",
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                    true
                ).then((response) => {
                    console.log('code', response.data.data);
                    if (response.data.meta.code == "200") {
                        for (var val = 0; val < response.data.data.length; val++) {
                            this.lookupobj.lookupFilter.push({
                                'columns': response.data.data[val]['columnsAlias'],
                                'children': response.data.data[val]['childern']
                            });
                        }
                    } else {

                    }
                    console.log('lookupobj', this.lookupobj);
                }).catch((error) => {
                    console.log('error', error);

                });

            },
            codeRegularExpression() {
                var regex = /^[A-Za-z_ ][A-Za-z\d_ ]*$/;
                console.log('name', this.lookupParamObj.name);
                var name = this.lookupParamObj.name;
                var lookupid = this.lookupParamObj.lookupid;
                if (lookupid == 0) {
                    if (regex.test(name)) {
                        this.errors.name = '';
                        var underScoreString = name.split(' ').join('_');
                        console.log('val', underScoreString.toUpperCase());
                        this.lookupParamObj.code = underScoreString.toUpperCase()
                    } else {
                        this.lookupParamObj.code = '';
                        this.errors.name = 'Please Enter Only Alphanumeric';
                        //console.log('notmatchec');
                        return false;
                    }
                }


            },
            submitLookup() {
                var payload = {};
                var name = this.lookupParamObj.name;
                var lookupid = this.lookupParamObj.lookupid;
                var code = this.lookupParamObj.code;
                var parentId = this.lookupParamObj.parent_id;
                var description = this.lookupParamObj.description;
                if (name == '') {
                    this.errors.name = 'Please Enter Name';
                }
                if (parentId == '') {
                    this.errors.parentId = 'Please select parent';

                }
                if (description == '') {
                    this.errors.description = 'Please add Description';
                }
                if (name != "" && parentId != "" && description != "" && lookupid == 0) {
                    payload = {
                        resource: "lookup",
                        action: "create",
                        data: this.lookupParamObj
                    };
                    this.saveLookup(payload);
                } else if (name != "" && parentId != "" && description != "" && lookupid != 0) {
                    payload = {
                        resource: "lookup",
                        action: "update",
                        id: lookupid,
                        data: this.lookupParamObj
                    };
                    this.saveLookup(payload);
                }
            },
            saveLookup(payload) {
                console.log('payload', payload);
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders,
                    true
                ).then((response) => {
                    console.log('saveRes', response);
                    $('#add_lookup').modal('hide');
                    if (response.status == 200) {
                        if (lookupid == 0) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Lookup has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Lookup has been updated',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                    this.dataTable.reloadProp = !this.dataTable.reloadProp;
                }).catch((error) => {
                    console.log('error', error);
                    // if (error.response.status == 422) {
                    //this.errors.code = error.response.data.error.error;
                    // }
                });
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
                                resource: "lookup",
                                action: "delete",
                                id: id
                            },
                            this.authHeaders,
                            true
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
            addlookup() {
                this.lookupParamObj.name = '';
                this.lookupParamObj.lookupid = '0';
                this.lookupParamObj.code = '';
                this.errors.code = '';
                this.errors.name = '';
                this.lookupParamObj.parent_id = '';
                this.lookupParamObj.description = '';
                this.lookup = 'Add';
                $("#add_lookup").modal("show");
            }
        },
        watch: {
            // 'lookupParamObj.name': function (newVal, oldVal){
            //     console.log('newVal',newVal);
            //     console.log('oldVal',oldVal);
            //     //to work with changes in someOtherProp
            // },
            lookupParamObj: {
                handler(lookupObj) {
                    console.log('lookupParamObj', lookupObj);
                    if(lookupObj.name!="")
                    {
                        this.errors.name='';
                    }
                    if(lookupObj.code!="")
                    {
                        this.errors.code='';
                    }
                    if(lookupObj.description!="")
                    {
                        this.errors.description='';
                    }
                    if(lookupObj.parent_id!="")
                    {
                        this.errors.parentId='';
                    }
                    // do stuff
                },
                deep: true
            }
        }
    }
</script>

<style>

</style>