<template>
    <div id="usertab1" v-bind:class="activetab === 'usertab1' ? 'tab active' : 'tab'">
        <div class="state_cols add">
            <div class="state_column">
                <strong class="title"><i class="fas fa-user"></i> Campaign Conversion Push</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Event ID</th>
                                <th>Event Value</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.campaign">
                            <tr v-for="value in dataProp.campaign" v-if="value.campaign_type=='push'">
                                <td><router-link v-bind:to="'/dashboard/campaign-stats/' +value.campaign_id">{{value.campaign_name}}</router-link></td>
                                <td>{{value.event_id}}</td>
                                <td>{{value.event_value}}</td>
                                <td>{{value.date}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="state_column">
                <strong class="title"><i class="far fa-chart-bar"></i> Campaign Coversion Inapp</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Event ID</th>
                                <th>Event Value</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.campaign">
                            <tr v-for="value in dataProp.campaign" v-if="value.campaign_type=='inapp'">
                                <td><router-link v-bind:to="'/dashboard/campaign-stats/' +value.campaign_id">{{value.campaign_name}}</router-link></td>
                                <td>{{value.event_id}}</td>
                                <td>{{value.event_value}}</td>
                                <td>{{value.date}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="state_column">
                <strong class="title"><i class="far fa-chart-bar"></i> Campaign User Action</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Value</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.userAction">
                            <tr v-for="value in dataProp.userAction">
                                <td><router-link v-bind:to="'/dashboard/campaign-stats/' +value.campaign_id">{{value.campaign_name}}</router-link></td>
                                <td>{{value.event_id}}</td>
                                <td>{{value.event_value}}</td>
                                <td>{{value.date}}</td>
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
        name: "userTabOne",
        props: ['dataProp', 'activetab'],
        mounted() {
            console.log('dataProp', this.dataProp);
        }
    }
</script>

<style scoped>

</style>