<template>
    <div id="attribute_listing" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Attribute Listing</h4>
                </div>
                <div class="modal-body" style="max-height: 500px;">
                    <div style="max-height: 450px; padding-right: 0px;" class="table_holder default_scroll">
                        <table id="example" class="display dataTable no-footer" role="grid">
                            <thead>
                            <tr role="row" class="overflow_tr">
                                <th>Attribute Code</th>
                                <th>
                                    Attribute
                                    Name
                                </th>
                                <th style="width: 150px">
                                    Operation(s)
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="attr in attributeListing" v-if="attr.attribute_type.toLocaleLowerCase() == 'user'">
                                <td>{{attr.code}}</td>
                                <td>{{attr.name}}</td>
                                <td>
                                    <button style="background: #2a8689;" class="btn btn-info"
                                            v-on:click="passCode(attr.code)">select
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "attribute_listing",
        props: ['openModal', 'closeModal'],
        data() {
            return {
                attributeListing: [],
            };
        },
        mounted() {
            this.getAttributeListing();
        },
        methods: {
            getAttributeListing() {
                const payLoad = {
                    resource: "company/presets/attribute/listing",
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.attributeListing = response.data.data;

                }).catch((error) => {

                })
            },
            passCode(code) {
                this.$emit("getCode", code);
            }
        },
        watch: {
            openModal(currVal, oldVal) {
                $("#attribute_listing").modal("show");
            },
            closeModal(currVal, oldVal) {
                $("#attribute_listing").modal("hide");
            }
        }
    }


</script>

<style scoped>
    .table_holder table td {
        border-left: 1px solid #eaeaea !important;
        text-align: center;
    }

</style>