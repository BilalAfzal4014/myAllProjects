<!DOCTYPE html>
<html lang="en">
<head>
    <title>Unsubscribe</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i,900,900i" rel="stylesheet">
    <style>

        /* Unsubscribe Page starts */
        .unsubscribe_area {
            height: 100vh;
            background: #f1f1f1;
            overflow: hidden;
        }

        .unsubscribe_holder {
            max-width: 601px;
            margin: 0 auto;
            color: #31394d;
            text-align: center;
            background: #fff;
            overflow: hidden;
            padding: 30px 10px 55px;
            font-weight: 700;
            font-size: 20px;
        }

        .unsubscribe_holder h1 {
            font-size: 34px;
            padding: 0;
            margin: 0 0 40px;
            color: #4a4a4a;
            font-weight: 900;
        }

        .unsubscribe_holder h2 {
            font-size: 29px;
            margin: 19px 0 26px;
            color: #4a4a4a;
            line-height: 34px;
        }

        .unsubscribe_holder p {
            margin: 0 20px 40px;
        }

        .unsubscribe_holder p.bordered {
            border-top: 1px solid #979797;
            padding: 37px 0 0;
            margin: 70px 20px 40px;
        }

        .btn_confirm {
            display: inline-block;
            vertical-align: top;
            background: #31394d;
            color: #fff;
            border-radius: 5px;
            padding: 16px 10px;
            font-size: 20px;
            width: 190px;
            margin: 0 19px;
        }

        .btn_confirm:hover {
            color: #fff;
        }

        .btn_confirm.inactive {
            color: #31394d;
            background: #e5e5e5;
        }

        /* Unsubscribe Page ends */
        body {
            min-width: 1200px;
            margin: 0;
            color: #b2b2b2;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: none;
            font: 16px/1.4 'Lato', sans-serif;
            background: #f0f0f0;
        }

        .d_table {
            width: 100%;
            height: 100%;
            display: table;
        }

        .v_middle {
            width: 100%;
            height: 100%;
            display: table-cell;
            vertical-align: middle;
        }
        a{
            text-decoration: none;
        }
        body{
            min-width: 320px;
        }
        @media only screen and (max-width:600px){
            .unsubscribe_holder h1 {
                font-size: 27px;
                margin: 0 0 20px;
            }
            .unsubscribe_holder h2 {
                font-size: 22px;
                margin: 11px 0 15px;
                line-height: 26px;
            }
            .unsubscribe_holder p {
                margin: 0 10px 30px;
            }
            .unsubscribe_holder p.bordered {
                padding: 17px 0 0;
                margin: 30px 20px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
<div id="wrapper">
    <div class="unsubscribe_area">
        <div class="d_table">
            <div class="v_middle">
                <div class="unsubscribe_holder">
                    <h1>{{$userObj['name']}}</h1>
                    <img src="{{asset('assets/images/mail-box-ico.svg')}}" alt="#" class="mail_icon">
                    <h2>You're about to unsubscribe!</h2>
                    <p class="bordered">Are you sure you want to unsubscribe?</p>
                   <form id="form" action="{{url('updateTrackingStatus')}}" method="post">
                       <input type="hidden" id="trkId" name="trkId" value="{{$userObj['trackKey']}}">
                   </form>
                    <div class="un_btns">
                        <a id="updateStatus" style="cursor: pointer" class="btn_confirm">Confirm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/jquery.js')}}"></script>
<script type="text/javascript">
    var trackKey = "{{$userObj['trackKey']}}";
    console.log('trackKey', trackKey);
    $("#updateStatus").click(function () {
        form.submit();
    });
</script>
</body>
</html>