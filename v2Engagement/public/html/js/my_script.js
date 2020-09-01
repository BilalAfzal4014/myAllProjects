

// Scroll-Bar

(function($){
  $(window).on("load",function(){

    $(".scrollbar_content").mCustomScrollbar();

    $(".disable-destroy a").click(function(e){
      e.preventDefault();
      var $this=$(this),
        rel=$this.attr("rel"),
        el=$(".scrollbar_content"),
        output=$("#info > p code");
      switch(rel){
        case "toggle-disable":
        case "toggle-disable-no-reset":
          if(el.hasClass("mCS_disabled")){
            el.mCustomScrollbar("update");
            output.text("$(\".scrollbar_content\").mCustomScrollbar(\"update\");");
          }else{
            var reset=rel==="toggle-disable-no-reset" ? false : true;
            el.mCustomScrollbar("disable",reset);
            if(reset){
              output.text("$(\".scrollbar_content\").mCustomScrollbar(\"disable\",true);");
            }else{
              output.text("$(\".scrollbar_content\").mCustomScrollbar(\"disable\");");
            }
          }
          break;
        case "toggle-destroy":
          if(el.hasClass("mCS_destroyed")){
            el.mCustomScrollbar();
            output.text("$(\".scrollbar_content\").mCustomScrollbar();");
          }else{
            el.mCustomScrollbar("destroy");
            output.text("$(\".scrollbar_content\").mCustomScrollbar(\"destroy\");");
          }
          break;
      }
    });

  });





});

(function($){
  $(window).on("load",function(){

    $(".scrollbar_content2").mCustomScrollbar();

    $(".disable-destroy a").click(function(e){
      e.preventDefault();
      var $this=$(this),
        rel=$this.attr("rel"),
        el=$(".scrollbar_content"),
        output=$("#info > p code");
      switch(rel){
        case "toggle-disable":
        case "toggle-disable-no-reset":
          if(el.hasClass("mCS_disabled")){
            el.mCustomScrollbar("update");
            output.text("$(\".scrollbar_content\").mCustomScrollbar(\"update\");");
          }else{
            var reset=rel==="toggle-disable-no-reset" ? false : true;
            el.mCustomScrollbar("disable",reset);
            if(reset){
              output.text("$(\".scrollbar_content\").mCustomScrollbar(\"disable\",true);");
            }else{
              output.text("$(\".scrollbar_content\").mCustomScrollbar(\"disable\");");
            }
          }
          break;
        case "toggle-destroy":
          if(el.hasClass("mCS_destroyed")){
            el.mCustomScrollbar();
            output.text("$(\".scrollbar_content\").mCustomScrollbar();");
          }else{
            el.mCustomScrollbar("destroy");
            output.text("$(\".scrollbar_content\").mCustomScrollbar(\"destroy\");");
          }
          break;
      }
    });

  });





});
//
// $(document).ready(function(){
//     $(".popup_close").click(function(){
//         $(".login_popup_outer").fadeOut(500);
//     });
// });
