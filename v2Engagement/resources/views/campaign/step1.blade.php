<div class="bcd_stp1 step-tab-panel active" id="step1">
    <div class="Campaigns_input b_r">
        <input maxlength="30" type="text" name="campaignTitle" id="campaignTitle" value="" placeholder="Campaign Title ">
    </div>
    <span id="campaignError" style="color: #F99; position: relative; top: -10px;"></span>

    <div class="Campaigns_type_sec inp_select  b_r">
        <select id="campaigns_type">
            <option value="1"> Email</option>
            <option value="2"> Push</option>
            <option value="3"> In App</option>
        </select>
    </div>

    <input type="text" value="" placeholder="Enter Tag(s)" name="tags" class="tags"/>

    <span id="tagsError" style="color: #F99"></span>

    <div class="email-2_stp1_outer">

        <div class="camp_title">
            <h3> Emails will be sent from this address </h3>
        </div>

        <div class="emil_send_sec clearfix">
            <div class="emial_list b_r">
                <input type="email" name="email" value="" id="email" placeholder="(From email)"
                       style="color: #2e2f23 !important;">
                <span id="emailError" style="display: block; color: #F99;"></span>
            </div>
            <div class="emial_list b_r">
                <input type="text" name="name" value="" id="name" placeholder="(From name)"
                       style="color: #2e2f23 !important;">
                <span id="nameError" style="display: block; color: #F99;"></span>
            </div>
            <div class="emial_list b_r">
                <input type="text" name="subject" value="" id="subject" placeholder="(Subject)"
                       style="color: #2e2f23 !important;">
                <span id="subjectError" style="display: block; color: #F99;"></span>
            </div>
        </div>

        <div class="camp_title">
            <h3>
                Choose Template
            </h3>
        </div>

        <div class="tamp_list_outer clearfix">
            <ul id="templateList">
            </ul>
        </div>

        <div class="camp_title">
            <h3>
                Saved Templates
            </h3>
        </div>

        <div class="saved_temp_outer">
            <ul id="campaignTemplateList">
            </ul>
        </div>
    </div>
</div>