<template>
    <div class="tab" id="delivery_tab">
        <ul class="inner_tabs list_none">
            <li v-bind:class="delivery.subStep == 'schedule'? 'active': ''"><a class="anchor_pointer"
                                                                               v-on:click="delivery.subStep = 'schedule'">Schedule</a>
            </li>
            <li v-bind:class="delivery.subStep == 'action'? 'active': ''"><a class="anchor_pointer"
                                                                             v-on:click="delivery.subStep = 'action'">Action-Based</a>
            </li>
            <li v-bind:class="delivery.subStep == 'api'? 'active': ''"><a class="anchor_pointer"
                                                                          v-on:click="delivery.subStep = 'api'">API
                Trigger</a>
            </li>
        </ul>
        <div class="inner_tab_content">
            <div v-show="delivery.subStep == 'schedule'" v-bind:class="delivery.subStep == 'schedule'? 'active': ''"
                 class="inner_tab_text" id="schedule">
                <header class="inner_tab_header">
                    <strong>Once trigger event criteria are met, send this campaign</strong>
                    <select v-model="delivery.selectedScheduleType" v-on:change="changeScheduleType">
                        <option v-for="type in deliveryUi.scheduleType" v-bind:value="type">{{type.label}}</option>
                    </select>
                    <select class="week_days" v-show="delivery.selectedScheduleType.value == 'weekly'" multiple
                            v-model="delivery.selectedDays">
                        <option v-for="day in deliveryUi.days" v-bind:value="day">{{day.label}}</option>
                    </select>
                    <span class="error">{{errors.selectedDays.message}}</span>
                </header>
            </div>
            <div v-show="delivery.subStep == 'action'" v-bind:class="delivery.subStep == 'action'? 'active': ''"
                 class="inner_tab_text" id="action-based">
                <header class="inner_tab_header">
                    <strong>Send This Campaign To Users Who</strong>
                    <select v-model="deliveryUi.selectedAction" v-on:change="addAction">
                        <option v-for="action in campaignAction" v-bind:value="action">{{action.name}}</option>
                    </select>
                    <p class="error">{{errors.action.message}}</p>
                </header>
                <div class="append_divs">
                    <div class="action_loop" v-for="(action, index) in deliveryUi.actions">
                        <div class="append_div">
                            <input disabled v-bind:value="action.name"/>
                            <select v-if="delivery.deliveryActions[index].value != '' && delivery.deliveryActions[index].value != null"
                                    v-model="delivery.deliveryActions[index].value">
                                <option v-for="value in action.values" v-bind:value="value">{{value}}</option>
                            </select>
                            <a v-on:click="popAction(index)" class="anchor_pointer row_remover"><i
                                    class="far fa-trash-alt"></i></a>
                        </div>
                        <span v-if="index != deliveryUi.actions.length - 1">AND</span>
                    </div>
                </div>
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <h2>Action Delay</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Select Message Delay</strong>
                            <input type="number" min="1" placeholder="0" v-model="delivery.actionDelay.value">
                            <select class="fix_width" v-model="delivery.actionDelay.unit">
                                <option v-for="delayValue in deliveryUi.actionDelayValue" v-bind:value="delayValue">
                                    {{delayValue.charAt(0).toUpperCase() + delayValue.slice(1)}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="delivery.subStep == 'api'" v-bind:class="delivery.subStep == 'api'? 'active': ''"
                 class="inner_tab_text" id="api-trigger">
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <h2>Campaign Code</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>This is the unique key for this Campaign. Use it to identify which Campaign to send
                                in a request to the Campaign Trigger API.</strong>
                            <div class="row camp_code_row">
                                <input type="text" id="campaignCode" disabled
                                       v-model="delivery.deliveryApiTrigger.code" class="camp_code">
                                <i class="anchor_pointer fas fa-paste copy_icon"
                                   v-on:click="copyToClipBoard('campaignCode')"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <h2>Sending Campaigns Via Curl</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Here is an example of cURL request. If you need more information, check out our <a
                                    class="anchor_pointer">documentation</a></strong>
                            <div class="row camp_code_row" style="position: relative">
                                <textarea id="camp_curl_textarea" style="display:none;">
                                &lt;?php
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                CURLOPT_URL => "/api/v1/create/campaign/api/trigger",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => " {\n \"user_id\":\"704\",\n \"campaign_code\":\"a-1534402702\",\n \"extra_params\": {\n \t\"key1\":\"val1\",\n \t\"key2\":\"val2\"\n \t}\n }",
                                CURLOPT_HTTPHEADER => array(
                                "Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjb21wYW55X2tleSI6IlpiZnZTeXlUWUgxc2xTUXJVclhlYld0b2dvNVNXdE1JOWliaml3NjVUNW00ZWxQTklNIiwidXNlcl90b2tlbiI6ImFhYTcwZDU0ZDQ0MmQyMzM4NTZiZTNkYzg3MDQxZjJiYzAzMjkwNmMyYjIxNzhlOWVkZTgyYzJmYTQ4YTFkMzAiLCJleHAiOjE1Mzk5NTE1Njh9.WKrs7GSVHrkcbP8I8b27QzeO2inSY75pJhjXVylvnXg",
                                "Cache-Control: no-cache",
                                "Content-Type: application/json",
                                "app_id: your app id",
                                "app_name: your app name",
                                "build: your build",
                                "device_type: your device type",
                                "version: your version"
                                ),
                                ));
                                $response = curl_exec($curl);
                                php&gt;
                            </textarea>
                                <p id="camp_curl">
                                    <code>&lt;?php</code><br>
                                    <code>$curl = curl_init();</code><br>
                                    <code>curl_setopt_array($curl, array(</code><br>
                                    <code> CURLOPT_URL =>
                                        "/api/v1/create/campaign/api/trigger",</code><br>
                                    <code> CURLOPT_RETURNTRANSFER => true,</code><br>
                                    <code> CURLOPT_ENCODING => "",</code><br>
                                    <code> CURLOPT_MAXREDIRS => 10,</code><br>
                                    <code> CURLOPT_TIMEOUT => 30,</code><br>
                                    <code> CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,</code><br>
                                    <code> CURLOPT_CUSTOMREQUEST => "POST",</code><br>
                                    <code> CURLOPT_POSTFIELDS => " {\n \"user_id\":\"704\",\n</code>><br>
                                    <code>\"campaign_code\":\"a-1534402702\",\n \"extra_params\": {\n</code><br>
                                    <code>\t\"key1\":\"val1\",\n</code><br>
                                    <code>\t\"key2\":\"val2\"\n \t}\n }",</code><br>
                                    <code> CURLOPT_HTTPHEADER => array(</code><br>
                                    <code> "Authorization: your company auth token",</code><br>
                                    <code> "Cache-Control: no-cache",</code><br>
                                    <code> "Content-Type: application/json",</code><br>
                                    <code> "app_id: your app id",</code><br>
                                    <code> "app_name: your app name",</code><br>
                                    <code> "build: your build",</code><br>
                                    <code> "device_type: your device type",</code><br>
                                    <code> "version: your version"</code><br>
                                    <code> ),</code><br>
                                    <code>));</code><br>
                                    <code>$response = curl_exec($curl);</code><br>
                                    <code>php&gt;</code><br>
                                </p>
                                <i style="margin-right: -23px;"
                                   class="curl_position anchor_pointer fas fa-paste copy_icon"
                                   v-on:click="copyToClipBoard('camp_curl_textarea')"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--common widgets-->
            <div class="inner_tab_text active" style="display: block !important;">
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <span>Time Zone: <em>UTC</em></span>
                        <h2>Campaign Duration</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Start Time (Required)</strong>
                            <input type="date" class="datepicker" v-model="delivery.dateTime.start.date"
                                   placeholder="mm/dd/yyyy">
                            <i>at</i>
                            <select v-model="delivery.dateTime.start.hours">
                                <option v-for="hour in deliveryUi.time.hours">{{ hour - 1 }}</option>
                            </select>
                            <select v-model="delivery.dateTime.start.mints">
                                <option v-for="mint in deliveryUi.time.mints">{{mint - 1}}</option>
                            </select>
                            <p class="error">{{errors.startDate.message}}</p>
                        </div>
                        <div class="row"
                             v-show="delivery.selectedScheduleType.value != 'once' || delivery.subStep != 'schedule'">
                            <strong>End Time (Required)</strong>
                            <input type="date" class="datepicker" placeholder="mm/dd/yyyy"
                                   v-model="delivery.dateTime.end.date" v-bind:min="delivery.dateTime.start.date">
                            <i>at</i>
                            <select v-model="delivery.dateTime.end.hours">
                                <option v-for="hour in deliveryUi.time.hours">{{ hour - 1 }}</option>
                            </select>
                            <select v-model="delivery.dateTime.end.mints">
                                <option v-for="mint in deliveryUi.time.mints">{{mint - 1}}</option>
                            </select>
                            <p class="error">{{errors.endDate.message}}</p>
                        </div>
                        <div class="row">
                            <p><b>Summary:</b> Send Campaign immediately after trigger criteria are met, beginning at
                                {{delivery.dateTime.start.hours}}:{{delivery.dateTime.start.mints}}UTC
                            </p>
                        </div>
                    </div>
                </div>
                <div class="inner_tab_widget"
                     v-show="delivery.selectedScheduleType.value != 'once' || delivery.subStep != 'schedule'">
                    <div class="heading_holder">
                        <h2>Delivery Controls</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <input v-model="delivery.deliveryEnable" type="checkbox" id="allow_users">
                            <label for="allow_users"><i class="fa fa-check-square" aria-hidden="true"></i> Allow users
                                to become re-eligible to
                                receive campaign</label>
                        </div>
                        <div v-show="delivery.deliveryEnable">
                            <div class="row">
                                <p class="notice_bar">Messaging Platform will send messages according to your message
                                    variant distribution each time users become re-legible. As a result, users may
                                    receive
                                    different message variants than they received on prior sends. In order to preserve
                                    the
                                    integrity of a historical control group if distributions change, you cannot use a
                                    control group with this option.</p>
                            </div>
                            <div class="row">
                                <input type="number" placeholder="1" min="1" v-model="delivery.deliveryControl.value">
                                <select class="fix_width" v-model="delivery.deliveryControl.selectedUnit">
                                    <option v-for="unit in deliveryUi.units" v-bind:value="unit">{{unit.label}}</option>
                                </select>
                                <p class="error">{{errors.deliveryControl.value.message}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <h2>Priority</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Select Message Priority</strong>
                            <select style="width: 12% !important;" v-model="delivery.deliveryControl.selectedPriority">
                                <option v-for="priority in deliveryUi.priorities" v-bind:value="priority">
                                    {{priority.label}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="inner_tab_widget" v-if="deliveryUi.enableCappingControl">
                    <div class="heading_holder">
                        <h2>Frequency Capping</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong class="fluid no_b_margin">
                                <div class="on_off_div no_float">

                                    <div class="an-onoffswitchContainer">
                                        <input type="checkbox" name="check1"
                                               class="abs_input an-onoffswitch-checkbox"
                                               id="an-onoffswitch0"
                                               v-bind:class="delivery.cappingValue ? 'active': ''"
                                               v-model="delivery.cappingValue"><!--an-switch-->
                                        <label class="an-onoffswitch-label" for="an-onoffswitch0">
                                            <span class="an-onoffswitch-inner"></span>
                                            <span class="an-onoffswitch-switch"></span>
                                        </label>
                                    </div>

                                    <strong>This campaign follows your Frequency Capping rules</strong>
                                    <router-link class="view_cap_rules" to="/dashboard/campaign/capping-settings"><i
                                            class="fa fa-eye" aria-hidden="true"></i> View
                                    </router-link>
                                </div>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="inner_tab_content sec_hidden">
            <div class="inner_tab_text active" id="schedule">
                <header class="inner_tab_header">
                    <strong>Once trigger event criteria are met, send this
                        campaign</strong>
                    <select>
                        <option>Weekly</option>
                        <option>Daily</option>
                        <option>Once</option>
                    </select>
                    <select>
                        <option>Select All</option>
                        <option>Daily</option>
                        <option>Once</option>
                    </select>
                </header>
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <span>Time Zone: <em>Abu Dhabi</em></span>
                        <h2>Campaign Duration</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Start Time (Required)</strong>
                            <input type="text" class="datepicker" placeholder="mm/dd/yyyy">
                            <i>at</i>
                            <select>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                            </select>
                            <select>
                                <option>00</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                            </select>
                            <select>
                                <option>pm</option>
                                <option>am</option>
                            </select>
                        </div>
                        <div class="row">
                            <strong>End Time (Required)</strong>
                            <input type="text" class="datepicker" placeholder="mm/dd/yyyy">
                            <i>at</i>
                            <select>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                            </select>
                            <select>
                                <option>00</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                            </select>
                            <select>
                                <option>pm</option>
                                <option>am</option>
                            </select>
                        </div>
                        <div class="row">
                            <input type="checkbox" id="send_campaign">
                            <label for="send_campaign"><i class="far fa-square"></i>
                                Send
                                campaign to users in their local time zone</label>
                        </div>
                        <div class="row">
                            <p><b>Summary:</b> Send Campaign immediately after trigger
                                criteria are met, beginning at</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_tab_text active" id="action-based">
                <header class="inner_tab_header">
                    <strong>Send This Campaign To Users Who</strong>
                    <select>
                        <option>Choose Actions...</option>
                        <option>App Launch</option>
                        <option>Help</option>
                        <option>Savings</option>
                        <option>Tutorials</option>
                    </select>
                </header>
                <div class="append_divs">
                    <div class="append_div">
                        <select>
                            <option>Choose A Custom Event</option>
                            <option>App Launch</option>
                            <option>Help</option>
                            <option>Savings</option>
                            <option>Tutorials</option>
                        </select>
                        <select>
                            <option>Choose A Custom Event</option>
                            <option>App Launch</option>
                            <option>Help</option>
                            <option>Savings</option>
                            <option>Tutorials</option>
                        </select>
                        <div class="right_check">
                            <input type="checkbox" id="filter_check">
                            <label for="filter_check">Add property filters</label>
                        </div>
                        <a class="row_remover"><i class="far fa-trash-alt"></i></a>

                    </div>
                    <div class="extra_checks"><span class="misc_check active">AND</span></div>
                    <div class="append_div">
                        <select>
                            <option>Choose A Custom Event</option>
                            <option>App Launch</option>
                            <option>Help</option>
                            <option>Savings</option>
                            <option>Tutorials</option>
                        </select>
                        <select>
                            <option>Choose A Custom Event</option>
                            <option>App Launch</option>
                            <option>Help</option>
                            <option>Savings</option>
                            <option>Tutorials</option>
                        </select>
                        <a class="row_remover"><i class="far fa-trash-alt"></i></a>
                    </div>
                    <div class="extra_checks"><span class="misc_check active">AND</span></div>
                    <div class="append_div">
                        <select>
                            <option>Choose A Custom Event</option>
                            <option>App Launch</option>
                            <option>Help</option>
                            <option>Savings</option>
                            <option>Tutorials</option>
                        </select>
                        <select>
                            <option>Choose Actions...</option>
                            <option>App Launch</option>
                            <option>Help</option>
                            <option>Savings</option>
                            <option>Tutorials</option>
                        </select>
                        <a class="row_remover"><i class="far fa-trash-alt"></i></a>
                    </div>
                    <div class="add_triger">
                        <a class="custom_btn">Add Triger</a>
                    </div>
                </div>
            </div>
            <div class="inner_tab_text" id="api-trigger">
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <h2>Campaign Code</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>This is the unique key for this Campaign. Use it to
                                identify which Campaign to send in a request to the
                                Campaign
                                Trigger API.</strong>
                            <div class="row camp_code_row">
                                <input type="text" placeholder="968698ef-48651453ddf68-46asdd4f4-5d" class="camp_code">
                                <i class="fas fa-paste copy_icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <h2>Sending Campaigns Via Curl</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Here is an example of cURL request. If you need more
                                information, check out our <a>documentation</a></strong>
                            <div class="row camp_code_row">
                                <textarea class="curl_key">Lorem ipsum sit amit Lorem ipsum sit amit Lorem ipsum sit amit Lorem ipsum sit amit Lorem ipsum sit amit Lorem ipsum sit amit.</textarea>
                                <i class="fas fa-paste copy_icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="inner_tab_widget sec_hidden">
            <div class="heading_holder">
                <h2>Delivery Controls</h2>
            </div>
            <div class="txt_holder">
                <div class="row">
                    <strong class="fluid no_b_margin">Manage the number of messages your
                        users will receive.</strong>
                </div>
                <div class="row">
                    <p class="notice_bar">Messaging Platform will send messages
                        according to
                        your message variant distribution each time users become
                        re-legible.
                        As a result, users may receive different message variants than
                        they
                        received on prior sends. In order to preserve the integrity of a
                        historical control group if distributions change, you cannot use
                        a
                        control group with this option.</p>
                </div>
                <div class="row">
                    <strong class="fluid">After user is messeged by this campaign, allow
                        them to become re-eligible to receive the Campaign again
                        in</strong>
                    <input type="number" min="1" class="inline_block" placeholder="3">
                    <select class="w_200">
                        <option>days</option>
                        <option>weeks</option>
                        <option>months</option>
                    </select>
                </div>
                <div class="row">
                    <input checked="" type="checkbox" id="allow_users">
                    <label for="allow_users"><i class="far fa-square"></i> Allow users
                        to
                        become re-eligible to receive campaign</label>
                </div>
                <div class="row">
                    <strong>Select Message Priority</strong>
                    <select class="medium add">
                        <option>Medium Priority</option>
                        <option>Normal Priority</option>
                        <option>High Priority (Shown before other messages)</option>
                    </select>
                    <a class="custom_btn">Set Exact Priority</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['campaignAction', 'hitApi', 'campaignId', 'getCampaignStep', 'getCode'],
        data() {
            return {
                locked: true,
                deliveryUi: {
                    enableCappingControl: true,
                    time: {
                        hours: 24,
                        mints: 60,
                    },
                    scheduleType: [
                        {
                            label: "Once",
                            value: "once"
                        },
                        {
                            label: "Daily",
                            value: "daily"
                        },
                        {
                            label: "Weekly",
                            value: "weekly"
                        },
                    ],
                    units: [
                        {
                            label: "Minute",
                            value: "minute"
                        },
                        {
                            label: "Day",
                            value: "day"
                        },
                        {
                            label: "Week",
                            value: "week"
                        },
                        {
                            label: "Month",
                            value: "month"
                        }
                    ],
                    priorities: [
                        {
                            label: "Low",
                            value: "low",
                        },
                        {
                            label: "Medium",
                            value: "medium",
                        },
                        {
                            label: "High",
                            value: "high",
                        },
                    ],
                    days: [
                        {
                            label: "Monday",
                            value: "monday"
                        },
                        {
                            label: "Tuesday",
                            value: "tuesday"
                        },
                        {
                            label: "Wednesday",
                            value: "wednesday"
                        },
                        {
                            label: "Thursday",
                            value: "thursday"
                        },
                        {
                            label: "Friday",
                            value: "friday"
                        },
                        {
                            label: "Saturday",
                            value: "saturday"
                        },
                        {
                            label: "Sunday",
                            value: "sunday"
                        },

                    ],
                    reInject: true,
                    dummyAction: {
                        id: -1,
                        code: "null",
                        name: "Choose Actions...",
                        values: []
                    },
                    selectedAction: {
                        id: -1,
                        code: "null",
                        name: "Choose Actions...",
                        values: []
                    },
                    actions: [],
                    actionDelayValue: [
                        "second",
                        "minute",
                        "hour"
                    ]
                },
                delivery: {
                    cappingValue: true,
                    step: "delivery",
                    subStep: 'schedule',
                    deliveryEnable: true,
                    selectedScheduleType: {
                        label: "Once",
                        value: "once"
                    },
                    selectedDays: [],
                    dateTime: {
                        start: {
                            date: "",
                            hours: 0,
                            mints: 0
                        },
                        end: {
                            date: "",
                            hours: 0,
                            mints: 0
                        }
                    },
                    deliveryControl: {
                        value: 1,
                        selectedUnit: {
                            label: "Day",
                            value: "day"
                        },
                        selectedPriority: {
                            label: "Low",
                            value: "low",
                        }
                    },
                    deliveryActions: [],
                    actionDelay: {
                        value: 1,
                        unit: "second"
                    },
                    deliveryApiTrigger: {
                        code: "",
                    },
                },
                errors: {
                    startDate: {
                        message: '',
                    },
                    endDate: {
                        message: '',
                    },
                    deliveryControl: {
                        value: {
                            message: ''
                        }
                    },
                    selectedDays: {
                        message: ''
                    },
                    action: {
                        message: ''
                    }

                }
            };
        },
        mounted() {
            this.bus.$on("displayCapController", (value) => {
                this.deliveryUi.enableCappingControl = value;
            });
            this.populateStartDateUtc();
        },
        methods: {
            populateStartDateUtc() {
                var isoDate = new Date();
                isoDate.setMinutes(isoDate.getMinutes() + 3);
                isoDate = isoDate.toISOString();

                this.delivery.dateTime.start.date = isoDate.split(":")[0].split("T")[0];
                this.delivery.dateTime.start.hours = parseInt(isoDate.split(":")[0].split("T")[1]);
                this.delivery.dateTime.start.mints = parseInt(isoDate.split(":")[1]);
            },
            changeScheduleType() {
                if (this.delivery.selectedScheduleType.value != 'weekly') {
                    this.delivery.selectedDays = [];
                }
            },
            submitStep() {
                this.removeDuplicateActions();

                this.locked = false;
                if (this.validate()) {

                    this.bus.$emit("disableNextBtn", true);

                    const payLoad = {
                        resource: "campaigns",
                        action: "update",
                        id: this.campaignId,
                        data: this.delivery
                    };

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                        true,
                    ).then((response) => {
                        this.bus.$emit("disableNextBtn", false);
                        if (response.data.meta.code === 200) {
                            this.updateStep6();
                            this.$emit("goNext");
                        }
                    }).catch((error) => {

                    });
                }
            },
            removeDuplicateActions() {
                for (let i = 0; i < this.delivery.deliveryActions.length; i++) {
                    for (let j = i + 1; j < this.delivery.deliveryActions.length; j++) {
                        if (JSON.stringify(this.delivery.deliveryActions[i]) == JSON.stringify(this.delivery.deliveryActions[j])) {
                            this.delivery.deliveryActions.splice(j, 1);
                            this.deliveryUi.actions.splice(j, 1);
                            j--;
                        }
                    }
                }
            },
            addAction() {
                if (this.deliveryUi.selectedAction.id != -1) {
                    this.deliveryUi.actions.push(JSON.parse(JSON.stringify(this.deliveryUi.selectedAction)));
                    let obj = {
                        id: this.deliveryUi.selectedAction.id,
                        value: this.deliveryUi.selectedAction.values.length > 0 ? this.deliveryUi.selectedAction.values[0] : '',
                    };
                    this.delivery.deliveryActions.push(obj);
                    this.deliveryUi.selectedAction = JSON.parse(JSON.stringify(this.deliveryUi.dummyAction));

                }
            },
            popAction(index) {
                this.deliveryUi.actions.splice(index, 1);
                this.delivery.deliveryActions.splice(index, 1);
            },
            copyToClipBoard(id) {
                $("#" + id).prop("disabled", false);
                $("#" + id).css({display: "inline-block"});
                let copyText = document.getElementById(id);
                copyText.select();
                document.execCommand("copy");
                if (id != 'campaignCode')
                    $("#" + id).css({display: "none"});
                $("#" + id).prop("disabled", true);
            },
            updateStep6() {
                let obj = {};
                if (this.delivery.deliveryEnable) {
                    obj.campaignRules = this.delivery.deliveryControl.value + ' ' + this.delivery.deliveryControl.selectedUnit.value + '(s)';
                } else {
                    obj.campaignRules = "NILL";
                }

                if (this.delivery.subStep == "action") {
                    obj.actionBased = this.delivery.actionDelay.value + ' ' + this.delivery.actionDelay.unit + '(s)';
                } else {
                    obj.actionBased = "NILL";
                }

                this.bus.$emit("step3", obj);
            },
            validate() {
                let success = [];
                let startDate = this.delivery.dateTime.start.date != '' ? this.delivery.dateTime.start.date + '-' + ('0' + this.delivery.dateTime.start.hours).slice(-2) + '-' + ('0' + this.delivery.dateTime.start.mints).slice(-2) : '';
                let endDate = this.delivery.dateTime.end.date != '' ? this.delivery.dateTime.end.date + '-' + ('0' + this.delivery.dateTime.end.hours).slice(-2) + '-' + ('0' + this.delivery.dateTime.end.mints).slice(-2) : '';

                let errors = {
                    startDate: {
                        message: '',
                    },
                    endDate: {
                        message: '',
                    },
                    deliveryControl: {
                        value: {
                            message: ''
                        }
                    },
                    selectedDays: {
                        message: ''
                    },
                    action: {
                        message: ''
                    }

                };
                this.errors = JSON.parse(JSON.stringify(errors));

                if (this.delivery.subStep == "schedule") {

                    success.push(this.validateStartDate(startDate));

                    if (this.delivery.selectedScheduleType.value != 'once') {

                        success.push(this.validateEndDate(endDate));
                        success.push(this.validateStartEndDate(startDate, endDate));

                        if (this.delivery.deliveryEnable) {
                            success.push(this.validateDeliveryControl());
                        }
                    }

                    if (this.delivery.selectedScheduleType.value == 'weekly') {
                        if (this.delivery.selectedDays.length == 0) {
                            success.push(false);
                            this.errors.selectedDays.message = 'Please select Days';
                        } else {
                            this.errors.selectedDays.message = '';
                        }
                    }


                } else {

                    if (this.delivery.subStep == "action") {
                        if (this.delivery.deliveryActions.length == 0) {
                            success.push(false);
                            this.errors.action.message = 'Please select actions';
                        } else {
                            this.errors.action.message = '';
                        }
                    }

                    success.push(this.validateStartDate(startDate));
                    success.push(this.validateEndDate(endDate));
                    success.push(this.validateStartEndDate(startDate, endDate));

                    if (this.delivery.deliveryEnable) {
                        success.push(this.validateDeliveryControl());
                    }

                }

                for (let i = 0; i < success.length; i++) {
                    if (!success[i]) {
                        return success[i];
                    }
                }

                return true;

            },
            validateStartDate(startDate) {
                let success = true;
                if (startDate == '') {
                    success = false;
                    this.errors.startDate.message = 'Start Date is required';
                } else {
                    this.errors.startDate.message = '';
                }
                return success;
            },
            validateEndDate(endDate) {
                let success = true;
                if (endDate == '') {
                    success = false;
                    this.errors.endDate.message = 'End Date is required';
                } else {
                    this.errors.endDate.message = '';
                }
                return success;
            },
            validateStartEndDate(startDate, endDate) {
                let success = true;
                if (endDate != '' && startDate != '') {
                    if (!(endDate > startDate)) {
                        success = false;
                        this.errors.startDate.message = 'Start Date should not be greater than End Date';
                    } else {
                        this.errors.startDate.message = '';
                    }
                }
                return success;
            },
            validateDeliveryControl() {
                let success = true;
                let expression = /^[1-9][0-9]*$/gi;

                if (!expression.test(this.delivery.deliveryControl.value)) {
                    success = false;
                    this.errors.deliveryControl.value.message = 'Unit value can only be a postive integer';
                } else {
                    this.errors.deliveryControl.value.message = '';
                }

                return success;
            }
        },
        watch: {
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 3) {
                        this.submitStep();
                    }
                },
                deep: true
            },
            getCampaignStep: {
                handler(currVal, oldVal) {
                    if (JSON.stringify(currVal) != "{}") {
                        this.delivery = JSON.parse(JSON.stringify(currVal));

                        for (var i = 0; i < this.delivery.deliveryActions.length; i++) {

                            for (var j = 0; j < this.campaignAction.length; j++) {

                                if (this.delivery.deliveryActions[i].id == this.campaignAction[j].id) {

                                    this.deliveryUi.actions.push(JSON.parse(JSON.stringify(this.campaignAction[j])));
                                    break;
                                }
                            }

                        }
                        this.updateStep6();
                    }

                },
                deep: true
            },
            campaignAction(currVal, oldVal) {
                if (this.deliveryUi.reInject) {
                    currVal.unshift(JSON.parse(JSON.stringify(this.deliveryUi.dummyAction)));
                    this.deliveryUi.reInject = false;
                } else {
                    this.deliveryUi.reInject = true;
                }
            },
            getCode(currVal, oldVal) {
                this.delivery.deliveryApiTrigger.code = currVal;
            },
            delivery: {
                handler(currVal, oldVal) {
                    if (!this.locked) {
                        this.validate();
                    }
                },
                deep: true
            }
        }
    }

</script>

<style scoped>

    .week_days {
        height: 76px !important;
        vertical-align: top !important;
        background: none !important;
    }

    .curl_position {
        position: absolute;
        top: -27px;
        right: 37%;
    }

    .on_off_div strong {
        display: inline-block;
        vertical-align: middle;
        font-size: 15px;
        margin-right: 10px;
        margin-bottom: 6px;
    }

    .on_off_div.no_float strong {
        margin: 0 10px 6px;
    }

    .on_off_div.no_float {
        padding: 3px 8px;
        float: none;
    }

    .inner_tab_widget .row input.abs_input {
        display: block !important;
        width: 42px !important;
        height: 20px !important;
        position: absolute;
        opacity: 0;
    }

    .action_loop {
        margin: 0 0 40px 0px;
    }
</style>