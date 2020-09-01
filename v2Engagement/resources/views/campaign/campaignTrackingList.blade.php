<style>
    /* datatable styling */
    table.dataTable tbody tr {
        background-color: #ffffff;
        height: 49px !important;
    }

    #campaignTrackingTable td:first-child {
        text-align: left;
        padding: 13px 20px !important;
    }

    #userTable_filter label {
        display: none !important;
    }

    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #c0c0c0 !important;
    }

    #importData {
        width: auto;
    }

    .modal-dialog .db_list_left_btm.scrollbar_content ul li {
        margin: 0 0 14px;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid white;
    }

    /*datepicker styling*/
    th {
        text-align: center;
    }

    tfoot {
        display: none;
    }

    .dropdown-menu {
        padding: 0px;
    }

    th.next, th.prev, td.day {
        cursor: pointer;
    }

    td.old, td.new {
        color: #ccc !important;
        background-color: #eee !important;
        cursor: not-allowed !important;
    }

    td.active {
        background: #ebedf2;
    }

    .lst_tbl_drop_outer ul {
        width: 125px;
    }

    .nf_seg_name_detail {
        left: 100%;
    }

    table tbody tr td:nth-child(2) {
        padding-left: 4px;
        padding-right: 0;
        text-align: left;
        font-size: 13px;
    }

    table.dataTable tbody th, table.dataTable tbody td {
        vertical-align: top;
    }

    .nf_seg_name_detail hr {
        margin: 5px 0;
    }

    .nf_seg_name_detail {
        width: 480px;
        padding: 10px 0 0;
    }

    #campaignTrackingTable_filter {
        width: 100%;
        padding-right: 14px;
    }

    #campaignTrackingTable_filter label {
        display: block;
        font-size: 0;
    }

    #campaignTrackingTable_filter input {
        width: 100%;
        background: #f0f0f0;
        display: block;
        float: none;
        height: 31px;
        padding: 0 15px;
        font-size: 16px;
        border-radius: 30px;
    }

    .modal-dialog .db_list_right_sec {
        border-left: none;
    }

    .modal-dialog .db_list_left_sec {
        border-right: 1px solid #c0c0c0;
        margin-top: 34px;
    }

    .modal-dialog .db_list_left_tp {
        padding: 10px 12px;
    }

    .modal-body {
        padding: 10px 0 0;
    }

    .modal {
        display: block !important;
        opacity: 0;
        z-index: -1;
    }

    .modal.active {
        opacity: 1;
        z-index: 99999;
    }

    .fade.in {
        z-index: 9999;
    }

    div.close_btn_holder {
        padding: 0 30px 10px 0;
    }

    div.close_btn_holder .btn.btn-default {
        color: #333;
        background-color: #e6e6e6;
        border-color: #adadad;
    }

    .nf_seg_name_detail {
        top: 20px;
    }
    .lst_tbl_drop_outer_Tracking ul {
        width: 115px;
        position: absolute;
        top: 33px;
        left: 0;
        background: #eaeaea;
        box-shadow: 0 0 6px -2px #000;
        z-index: 99999;
        display: none;
    }
    .lst_tbl_drop_outer_Tracking ul a {
        padding: 3px 0;
    }
    .modal-body table td, .lst_tbl_drop_outer_Tracking span{ position:relative; }
    .lst_tbl_drop_outer_Tracking span {
        display: block;
    }
    .lst_tbl_drop_outer_Tracking span:after {
        margin-top: -3px;
        position: absolute;
        content:'';
        top: 50%;
        right: 5px;
        vertical-align: middle;
        border-top: 5px solid #2a8689;
        border-right: 5px solid transparent;
        border-left: 5px solid transparent;
    }
    .ajax_call_loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 5;
        text-align: center;
    }

    .ajax_call_loader img {
        width: 40px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -25px 0 0 -40px;
    }

    .list_table_body {
        position: relative;
    }

