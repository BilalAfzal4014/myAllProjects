<template>
    <div id="usertab4" v-bind:class="activetab === 'usertab4' ? 'tab active' : 'tab'">
        <div class="accordion" id="accordionExample">
            <div v-for="(row, index) in this.dataProp.attributeList">
                <div class="card">
                    <div>
                        <div class="card-header" id="headingOne">
                            <h5>
                                <button class="btn btn-link"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseOne"
                                        aria-expanded="true"
                                        aria-controls="collapseOne" v-on:click="showToggle(index + row['device_type'])" >
                                    {{row['device_type']}}
                                    <span class="toggle_indicator"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                </button>
                            </h5>
                        </div>

                        <div v-bind:id="index + row['device_type']" class="collapse collapsed toggle_content" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="state_cols">
                                    <div class="state_column fluid">
                                        <strong class="title"><i class="fas fa-user-tie"></i> Device List</strong>
                                        <div class="text">
                                            <div class="table_holder default_scroll">
                                                <table class="display" id="example2">
                                                    <thead>
                                                    <tr>
                                                        <th class="1 small-th">Row Name</th>
                                                        <th class="auto">Values</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(value, key) in row">
                                                    <td>{{key}}</td>
                                                    <td>{{value}}</td>

                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
    export default {
        name: "usertab4",
        props: ['dataProp', 'activetab'],
        mounted() {
            //alert('tab4');
            //console.log('prop',this.dataProp.attributeList);
        },
        data() {
            return{
                activeToggle:false
            }
        },
        methods: {
            showToggle(deviceType)
            {
                $('#'+deviceType).toggle("show");
               // console.log('deviceType',deviceType);

            }
        }
    }

</script>

<style scoped>

</style>