@extends('layouts.master')

@section('create')
    <style>
       

        .selectEle {
            display: block;
            border: 1px solid #b2b2b2;
            padding: 8px 11px;
            border-radius: 6px;
            width: 85%;
        }

      
       

        .spantest:after {
            content: '';
            margin-top: 3px;
            position: relative;
            left: 73%;
            bottom: 19px;
            margin-left: 6px;
            vertical-align: middle;
            border-top: 6px solid #2a8689;
            border-right: 6px solid transparent;
            border-left: 6px solid transparent;
            pointer-events: none;
        }

       
        .tag_holder{
            background-color: #FFFF00;
           
        }
    </style>
 
    <div class="db_content_holder step-app">
        <div class="tp_BreadCrumb_list_sec clearfix">
            <label id="" class="sec_tp_title"> <span id=""> Cache Viewer/Cache Commands </span></label>
           
                <div class="company_right" style="float: right">
                  
                    <div class="outerDivSelect">
                        <label>Select Company</label>
                        <span class="spantest">
                    <select id="company" class="selectEle" step="0">
                        <option value="">Select Company</option>
                        @for($val=0;$val<count($users);$val++)
                            <option value="{{$users[$val]['id']}}">{{$users[$val]['name']}}</option>
                        @endfor
                    </select>
                </span>
                    </div>
                </div>
           
        </div>

        <div class="breadcrumb_step_det_outer step-content" style="margin-top: 10px;">

            
                <label>Cache Commands</label>
                
                    
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span><span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_<span  class="tag_holder" contenteditable="true">APPNAME</span>_<span  class="tag_holder" contenteditable="true">UserID</span><i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder" contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_rows<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_row_data_<span  class="tag_holder" contenteditable="true">ROWID</span><i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_row_details_<span  class="tag_holder" contenteditable="true">ROWID</span><i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_campaign_<span class="tag_holder" contenteditable="true">CAMPAIGNID</span>_segments_rows<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_campaign_<span  class="tag_holder" contenteditable="true">CAMPAIGNID</span>_tracking<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_campaign_<span  class="tag_holder" contenteditable="true">CAMPAIGNID</span>_conversions<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_campaigns<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_newsfeeds<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_newsfeed_<span  class="tag_holder" contenteditable="true">NEWSFEEDID</span>_clicks<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_newsfeed_<span  class="tag_holder" contenteditable="true">NEWSFEEDID</span>_views<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder" contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_segments<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_segment_<span  class="tag_holder" contenteditable="true">SEGMENTID</span>_rows<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>company_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span>_campaign_<span class="tag_holder"  contenteditable="true">CAMPAIGNID</span>_segments<i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                <div><span class="prefix">GET <span class="tag_holder"  contenteditable="true">engagement</span>:</span>email_list_<span class="tag_holder company_id" contenteditable="true">COMPANYID</span><i style="margin-left:20px;cursor:pointer;" onclick="getCommand(this)" class="fa fa-forward" aria-hidden="true"></i></div>
                               

            <div class="comp_input_sec p_t_b" style="padding: 10px 20px 10px 0px;">
                <textarea placeholder="Message" id="cache_data" name="cache_data" class="b_r "
                          style="height: 200px"></textarea>
                <span id="notificationError" style="color: #F99;"></span>
            </div>         

            <div class="" style="padding:5px; background:#fff;">
            </div>

        </div>

    </div>
@stop

@section('jsSection')
    
    <script>
        $("#company").on('change',function(){
           
           $(".company_id").html(this.value);
        });
        
        function getCommand(elem){  
          
          var textContent = elem.parentElement.textContent.toString();
          var prefix = elem.parentElement.closest('div').children[0].textContent.toString();
          
          textContent = textContent.replace(prefix,"");
          
          $.ajax({
                type: "POST",
                url: baseUrl + "/cache/execute_command",
                cache: false,
                data: {"command": textContent},
                headers: {'X-CSRF-TOKEN': "<?php echo csrf_token(); ?>"},
                dataType: 'json',
                success: function (response) {
                    
                   $("#cache_data").val(JSON.stringify(response)); 
                   
                }, error(e) {
                    console.log(e);
                }
            });
        }
        
       
        $(".db_content_holder").css({'display': 'block'});
      
    </script>
@stop