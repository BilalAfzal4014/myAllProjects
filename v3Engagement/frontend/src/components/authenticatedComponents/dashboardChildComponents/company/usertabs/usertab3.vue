<template>
    <div id="usertab3" v-bind:class="activetab === 'usertab3' ? 'tab active' : 'tab'">
        <div class="state_cols">
            <div class="state_column">
                <strong class="title"><i class="fas fa-user-tie"></i>Attributes</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display" id="example2">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp !== undefined">
                            <tr v-for="value in dataProp.activity"
                                v-if="value.attribute_type && value.attribute_type.toLowerCase() == 'conversion'">
                                <td>{{value.event_value}}</td>
                                <td>{{value.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="state_column">
                <strong class="title"><i class="fas fa-envelope-open"></i>User Action</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display" id="example3">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp !== undefined">
                            <tr v-for="value in dataProp.activity"
                                v-if="value.attribute_type && value.attribute_type.toLowerCase() == 'action'">
                                <td>{{value.code}}</td>
                                <td>{{value.event_value}}</td>
                                <td>{{value.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="state_cols">
            <div class="state_column">
                <strong class="title"><i class="fas fa-user-tie"></i>User Information</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th style="width: 50%">Name</th>
                                <th>Value</th>
                                <th style="width: 0px"></th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp !== undefined">
                            <tr v-for="(value, key) in dataProp.user">
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
</template>

<script>
    export default {
        name: "userTabThree",
        props: ['dataProp', 'activetab'],
        data() {
            return {
                'userListing': '',
            }
        },
        mounted() {
            //alert('tab3');
            //usertab3
        }
    }
</script>

<style scoped>

</style>