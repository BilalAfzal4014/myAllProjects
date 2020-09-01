<div class="modal gallery_popup" style="width: 100%;" tabindex="-1" role="dialog"
     id="cropImageSelected">
    <div class="modal-dialog" style="min-width: 88%;width: 88%;" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <span class="modal-title head_crop crop_heading">Crop Image</span>
                <div id="crop_info" style="display: none" class="notice">
                    <img src="{{asset('/assets/images/bulb_icon.png')}}">
                    <ul>
                        <li>
                            <span>1 - Minimum optimum size for banner image is Width: 640px Height: 1136px</span>
                            <span>2 - For full screen minimum preferred image size is Width: 600px Height: 800px </span>
                        </li>
                        <li>
                            <span>3 - For dialog minimum preferred image size is Width: 100px Height: 100px</span>
                            <span>4 - We allow .PNG, .GIF, .JPG and JPEG image file formats.</span>
                        </li>
                    </ul>
                </div>
                <div class="cropper_height_width">
                    <span>Height: </span>
                    <span>Width: </span>
                </div>
            </div>
            <div class="modal-body">
                <div id="cropper_mcsb" class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                    <img id="cropImage" src="{{ asset('assets/images/john_doe.jpg') }}">

                </div>
            </div>
            <div class="modal-footer">
                <div class="button_crop">
                    <a href="javascript:void(0);" id="cancelCaropButton"
                       class="btn btn-danger">Cancel</a>
                    <a href="javascript:void(0);" id="cropButton" class="btn btn-success">
                        <i style="margin-left:0px" class="fa fa-refresh fa-spin"></i>
                        Crop & Insert</a>
                </div>
            </div>
        </div>
    </div>
</div>
