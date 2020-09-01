<form id="DELIVERY" class="newsfeed hide" name="newsfeed_step_two" autocomplete="off"  method="post" action="" enctype="multipart/form-data">

    <div class="bcd_stp2 step-tab-panel">

    <div class="segment_location_outer clearfix">

        <div class="Campaigns_type_sec inp_select">
            <select name="seg_id" id="seg_id" data-placeholder="Please select one..."
                    class="chzn-select form-control" tabindex="1">
                <option value="">Please Select Segment</option>
            </select>
            <span id="segError" class="inputError" style="color: #f00;margin: 100px;"></span>
        </div>


        <div class="Campaigns_type_sec inp_select">

            <select name="loc_id" id="loc_id" data-placeholder="Please select one..." class="chzn-select form-control" tabindex="1">
                <option value="">Please Select Location</option>
            </select>
            <span id="locError" class="inputError" style="color: #f00;margin: 100px;"></span>
        </div>

    </div>

    <div class="camp_title">
        <h3> Newsfeed Duration
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
                        <label for="start_tm">Start Time (Required)</label>
                    </div>
                    <div class="camp_timing_inp_sec">
                        <div class="b_r">
                            <label>
                                <input type="date" name="startDate" id="startDate">
                            </label>
                        </div>
                        <b>at</b>
                        <div class=" inp_select  b_r shour">
                            <select name="startHour" id="startHour" required>
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
                            <select name="startmin" id="startmin" required>
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
                <span id="startDateError" class="inputError"
                      style="color: #f00;margin: 100px;"></span>
            </li>
            <li>
                <div class="camp_Dur_timing clearfix">

                    <div class="camp_timing_check_box">
                        <input type="checkbox" id="end_tm" name="end_tm" value="true" @if(isset($news) and $news->enable_end_time == 1)checked @endif>
                        <label for="end_tm">End Time (Optional) </label>
                    </div>

                    <div class="camp_timing_inp_sec" id="end_time_dev">

                        <div class="b_r">
                            <label>
                                <input type="date" name="endDate" id="endDate">
                            </label>

                        </div>
                        <b>at</b>
                        <div class="inp_select  b_r shour">
                            <select name="endHour" id="endHour">
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
                            <select name="endmin" id="endmin">
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
                    <span id="endDateError" class="inputError"
                          style="color: #f00;margin: 100px;"></span>
                </div>
            </li>
        </ul>
    </div>

</div>
</form>