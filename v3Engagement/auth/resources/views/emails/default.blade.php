<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @font-face {
            font-family: "Gotham Medium";
            src: url("fonts/gotham-medium.eot");
            src: url("fonts/gotham-medium.woff") format("woff"),
            url("fonts/gotham-medium.otf") format("opentype"),
            url("fonts/gotham-medium.svg#filename") format("svg");
        }

        @font-face {
            font-family: "Gotham Bold";
            src: url("fonts/gotham-medium.eot");
            src: url("fonts/gotham-bold.woff") format("woff"),
            url("fonts/gotham-bold.otf") format("opentype"),
            url("fonts/gotham-bold.svg#filename") format("svg");
        }
    </style>
</head>
<body style="background:#f7f7f9; margin:0; font:17px/22px 'Gotham Medium', san-serif; color:#58595b;">
<table style="width:634px; background:#fff; table-layout:fixed; border-collapse: collapse; margin:50px auto;">
    <thead>
    <tr>
        <th colspan="4" style="padding: 20px 45px;"><img src="{{url('images/logo.png')}}" alt="Hermis"></th>
    </tr>
    <tr>
        <th colspan="4" style="padding: 0 45px 38px; font-size:28px; line-height:32px; font-weight: 500;">Password
            change request !
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="4" style="padding: 14px 45px 10px;">
            <img src="{{url('images/img1.png')}}" alt="#" style="display:block; margin:0 auto;">
        </td>
    </tr>

    <tr>
        <td colspan="4" style="padding: 14px 45px 0;">
            <p style="margin:0 0 30px;">Congratulations! Your password has been reset. Your new password is given
                below:</p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 14px 45px 0;">
            <a style="color:#58595b; display:block; width:250px; margin:0 auto; background:#bed62f; padding:8px 13px; text-align: center; text-decoration: none;"><?php echo $template_content; ?></a>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 60px 45px 42px;">
            <p style="margin:0; text-align: center;">Email us at <a href="mailto:support@engagement.com"
                                                                    style="color:#2b72ff;">support@engagement.com</a> if
                you have any questions regarding our platform. Feel free to contact us 24/7. We are here to help.</p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 0 45px 27px;">
            <!--p style="margin:0 0 2px; text-align: center; ">Yours strategic partner in marketing services</p-->
            <p style="margin:0; text-align: center;"><span style="color:#bed62f;">The Engagement Team</span></p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 30px 45px; text-align: center;">
            <table style="width:50%; margin:0 auto;">
                <tr style="text-align:center;">
                    <td><a href="#" style="text-decoration:none;color:#b2b2b2; font-size: 12px; line-height: 18px;">Unsubscribe</a>
                    </td>
                    <td><a href="" style="text-decoration:none; color:#b2b2b2; font-size: 12px; line-height: 18px;">|
                    </td>
                    <td><a href="#" style="text-decoration:none;  color:#b2b2b2; font-size: 12px; line-height: 18px;">Visit
                            Web</a></td>
                    <td><a href="" style="text-decoration:none; color:#b2b2b2; font-size: 12px; line-height: 18px;">|
                    </td>
                    <td><a href="#" style="text-decoration:none;  color:#b2b2b2; font-size: 12px; line-height: 18px;">Contact
                            Us</a></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>