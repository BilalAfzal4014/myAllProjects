<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content lists_page">
                <header class="sec_header">
                    <div class="align_left">
                        <strong>Data Import</strong>
                    </div>
                    <input type="search" id="table_search" placeholder="Search here...">
                    <div class="align_right">
                        <strong class="actions_droper">Actions <i class="fas fa-sort-down"></i></strong>
                        <ul class="actions_drop list_none">
                            <li>
                                <router-link to="/dashboard/attribute/import-attribute-data">
                                    Upload
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </header>
                <div class="list_holder">
                    <div class="align_left mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                        <div class="side_widget">
                            <strong class="title colored">Filter(s):</strong>
                            <ul class="list_none side_tags">
                                <li>
                                    <a class="opener anchor_pointer">Status <i class="fas fa-sort-down"></i></a>
                                    <ul style="width: 147px" class="list_none side_tags inner_tags indicators">
                                        <li style="width: 100%" v-for="(status, index) in statuses"
                                            v-bind:class="dataTable.searchProp.status == status ? 'active': ''">
                                            <a style="cursor: pointer" class="sideBar-filter"
                                               v-bind:id="'active'+index"
                                               v-on:click="importsFilter(status, index)">{{status}}
                                                <i v-if="dataTable.searchProp.status == status"
                                                   class="fa fa-times" aria-hidden="true"
                                                   v-on:click.stop="removeActive(index)"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table_holder mCustomScrollbar _mCS_2 mCS_no_scrollbar">
                        <div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"
                             style="max-height: none;">
                            <div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y"
                                 style="position:relative; top:0; left:0;" dir="ltr">
                                <div id="example_wrapper" class="dataTables_wrapper no-footer">
                                    <listing v-bind:apiPayLoad="dataTable.payLoad"
                                             v-bind:tableColumn="dataTable.column"
                                             v-bind:tableColumnsClasses="dataTable.columnsClasses"
                                             v-bind:tableFilterable="dataTable.filterable"
                                             v-bind:tableHeadings="dataTable.headings"
                                             v-bind:tableMapColumns="dataTable.mapColumns"
                                             v-bind:tableReloading="dataTable.reloadProp"
                                             v-bind:tableSearch="dataTable.searchProp"
                                             v-bind:tableSideBarSearch="dataTable.importsProp"
                                             v-bind:tableSortable="dataTable.sortable"
                                             v-bind:componentName="dataTable.componentName"
                                             v-bind:footerComponent="dataTable.isFooterComponent">
                                        <template slot="listing" slot-scope="data">
                                            <i class="fas fa-cog"
                                               v-on:click.stop="importAction(data.actionlisting)"></i>
                                            <i class="fas fa-sort-down"
                                               v-on:click.stop="importAction(data.actionlisting)"></i>
                                            <ul class="table_drop list_none"
                                                v-bind:id="data.actionlisting.row.id+'_action'">
                                                <li v-if="data.actionlisting.row.status !== 'Inprogress'">
                                                    <a v-on:click="importFile(data.actionlisting.row.id)"><i
                                                            class="fas fa-file-export"></i> Import File
                                                    </a>
                                                </li>
                                                <li>
                                                    <a v-on:click="downloadFile(data.actionlisting.row)"><i
                                                            class="fas fa-download"></i> Download
                                                    </a>
                                                </li>
                                                <li v-if="data.actionlisting.row.status !== 'Inprogress'">
                                                    <a v-on:click="deleteImportFile(data.actionlisting.row.id)"><i
                                                            class="fas fa-trash-alt"></i> Delete </a>
                                                </li>
                                            </ul>
                                        </template>
                                        <template slot="image" slot-scope="data">
                                            <img v-bind:src="data.imagelisting.row.image"/>
                                        </template>
                                        <template slot="flag-slot" slot-scope="data">


                                            <div class="inner_table_info" style="width: 150%; padding: 5px 5px;">
                                                <ul class="list_none">
                                                    <li>
                                                        <span>{{data.data}}</span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </template>
                                    </listing>
                                </div>
                            </div>
                            <div id="mCSB_2_scrollbar_vertical"
                                 class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical"
                                 style="display: none;">
                                <div class="mCSB_draggerContainer">
                                    <div id="mCSB_2_dragger_vertical" class="mCSB_dragger"
                                         style="position: absolute; min-height: 130px; height: 0px; top: 0px; display: block; max-height: 72px;">
                                        <div class="mCSB_dragger_bar" style="line-height: 130px;"></div>
                                    </div>
                                    <div class="mCSB_draggerRail"></div>
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
    import listing from '../../../otherComponents/datatable/datatableComponent';

    export default {
        components: {
            listing,
        },
        data() {
            return {
                dataTable: {
                    reloadProp: false,
                    column: ['id', 'file_name', 'file_size', 'created_at', 'flag', 'remaining_files', 'process_date', 'Action'],
                    mapColumns: ['id', 'file_name', 'file_size', 'created_at', 'flag', 'reason', 'remaining_files', 'process_date', 'file_path'],
                    filterable: ['id', 'file_name', 'file_size', 'created_at', 'flag', 'remaining_files', 'process_date'],
                    sortable: ['id', 'file_name', 'file_size', 'created_at', 'flag', 'remaining_files', 'process_date'],
                    isFooterComponent: true,
                    componentName: "importListing",
                    headings: {
                        id: 'ID',
                        file_name: 'Name',
                        file_size: 'Size',
                        created_at: 'Upload Date',
                        flag: 'Status',
                        remaining_files: 'Remaining Files',
                        process_date: 'Process Date',
                        Action: 'Action'

                    },
                    // columnsClasses: {
                    //     id: 'width_20',
                    //     code: 'width_60',
                    //     image: 'width_60',
                    //     name: 'width_100',
                    //     created_at: 'width_60',
                    //     Action: 'width_60'
                    // },
                    payLoad: {
                        resource: "imports",
                        action: "get",
                    },
                    searchProp: '',
                    importsProp: {},

                },
                importsObj: {
                    search: '',
                    importsFilter: []
                },
                languageParamObj: {
                    languageid: '0',
                    name: '',
                    code: '',
                    image: '',
                    dir: ''

                },
                errors: {
                    name: '',
                    code: '',
                    image: '',
                    dir: ''
                },
                statuses: ['Pending', 'Inprogress', 'Failed', 'Complete']
            }
        },
        created() {

        },
        mounted() {

        },
        methods: {
            searching111() {
                this.dataTable.searchProp = this.importsObj.search;
            },
            importAction(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive(id) {
                $("#active" + id).removeClass("active");
                this.dataTable.searchProp = ''
            },
            importsFilter(status, index) {
                $(".sideBar-filter").removeClass("active");
                $("#active" + index).addClass("active");
                this.dataTable.searchProp = {status: status}
            },
            importFile(id) {
                this.$swal({
                    title: "Import File",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Import it!"
                }).then((result) => {
                    if (result.value) {
                        var payload = {
                            resource: "import/import-file",
                            action: "post",
                            data: {id: id}
                        };
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payload,
                            this.authHeaders,
                            true
                        ).then((response) => {
                            let data = response.data;
                            if (data.meta.code == 200) {
                                this.$swal('Success', data.success[0], 'success');
                            }
                            if (data.meta.code == 404) {
                                this.$swal('Failed', data.error[0], 'error');
                            }
                            this.dataTable.searchProp = {status: ''}
                        }).catch((error) => {
                        });
                    }
                });
            },
            downloadFile(row) {
                console.log(row)
                let filePath = row.file_path

                window.location.href = filePath
            },
            deleteImportFile(id) {
                this.$swal({
                    title: "Delete?",
                    text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => {
                    if (result.value) {
                        var payload = {
                            resource: "import/delete-file",
                            action: "post",
                            data: {id: id}
                        };
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            payload,
                            this.authHeaders
                        ).then((response) => {
                            if (response.data.error) {
                                this.$swal('Failed', response.data.error[0], 'error');
                                return
                            }
                            this.$swal('Success', 'Import File deleted successfully.', 'success');
                            this.dataTable.reloadProp = true
                        }).catch((error) => {
                            console.log(error.response.data)
                            this.$swal('Failed', error.response.data, 'error');
                        });
                    }
                });
            }
        }
    }
</script>

<style>

    span.VueTables__heading {
        width: 80px !important;
        display: inline-block !important;
        word-break: break-word !important;
    }

    th.VueTables__sortable {
        vertical-align: top !important;
    }

</style>