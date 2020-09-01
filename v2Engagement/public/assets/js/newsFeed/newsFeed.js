$(document).ready(function () {


    $('body').on('click', '.modal', function(e) {

        $('.language_newsfeed').show();
        e.preventDefault();
    });
    $("#submitForm").on("click",function () {

        if($("#formArabic").validate()) {
            $("#formArabic").submit();
        }
    });
    $(document).on("click","[data-action='suspend']",function () {

        $.get(baseUrl+"/backend/newsfeed/suspend/"+$(this).attr("id"),function () {

            $('#newsListing').DataTable().draw();
        });
    });
    $("[data-dismiss='modal']").on("click",function (e) {

        e.preventDefault();
        $('#language_newsfeed').slideUp();
    });
    var today = new Date().toISOString().split('T')[0];

    if($("#is_edit").val() != 1) {
        $("#startDate").attr("min", today);
        $("#endDate").attr("min", today);
    }
    $('[data-toggle="tooltip"]').tooltip();
    getNewsfeedListing();




    var tmppath = '';
    $('#i_file').change(function (event) {
        tmppath = URL.createObjectURL(event.target.files[0]);
        $(".col-xs-2 img").fadeIn("slow").attr('src', URL.createObjectURL(event.target.files[0]));
        $('.content_holder img').fadeIn("slow").attr('src', URL.createObjectURL(event.target.files[0]));
    });

    function textUpdateTitle() {
        var titleText = $('#m_title').val();
        $('.content_holder  #title').html(titleText)
    }


    $(document).on('click', '.lst_tbl_drop_outer ul li', function () {
        var newsObj;
        rowId = $(this).attr('id');
        var action = $(this).attr('data-action');
        if (action == 'edit') {
            location.href = baseUrl + '/backend/newsfeed/edit/' + rowId;
        }
        if (action == 'view') {
            location.href = baseUrl + '/backend/newsfeed/view/' + rowId;
        }
        if (action == 'delete') {
            newsDelete(rowId);
            table.ajax.reload();
        }
    });

    var table;
    var filter;
    var filterType;

    function getNewsfeedListing() {
        var url = baseUrl + '/backend/newsfeed/listing/1';
        $('#newsListing').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "bLengthChange": false,
            "iDisplayLength": listingSize,
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
                {"data": "id"},
                {"data": "name"},
                {"data": "location_id"},
                {"data": "segment_id"},
                {
                    "data": "start_time",
                    "render": $.fn.dataTable.render.text()
                },
                {
                    "data": "end_time",
                    "render": $.fn.dataTable.render.text()
                },
                {"data": "active"},
                {"data": "action"},
            ],
            "aoColumnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "aTargets": [1],
                    "mData": "name",
                    "className":"nf_seg_name",
                    "mRender": function (data, type, full) {
                        return '<a href="' + baseUrl + '/backend/newsfeed/edit/' + full.id + '">' + full.name + '</a>\n' +
                            '<div class="nf_seg_name_detail">\n' +
                            '    <table>\n' +
                            '<thead>' +
                            '<tr>' +
                            '<th colspan="" style="text-align: center; width: 42%;"></th>' +
                            '<th colspan="" style="text-align: center;">Click</th>' +
                            '<th colspan="" style="text-align: center;">View</th>' +
                            '</tr>' +
                            '</thead>'+
                            '<tbody>'+
                            '        <tr>\n' +
                            '            <td style="width:80%;">Max</td>\n' +
                            '            <td style="width:20%;">'+full.maxClick+'</td>\n' +
                            '            <td style="width:20%;">'+full.maxViewed+'</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Min</td>\n' +
                            '            <td style="width:20%;">'+full.minClick+'</td>\n' +
                            '            <td style="width:20%;">'+full.minViewed+'</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">IOS</td>\n' +
                            '            <td style="width:20%;">'+full.countClickIphone+'</td>\n' +
                            '            <td style="width:20%;">'+full.sumViewedIphone+'</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Android</td>\n' +
                            '            <td style="width:20%;">'+full.countClickAndroid+'</td>\n' +
                            '            <td style="width:20%;">'+full.sumViewedAndroid+'</td>\n' +
                            '        </tr>\n' +
                            '        <tr>\n' +
                            '            <td style="width:80%;">Desktop</td>\n' +
                            '            <td style="width:20%;">'+full.countClickDesktop+'</td>\n' +
                            '            <td style="width:20%;">'+full.sumViewedDesktop+'</td>\n' +
                            '        </tr>\n' +
                            '    </tbody>' +
                            '</table>\n' +
                            '</div>';
                      }

                },
                {
                    "targets": [2],
                    "searchable": false,
                    "mData":"location_id",
                    "mRender":function (data,type,full) {


                        if(!full.location_id) {
                            return "Not Selected"
                        }else{

                            return full.location_id
                        }
                    }
                }, {
                    "targets": [3],
                    "searchable": false,
                    "mData":"segment_id",
                    "mRender":function (data,type,full) {


                        if(!full.segment_id) {
                            return "Not Selected"
                        }else{

                            return full.segment_id
                        }
                    }
                },
                {
                    "aTargets": [6],
                    "searchable": false,
                    "mData": "active",
                    "mRender": function (data, type, full) {

                        if (full.status == 'active') {
                            return 'Active';
                        } else if(full.status == 'draft') {
                            return 'Drafted'
                        }else if(full.status == 'suspend'){
                            return 'Suspended'

                        }else if(full.status == 'expired'){
                            return 'Expired'

                        }else{
                            return "";
                        }
                    },
                },
                {
                    "aTargets": [7],
                    "mData": "action",
                    "mRender": function (data, type, full) {

                        var suspendHtml = '';
                        var editHtml = '';
                        if(full.status !== 'suspend'){

                            suspendHtml = '<li id="' + full.id + '" data-action="suspend"><a href="#" ><img src="' + baseUrl + '/assets/images/Suspend.png' + '" alt="#">  Suspend</a></li>'
                        }
                        if(full.status === 'expired'){
                            suspendHtml = "";
                        }
                        if(full.status !== 'expired' ){

                            editHtml = '<li id="' + full.id + '"  data-action="edit" ><a href="' + baseUrl + '/backend/newsfeed/edit/' + full.id + '"> <img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#"> Edit</a></li><li id="' + full.id + '" data-action="editArabic"><a href="javascript:void(0);" ><img src="' + baseUrl + '/assets/images/edit_icon.png' + '" alt="#">  Edit Arabic</a></li>'
                        }
                        return '<div class="lst_tbl_drop_outer"><span class=""> <img src="' + baseUrl + '/assets/images/sett_icon.png' + '"  alt="#"> </span><ul>'+editHtml+' <li id="' + full.id + '" data-action="view"><a href="#" > <img src="' + baseUrl + '/assets/images/view_icon.png' + '" alt="#"> View</a></li> <li id="' + full.id + '" data-action="delete"><a href="#" ><img src="' + baseUrl + '/assets/images/del_icon.png' + '" alt="#">  Delete</a></li>'+suspendHtml+'</ul></div></td>';
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
                var description = $("#newsListing_info").text();
                $("#newsListing_info").text("");
                $(".listing_sec_ftr_detail p").text(description);
            },
            "createdRow": function (row, data, dataIndex) {
                if (data.status === 'draft') {
                    $(row).addClass('draft');
                }
                else if (data.status === 'active') {
                    $(row).addClass('launch');
                }
                else if (data.status === 'suspend') {
                    $(row).addClass('suspend');
                }
                else if (data.status === 'expired') {
                    $(row).addClass('expired');
                }
            },

        });
        table = $('#newsListing').DataTable();
        $('#searchBar').on("change", function () {
            $('#newsListing').DataTable().search($(this).val()).draw();
        });
    }


    $(".filter-query").on("click", function () {

        filter = $(this).attr("data-filter");
        filterType = $(this).attr("data-filtertype");
        table.draw();
    });
    $(document).on("click", ".news-feed-filters", function (e) {
        var tag = $(this).attr('data-tag');
        filterType = "tags";
        filter = tag;
        table.draw();
    });

    if ($(".tags").val() != '') {
        $("#newstags").parent().children().filter('.bootstrap-tagsinput').find('input').attr('placeholder', '');
    }


    $(document).on("click","[data-action='editArabic']",function () {

        $("#newsfeedTraslationId").val("");
        $("[name='title']").val("");
        $("[name='description']").val("");
        var id = $(this).attr('id');
        $.get(baseUrl + "/backend/newsFeeds/getTranslation/"+id,function (data) {

            if(data.status == 200){

                if(data.newsfeedObj){

                    $("#newsfeedTraslationId").val(data.newsfeedObj.id);
                    $("[name='title']").val(data.newsfeedObj.title);
                    $("[name='description']").val(data.newsfeedObj.message);
                }
            }
            $(".language_newsfeed").modal("show");
            $("#neesfeedId").val(id);
        });

    });
});


// $('#searchBar').keyup(function () {
//     table.search($(this).val()).draw();
// });

$('#active').click(function () {
    $('#activeFilter').val(1);

    var filter = 1;
    var filterType = 'status';
    table.destroy();
    getNewsfeedListing(filter, filterType)
});

$('#draft').click(function () {
    $('#activeFilter').val(0);
    var filter = 0;
    var filterType = 'status';
    table.destroy();
    getNewsfeedListing(filter, filterType)
});

$('#all').click(function () {
    $('#activeFilter').val(2);
    var filter = 2;
    var filterType = 'status';
    table.destroy();
    getNewsfeedListing(filter, filterType)

});




