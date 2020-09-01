<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="white_boxed_content">
                <header class="sec_header">
                    <h1>Cache Viewer/Cache Commands
                        <div class="on_off_div">
                            <div>
                                <strong>Select Company</strong>
                                <select class="selectEle" v-on:change="getGroups($event)" v-model="objComapny">
                                    <option value="">Select Company</option>
                                    <option v-for="company in companies" :value="company">{{company.name}}</option>
                                </select>
                                <select class="selectEle" id="app_groups" style="display: none;" v-model="group"
                                        v-on:change="getCommands(group.id)">
                                    <option value="">Select Group</option>
                                    <option v-for="group in app_groups" :value="group">{{group.name}}</option>
                                </select>
                            </div>
                        </div>
                    </h1>
                </header>
                <div class="list_holder inner_padding full_scroll">
                    <div id="tab-content" style="color: black;">
                        <div class="tab active" id="general_tab">
                            <label>Cache Commands</label>
                            <div v-for="(command, index) in commands">
                                <span class="prefix">GET <span :id="'command'+ index" class="tag_holder"
                                                               contenteditable="true"
                                                               v-html="commandComputed(command)"></span>:</span>
                                <i style="margin-left:20px; cursor:pointer;" v-on:click="getCommand(index)"
                                   class="fa fa-forward" aria-hidden="true"></i>
                            </div>
                            <div class="comp_input_sec p_t_b" style="padding: 10px 20px 10px 0px;">
                                <textarea placeholder="Message" id="cache_data" name="cache_data" class="b_r"
                                          style="height: 200px; width: 100%;"></textarea>
                                <span id="notificationError" style="color: #F99;"></span>
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
        name: "cacheViewer",
        data() {
            return {
                companies: [],
                objComapny: "",
                app_groups: {},
                group: "",
                commands: []
            }
        },
        mounted() {
            this.fetchCompanies();
            this.getCommands('ID')
        },
        methods: {
            fetchCompanies() {
                var payload = {
                    resource: "companies",
                    action: "get",
                    data: {}
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    this.companies = response.data
                }).catch((error) => {
                    console.log(error.response.data)
                });
            },
            getCommand(index) {
                var command = $("#command" + index).text();

                var payload = {
                    resource: "command/data",
                    action: "get",
                    data: {command: command}
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    console.log(response.data)
                    $("#cache_data").val(response.data)
                }).catch((error) => {
                    console.log(error.response.data)
                });
            },
            getGroups(e) {
                var company = this.objComapny
                this.app_groups = company.app_groups
                $("#app_groups").show()
            },
            getCommands(id) {
                var payload = {
                    resource: "commands",
                    action: "get",
                    data: {group_id: id}
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    this.commands = response.data
                }).catch((error) => {
                    console.log(error.response.data)
                });
            },
            commandComputed(command) {
                command = command.replace(new RegExp("ID", 'g'), "<mark>ID</mark>")

                return command.replace(this.group.id, "<mark>" + this.group.id + "</mark>")
            }
        }
    }
</script>

<style>
    #footer {
        display: none;
    }

    .on_off_div {
        display: table;
    }

    .on_off_div div {
        display: table-cell;
    }

    .on_off_div div select {
        vertical-align: middle;
        font-size: 85% !important;
        padding-right: 30px !important;
    }
</style>