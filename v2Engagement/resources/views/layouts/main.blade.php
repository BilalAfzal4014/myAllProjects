<!doctype html>
<html>
  <head>
    <meta http-equiv="Cache-control" content="public">
    <meta charset="utf-8">
    <meta name="viewport" content=" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"  >
    <title>Dashboard</title>
    <link href="{{asset('html/css/all.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('html/css/jquery-steps.css')}}" rel="stylesheet" type="text/css">
    <!-- custom scrollbar stylesheet -->
    <link rel="stylesheet" href="{{asset('html/css/jquery.mCustomScrollbar.css')}}">



  </head>
  <!-- email_html-2,  -->
  <body class="">

    <div class="wrapper">
              <!-- left_menu_expand => Add for left Bar Expand -->
        <div class="wpr_content_holder  clearfix">
      @include('partials.left-menu')
      <div class="right_sec_outer">
      @include('partials.top-bar')
      <div class="rt_content_outer newsfeed_content" id="demo">
      @yield('create')
      <div class="db_content_listing_holder">
                @yield('content')
                </div>

                <div class=" step-content">

                  <div class="step-footer save_next_sec ">

                     <div class="listing_sec_ftr_detail">
                       <p> Showing rows 1 to 10 of 5,286 </p>
                     </div>


                   </div>

                </div>

                <!--  -->
              </div>


        </div>

      </div>
    </div>

      <!-- <div class="footer_outer">
        <p> &#169; Engagement Platform 2018 </p>
      </div> -->

   </div>
   <script type="text/javascript" src="{{ asset('html/js/jquery-1.10.2.js') }}"></script>
   <script src="{{ asset('html/js/my_script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('html/js/jquery-resizable.js') }}"></script>
   <script type="text/javascript" src="{{ asset('html/js/jquery-steps.js') }}"></script>
   <script src="{{ asset('html/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
   <script type="text/javascript">

   $(document).ready(function(){
 // Class Add/ Remove On Wrapper
     $(".hdr_menu_btn").click(function(){
         $(".wpr_content_holder ").toggleClass("left_menu_expand");
     });
 // Left Header button active on click Function
     $(".left_menu_list ul li a").click(function(){
       $(".left_menu_list ul li a").removeClass("active");
       $(this).addClass("active");
     });

//  campaigns_type Section add/Remove class on parent
      $("#campaigns_type").change(function(){

      var lang_var = $("#campaigns_type option:selected").val();

      $("body").attr("class",lang_var);

      });

  // Compose In-App Messages Tab Function
       $(".pre_comp_title_icons ul li a").click(function(e){
   		  $(".pre_comp_title_icons ul li a").removeClass("active");
   		  $(this).addClass("active");

   		  var this_id = $(this).attr("href");
   		   $(".comp_det_sec").hide();
   		  $(this_id).show( );
   		  return false;

   		});
 // Top Header-right Drop Down Function
       $(".hdr_rit_search_sec a").click(function(){
         $(".hdr_rt_drop_down").slideToggle();

       });
       $(".hdr_rt_drop_down ul li a").click(function(){
         $(this).parents(".hdr_rt_drop_down") .slideUp();

       });

 // Step function
       $('#demo').steps({
          startAt: 0,
          showBackButton: true,
          showFooterButtons: true,
        });
//////////////////

        $(".con_event_dropdown span ").click(function(e){
          $(".con_event_dropdown ul").slideToggle();

        });

        $(".con_event_dropdown a").click(function(e){
          $(".con_event_dropdown ul ").slideUp();

        });

//    Step-5 Tab with Dropdown
        $(".con_event_dropdown li a").click(function() {

          var test = $(this).text();
          $('.con_event_dropdown span').text('');
          $('.con_event_dropdown span').text(test);

          var this_id = $(this).attr("tab");
          $('.conversion_event_step').removeClass('active');
          $('#' + this_id).addClass('active');
         });


        $(".rt_aligning_btn ul li a").click(function(e){
          $(".rt_aligning_btn ul li a").removeClass("active");
          $(this).addClass("active");
        });


// And Or Toggle Btns Effects

    $(".and_or_toggle_btn ").click(function() {
      $(this).toggleClass('active');


    });


 //  Tab Step-3
 // tab Function
      $(".select_button a").click(function(e){
      	$(".select_button a").removeClass("active");
      	$(this).addClass("active");

      	var this_id = $(this).attr("href");
      	$(".sel_btn_det").fadeOut('fast');
      	$(this_id).fadeIn('slow');
      	return false;
      });

// Sec-2 - Email

//  step-1 temp_Add Remove class

$(".tamp_list_outer ul li a").click(function(e){
  $(".tamp_list_outer ul li a").removeClass("active");
  $(this).addClass("active");
});

//
//  Compose In-App Messages Tab Function
     $(".pre_comp_title_icons2 ul li a").click(function(e){
      $(".pre_comp_title_icons2 ul li a").removeClass("active");
      $(this).addClass("active");

      var this_id = $(this).attr("href");
       $(".comp_det_sec").fadeOut('fast');
      $(this_id).fadeIn('slow');
      return false;

    });


    $(".lst_tbl_drop_outer span").click(function(e){
     $(this).parent(".lst_tbl_drop_outer").find("ul").slideToggle('slow');
     return false;

     });
    $(".lst_tbl_drop_outer ul li").click(function(e){
    $(this).parent(".lst_tbl_drop_outer ul").slideUp('slow');
    return false;

  });



// Splitter function
      $(".panel-left").resizable({
      handleSelector: ".splitter",
      resizeHeight: false,
      resizeWidth: true
      });

//    Newsfeed title Detail

      // $(".nf_seg_name a").click(function(){
      //   $(this).parent().find('.nf_seg_name_detail').slideToggle(500);
      // });

//   Listing-left-bottom- Sub-Menu

      $(".db_list_left_sublist h3").click(function(){
        $(this).toggleClass('list_left_sublist_active');
        $(this).parent().find('ul').slideToggle(500);
      });




   });

   //   listing to newsfeed show ///////////
     $("#campaigns_type2").change(function(){

     var lang_var = $("#campaigns_type2 option:selected").val();

     $("body").attr("class",lang_var);
     $(".db_content_holder").show();
     $(".db_content_listing_holder").hide();
     });
   ////////////////////////

    </script>

  </body>
<script>
    var baseUrl = '{{url('')}}' ;
    var addImageIcon = "{{asset('/assets/images/addImage.png')}}";
</script>
@yield('jsSection')
</html>
