<div class="modal fade gallery_popup" tabindex="-1" role="dialog" id="exampleModalCenter">
    <div class="modal-dialog" role="document" style="width: 88%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gallery</h5>
            </div>
            <div class="modal-body">
                <div class="btn_header" style="margin-bottom: 10px; height: 57px">
                    <input type="hidden" id="tokenForUploadImg" value="{{ csrf_token() }}"/>
                    <input id="galleryUpload" type="file">
                    <button id="galleryUploadBtn" class="btn"
                            style="padding: 11px 12px; background: #2a8689; color: white">
                        <i class="fa fa-refresh fa-spin"></i>
                        Upload
                    </button>
                    <span id="uploadError" style="color: #F99;"></span>
                </div>
                <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                    <div class="table-responsive">
                        <table style="width: 100%;" class="table table-bordered table-striped" id="gallery">
                            <thead>
                            <th style="width:30%;">Image</th>
                            <th style="width:20%;">Name</th>
                            <th style="width:20%;">Dimensions</th>
                            <th style="width:20%;">Size</th>
                            <th style="width:30%;">Date/Time Added</th>
                            <th style="width:10%;"></th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>