<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Attribute Data</strong>
                    </div>
                    <input id="table_search" name="table_search" placeholder="Search here..."
                           type="search"
                           v-model="lookupobj.search"
                           v-on:change="attributeSearch">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li><a v-on:click="addAttribute">Create Attribute</a></li>
                        </ul>
                    </div>
                </header>
                <div class="modal fade" id="add_attribute" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">{{mode}} Attribute</h4>
                            </div>
                            <div class="modal-body">
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Name:</h4>
                                        <input type="text" v-if="attributeParamObj.attributeid==0"
                                               v-model="attributeParamObj.name"
                                               v-on:keyup="attributeCode()"/>
                                        <input type="text" v-else v-model="attributeParamObj.name"/>
                                        <input id="lookupid" type="hidden" v-model="attributeParamObj.id">
                                        <p class="error" v-if="errors.name">{{ errors.name }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Code:</h4>
                                        <input disabled type="text" v-model="attributeParamObj.code">
                                        <p class="error" v-if="errors.code">{{ errors.code }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Data Type:</h4>
                                        <select class="add" v-model="selectedDataType">
                                            <option value="">Select Data Type</option>
                                            <option v-bind:value="type.name" v-for="type in dataTypes">{{type.name}}
                                            </option>
                                        </select>
                                        <p class="error" v-if="errors.data_type">{{ errors.data_type }}</p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">Length:</h4>
                                        <input
                                                type="text"
                                                v-bind:disabled="!(selectedDataType.toLowerCase() == 'varchar')"
                                                v-model="attributeParamObj.length"
                                        >
                                        <p class="error" v-if="errors.length">{{ errors.length }}</p>
                                    </div>
                                </div>
                                <div class="modal_row">
                                    <div class="modal_col">
                                        <h4 class="modal-title">Attribute Type:</h4>
                                        <select v-model="attributeParamObj.attribute_type">
                                            <option v-bind:value="'user'">USER</option>
                                            <option v-bind:value="'action'">ACTION</option>
                                            <option v-bind:value="'conversion'">CONVERSION</option>
                                        </select>
                                        <p class="error"></p>
                                    </div>
                                    <div class="modal_col">
                                        <h4 class="modal-title">&nbsp;</h4>
                                        <input class="right_btn" type="submit" v-on:click="submitAttribute"
                                               value="Submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="add_value" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title">Add Value(s)</h4>
                            </div>
                            <div class="modal-body">
                                <div class="table_holder default_scroll"
                                     style="max-height: calc(100vh - 223px); height: auto;">
                                    <table class="auto_tds" style="table-layout: auto;">
                                        <thead>
                                        <tr class="overflow_tr" role="row">
                                            <th style="text-align:center;">
                                                Value
                                            </th>
                                            <th>
                                                Operation(s)
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-bind:style="'background:' + (value.id == attributeValueObj.id ? '#e2e0e0': 'white')"
                                            v-for="value in attributeValues">
                                            <td>
                                                {{value.value}}
                                            </td>
                                            <td><a class="gall_btn"
                                                   v-on:click="editValue(value)"><i aria-hidden="true"
                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                <a class="gall_btn"
                                                   v-on:click="deleteValue(value)">
                                                    <i aria-hidden="true" class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr v-if="attributeValues.length == 0">
                                            <td colspan="2">No record Found</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="input_row attribute_value">
                                    <input type="text" v-model="attributeValueObj.value"/>
                                    <button v-bind:disabled="attributeValueObj.spinner"
                                            v-on:click="doActionForValue(attributeValueObj)">
                                        <i class="fa fa-refresh fa-spin"
                                           v-bind:style="attributeValueObj.spinner ? 'display: inline-block': 'display: none'">
                                        </i> Add/Update
                                    </button>
                                    <button style="width: 13%"
                                            v-on:click="resetValue">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1">
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags indicators">
                                <li style="padding: 2px 0;" v-for="section in attributeObj.attributeFilter">
                                    <a class="opener anchor_pointer attribute_arrow_opener">{{section.label}}<i
                                            class="fas fa-sort-down"></i></a>
                                    <ul class="list_none side_tags inner_tags" style="margin-top: 8px;  width: 150px">
                                        <li style="width: 100%"
                                            v-bind:class="(dataTable.lookupProp.column == section.column && dataTable.lookupProp.value == child) ? 'active': ''"
                                            v-for="child in section.values"
                                            v-on:click="attributeFilter(section.column, child)">
                                            <a class="anchor_pointer sideBar-filter" style="cursor: pointer"
                                               v-bind:id="section.column+child">{{child.charAt(0).toUpperCase() +
                                                child.slice(1)}}
                                                <i aria-hidden="true" class="fa fa-times"
                                                   style="margin-right: 4px; margin-top: 3px;"
                                                   v-if="(dataTable.lookupProp.column == section.column && dataTable.lookupProp.value == child) ? 'active': ''"
                                                   v-on:click.stop="removeActive"></i>
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
                                            <a v-on:click="editAttribute(data.actionlisting.row.id)"><i
                                                    class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li v-if="data.actionlisting.row.attribute_type != 'user'">
                                            <a v-on:click="addValueForAttribute(data.actionlisting.row)"><i
                                                    class="fas fa-plus"></i> Add Value(s)
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
        name: "attributeListing",
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['code', 'name', 'data_type', 'length', 'level_type', 'attribute_type', 'created_at', 'Action'],
                    mapColumns: ['id', 'code', 'name', 'data_type', 'length', 'level_type', 'attribute_type', 'created_at'],
                    filterable: ['code', 'name', 'data_type', 'length', 'level_type', 'created_at'],
                    sortable: ['code', 'name', 'data_type', 'length', 'level_type', 'created_at'],
                    isFooterComponent: true,
                    componentName: "attributeListing",
                    headings: {
                        code: 'Code',
                        name: 'Name',
                        data_type: 'Data Type',
                        length: 'Length',
                        level_type: 'Type',
                        attribute_type: 'Attribute Type',
                        created_at: 'Created At',
                        Action: 'Action'

                    },
                    columnsClasses: {
                        // id: 'attribute_listing_first_column',
                    },
                    payLoad: {
                        resource: "attribute",
                        action: "get",
                    },
                    searchProp: '',
                    lookupProp: {},

                },
                lookupobj: {
                    search: '',
                    lookupFilter: []
                },
                attributeObj: {
                    attributeFilter: []
                },
                attributeParamObj: {
                    attributeid: '0',
                    code: '',
                    name: '',
                    data_type: '',
                    length: '',
                    attribute_type: 'user',
                    level_type: ''
                },
                errors: {
                    name: '',
                    code: '',
                    data_type: '',
                    length: ''
                },
                dataTypes: [
                    {
                        id: "INT",
                        name: "INT"
                    },
                    {
                        id: "VARCHAR",
                        name: "VARCHAR"
                    },
                    {
                        id: "DATE",
                        name: "DATE"
                    }
                ],
                selectedDataType: "",
                attributeValues: [],
                attributeValueObj: {
                    id: '',
                    value: '',
                    code: '',
                    type: '',
                    action: 'add',
                    spinner: false
                },
                mode: 'Add',
            }
        },
        mounted() {
            this.Filter();
            this.getFilter();
        },
        methods: {
            attributeSearch() {
                this.dataTable.searchProp = this.lookupobj.search;
            },
            segmentAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive() {
                $(".sideBar-filter").removeClass("active");

                this.dataTable.lookupProp = {}
            },
            attributeCode() {
                this.errors.name = "";
                var name = this.attributeParamObj.name;
                var regex = /^[A-Za-z_][A-Za-z\d_]*$/;
                if (regex.test(name)) {
                    var underScoreString = name.split(' ').join('_');
                    this.attributeParamObj.code = underScoreString.toLowerCase();
                } else {
                    this.attributeParamObj.code = '';
                    this.errors.name = "Please enter only alphanumeric";
                    return false;
                }
            },
            editAttribute(editId) {
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "attribute/current",
                        action: "get",
                        id: editId
                    },
                    this.authHeaders
                ).then((response) => {
                    this.attributeParamObj.attributeid = editId;
                    this.attributeParamObj.name = response.data.data.name;
                    this.attributeParamObj.code = response.data.data.code;
                    this.selectedDataType = response.data.data.data_type;
                    this.attributeParamObj.level_type = response.data.data.level_type;
                    this.attributeParamObj.length = response.data.data.length;
                    this.attributeParamObj.attribute_type = response.data.data.attribute_type;
                    this.mode = 'Edit';
                    $("#add_attribute").modal("show");
                }).catch((error) => {
                    console.log('error', error);
                })
            },
            Filter() {
                this.dataTable.lookupProp = [];
            },
            submitAttribute() {
                var error = true;
                var payload;
                var name = this.attributeParamObj.name;
                var code = this.attributeParamObj.code;
                var data_type = this.selectedDataType;
                this.attributeParamObj.data_type = this.selectedDataType;
                var length = this.attributeParamObj.length;
                var id = this.attributeParamObj.attributeid;
                if (name == '') {
                    this.errors.name = 'Please Enter Name';
                }
                if (code == '') {
                    this.errors.code = 'Please Enter Code';
                }
                if (data_type.length == "") {
                    this.errors.data_type = 'Please Select Data Type';
                } else if (data_type == 'VARCHAR') {
                    if (length == "") {
                        error = false;
                        this.errors.length = 'Please Enter Length';
                    }
                }
                if (name != "" && code != "" && data_type != "" && id == 0 && error) {
                    payload = {
                        resource: "attribute",
                        action: "create",
                        data: this.attributeParamObj
                    };
                    this.saveAttribute(payload, id);
                } else if (name != "" && code != "" && data_type != "" && id != 0 && error) {
                    payload = {
                        resource: "attribute",
                        action: "update",
                        id: id,
                        data: this.attributeParamObj
                    };
                    this.saveAttribute(payload, id);
                }

            },
            deletLookup(id) {
                this.$swal({
                    title: "Are you sure? You want to delete this !",
                    //text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => {
                    if (result.value) {
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "attribute",
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
            addAttribute() {
                this.attributeParamObj.attributeid = 0;
                this.attributeParamObj.name = '';
                this.attributeParamObj.code = '';
                this.attributeParamObj.length = '';
                this.selectedDataType = '';
                this.errors.name = '';
                this.errors.code = '';
                this.errors.length = '';
                this.errors.length = '';
                this.mode = 'Add';
                $("#add_attribute").modal("show");
            },
            saveAttribute(payload, id) {
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders,
                    true
                ).then((response) => {
                    console.log('resp', response);
                    $('#add_attribute').modal('hide');
                    if (response.data.meta.code == 200) {
                        if (id == 0) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Attribute has been saved, you can further add its value(s) by selecting add values(s) option in settings',
                                showConfirmButton: true,
                            })
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Attribute has been updated',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    } else {
                        this.$swal({
                            position: 'top-end',
                            type: 'error',
                            title: response.data.error[0],
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    this.dataTable.reloadProp = !this.dataTable.reloadProp;
                }).catch((error) => {
                });
            },
            addValueForAttribute(obj) {
                $("#add_value").modal("show");

                this.attributeValueObj = {
                    id: '',
                    value: '',
                    code: obj.code,
                    type: obj.attribute_type,
                    action: 'add'
                };

                this.showListingForValues(obj.code);

            },
            editValue(obj) {
                this.attributeValueObj.id = obj.id;
                this.attributeValueObj.value = obj.value;
                this.attributeValueObj.action = 'add';
            },
            resetValue() {
                this.attributeValueObj.id = '';
                this.attributeValueObj.value = '';
            },
            deleteValue(obj) {
                this.resetValue();
                this.$swal({
                    position: 'center',
                    type: 'info',
                    title: "Are you sure ?",
                    text: "You want to delete this Value",
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((willDelete) => {
                    if (willDelete.value != undefined && willDelete.value) {
                        obj.action = 'delete';
                        obj.code = this.attributeValueObj.code;
                        obj.type = this.attributeValueObj.type;
                        this.doActionForValue(obj);
                    }
                });
            },
            doActionForValue(obj) {

                const payLoad = {
                    resource: "attribute/value/take-action",
                    action: "post",
                    data: obj
                };

                this.attributeValueObj.spinner = true;
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {

                    this.$swal({
                        position: 'top-right',
                        type: 'success',
                        title: response.data.data.message,
                        timer: 3000,
                    });

                    this.attributeValueObj.spinner = false;
                    this.attributeValueObj.id = '';
                    this.attributeValueObj.value = '';
                    this.showListingForValues(this.attributeValueObj.code);
                }).catch((error) => {
                    this.attributeValueObj.spinner = false;
                });
            },
            showListingForValues(code) {
                const payLoad = {
                    resource: "attribute/value/" + code,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {
                    this.attributeValues = response.data.data;

                }).catch((error) => {
                });
            },
            getFilter() {
                this.attributeObj.attributeFilter = [
                    {
                        label: 'Type',
                        column: 'level_type',
                        values: ["platform", "custom"]
                    },
                    {
                        label: 'Attribute Type',
                        column: 'attribute_type',
                        values: ["conversion", "action", "user"]
                    },
                    {
                        label: 'Data Type',
                        column: 'data_type',
                        values: ["VARCHAR", "INT", "DATE"]
                    }
                ]
            },
            attributeFilter(column, value) {
                $(".sideBar-filter").removeClass("active");
                $("#" + column + value).addClass("active");
                this.dataTable.lookupProp = {column: column, value: value}
            }
        },
        watch: {
            dataTypes: {
                handler(currVal, oldVal) {
                    console.log('dataTypes', currVal);
                    this.selectedDataType = currVal[0];
                },
                deep: true
            },
            attributeParamObj: {
                handler(attributeParam, oldVal) {
                    console.log('attributeParam', attributeParam);
                    if (attributeParam.name != '') {
                        this.errors.name = '';
                    }
                    if (attributeParam.code != '') {
                        this.errors.code = '';
                    }
                    if (attributeParam.length != '') {
                        this.errors.length = '';
                    }
                },
                deep: true

            },
            selectedDataType: {
                handler(selectedDataType, oldVal) {
                    console.log('selectedDataType', selectedDataType);
                    if (selectedDataType != '') {
                        this.errors.data_type = '';
                    }
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
    .table_holder .auto_tds tr th:last-child {
        width: auto;
    }

    .table_holder table td {
        border-left: 1px solid #eaeaea !important;
        text-align: center !important;
    }

    /*.table_holder {*/
    /*overflow: hidden !important;*/
    /*height: 100vh !important;*/
    /*padding-right: 23px !important;*/
    /*padding-bottom: 50px !important;*/
    /*}*/

    .attribute_value {
        margin-top: 15px;
    }

    .attribute_value input {
        width: 63.7%;
        display: inline-block;
    }

    .attribute_value button {
        color: #fff;
        background: #2a8689;
        padding: 9px 0px;
        border: none;
        width: 21%;
    }

    .selected_value {
        float: left;
        display: inline-block;
        height: 15px;
        width: 10%;
    }

    .edit_attribute_val {
        background-color: #5cb85c !important;
        border-color: #4cae4c !important;
    }

</style>


<style scoped>

    .attribute_arrow_opener i.active {
        margin-top: 13px;
    }

    /*.indicators li {
    margin: 0px 0px 4px;
    overflow: hidden;
    padding: 1px 0px;
}*/
</style>
