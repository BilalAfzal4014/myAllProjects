$(document).ready(function () {


    function saveGeneralInfo(form,formIndex){

        showLoader();
        $.ajax({
            type: 'POST',
            url: baseUrl + '/backend/app/crud/submit?form='+formIndex,
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            data: new FormData(form),
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            success: function (response) {
            hideLoader();

                if(formIndex == 0){
                    if(response.status === 200){
                        $("#basicFormSubmit").val(1);
                        $("#appId").val(response.id);
                        $("#appId_second").val(response.id);
                        $("#appId_third").val(response.id);
                    }
                }
                if(response.status === 200){

                    toastr.success(response.message);
                    window.location = baseUrl + '/backend/app/listing';

                }
                if(response.status === 500){

                    toastr.error(response.message);
                }



            },
            error: function () {

            }


        });
    }
   $(".sub_btn").on("click",function (e) {

       var basicFormSubmit = $("#basicFormSubmit").val();
       var tabNumber = $(this).attr("data-action-for");

       if(basicFormSubmit == 0 ){
           //alert("here"+basicFormSubmit);
           if(tabNumber !== "tab1"){

               toastr.error("Please save general information first");
           }else{

               if($('#firstTab').valid()) {
                   saveGeneralInfo($("#firstTab")[0],0);
               }
           }
       }else{

           //alert("here");

           if(tabNumber === "tab1"){
               if($('#firstTab').valid()) {
                   saveGeneralInfo($("#firstTab")[0],0);
               }
           }else if(tabNumber === "tab2"){

               if($('#secondTab').valid()) {

                   saveGeneralInfo($("#secondTab")[0],1);
               }
           }else if(tabNumber === "tab3"){

               if($('#thirdTab').valid()) {
                   saveGeneralInfo($("#thirdTab")[0],2);

               }
           }
       }
   });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#appLogoPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#appLogo").change(function(){
        $("#companyLogoPreviewLabel").hide()
        readURL(this);
    });

    $("#platform").on("change",function (e) {

        if($(this).val() == 'IOS'){

            $("#ios_section").slideDown();
        }else{

            $("#ios_section").slideUp();

        }
    });
});