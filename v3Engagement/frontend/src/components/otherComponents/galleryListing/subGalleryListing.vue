<template>
    <div>
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
                 v-bind:tableSideBarSearch="dataTable.filterProp"
                 v-bind:tableSortable="dataTable.sortable">
            <template slot="listing" slot-scope="data">
                <div v-if="!cropAction">
                    <i class="fas fa-cog" v-on:click.stop="action(data.actionlisting)"></i>
                    <i class="fas fa-sort-down" v-on:click.stop="action(data.actionlisting)"></i>
                    <ul class="table_drop list_none" v-bind:id="data.actionlisting.row.id+'_action'">
                        <li>
                            <a v-on:click="editGallery(data.actionlisting.row.image_url)"><i
                                    class="fas fa-edit"></i> View
                            </a>
                        </li>
                        <li>
                            <a v-on:click="deleteGalleryListing(data.actionlisting.row.id)"><i
                                    class="fas fa-trash"></i> Delete </a>
                        </li>
                    </ul>
                </div>
                <div class="pop_actions" v-else>
                    <a class="anchor_pointer selectImage galleryModalActionBtn"
                       v-on:click="passUrl(data.actionlisting.row.image_url)"><i
                            aria-hidden="true" class="fa fa-check"></i></a>
                    <a class="anchor_pointer cropImage galleryModalActionBtn"
                       v-on:click="cropImage(data.actionlisting.row.image_url)"><i
                            aria-hidden="true" class="fa fa-crop"></i></a>
                </div>
            </template>
            <template slot="image_url" slot-scope="data">
                <img v-bind:src="data.imagelisting.row.image_url"/>
            </template>
            <template slot="dimension" slot-scope="data">
                <b>Width : {{data.galleryList.row.image_width}}</b><br/>
                <b>Height : {{data.galleryList.row.image_height}}</b>
            </template>
        </listing>
    </div>
</template>

<script>
    import listing from '../../../components/otherComponents/datatable/datatableComponent';

    export default {
        name: "subGalleryListing",
        components: {
            listing
        },
        props: [
            'column',
            'mapColumns',
            'filterable',
            'sortable',
            'headings',
            'columnsClasses',
            'payLoad',
            'searchProp',
            'reloadProp',
            'cropAction',
            'filterProp',
            'isThisFooterComponent',
            'componentName'
        ],
        data() {
            return {
                dataTable: {
                    reloadProp: this.reloadProp,
                    componentName: this.componentName,
                    column: this.column,
                    mapColumns: this.mapColumns,
                    filterable: this.filterable,
                    sortable: this.sortable,
                    headings: this.headings,
                    columnsClasses: this.columnsClasses,
                    payLoad: this.payLoad,
                    searchProp: this.searchProp,
                    filterProp: this.filterProp,
                    isFooterComponent: this.isThisFooterComponent,
                },
            }
        },
        methods: {
            action(rowObj) {
                $(".table_drop:not(#" + rowObj.row.id + "_action)").hide();
                $("#" + rowObj.row.id + "_action").slideToggle('100');
            },
            removeActive(id) {
                $("#" + id).removeClass("active");
            },
            editGallery(path) {
                this.$emit("imageSrc", path);
                $("#add_gallery").modal("show");

            },
            Filter() {
                this.dataTable.lookupProp = [];
            },
            deleteGalleryListing(id) {
                this.$swal({
                    title: "Are you sure? You want to delete this !",
                    // text: "Are you sure? You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => {
                    if (result.value) {
                        this.api("post",
                            this.constants.getUrl("backendApiUrl"),
                            {
                                resource: "gallery/" + id,
                                action: "delete",
                                data: {
                                    "id": id
                                }
                            },
                            this.authHeaders
                        ).then((response) => {
                            this.$swal('Deleted', 'You successfully deleted this file', 'success');
                            this.dataTable.reloadProp = !this.dataTable.reloadProp;
                        }).catch((error) => {

                        });
                    }
                });
            },
            passUrl(url) {
                this.$emit("getUrlForImage", url);
            },
            cropImage(url) {
                this.$emit("getUrlForCrop", url);
            }
        },
        watch: {
            reloadProp(currVal, OldVal) {
                if (currVal == true || currVal == false) {
                    this.dataTable.reloadProp = !this.dataTable.reloadProp;
                }
            },
            searchProp(currVal, OldVal) {
                this.dataTable.searchProp = currVal;
            },
            filterProp(currVal, OldVal) {
                this.dataTable.filterProp = currVal;
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

    .pop_actions a {
        color: grey;
        margin: 0 2px;
    }

</style>