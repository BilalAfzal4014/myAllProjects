<style>
    .fr_cap:checked + .fr_slider {
        background-color: #2a8689;
    }

    .view_cap_rules {
        display: inline-block;
        width: 59px;
        margin-left: 10px;
        text-decoration: none;
        color: #666;
    }

    .view_cap_rules img {
        display: inline;
        max-width: 32%;
    }
</style>

<div class="bcd_stp3 step-tab-panel" id="step3">
    <div class="select_btn_detail_outer">
        <div class="select_button  clearfix">
            <ul>
                <li>
                    <a href="#step_b" class="active">
                        <input type="radio" id="contactChoice2" name="contact" value="email" checked="">
                        <b for="contactChoice2">Schedule</b>

                    </a>
                </li>
                <li>
                    <a href="#step_a">
                        <input type="radio" id="contactChoice1" name="contact" value="notemail">
                        <b for="contactChoice1">Action-Based</b>
                    </a>

                </li>
                <li>
                    <a href="#step_c">
                        <input type="radio" id="contactChoice3" name="contact" value="email">
                        <b for="contactChoice2">API Trigger</b>

                    </a>
                </li>
            </ul>

        </div>

        <div class="sel_btn_det_outer">

            <div class=" sel_btn_det" id="step_a" style="display:none;">

                <div class="inp_and_sel_det_outer rad_det_step clearfix">
                    <label>Send This Campaign To Users Who </label>
                    <ul id="actionCollection">

                        <li id="actionMultiSelectLi">
                            <div class="inp_select b_r" style="width: 100%;">
                                <select id="actionMultiSelect">
                                </select>
                            </div>
                            <span id="actionError" style="color: #F99; float: left; width: auto !important;"></span>
                        </li>
                    </ul>
                </div>

                <div class="camp_title">
                    <h3> Action Delay </h3>
                </div>

                <div class="camp_Dur_detail">
                    <ul>
                        <li>
                            <div style="border-top: none; margin-top: -23px;" class="btm_priority_sec inp_select_sec">
                                <span>Select Message Delay</span>
                                <div style="margin-left: 20px" class="Campaigns_input b_r">
                                    <input id="actionTriggerInput" style="padding: 0px 5px;" type="number" min="0"
                                           max="999" name=""
                                           value=""
                                           placeholder="0">
                                </div>

                                <div class="Campaigns_type_sec inp_select  b_r">
                                    <select id="actionTriggerValue">
                                        <option value="Second">Second</option>
                                        <option value="Minute">Minute</option>
                                        <option value="Hour">Hour</option>
                                    </select>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class=" sel_btn_det" id="step_b" style="display: block">

                <div class="event_critaria_outer clearfix">
                    <span>Once trigger event criteria are met, send this campaign </span>
                    <div class="event_cri_selecttion clearfix">

                        <div class=" inp_select  b_r campaignRepitition">
                            <select>
                                <option value="ONCE"> Once</option>
                                <option value="DAILY"> Daily</option>
                                <option value="WEEEKLY"> Weekly</option>
                            </select>
                        </div>
                        <div style="position: static" class=" inp_select  b_r campaignAll">
                            <select multiple>
                                <option value="MONDAY"> MONDAY</option>
                                <option value="TUESDAY"> TUESDAY</option>
                                <option value="WEDNESDAY"> WEDNESDAY</option>
                                <option value="THURSDAY"> THURSDAY</option>
                                <option value="FRIDAY"> FRIDAY</option>
                                <option value="SATURDAY"> SATURDAY</option>
                                <option value="SUNDAY"> SUNDAY</option>
                            </select>
                        </div>
                        <span id="weekError" style="color: #F99;"></span>

                    </div>
                </div>
            </div>

            <div class=" sel_btn_det" id="step_c" style="display: none;">

                <div class="event_critaria_outer clearfix">
                    <div class="camp_title">
                        <h3>CAMPAIGN CODE</h3>
                    </div>
                    <div class="camp_Dur_detail">
                        <div class="trigger_widget">
                            <p>This is the unique key for this Campaign. Use it to identify which Campaign to send in a
                                request to the CAmpaign Trigger API.</p>
                            <input type="text" id="camp_code" value="968698ef-48651453ddf68-46asdd4f4-5d">
                            <button type="button" class="copy_btn" id="camp_code_copy"><i class="fa fa-clipboard"
                                                                                          aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="camp_title">
                        <h3>SENDING COMPAIGNS VIA CURL</h3>
                    </div>
                    <div class="camp_Dur_detail">
                        <div class="trigger_widget">
                            <p>Here is an example of cURL request. If you need more information, check out our <a
                                        href="#">documentation</a></p>
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
                                <code>&lt;?php</code></br>
                                <code>$curl = curl_init();</code></br>
                                <code>curl_setopt_array($curl, array(</code></br>
                                <code> CURLOPT_URL =>
                                    "/api/v1/create/campaign/api/trigger",</code>
                                <code> CURLOPT_RETURNTRANSFER => true,</code></br>
                                <code> CURLOPT_ENCODING => "",</code></br>
                                <code> CURLOPT_MAXREDIRS => 10,</code></br>
                                <code> CURLOPT_TIMEOUT => 30,</code></br>
                                <code> CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,</code></br>
                                <code> CURLOPT_CUSTOMREQUEST => "POST",</code></br>
                                <code> CURLOPT_POSTFIELDS => " {\n \"user_id\":\"704\",\n
                                    \"campaign_code\":\"a-1534402702\",\n \"extra_params\": {\n \t\"key1\":\"val1\",\n
                                    \t\"key2\":\"val2\"\n \t}\n }",</code></br>
                                <code> CURLOPT_HTTPHEADER => array(</code></br>
                                <code> "Authorization: your company auth token",</code></br>
                                <code> "Cache-Control: no-cache",</code></br>
                                <code> "Content-Type: application/json",</code></br>
                                <code> "app_id: your app id",</code></br>
                                <code> "app_name: your app name",</code></br>
                                <code> "build: your build",</code></br>
                                <code> "device_type: your device type",</code></br>
                                <code> "version: your version"</code></br>
                                <code> ),</code></br>
                                <code>));</code></br>
                                <code>$response = curl_exec($curl);</code></br>
                                <code>php&gt;</code></br>
                            </p>
                            <button type="button" class="copy_btn" id="camp_curl_copy"><i class="fa fa-clipboard"
                                                                                          aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div> {{--campaign duration html--}}
                <div class="camp_title">
                    <h3> Campaign Duration
                        <strong>Time Zone:
                            <mark>UTC</mark>
                        </strong>
                    </h3>

                </div>
                <div class="camp_Dur_detail">
                    <ul>
                        <li>
                            <div class="camp_Dur_timing clearfix">

                                <div class="camp_timing_check_box">
                                    <label for="start_tm"> Start Time (Required) </label>
                                </div>

                                <div class="camp_timing_inp_sec">

                                    <div class=" inp_dat_picker b_r">
                                        <label>
                                            <input type="date" id="startDate" name="bday">
                                        </label>
                                    </div>
                                    <span id="startDateError" style="color: #F99;"></span>
                                    <b>at</b>
                                    <div class=" inp_select  b_r shour">
                                        <select>
                                            <option value="00"> 00</option>
                                            <option value="01"> 01</option>
                                            <option value="02"> 02</option>
                                            <option value="03"> 03</option>
                                            <option value="04"> 04</option>
                                            <option value="05"> 05</option>
                                            <option value="06"> 06</option>
                                            <option value="07"> 07</option>
                                            <option value="08"> 08</option>
                                            <option value="09"> 09</option>
                                            <option value="10"> 10</option>
                                            <option value="11"> 11</option>
                                            <option value="12"> 12</option>
                                            <option value="13"> 13</option>
                                            <option value="14"> 14</option>
                                            <option value="15"> 15</option>
                                            <option value="16"> 16</option>
                                            <option value="17"> 17</option>
                                            <option value="18"> 18</option>
                                            <option value="19"> 19</option>
                                            <option value="20"> 20</option>
                                            <option value="21"> 21</option>
                                            <option value="22"> 22</option>
                                            <option value="23"> 23</option>
                                        </select>
                                    </div>
                                    <div class=" inp_select  b_r smint">
                                        <select>
                                            <option value="00"> 00</option>
                                            <option value="01"> 01</option>
                                            <option value="02"> 02</option>
                                            <option value="03"> 03</option>
                                            <option value="04"> 04</option>
                                            <option value="05"> 05</option>
                                            <option value="06"> 06</option>
                                            <option value="07"> 07</option>
                                            <option value="08"> 08</option>
                                            <option value="09"> 09</option>
                                            <option value="10"> 10</option>
                                            <option value="11"> 11</option>
                                            <option value="12"> 12</option>
                                            <option value="13"> 13</option>
                                            <option value="14"> 14</option>
                                            <option value="15"> 15</option>
                                            <option value="16"> 16</option>
                                            <option value="17"> 17</option>
                                            <option value="18"> 18</option>
                                            <option value="19"> 19</option>
                                            <option value="20"> 20</option>
                                            <option value="21"> 21</option>
                                            <option value="22"> 22</option>
                                            <option value="23"> 23</option>
                                            <option value="24"> 24</option>
                                            <option value="25"> 25</option>
                                            <option value="26"> 26</option>
                                            <option value="27"> 27</option>
                                            <option value="28"> 28</option>
                                            <option value="29"> 29</option>
                                            <option value="30"> 30</option>
                                            <option value="31"> 31</option>
                                            <option value="32"> 32</option>
                                            <option value="33"> 33</option>
                                            <option value="34"> 34</option>
                                            <option value="35"> 35</option>
                                            <option value="36"> 36</option>
                                            <option value="37"> 37</option>
                                            <option value="38"> 38</option>
                                            <option value="39"> 39</option>
                                            <option value="40"> 40</option>
                                            <option value="41"> 41</option>
                                            <option value="42"> 42</option>
                                            <option value="43"> 43</option>
                                            <option value="44"> 44</option>
                                            <option value="45"> 45</option>
                                            <option value="46"> 46</option>
                                            <option value="47"> 47</option>
                                            <option value="48"> 48</option>
                                            <option value="49"> 49</option>
                                            <option value="50"> 50</option>
                                            <option value="51"> 51</option>
                                            <option value="52"> 52</option>
                                            <option value="53"> 53</option>
                                            <option value="54"> 54</option>
                                            <option value="55"> 55</option>
                                            <option value="56"> 56</option>
                                            <option value="57"> 57</option>
                                            <option value="58"> 58</option>
                                            <option value="59"> 59</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li id="endTimeHide">
                            <div class="camp_Dur_timing clearfix">

                                <div class="camp_timing_check_box">
                                    <label for="end_tm"> End Time (Required) </label>
                                </div>

                                <div class="camp_timing_inp_sec">

                                    <div class=" inp_dat_picker b_r">
                                        <label>
                                            <input type="date" name="bday" id="endDate">
                                        </label>
                                    </div>
                                    <span id="endDateError" style="color: #F99;"></span>
                                    <b>at</b>
                                    <div class=" inp_select  b_r ehour">
                                        <select>
                                            <option value="00"> 00</option>
                                            <option value="01"> 01</option>
                                            <option value="02"> 02</option>
                                            <option value="03"> 03</option>
                                            <option value="04"> 04</option>
                                            <option value="05"> 05</option>
                                            <option value="06"> 06</option>
                                            <option value="07"> 07</option>
                                            <option value="08"> 08</option>
                                            <option value="09"> 09</option>
                                            <option value="10"> 10</option>
                                            <option value="11"> 11</option>
                                            <option value="12"> 12</option>
                                            <option value="13"> 13</option>
                                            <option value="14"> 14</option>
                                            <option value="15"> 15</option>
                                            <option value="16"> 16</option>
                                            <option value="17"> 17</option>
                                            <option value="18"> 18</option>
                                            <option value="19"> 19</option>
                                            <option value="20"> 20</option>
                                            <option value="21"> 21</option>
                                            <option value="22"> 22</option>
                                            <option value="23"> 23</option>
                                        </select>
                                    </div>
                                    <div class=" inp_select  b_r emint">
                                        <select>
                                            <option value="00"> 00</option>
                                            <option value="01"> 01</option>
                                            <option value="02"> 02</option>
                                            <option value="03"> 03</option>
                                            <option value="04"> 04</option>
                                            <option value="05"> 05</option>
                                            <option value="06"> 06</option>
                                            <option value="07"> 07</option>
                                            <option value="08"> 08</option>
                                            <option value="09"> 09</option>
                                            <option value="10"> 10</option>
                                            <option value="11"> 11</option>
                                            <option value="12"> 12</option>
                                            <option value="13"> 13</option>
                                            <option value="14"> 14</option>
                                            <option value="15"> 15</option>
                                            <option value="16"> 16</option>
                                            <option value="17"> 17</option>
                                            <option value="18"> 18</option>
                                            <option value="19"> 19</option>
                                            <option value="20"> 20</option>
                                            <option value="21"> 21</option>
                                            <option value="22"> 22</option>
                                            <option value="23"> 23</option>
                                            <option value="24"> 24</option>
                                            <option value="25"> 25</option>
                                            <option value="26"> 26</option>
                                            <option value="27"> 27</option>
                                            <option value="28"> 28</option>
                                            <option value="29"> 29</option>
                                            <option value="30"> 30</option>
                                            <option value="31"> 31</option>
                                            <option value="32"> 32</option>
                                            <option value="33"> 33</option>
                                            <option value="34"> 34</option>
                                            <option value="35"> 35</option>
                                            <option value="36"> 36</option>
                                            <option value="37"> 37</option>
                                            <option value="38"> 38</option>
                                            <option value="39"> 39</option>
                                            <option value="40"> 40</option>
                                            <option value="41"> 41</option>
                                            <option value="42"> 42</option>
                                            <option value="43"> 43</option>
                                            <option value="44"> 44</option>
                                            <option value="45"> 45</option>
                                            <option value="46"> 46</option>
                                            <option value="47"> 47</option>
                                            <option value="48"> 48</option>
                                            <option value="49"> 49</option>
                                            <option value="50"> 50</option>
                                            <option value="51"> 51</option>
                                            <option value="52"> 52</option>
                                            <option value="53"> 53</option>
                                            <option value="54"> 54</option>
                                            <option value="55"> 55</option>
                                            <option value="56"> 56</option>
                                            <option value="57"> 57</option>
                                            <option value="58"> 58</option>
                                            <option value="59"> 59</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p id="setScheduleTime"><strong>Summary:</strong> Send Campaign immediately after trigger
                                criteria are met, beginning at </p>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="delivery_control_html"> {{--delievery control html--}}
                <div class="camp_title">
                    <h3> Delivery Controls </h3>
                </div>
                <div class="camp_Dur_detail">
                    <ul id="hideLastThreeChildren">
                        <li>
                            <div class="camp_Dur_timing clearfix">

                                <div class="camp_timing_check_box">
                                    <input type="checkbox" id="rec_comp" name="contact" value="email" checked="">
                                    <label for="rec_comp"> Allow users to become re-eligible to receive
                                        campaign </label>
                                </div>

                            </div>
                        </li>
                        <li>
                            <div class="alert">
                                <p>
                                    Messaging Platform will send messages according to your message variant distribution
                                    each time users become re-legible. As a result, users may receive different
                                    message variants than they received on prior sends. In order to preserve the
                                    integrity of a historical control group if distributions change, you cannot use a
                                    control group with this option.
                                </p>
                            </div>


                        </li>
                        <li>
                            <p>
                                After user is messaged by this campaign, allow them to become re-eligible to receive the
                                Campaign again in
                            </p>

                        </li>
                        <li>
                            <div class="inp_select_sec inline_block">
                                <div class="Campaigns_input b_r">
                                    <input id="deliveryInput" style="padding: 0px 9px;" type="number" min="0" max="999"
                                           name=""
                                           value="1"
                                           placeholder="0">
                                </div>

                                <div class="Campaigns_type_sec inp_select  b_r">
                                    <select id="deliveryValue">
                                        <option value="Minute">Minute</option>
                                        <option value="Day">Day</option>
                                        <option value="Week">Week</option>
                                        <option value="Month">Month</option>
                                    </select>
                                </div>

                            </div>

                            <div style="border-top: none !important" id="priorityDelieveryType"
                                 class="btm_priority_sec inline_block clearfix">
                                <span>Select Message Priority</span>
                                <div class="rt_priority_selection clearfix">
                                    <div class="  inp_select  b_r">
                                        <select id="deliveryPriority">
                                            <option value="Low">Low</option>
                                            <option selected value="Medium">Medium</option>
                                            <option value="High">High</option>
                                        </select>
                                    </div>
                                    <button style="display: none" type="submit" name="button">Set Exact Priority
                                    </button>

                                </div>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="capping_control"> {{--capping control html--}}
                <div class="camp_title">
                    <h3> Frequency Capping </h3>
                </div>
                <div class="camp_Dur_detail">
                    <label class="switch">
                        <input class="fr_cap" type="checkbox">

                        <span class="slider round fr_slider"></span>
                    </label>
                    <span><strong style="color:#666">This campaign follows your Frequency Capping rules</strong></span>
                    <a target="_blank" class="view_cap_rules" href="{{url('/backend/campaign/capping-settings')}}"> <img
                                src="{{asset('/assets/images/view_icon.png')}}
                                        " alt="#"> View</a>
                </div>
            </div>
        </div>
    </div>

</div>
