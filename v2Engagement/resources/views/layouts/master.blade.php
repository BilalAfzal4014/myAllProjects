<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Engagement</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.css-file')
</head>
<body class=@yield('create-class')>
<div class="wrapper">
    {{ csrf_field() }}
    <div class="wpr_content_holder dashboard clearfix">
        @include('partials.left-menu')
        <div class="right_sec_outer">
            @include('partials.top-bar')
            <div class="rt_content_outer" id="demo">

                @php
                    $roleArr = Auth::user()->roles()->pluck('name')->toArray()
                @endphp
                @if( !in_array('SUPER-ADMIN', $roleArr))
                    @inject('shared','App\Helpers\CommonHelper')
                    @if ( $shared->attributeDataExist() == 0 )
                        <div class="alert attributeDataExist" style="background: #fff;">
                            <p>
                                Please import your app data before creating newsfeed and campaign.
                                <a href="{{ route('importAttributeDataView') }}">click here</a>
                            </p>
                        </div>
                    @endif
                @endif

                @yield('newFeed_static')

                <div class="custom_load_div">
                    {{--<div class="loading_popup_outer">
                        <div class="center_alignment">
                            <div class="pop_up_body">
                                <div class="lds-ripple">
                                    <div></div>
                                    <div></div>
                                    <h2>Loading...</h2>
                                </div>

                            </div>
                        </div>
                    </div>--}}
                    <div class="loading_popup_outer_ajax" style="display: none;">
                        <div class="center_alignment">
                            <div class="pop_up_body">
                                <div class="lds-ripple">
                                    <div></div>
                                    <div></div>
                                    <h2>Loading...</h2>
                                </div>

                            </div>
                        </div>
                    </div>
                    @yield('create')

                    <div class="db_content_listing_holder">
                        @yield('searchBar')
                        <div class="listing_sec_outer clearfix">
                        <!-- @include('partials.left-scroll-bar') -->
                        <!-- @yield('left-scroll-bar') -->

                            @yield('content')

                        </div>
                        @include('partials.footer')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
@include('partials.script-file')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const baseUrl = '{{url('')}}';
    const listingSize = 10;
    const addImageIcon = "{{asset('/assets/images/addImage.png')}}";

    getCompanyLogo();

    function getCompanyLogo() {
        var key = "{{Auth::user()->id}}";
        $.ajax({
            type: 'GET',
            global: false,
            url: baseUrl + '/getCompanyLogo/' + key,
            dataType: 'json',
            success: function (response) {
                $(".menu_left_logo a img").attr("src", response.data);
            },
            error: function () {

            }
        });
    }

    $(document).ready(function (e) {
        $("body").on("click", function (e) {
            $(".hdr_rt_drop_down").hide();
            $(".lst_tbl_drop_outer ul").hide();
        })
    });

</script>

@yield('jsSection')
</html>