</style>
<div id="campaignModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1024px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.left-scroll-bar-trackingListing')
                        <div class="db_list_right_sec">
                            @if (Session::has('flash_message'))
                                <div class="alert alert-info">{{ Session::get('flash_message') }}</div>
                            @endif
                            <div id="MSG"></div>
                            <div>
                                <div class="list_table_body scrollbar_content mCustomScrollbar _mCS_1">
                                    <div class="ajax_call_loader" style="display: none;">
                                        <img src="{{asset('assets/images/loader_ajax.gif')}}">
                                    </div>
                                    <table cellspacing="0" cellpadding="0" padding-right:10px; id="campaignTrackingTable">
                                        <thead>
                                        <tr>
                                            <th width="20%">Track Key</th>
                                            <th width="20%">Row Id</th>
                                            <th width="20%">Sent At</th>
                                            <th width="20%">Status</th>
                                            <th width="20%">Viewed At</th>
                                            <th width="20%">Message</th>
                                            <th width="20%">Action</th>
                                            <th>Event Value</th>
                                            <th>firebase_key</th>
                                            <th>device_key</th>
                                            <th>build</th>
                                            <th>version</th>
                                            <th>event_id</th>
                                            <th>device_type</th>
                                            <th>created_at</th>
                                            <th>email</th>
                                            <th>companyid</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right close_btn_holder">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    // $(document).on('click', '.lst_tbl_drop_outer_Tracking', function (e) {
    //     $(this).find('ul').slideToggle();
    // });
    var TrackingTable;
    var campaginStatus;
    var filter = null;
    var filterType = null;
    $(document).ready(function () {
        $(document).on('click', '#importData', function (e) {
            $(".buttons-csv").trigger("click");
        });

        var campaignId = "{{$id}}";
        console.log('campaignId', campaignId);
        getSegmentListing();
        events();
        $(".filter").on("click", function (e) {
            filter = $(this).attr("data-action");
            filterType = $(this).attr("data-type");
            $("#campaignTrackingTable").DataTable().draw();
        });

        function getSegmentListing() {
            var url = baseUrl + '/campaign/campaignTracklistingFilter/' + id;
            $('#campaignTrackingTable').DataTable({
                "dom": 'Bfrtip',
                "buttons": [
                    'csv'
                ],
                "processing": true,
                "serverSide": true,
                "searching": true,
                "bLengthChange": false,
                "iDisplayLength": listingSize,
                "defaultContent":"",
                "ajax": {
                    "url": url,
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {
                        data.filter = filter;
                        data.filterType = filterType;
                    },
                },
                "columns": [
                    {
                        "data": "Trackkey",
                        "className": "nf_seg_name",
                        "mRender": function (data, type, full) {
                            return '<a href="#">' + full.Trackkey + '</a>' +
                                '<div class="nf_seg_name_detail">' +
                                '<h3 style="text-align: center">Conversion</h3>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<label style="margin-left: 15px">Key:</label>' +
                                '<span>"' + full.Trackkey + '"</span>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<label style="margin-left: 15px">Firebase Key:</label>' +
                                '<span>' + full.firebase_key + '</span>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<label style="margin-left: 15px">Device Key:</label>' +
                                '<span>' + full.device_key + '</span>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Event Id:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.event_id + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Email:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.email + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Event Name:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.event_name + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Event Value:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.event_value + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Version:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.version + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Build:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.build + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Device Type:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.device_type + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div><hr>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="col-sm-4">' +
                                '<label>Conversion Date:</label>' +
                                '</div>' +
                                '<div class="col-sm-8">' +
                                '<span>' + full.created_at + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        },
                        "visible": true,
                        "render": $.fn.dataTable.render.text()
                    },
                    {
                        "data": "Row_id",
                    },
                    {
                        "data": "sent_at",
                        "render": $.fn.dataTable.render.text()
                    },
                    {
                        "data": "completestatus",
                        "render": $.fn.dataTable.render.text()
                    },
                    {
                        "data": "viewed_at",
                        "render": $.fn.dataTable.render.text()
                    },
                    {
                        "data": "message",
                        "render": $.fn.dataTable.render.text()
                    },
                    {"data": "action"},
                    {
                        "data": "event_value",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "firebase_key",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "event_value",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "device_key",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "build",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "version",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "event_id",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "device_type",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "created_at",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "email",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    },
                    {
                        "data": "company_id",
                        "render": $.fn.dataTable.render.text(),
                        "visible": false,
                    }
                ],
                "aoColumnDefs": [
                    {
                        "aTargets": [6],
                        "mData": "action",
                        "mRender": function (data, type, full) {
                            if (full.completestatus == 'failed' ) {
                                console.log('data', full);
                                return '<div class="lst_tbl_drop_outer">' +
                                    '<span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span>' +
                                    '<ul>' +
                                    '<a onclick=campaignResenNotificaition("' +full.campaign_id+'/'+full.id +'/'+full.company_id+'")  style="cursor: pointer">'+
                                    '<img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#">'+"Resend"+'</a>'+
                                    '</ul>'+'</div>';
                            }
                            return '';

                        },
                        "visible": true,
                        "searchable": false,
                        "orderable": false
                    }
                ],
                "order": [[0, "desc"]],
                "initComplete": function (settings, json) {
                    // call after loaded only first time
                },
                "drawCallback": function (settings, json) {
                    //call after every event cause change in datatable
                    // var description = $("#campaignTrackingTable_info").text();
                    // $("#campaignTrackingTable_info").text("");
                    // $(".listing_sec_ftr_detail p").text(description);
                }
            });
            TrackingTable = $('#campaignTrackingTable').DataTable();
            console.log(TrackingTable,'TrackingTable');
            $('#searchBar').on("change", function () {
                TrackingTable.search($(this).val()).draw();
            });
        }
        function events() {
            // console.log('call');
            // // $('.resend').click(function () {
            // //     alert($(this).attr('id'));
            // // });
            // $(document).on('c', '.lst_tbl_drop_outer ul li', function () {
            //     campaignResenNotificaition($(this).attr('id'));
            // });
        }

    });
    function campaignResenNotificaition(id)
    {
        var url = baseUrl + '/campaignResendNotification/'+ id;
        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function () {
                $(".ajax_call_loader").css('display', 'block');
            },
            complete: function () {
                $(".ajax_call_loader").css('display', 'none');
            },
            success: function (response) {
                console.log('respones', response);
                if (response.status == 200) {
                    finalArray = [];
                    toastr.success(response.message);
                    for (var val = 0; val < response.data.length; val++) {
                        finalArray.push([
                            // response.data[val]['email'],
                            response.data[val]['id'],
                            '<a target="_blank" href=' + baseUrl + "/backend/attribute/user/Stat/" + response.data[val]['rowId'] + '>' + response.data[val]['rowId'] + '</a>',
                            response.data[val]['jobstatus'],
                            response.data[val]['message'],
                            response.data[val]['created_at'],
                            '<input type="button"  value="Retry" onclick="fn_sendNotification(' + response.data[val]['id'] + ')" class="btn btn-secondary"/>',
                            '<input type="button"  value="Detail" onclick="fn_detailCampaign(' + response.data[val]['id'] + ')" class="btn btn-secondary"/>'
                        ]);
                    }
                    TrackingTable.ajax.reload();
                } else if (response.status == 402) {
                    for (var val = 0; val < response.data.length; val++) {
                        toastr.error(response.data[val].message );
                    }
                    TrackingTable.ajax.reload();
                }else {
                    toastr.error(response.message);
                    TrackingTable.ajax.reload();
                }
            }, error: function (error) {
                console.log('error', error);
            }
        });
    }
</script>