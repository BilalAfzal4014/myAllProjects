<div class="col-lg-10 col-lg-offset-1 nfc_template_view">
    @if(empty($htmlArr))
        @if($lang=='en')
            <h1>No notifications yet!</h1>
        @else
            <h1>!لا توجد إخطارات حتى الآن</h1>
        @endif
    @else
        @foreach ($htmlArr as $newsList)

            {!! $newsList !!}

        @endforeach

    @endif
</div>
<style>
    .nfc_template_view .content_holder div {
        max-width: none !important;
    }

    .nfc_template_view .content_holder div img {
        max-width: 210px !important;
    }

    @media only screen and (max-width: 585px) {
        .cap_text {
            width: 64% !important;
        }
    }
</style>