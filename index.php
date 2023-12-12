<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require 'mail/Mail.php';
//   require __DIR__mail/Mail.php';
if (isset($_SESSION['notification_showed'])) {
    if ($_SESSION['notification_showed'] == true) {
        unset($_SESSION['nameErr']);
        unset($_SESSION['emailErr']);
        unset($_SESSION['phoneErr']);
        unset($_SESSION['messageErr']);
    }
}

if (
    isset($_SESSION['nameErr']) ||
    isset($_SESSION['emailErr']) ||
    isset($_SESSION['phoneErr']) ||
    isset($_SESSION['messageErr'])
) {
    $_SESSION['notification_showed'] = true;
}
if (isset($_POST['form_submit'])) {
    if (
        isset($_POST['g-recaptcha-response']) &&
        !empty($_POST['g-recaptcha-response'])
    ) {
        // check capcha submitted

        $secret = '6LfOyCkUAAAAALZXPK8Cpr4Y5PBurthGMIVVGUhm';
        //get verify response data
        $verifyResponse = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret=' .
            $secret .
            '&response=' .
            $_POST['g-recaptcha-response']
        );
        $responseData = json_decode($verifyResponse);

        if ($responseData->success) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                unset($_SESSION['nameErr']);
                unset($_SESSION['emailErr']);
                unset($_SESSION['phoneErr']);
                unset($_SESSION['messageErr']);

                $_SESSION['notification_showed'] = false;

                if (empty($_POST['contact_name'])) {
                    $_SESSION['nameErr'] = 'Name is required';
                } else {
                    $name = test_input($_POST['contact_name']);
                    // check if name only contains letters and whitespace
                    // if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                    //   $_SESSION['nameErr'] = "Only letters and white space allowed";
                    // }
                }

                if (empty($_POST['contact_email'])) {
                    $_SESSION['emailErr'] = 'Email is required';
                } else {
                    $email = test_input($_POST['contact_email']);
                    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    //     $_SESSION['emailErr'] = "Invalid email format";
                    //   }
                }

                if (empty($_POST['contact_mobile_number'])) {
                    $_SESSION['phoneErr'] = 'Mobile number is required';
                } else {
                    $phone = test_input($_POST['contact_mobile_number']);
                    // if (!preg_match("/^[1-9][0-9]{0,15}$/",$phone)) {
                    //     $_SESSION['phoneErr'] = "Invalid Mobile number";
                    //   }
                }

                if (empty($_POST['contact_message'])) {
                    $_SESSION['messageErr'] = 'Message is required';
                } else {
                    $message = test_input($_POST['contact_message']);
                    $messageLen = strlen($message);

                    // if(preg_match('/[#$%^&*()+=\-\[\]\;\/{}|":<>?~\\\\]/', $message)){
                    //     $_SESSION['messageErr'] = "Special Character not allowed";
                    // }
                    // if ($messageLen > 50) {
                    //     $_SESSION['messageErr'] = "Message will not greater than 50 characters";
                    //   }
                }

                //   if(isset($_SESSION['nameErr']) || isset($_SESSION['emailErr']) || isset($_SESSION['phoneErr']) || isset($_SESSION['messageErr'])){
                //     header("Location: contact.php#Requestdemo");
                //   }

                // if (!empty($_POST['contact_email'])) {
                //     $connection = mysqli_connect("localhost", "taxi_user", "nPlus@2014"); // Establishing Connection with Server
                //     $db = mysqli_select_db($connection, "taxiapp"); // Selecting Database from Server
                //     if (isset($_POST["form_submit"])) { // Fetching variables of the form which travels in URL

                //         $name = $_POST['contact_name'];
                //         $dialcodes = $_POST['dialcodes'];
                //         $email = encrypt($_POST['contact_email']);
                //         $phone = encrypt($_POST['contact_mobile_number']);
                //         $message = $_POST['contact_message'];
                //         $plan = $_POST["optradio"];
                //         $dialphone = $dialcodes . $phone;

                //         if ($name != '' && $email != '' && $phone != '' && empty($_SESSION['nameErr']) && empty($_SESSION['emailErr']) && empty($_SESSION['phoneErr'] && empty($_SESSION['messageErr']) ) ) {
                //             // Insert Query of SQL
                //             $query = "INSERT INTO tab_client_token_request (name,email,dialcode,contact_mobile_number,message,plan) VALUES ($name, $email,$dialcodes,$phone,$message,$plan)";
                //             mysqli_query($connection, $query);
                //         } else {
                //             echo "<p>Insertion Failed <br/> Some Fields are Blank....!!</p>";
                //         }
                //     }
                //     mysqli_close($connection); // Closing Connection with Server
                // }
            }

            //--------------------------------------------------------
            if (
                !empty($_POST['contact_name']) &&
                !empty($_POST['dialcodes']) &&
                !empty($_POST['contact_mobile_number']) &&
                !empty($_POST['contact_email']) &&
                !empty($_POST['contact_message'])
            ) {
                $name = $_POST['contact_name'];
                $dialcodes = $_POST['dialcodes'];
                $email = encrypt($_POST['contact_email']);
                $phone = encrypt($_POST['contact_mobile_number']);
                $message = $_POST['contact_message'];
                $plan = $_POST['optradio'];
                $dialphone = $dialcodes . $phone;

                $Contact_name = filter_var(
                    $_POST['contact_name'],
                    FILTER_UNSAFE_RAW
                );
                $Contact_email = filter_var(
                    $_POST['contact_email'],
                    FILTER_UNSAFE_RAW
                );
                $Contact_mobile = filter_var(
                    $_POST['contact_mobile_number'],
                    FILTER_UNSAFE_RAW
                );
                $Contact_Message = filter_var(
                    $_POST['contact_message'],
                    FILTER_UNSAFE_RAW
                );
                $dialcodes = filter_var($_POST['dialcodes'], FILTER_UNSAFE_RAW);
                $countryName = filter_var(
                    $_POST['countryName'],
                    FILTER_UNSAFE_RAW
                );

                $get_country = explode(' (', $countryName);
                $Contact_country = $get_country[0];

                $Body = 'Hi,' . $Contact_Message;
                $sender = 'sales@taxiappz.com';
                $myfile_read = fopen('mail_count.php', 'r');
                $m = fgets($myfile_read);
                fclose($myfile_read);
                $myfile_write = fopen('mail_count.php', 'w');
                $s = strval($m + 1);
                $r = fwrite($myfile_write, $s);
                //echo $s;
                fclose($myfile_write);

                $Body =
                    '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="utf-8">
            <!--  This file has been downloaded from https://bootdey.com  -->
            <!--  All snippets are MIT license https://bootdey.com/license -->
            <title>Bootdey.com</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.livechatinc.com/tracking.js"></script>
            <style type="text/css">
                /* -------------------------------------
            GLOBAL
            A very basic CSS reset
        ------------------------------------- */
                * {
                    margin: 0;
                    padding: 0;
                    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                    box-sizing: border-box;
                    font-size: 14px;
                }
        
                img {
                    max-width: 100%;
                }
        
                body {
                    -webkit-font-smoothing: antialiased;
                    -webkit-text-size-adjust: none;
                    width: 100% !important;
                    height: 100%;
                    line-height: 1.6;
                }
        
                table td {
                    vertical-align: top;
                }
        
                /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
                body {
                    background-color: #f6f6f6;
                }
        
                .body-wrap {
                    background-color: #f6f6f6;
                    width: 100%;
                }
        
                .container {
                    display: block !important;
                    max-width: 600px !important;
                    margin: 0 auto !important;
                    /* makes it centered */
                    clear: both !important;
                }
        
                .content {
                    max-width: 600px;
                    margin: 0 auto;
                    display: block;
                    padding: 20px;
                }
        
                /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
                .main {
                    background: #fff;
                    border: 1px solid #e9e9e9;
                    border-radius: 3px;
                }
        
                .content-wrap {
                    padding: 20px;
                }
        
                .content-block {
                    padding: 0 0 20px;
                }
        
                .header {
                    width: 100%;
                    margin-bottom: 20px;
                }
        
                .footer {
                    width: 100%;
                    clear: both;
                    color: #999;
                    padding: 20px;
                }
        
                .footer a {
                    color: #999;
                }
        
                .footer p,
                .footer a,
                .footer unsubscribe,
                .footer td {
                    font-size: 12px;
                }
        
                /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
                h1,
                h2,
                h3 {
                    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
                    color: #000;
                    margin: 40px 0 0;
                    line-height: 1.2;
                    font-weight: 400;
                }
        
                h1 {
                    font-size: 32px;
                    font-weight: 500;
                }
        
                h2 {
                    font-size: 24px;
                }
        
                h3 {
                    font-size: 18px;
                }
        
                h4 {
                    font-size: 14px;
                    font-weight: 600;
                }
        
                p,
                ul,
                ol {
                    margin-bottom: 10px;
                    font-weight: normal;
                }
        
                p li,
                ul li,
                ol li {
                    margin-left: 5px;
                    list-style-position: inside;
                }
        
                /* -------------------------------------
            LINKS & BUTTONS
        ------------------------------------- */
                a {
                    color: #1ab394;
                    text-decoration: underline;
                }
        
                .btn-primary {
                    text-decoration: none;
                    color: #FFF;
                    background-color: #1ab394;
                    border: solid #1ab394;
                    border-width: 5px 10px;
                    line-height: 2;
                    font-weight: bold;
                    text-align: center;
                    cursor: pointer;
                    display: inline-block;
                    border-radius: 5px;
                    text-transform: capitalize;
                }
        
                /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
                .last {
                    margin-bottom: 0;
                }
        
                .first {
                    margin-top: 0;
                }
        
                .aligncenter {
                    text-align: center;
                }
        
                .alignright {
                    text-align: right;
                }
        
                .alignleft {
                    text-align: left;
                }
        
                .clear {
                    clear: both;
                }
        
                /* -------------------------------------
            ALERTS
            Change the class depending on warning email, good email or bad email
        ------------------------------------- */
                .alert {
                    font-size: 16px;
                    color: #fff;
                    font-weight: 500;
                    padding: 20px;
                    text-align: center;
                    border-radius: 3px 3px 0 0;
                }
        
                .alert a {
                    color: #fff;
                    text-decoration: none;
                    font-weight: 500;
                    font-size: 16px;
                }
        
                .alert.alert-warning {
                    background: #f8ac59;
                }
        
                .alert.alert-bad {
                    background: #ed5565;
                }
        
                .alert.alert-good {
                    background: #1ab394;
                }
        
                /* -------------------------------------
            INVOICE
            Styles for the billing table
        ------------------------------------- */
                .invoice {
                    margin: 40px auto;
                    text-align: left;
                    width: 80%;
                }
        
                .invoice td {
                    padding: 5px 0;
                }
        
                .invoice .invoice-items {
                    width: 100%;
                }
        
                .invoice .invoice-items td {
                    border-top: #eee 1px solid;
                }
        
                .invoice .invoice-items .total td {
                    border-top: 2px solid #333;
                    border-bottom: 2px solid #333;
                    font-weight: 700;
                }
        
                /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
                @media only screen and (max-width: 640px) {
        
                    h1,
                    h2,
                    h3,
                    h4 {
                        font-weight: 600 !important;
                        margin: 20px 0 5px !important;
                    }
        
                    h1 {
                        font-size: 22px !important;
                    }
        
                    h2 {
                        font-size: 18px !important;
                    }
        
                    h3 {
                        font-size: 16px !important;
                    }
        
                    .container {
                        width: 100% !important;
                    }
        
                    .content,
                    .content-wrap {
                        padding: 10px !important;
                    }
        
                    .invoice {
                        width: 100% !important;
                    }
                }
            </style>
        </head>
        
        <body>
            <table class="body-wrap">
                <tbody>
                    <tr>
                        <td></td>
                        <td class="container" width="600">
                            <div class="content">
                                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td class="content-wrap aligncenter">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="content-block">
                                                                <h2>A New Demo Request From Client</h2>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block">
                                                                <table class="invoice">
                                                                    <tbody>                                                                        
                                                                        <tr>
                                                                            <td>
                                                                                <table class="invoice-items" cellpadding="0"
                                                                                    cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>Name</td>
                                                                                            <td class="alignright">' .
                    $Contact_name .
                    '</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Email</td>
                                                                                            <td class="alignright">
                                                                                                ' .
                    $Contact_email .
                    '</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Mobile Number</td>
                                                                                            <td class="alignright">' .
                    $dialcodes .
                    '' .
                    $Contact_mobile .
                    '
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Country</td>
                                                                                            <td class="alignright">
                                                                                                ' .
                    $Contact_country .
                    '</td>
                                                                                        </tr>
                                                                                       
                                                                                        <tr>
                                                                                            <td>Message</td>
                                                                                            <td class="alignright">
                                                                                                ' .
                    $Contact_Message .
                    '</td>
                                                                                        </tr>                                                                                        
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- <td class="content-block">
                                                 
                                            </td> -->
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="footer">
                                    <table width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="aligncenter content-block">Copyright © ' .
                    date('Y') .
                    ' <a
                                                        href="http://taxiappz.com/">Taxiappz</a> , All rights reserved.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        
            <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script type="text/javascript">
        
            </script>
        </body>
        
        </html>';

                $crlf = "\n";

                //----------------------------------

                $from = 'Taxiappz <sales@taxiappz.com>';
                //    $to       = "packiyaraj.nplus@gmail.com";
                $to = 'Sales <sales@taxiappz.com>';
                $subject = 'Lead #000' . $m . ' - ' . $Contact_name;
                $body = $Body;
                $host = 'ssl://smtp.gmail.com';
                $username = 'sales@taxiappz.com';
                $password = 'hdxvjqaycnqqbshw';
                //              $username = "testersmail2014@gmail.com";
                //              $password = "nplustech2014";
                $headers = [
                    'From' => $from,
                    'To' => $to,
                    'Subject' => $subject,
                    'MIME-Version' => 1,
                    'Content-type' => 'text/html;charset=iso-8859-1',
                ];
                $smtp = Mail::factory('smtp', [
                    'host' => $host,
                    'port' => '465',
                    'auth' => true,
                    'username' => $username,
                    'password' => $password,
                ]);

                if (
                    empty($_SESSION['nameErr']) &&
                    empty($_SESSION['emailErr']) &&
                    empty(
                    $_SESSION['phoneErr'] && empty($_SESSION['messageErr'])
                )
                ) {
                    $mail = $smtp->send($to, $headers, $body);
                }

                if (PEAR::isError($mail)) {
                    echo "<script>window.location.href='https://www.taxiappz.com/'</script>";
                } else {
                    echo "<script>window.location.href='thankyou'</script>";
                    // echo "<script>window.location.href='thankyou.php'</script>";
                }
            } else {
                echo "<script>alert('All Fields are required')</script>";
            }

            //------------------------------------------------------------
        }
    }
}

function encrypt($value)
{
    $encryptionKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnwr2aDHtAv9LDsokqm9uuimsa
i77TmA/olL0WiFWRqRjn50U7oUXeleYesc1l/fpPCI5KVkC/B+yOn/cNtygYEZ2O
JTi+NRc8G1Y3Bu+TYE/S8oyg4CETy09tivCv1v3yCx5AflgOGdYbhRsFRR97ydi8
+P4gRvwL9bETBGqrQwIDAQAB
-----END PUBLIC KEY-----";

    $store = '';

    openssl_public_encrypt($value, $store, $encryptionKey);

    return urlencode($store);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html class="html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/imgs/favicon.png" />
    <title>Uber Clone | Taxi Dispatch Software - TaxiAppz</title>
    <meta name="description" content="Expand your online taxi business by creating your own taxi app. Our specialization lies in crafting custom taxi dispatch software and apps for both native Android and iOS platforms, complemented by a web-based dispatch system." />
    <meta name="keywords" content= "taxi dispatch software, taxi dispatch system, create taxi app, build taxi app, taxi app development, uber clone, uber clone app" />
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Heebo:100%7COpen+Sans:300,400,400i,600,700,800"> -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="canonical" href="https://www.taxiappz.com/" />
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootsnav/css/bootsnav.css">
    <!-- <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="assets/vendor/alien-icon/css/style.css">
    <link rel="stylesheet" href="assets/vendor/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/vendor/switchery/switchery.min.css">
    <link rel="stylesheet" href="assets/css/alien.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="build/css/intlTelInput.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-bold-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-medium-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-regular-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-semibold-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-text-heavy-webfont.woff"> -->
    <!-- <link rel="preload" href="/wp-content/themes/Avada-Child-Theme/fonts/sfpro/sf-pro-text-heavy-webfont.woff" as="font" type="font/woff2" crossorigin=""> -->
    <!-- <link rel="preload" href="/wp-content/themes/Avada-Child-Theme/fonts/sfpro/sf-pro-display-semibold-webfont.woff2" as="font" type="font/woff2" crossorigin=""> -->

    <!-- Lazysize img-->
    <script src="assets/js/lazysizes.min.js" async></script>
    <!-- endinject -->
    <link rel="stylesheet" href="assets/vendor/slider/css/style.css">
    <!-- Resource style -->

    <!-- poppins css -->
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:600%2C300%2C700">

    <!-- Satoshi css -->
    <link rel="stylesheet" href="assets/fonts/satoshi/WEB/css/satoshi.css">
    <link rel="stylesheet" href="assets/fonts/satoshi/WEB/fonts/Satoshi-Regular.ttf">
    <link rel="stylesheet" href="assets/fonts/satoshi/WEB/fonts/Satoshi-Regular.woff">
    <link rel="stylesheet" href="assets/fonts/satoshi/WEB/fonts/Satoshi-Regular.eot">
    <link rel="stylesheet" href="assets/fonts/satoshi/WEB/fonts/Satoshi-Regular.woff2">

    <style>
        .carousel-inner img{
            border-radius: 10px;
        }
        .owl-carousel.owl-drag .owl-item {
            height: auto;
        }
        
        #owl-demo .item {
            margin: 10px
        }
        
        #owl-demo .item img {
            display: block;
            width: 100%;
            height: auto
        }
        
        .flag-option {
            display: inline-block;
            text-align: center
        }
        
        .flag-option a {
            display: block
        }
        /* img {
            max-width: 100%;
            height: auto
        } */
        
        .owl-pagination {
            display: block;
            margin-top: 50px;
        }
        
        .section-title {
            padding: 50px 0px;
        }
        
        .testimonial-section {
            padding-bottom: 50px;
            background: #e5e5e5;
        }
        
        .client-tes-one,
        .client-tes-two,
        .client-tes-three,
        .client-tes-four,
        .client-tes-five {
            text-align: center;
        }
        
        .client-tes-one img,
        .client-tes-two img,
        .client-tes-three img,
        .client-tes-four img,
        .client-tes-five img {
            cursor: pointer;
        }
        
        .owl-theme .owl-dots {
            display: block;
            width: 5%;
            margin: auto;
            margin-top: 15px;
            text-align: center;
        }
        .head{
            font-family: inherit;
            font-size: 1.9rem;
        }
        .owl-theme .owl-dots .owl-dot span,
        .owl-theme .owl-dots .owl-dot.active span {
            background-color: #e83939;
        }
        
        .our-clients-div .owl-nav,
        .our-clients-div .owl-dots {
            display: none;
        }
        
        .media p {
            margin-top: 5px;
        }
        
        .div-container .col-md-4 .u-MarginTop25 {
            padding: 15px 10px;
        }
        
        .div-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        .m-0{
            margin: 0 !important;
        }
        .div-container .col-md-4 .u-MarginTop25:hover {
            border: 1px solid #d6d6d6;
            box-shadow: 0px 0px 25px #d6d6d6;
        }
        .ready h5{
            font-size: 16px;
            font-family: inherit;
        }
        
        .ready h3{
            font-size: 22px;
            font-weight: 600;
            font-family: inherit;
        }
        .non-scroll p {
            font-size: 14px;
        }
        
        nav.navbar.bootsnav ul.nav>li>a {
            font-weight: bold !important;
            font-size: 15px !important;
        }
        
       
        
        
        .carousel-indicators .active {
            background-color: #e83939;
        }
        
        .carousel-indicators li {
            background-color: #e83939;
            border: 1px solid #e83939;
        }
        /* .item.our-client-scroll {
            display: inline-block;
        } */
        
        .item.our-client-scroll div.col-lg-12.col-xl-12.col-md-12.col-xs-12 {
            display: inline-block;
            padding: 0px;
            margin-bottom: 30px;
        }
        
        /* .u-PaddingTop70.wel-taxiappz {
            padding-top: 30px;
        }
         */
        /* .u-PaddingBottom70.wel-taxiappz {
            padding-bottom: 30px;
        } */

.lh-15{
    line-height: 1.5;
    font-weight: 500;
}
.mrl-40{
    margin: 0 40px !important;
}

        .popup-btn{
        position: fixed;
        width: 160px;
        left: -0.5%;
        padding-left:50px;
        top: 30%;
        z-index: 1000;
        padding: 1%;
        border-radius: 5px 5px 5px 5px;
        background: rgb(131,58,180);
        background: linear-gradient(90deg, rgba(131,58,180,1) 0%, rgba(246,7,7,1) 50%, rgba(252,176,69,1) 100%);
        transform-origin: 0% 0%;
        -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            transform: rotate(-90deg) translateX(-50%);
        }
        
        @media only screen and (max-width: 600px) {

            .popup-btn{
            position: fixed;
            width: 140px;
            height: 30px;
            left: 0.5%;
            padding-left:50px;
            top: 30%;
            z-index: 1000;
            padding: 1%;
            border-radius: 5px 5px 5px 5px;
            background: rgb(131,58,180);
            background: linear-gradient(90deg, rgba(131,58,180,1) 0%, rgba(246,7,7,1) 50%, rgba(252,176,69,1) 100%);
            transform-origin: 0% 0%;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            transform: rotate(-90deg) translateX(-50%);
            }


            .item.our-client-scroll div.col-md-6.col-xl-3.col-lg-3.col-xs-6.col-sm-6,
            .item.our-client-scroll div.col-md-6.col-xl-4.col-lg-4.col-xs-6.col-sm-6 {
                /* border: 1px solid #f5f5f5; */
                margin-bottom: 10px;
                margin-top: 10px;
            }
            .item.our-client-scroll div.col-md-12 {
                padding: 0px;
            }
            .item.our-client-scroll div.col-lg-12.col-xl-12.col-md-12.col-xs-12 {
                display: inline-block;
                padding: 0px;
                margin-bottom: 0px;
            }
            /* .carousel-indicators {
                top: 210px;
            } */
            .div-container {
                margin-top: 0px;
            }
            .div-container .col-md-4 .u-MarginTop25 {
                padding: 0px;
            }
            .div-container .u-MarginTop25 {
                margin-top: 10px;
            }
            .section-title {
                padding: 0px;
            }
            div.Heading.join_head div.u-MarginTop40 {
                margin-top: 0px;
            }
            #accordion8.u-MarginTop50 {
                margin-top: 10px;
            }
            div.wel-taxiappz p.u-LineHeight2.u-MarginBottom50 {
                margin-bottom: 0px;
            }
            div.wel-taxiappz .col-md-6.col-md-offset-1.col-sm-10.col-sm-offset-1.col-xs-8.col-xs-offset-2.u-sm-MarginTop50,
            div.our-clients-div div.col-md-12.col-sm-12.u-MarginTop40,
            .our-client-heading.u-MarginTop35 {
                margin-top: 0px;
            }
           
        }
        .bg-gray{
                background-color: #dddddd;
            }
        

    </style>


<style>
    #mySidenav a {
      position: absolute;
      left: -80px;
      transition: 0.3s;
      padding: 15px;
      width: 100px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      color: white;
      border-radius: 0 5px 5px 0;
    }

    #mySidenav a:hover {
      left: 0;
    }
    
    #about {
      top: 20px;
      background-color: #04AA6D;
    }

    .tab1 button {
        display: block;
        background-color: inherit;
        color: black;
        padding-right: 20px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        border-radius: 10px;
        float: left;
    }

    .tab1 button:hover {
        box-shadow: 0 10px 15px 0 rgb(0 0 0 / 6%);
        transition: .4s;
        box-shadow: 0 10px 15px 0 rgb(0 0 0 / 6%);
        border-left: 5px solid #b10507;
        transition: .4s;
    }

    .ulist {
        padding: 0;
        margin: 0;
        display: block;
        list-style-type: none
    }
    .list {
    display:list-item;
    width: 100%; /* sometimes firefox gives issue*/
    word-break: break-all; /* break word in next line, if no space*/
    }
    .taxi_booking{
        color: #b10507;
        font-weight:600;
    }

    /* @media only screen and (max-width: 600px) {
    .img1 img{
        width:60% !important;
    }
    } */

    @media only screen and (max-width: 600px) {
    .img2 img{
        width:70% !important;
    }
}
    @media only screen and (min-width: 1200px) {
    .d-flex-end{
    display: flex;
    align-items: end;
    }
}

    @media only screen and (max-width: 600px) {
    .img3 img{
        width:90% !important;
    }
    }

    /* @media only screen and (max-width: 600px) {
    div.txts {
        align-text:center !important;
    }
    }

    @media only screen and (max-width: 500px) {
    div.texts {
        height:800px
    }
    } */

    /* @media only screen and (max-width: 600px) {
    div.text1 {
        height:700px
    }
    } */
/* 
    @media only screen and (max-width: 500px) {
    div.txt1 {
        height:200px
    }
    } */
    
    .pricing_gn{
        background: #b10507;
        border: 2px solid #b10507;
        color: #ffffff;
    }
    .Home{
        width: 75% !important;
        margin-top: 47px;
    }
    </style>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-10811920151"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}  
  gtag('js', new Date());

  gtag('config', 'AW-10811920151');
</script>

    <!-- Smartsupp Live Chat script -->
        <script type="text/javascript">
        var smartsupp = smartsupp || {};
        _smartsupp.key = 'ede1c9cdfd9fe4792fdf952da007b4b624d05309';
        window.smartsupp || (function (d) {
            var s, c, o = smartsupp = function () { o._.push(arguments) }; o._ = [];
            s = d.getElementsByTagName('script')[0]; c = d.createElement('script');
            c.type = 'text/javascript'; c.charset = 'utf-8'; c.async = true;
            c.src = 'https://www.smartsuppchat.com/loader.js?'; s.parentNode.insertBefore(c, s);
        })(document);
    </script>
    <!-- <noscript> Powered by <a href=“https://www.smartsupp.xn--com-9o0a target=“_blank”>Smartsupp</a></noscript> -->



</head>

<body>
    <!-- <div class="se-pre-con"></div> -->

     <!--header start-->
     <header>
        <!-- Start Navigation -->
        <nav class="navbar navbar-default navbar-sticky bootsnav">

            <!-- Start Top Search -->
            <!-- <div class="top-search">
                <div class="container">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                        <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                    </div>
                </div>
            </div> -->
            <!-- End Top Search -->

            <div class="container">
                <!-- Start Atribute Navigation -->
                <!-- <div class="attr-nav">
                    <ul>
                        <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                    </ul>
                </div> -->
                <!-- End Atribute Navigation -->

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars icon-font"></i>
                    </button>
                    <a class="navbar-brand" href="https://www.taxiappz.com/"><img src="assets/imgs/logo.png" class="logo logo-scrolled lazyload" alt=""></a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="" data-out="">
                        <li class="active"><a href="https://www.taxiappz.com/">Home</a></li>
                        <li><a href="features">Features</a></li>
                        <li><a href="pricing">Pricing</a></li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            Our Products
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="food-ordering-and-delivery-system"> <img src="assets/imgs/food-ordering.svg" width="20px" alt="taxi booking">&nbsp;Food Ordering and Delivery System</a>
                            </div>
                        </li> -->
                        <li><a href="portfolio">Portfolio</a></li>
                        <li><a href="contact">Contact Us</a></li>
                        <li>
                            <a class="demo_btn" href="request-demo">
                                <span>
                                   Request Demo
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>

            <!-- End Side Menu -->
        </nav>
        <!-- End Navigation -->
        <div class="clearfix"></div>
    </header>
    <!--header end-->


    <section class="cd-hero js-cd-hero js-cd-autoplay">
        <div class="container">
            <div class="row">
                <div class="cd-hero__slider ">
                    <div class="cd-hero__slide cd-hero__slide--selected js-cd-slide ">
                    <!-- <ul class="cd-hero__slider">
                        <li class="cd-hero__slide cd-hero__slide--selected js-cd-slide"> -->
                            <div class="col-9 Home cd-hero__content cd-hero__content--half-width animate_left">
                                
                                <h3 class="u-Weight700 head mrl-40">A Cutting-Edge Solution for Taxi Booking, Scheduling, and Dispatching. Build Your App in Just 5 Days!</h3>
                                <h5 class="u-Weight500 fs-125 mrl-40" style="font-style: normal;">Save time, reduce costs, and take control of your business with your own taxi dispatch software.</h5>
                                <ul class="text-left ulist fs-125 mrl-40" style="list-style-type:disc;padding-left:5%;">
                                    <li class="list">Most advanced support system</li>
                                    <li class="list">Easy-to-use booking & tracking modules</li>
                                    <li class="list">Simple & quick to install</li>
                                    <li class="list">Quick accessible & fast-loading</li>
                                </ul><br>
                                <div class="txt mrl-40">
                                    <a href="" class=" pricing_btn " data-toggle="modal" data-target="#contact-modal">Try Demo</a>
                                </div>
                                
                            </div>
                            <div class="col-3">
                                <div class="cd-hero__content cd-hero__content--img" style="width: 75%;">
                                <!-- <img src="assets/imgs/demo/admin.png" class="animate_1 lazyload" alt="tech 1"> -->
                                    <img src="assets/imgs/ihpone.png" class="animate_2 lazyload" alt="techdsadas 1">
                                    <img src="assets/imgs/ipad.png" class="animate_3 lazyload" alt="tech 1">
                                </div>
                            </div>
                        <!-- .cd-hero__content -->
                        </div>
                        <!-- </li>
                </ul> -->
                </div>
            </div>
        </div>
        <!-- .cd-hero__slider -->

    </section>
    <!-- .cd-hero -->

    <!--short start-->

    <div class="popup-btn">
        <a href="" data-toggle="modal" data-target="#contact-modal" style="color:#fff; font-weight:bold;">Get a Free Quote</a>
    </div>
    <div id="contact-modal" class="modal fade " role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding:0px !important; padding-left:15px !important;">
                    <a class="close" style="padding:10px !important;" data-dismiss="modal">×</a>
                    <h4>GET A FREE REQUEST DEMO</h4>
                </div>
                <form  action=""  method="post" onsubmit="return validateForm()">
                    <div class="modal-body">				
                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="name" style="font-size:16px;">Name <span style="color:#ff0000;">*</span></label>
                            <input type="text"  style="color:black !important; height:30px;" placeholder="Name" name="contact_name" data-error="You must enter name" required class="form-control">
                            <?php if (isset($_SESSION['nameErr'])) { ?>
                                    <div class="help-block with-errors"><?= $_SESSION[
                                        'nameErr'
                                    ] ?></div>
                                <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="email" style="font-size:16px;">Email <span style="color:#ff0000;">*</span></label>
                            <input type="email"  style="color:black !important;  height:30px;" name="contact_email" class="form-control" placeholder="Email" data-parsley-required="true" required>
                            <?php if (isset($_SESSION['emailErr'])) { ?>
                                    <div class="help-block with-errors"><?= $_SESSION[
                                        'emailErr'
                                    ] ?></div>
                                <?php } ?>
                        </div>

                        <input id="dialcodes" name="dialcodes" type="hidden" value="">
                        <input id="countryName" name="countryName" type="hidden" value="" >


                        <div class="form-group" style="margin-bottom:10px;">
                        
                            <label for="name" style="font-size:16px;">Mobile Number <span style="color:#ff0000;">*</span></label>
                            <input id="phone"  class="form-control" onkeypress="return isNumberKey(event)" name="contact_mobile_number" type="tel" onchange="getNumber()" >
                                <?php if (isset($_SESSION['phoneErr'])) { ?>
                                        <div class="help-block with-errors"><?= $_SESSION[
                                            'phoneErr'
                                        ] ?></div>
                                        <?php } ?>
                        </div>

                        
                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="message" style="font-size:16px; ">Message <span style="color:#ff0000;">*</span></label>
                            <textarea  placeholder="Enter Your Message" rows="2" cols="3" name="contact_message" data-parsley-required="true" required  style="color:black !important;  min-height:30px;" class="form-control"></textarea>
                            <?php if (isset($_SESSION['messageErr'])) { ?>
                                        <div class="help-block with-errors"><?= $_SESSION[
                                            'messageErr'
                                        ] ?></div>
                                        <?php } ?>
                        </div>	
                        
                        <div class="form-group" style="margin-bottom:10px;">
                    <div class="g-recaptcha" data-sitekey="6LfOyCkUAAAAAH-vwFiO6WYFXg1prSnFheMAlffe" data-callback="verifyCaptcha"></div>
                        <div id="g-recaptcha-error" class="contact-info"></div>
                    </div>
                    </div>

                

                    <div class="modal-footer">		
                        <button type="submit" name="form_submit" onclick="myAction()" class="pop_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <br><br><br><br><br> -->

    <section class="u-PaddingTop10 u-PaddingBottom20 u-xs-PaddingTop50 u-xs-PaddingBottom20 " style="padding-bottom: 15px;">
        <div class="container ">
            <!-- <div class="row text-center u-MarginTop35 our-client-heading ">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
                    <div class="Heading" data-title="Our">
                        <div class="Split Split--height2 u-MarginBottom5"></div>
                        <h1 class="u-Weight700 u-Margin0 u-PaddingBottom20">Our Clients</h1>
                    </div>
                </div>
            </div> -->
            <div class="row our-clients-div bg-gray">
                <div class="col-md-12 col-sm-12 u-MarginTop40">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="2000">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner u-MarginBottom10">
                            <div class="item our-client-scroll  active">
                                <div class="col-md-12">
                                    <div class="col-lg-12 col-xl-12 col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/on-taxi.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_11.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/fast-taxi.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/u-taxi.png" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item our-client-scroll">
                                <div class="col-md-12">
                                    <div class="col-lg-12 col-xl-12 col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_2.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/sahl.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_7.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/allocab.png" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="item our-client-scroll">
                                <div class="col-md-12">
                                    <div class="col-lg-12 col-xl-12 col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_1.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/imove.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/didi.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/iTaxi.png" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="item our-client-scroll">
                                <div class="col-md-12">
                                    <div class="col-lg-12 col-xl-12 col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/obr.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/cabonline.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/nasaro.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/fixutaxi.png" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        
                            <div class="item our-client-scroll">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom: 20px;">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/zawar.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/start-go.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/Tappz.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/bentux.png" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="item our-client-scroll">
                                <div class="col-md-12">
                                    <div class="col-lg-12 col-xl-12 col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_3.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_5.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/japantaxi.png" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive lazyload" src="assets/imgs/client/client_10.jpg" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="item our-client-scroll">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom: 20px;">
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_8.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_1.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_10.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-3 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_11.jpg" alt="...">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-lg-10 col-lg-offset-1">
                                        <div class="col-md-6 col-xl-4 col-lg-4 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_7.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-4 col-lg-4 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_6.jpg" alt="...">
                                        </div>
                                        <div class="col-md-6 col-xl-4 col-lg-4 col-xs-6 col-sm-6 float-left">
                                            <img class="img-responsive" src="assets/imgs/client/client_5.jpg" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <!-- Indicators -->
                        <div claSS="row">
                            <ol class="carousel-indicators u-MarginTop10">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li data-target="#myCarousel" data-slide-to="1"></li>
                                <li data-target="#myCarousel" data-slide-to="2"></li>
                                <li data-target="#myCarousel" data-slide-to="3"></li>
                                <li data-target="#myCarousel" data-slide-to="4"></li>
                                <li data-target="#myCarousel" data-slide-to="5"></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    
    <section class="bg-white u-PaddingTop10 u-PaddingBottom10 u-PaddingLeft30 ">
        <div class="container">
            <div class="row u-MarginTop10" style="margin-top: 50px;margin-bottom:30px">
                <div class="col-lg-3  col-sm-6  u-MarginBottom10">
                    <div class="Blurb bg-light align-items-justify">
                        <div class="u1-FlexCenter">
                            <div class="u-PaddingRight25">
                                <img src="assets/imgs/icon/taxi.png" alt="" height="42" class="lazyload">
                            </div>
                            <div class="u-PaddingTop10 ready">
                                <h3 class="Blurb__hoverText u-Margin0">Ready</h3>
                                <h5 class="u-LetterSpacing1 start_text u-MarginTop10 mt-2" >Get your taxi app ready.</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 u-MarginBottom10">
                    <div class="Blurb bg-light align-items-justify">
                        <div class="u1-FlexCenter">
                            <div class="u-PaddingRight25 ">
                                <img src="assets/imgs/icon/mobile-phone.png" alt="" class="lazyload" height="42">
                            </div>
                            <div class="u-PaddingTop10 ready">
                                <h3 class="Blurb__hoverText u-Margin0">Steady</h3>
                                <h5 class="u-LetterSpacing1 start_text u-MarginTop10 mt-2">Take mobile app taxi bookings.</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 u-MarginBottom10">
                    <div class="Blurb bg-light align-items-justify">
                        <div class="u1-FlexCenter">
                            <div class="u-PaddingRight25">
                                <img src="assets/imgs/icon/location.png" class="lazyload" alt="" height="42">
                            </div>
                            <div class="u-PaddingTop10 ready">
                                <h3 class="Blurb__hoverText u-Margin0 ">Go &amp; Start</h3>
                                <h5 class="u-LetterSpacing1 start_text u-MarginTop10 mt-2" >Maximize your taxi business profits.</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-3 col-sm-6 u-MarginBottom10">-->
                    <div class="Shortcode-button u-MarginTop30"> 
                        <a href="contact" class="u-PaddingTop50 pricing_btn ">Lets Talk</a>
                     </div>
               <!-- </div> -->
            </div>
        </div>
    </section>


    <section class="cd-hero js-cd-hero js-cd-autoplay">
        <div class="container">
            <div class="row d-flex-end">
                <div class="col-lg-6 col-md-4"> 
                    <div class="w-100 img1">
                        <!-- <img src="assets/imgs/demo/admin.png" class="animate_1 lazyload" alt="tech 1"> -->
                        <img src="assets/imgs/demo/admin.png" heigth="auto" width="100%">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12" >
                    <div><h2 class=" u-Weight600">TaxiAppz - Your All-In-One Taxi Dispatch Platform</h2></div>
                    <div class="text-left ulist fs-125 mrl-40" >
                        <h5 class="lh-15 fs-125" style="margin-bottom: 15px;">Unlock greater creative rewards with our end-to-end taxi app solution, perfect for both startups and experienced entrepreneurs. With our taxi dispatch platform, you can have your own branded taxi booking app in just 7 days. Your customers can easily book, rent, or share nearby taxis with a few taps on their smartphones.</h5>
                        <h5 class="lh-15 fs-125" style="margin-bottom: 15px;">We'll quickly set up and customize the app with your company's name, logo, color scheme, preferred languages, currencies, SMS, and payment gateways. You'll start earning commissions from day one when customers use the app.</h5>
                        <h5 class="lh-15 fs-125">Since 2016, we've assisted taxi companies in launching taxi dispatch software, making us experts in the industry. Our taxi app clone is the most advanced on the market, incorporating industry-specific features. You can modernize your taxi business in just one week with a ready-made app and transition into a contemporary ride-hailing platform.</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class=" bg-white">
        <div class="container pt-5">
            <div class="row text-center">
                <div class="col-md-12  col-md-offset-0 col-sm-10 col-sm-offset-1 text-center u-MarginTop10">
                    <div class="Heading" data-title="See what the app can do">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div><br>
                        <h2 class="text-uppercase u-Weight700 u-Margin0" >See what the app can do for your business</h2>
                    </div><br>
                    <div class="text-center"><h5 class="fs-125 u-Margin0">Our service offers a revenue-boosting taxi dispatch platform with a carefully designed script, enabling efficient driver route management, real-time tracking, and region-specific promotions.</h5></div>
                </div>
            </div>
            <!-- <div class="row u-sm-Block u-MarginTop10"> -->
            <div class="col-md-12 div-container">
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-left u-OverflowVisible">
                            <div class="media-body text-center non-scroll">   
                                <h4 class="u-MarginTop0 u-MarginBottom15 ">Easy Onboarding</h4>
                                <div class="text-md"><h5 class="text-center fs-125">New users can easily join using email or phone numbers. Easy installation and user-friendly interface simplify the driving experience.</h5></div>
                            </div> 
                            <!-- <div class="media-right u-Paddingleft25  text-right u-Width65 ">
                                <p><i class="Icon Icon-rocket Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-right u-OverflowVisible">
                            <!-- <div class="media-left u-PaddingRight25 u-Width65 ">
                                <p><i class="Icon Icon-browser Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                            <div class="media-body non-scroll">
                                <h4 class="u-MarginTop0 text-center u-MarginBottom15">Quick Bookings</h4>
                                <div class="text-md"><h5 class="text-center fs-125">Our app provides a seamless, real-time booking experience with no waiting time or booking errors, making it incredibly convenient for users. </h5></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
                <!-- <div class="row u-FlexCenter u-sm-Block u-MarginTop10"> -->
                <!-- <div class="col-md-4 col-md-push-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 u-sm-MarginBottom60">
                <img class="img-responsive" src="assets/imgs/mobile-parallal-600.png" alt="...">
             </div> -->
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-left  u-OverflowVisible">
                            <div class="media-body text-center non-scroll">
                                <h4 class="u-MarginTop0 u-MarginBottom15">Ride Scheduling</h4>
                                <div class="text-md"><h5 class="text-center fs-125">Schedule rides for future dates, times, and locations effortlessly. Plan ahead without missing important events using easy pre-booking steps.</h5></div>
                            </div>
                            <!-- <div class="media-right u-Paddingleft25  text-right u-Width65 ">
                                <p><i class="Icon Icon-strategy Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-right u-OverflowVisible">
                            <!-- <div class="media-left u-PaddingRight25 u-Width65 ">
                                <p><i class="Icon Icon-mobile Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                            <div class="media-body non-scroll">
                                <h4 class="u-MarginTop0 text-center u-MarginBottom15">Ride Details</h4>
                                <div class="text-md"><h5 class="text-center fs-125">After a successful booking, passengers receive all ride details at their fingertips, including pickup time, drop-off location, travel duration, and fare information.</h5></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
                <!-- <div class="row u-sm-Block u-MarginTop10"> -->
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-left u-OverflowVisible">
                            <div class="media-body text-center non-scroll">
                                <h4 class="u-MarginTop0 u-MarginBottom15">Seamless Payments</h4>
                                <p class="text-md"><h5 class="text-center fs-125">Choose from multiple payment options for easy and hassle-free ride payments, whether it's cash or credit/debit cards, all without transaction issues.</h5></div>
                            </div>
                            <!-- <div class="media-right u-Paddingleft25  text-right u-Width65 ">
                                <p><i class="Icon Icon-rocket Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                        </div>
                    </div>
                <div class="col-md-4 float-left text-center">
                    <div class=" u-MarginTop25">
                        <div class="media-right u-OverflowVisible">
                            <!-- <div class="media-left u-PaddingRight25 u-Width65 ">
                                <p><i class="Icon Icon-browser Icon--32px " aria-hidden="true "></i></p>
                            </div> -->
                            <div class="media-body non-scroll">
                                <h4 class="u-MarginTop0 u-MarginBottom15">Real-Time Tracking</h4>
                                <div class="text-md"><h5 class="text-center fs-125">Taxiappz provides GPS tracking and estimated time of arrival (ETA) for drivers and passengers guaranteeing an efficient experience.</h5></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-right u-OverflowVisible">
                            <!-- <div class="media-left u-PaddingRight25 u-Width65 ">
                                <p><i class="Icon Icon-mobile Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                            <div class="media-body non-scroll">
                                <h4 class="u-MarginTop0 text-center u-MarginBottom15">Wallet System</h4>
                                <div class="text-md"><h5 class="text-center fs-125">Taxiappz provides a Wallet feature that allows passengers to make payments, for their rides using credits or loyalty points seamlessly integrated with payment gateways.</h5></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
                <!-- <div class="row u-sm-Block u-MarginTop10"> -->
                <div class="col-md-4 float-left">
                    <div class="u-MarginTop25">
                        <div class="media-left u-OverflowVisible">
                            <div class="media-body text-center non-scroll">
                                <h4 class="u-MarginTop0 u-MarginBottom15">Source Code & Customization</h4>
                                <div class="text-md"><h5 class="text-center fs-125">What sets us apart? When you buy the app, you gain source code access for easy customization in your Backend Admin Panel and mobile apps at any time.</h5></div>
                            </div>
                            <!-- <div class="media-right u-Paddingleft25  text-right u-Width65 ">
                                <p><i class="Icon Icon-rocket Icon--32px" aria-hidden="true"></i></p>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 float-left text-center">
                    <div class=" u-MarginTop25">
                        <div class="media-right u-OverflowVisible">
                            <!-- <div class="media-left u-PaddingRight25 u-Width65 ">
                                <p><i class="Icon Icon-browser Icon--32px " aria-hidden="true "></i></p>
                            </div> -->
                            <div class="media-body non-scroll">
                                <h4 class="u-MarginTop0 u-MarginBottom15">Ratings & Reviews</h4>
                                <p class="text-md"><h5 class="text-center fs-125">Customers can freely share feedback, reviews, and ratings after every trip without limitations on expressing positivity or negativity, ensuring a smooth process.</h5></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <!-- </div> -->
        </div>

        <div class="col-md-12 u-MarginBottom10" style="display:auto">
            <div class="Shortcode-button u-MarginTop10 text-center" style="margin-top: 25px;">
            <a href="features" class="u-PaddingTop50 pricing_btn fs-125"><b>View All Features</b></a>
            </div>
        </div>
    </section>
    <!--banner end-->


    <section>
        <div class="container">
            <div class="portfolio_screens" style="border-bottom-width: 0px;margin-bottom: 0px;">
                <div class="row">
                    <div class="Heading text-center" data-title="What Do You Get" >
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5" ></div><br>
                        <h2 class="text-uppercase u-Weight700 text-center">What Do You Get With Our Taxi Dispatch Solution?</h2>
                    </div>
                    <div class="col-md-8 text-center">
                        <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                    </div>
                </div>
                <div class="container" style=" margin-right: auto;margin-left:20px;">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row tab1" id="tabs1">
                                <button id="tab1" class="tablinks active">
                                <h3 class="u-MarginBottom5 u-MarginTop20 taxi_booking">Taxi Booking App</h3>
                                <h5 class="fs-125 u-MarginTop0 u-MarginBottom20">Bring taxis to your users' doors with only a few clicks. With iOS/Android mobile applications, you may reach a large user base.</h5>
                            </div>
                            <div class="row tab1" id="tabs2">
                                <button id="tab2" class="tablinks ">
                                <h3 class="u-MarginBottom5 u-MarginTop20 taxi_booking">Driver App</h3>
                                <h5 class="fs-125 u-MarginTop0 u-MarginBottom20">Allow drivers to accept/reject rides and leverage opportunities to earn big bucks etc., with driver apps.</h5>
                            </div>
                            <div class="row tab1" id="tabs3">
                                <button class="tablinks " style="margin-bottom:4px">
                                <h3 class="u-MarginBottom5 u-MarginTop20 taxi_booking">Admin Panel</h3>
                                <h5 class="fs-125 u-MarginTop0 u-MarginBottom20">Our interactive admin panel empowers you to manage every aspect of your taxi business, from customers to service providers.</h5>
                            </div>
                            <div class="row tab1" id="tabs4">
                                <button class="tablinks " style="margin-bottom:4px">
                                <h3 class="u-MarginBottom5 u-MarginTop20 taxi_booking">Dispatcher Panel</h3>
                                <h5 class="fs-125 u-MarginTop0 u-MarginBottom20">A vibrant dashboard for automatically managing your taxi fleet, drivers, and cab dispatching.</h5>
                            </div>
                            <div class="row tab1" style="margin-top:5%">
                            <a href="" class=" pricing_btn " data-toggle="modal" data-target="#contact-modal">Request Demo</a>
                                <!-- <a href="request-demo" class="pricing_btn">Request Demo</a> -->
                            </div>
                        </div>
                        <div class="col-md-4 text-center mob-res-photo img3" style="display:inline ">
                            <div id="img1">
                                <img src="assets/img/users_app.png" style="padding-top:30px;padding-right:10px;padding-left:10px;padding-bottom:10px" alt="">
                            </div>
                            <div id="img2" style="display:none">
                                <img src="assets/img/driver_app.png" style="padding-top:30px;padding-right:10px;padding-left:10px;padding-bottom:10px" alt="">
                            </div>
                            <div id="img3" style="display:none">
                                <img src="assets/img/admin_panel.png" style="padding-top:70px;padding-right:10px;padding-left:10px;padding-bottom:10px" alt="">
                            </div>
                            <div id="img4" style="display:none">
                                <img src="assets/img/dispatcher_panel.png" style="padding-top:70px;padding-right:10px;padding-left:10px;padding-bottom:10px" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- portfolio_grid -->
    <section class="u-PaddingTop30 u-PaddingBottom30 mobile-portfolio ">
        <div class="container">
            <div class="row section-title" style="padding-top: 0px;">
                <div class="col-md-12 col-xs-12 text-center">
                    <div class="Heading join_head" data-title="Join The On-demand Revolutions">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h2 class="text-uppercase u-Weight700 u-Margin0">JOIN THE ON-DEMAND REVOLUTIONS OF TAXI APPZ MOBILE SOLUTIONS.</h2>
                    </div>
                    <div class="row ">
                        <h4 class="text-center fs-125">Double Your Revenue by Connecting To The World With The Best-in-Market Solutions</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class=" js-Portfolio portfolio-grid gutter">
                <div class="row" style="max-height:200px;">
                    <div class="col-xs-6 col-md-2 col-sm-6 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-del.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content fonts" style="font-size:28px">
                                    <h3>Delivery Services</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2 col-sm-6 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-ecom.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content fonts" style="font-size:28px">
                                    <h3>Ecommerce</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 col-sm-12  portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-medical.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content fonts" style="font-size:28px">
                                    <h3>Medical Services</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-4 col-sm-6 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-limo.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content fonts" style="font-size:28px">
                                    <h3>limo Services</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-4 col-sm-6 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-hailing.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content" style="font-size:28px">
                                    <h3>Taxi Hailing</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-homecare.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content" style="font-size:28px">
                                    <h3>Home Care Services</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2 col-sm-6 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-fieldservice.jpg" class="img-responsive lazyload">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content fonts" style="font-size:28px">
                                    <h3>Field Service</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2 col-sm-6 portfolio-item pd_5">
                        <div class="portfolio-image popup-gallery" title="We are creative">
                            <img src="assets/imgs/srvc-img-towing.jpg" class="img-responsive">
                            <div class="portfolio-hover-title">
                                <div class="portfolio-content fonts" style="font-size:28px">
                                    <h3>Towing Services</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="u-PaddingTop70 u-PaddingBottom20 u-xs-PaddingTop20 u-xs-PaddingBottom20">
        <div class="container">
            <div class="row u-MarginBottom20 u-xs-MarginBottom50 text-center">
                <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1">
                    <div class="Heading" data-title="Choose us">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h2 class="text-uppercase u-Weight700 u-Margin0">MORE REASONS TO CHOOSE US</h2>
                    </div>
                </div>
            </div>
            <div class="row u-FlexCenter u-sm-Block">
                <div class="col-md-4 col-md-push-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 u-sm-MarginBottom30 u-xs-MarginBottom0">
                    <img class="img-responsive lazyload" src="assets/imgs/mobile-parallal1-600.png" alt="...">
                </div>
                <div class="col-md-4 col-md-pull-4 col-sm-6 col-xs-12">
                    <div class="panel-group u-PaddingRight20 u-sm-PaddingRight0 text-right text-left--xs u-MarginTop50" id="accordion7">
                        <div class="panel panel-shadow">
                            <div class="panel-heading" id="heading7-1">
                                <div class="panel-title text-left">
                                    <a role="button"data-toggle="collapse" data-parent="#accordion7" href="#collapse7-1" aria-expanded="true" aria-controls="collapse7-1">
                                        All in one solution
                                    </a>
                                </div>
                            </div>
                            <div id="collapse7-1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading7-1">
                                <div class="panel-body text-left">
                                    <h5 class="fs-125 u-Margin0">Taxiappz automates everything from booking to billing, dispatching to ratings,helps customer management, accounting and more.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-shadow">
                            <div class="panel-heading" role="tab" id="heading7-2">
                                <div class="panel-title text-left">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion7" href="#collapse7-2" aria-expanded="false" aria-controls="collapse7-2">
                                        Social sharing & Ratings
                                    </a>
                                </div>
                            </div>
                            <div id="collapse7-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading7-2">
                                <div class="panel-body text-left">
                                   <h5  class="fs-125 u-Margin0">Passengers and drivers can share ride experiences with friends and families through social media platform. And Rate his driver and Passenger in the trip ends.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-shadow">
                            <div class="panel-heading" role="tab" id="heading7">
                                <div class="panel-title text-left">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion7" href="#collapse7-3" aria-expanded="false" aria-controls="collapse7-3">
                                        Multiple Payment Options 
                                    </a>
                                </div>
                            </div>
                            <div id="collapse7-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading7-3">
                                <div class="panel-body text-left">
                                <h5  class="fs-125 u-Margin0">Let your passengers pay, the way they want. Either cash or credit card once they reach the destination. More payment options can be added with little customization</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="panel-group u-PaddingRight20 u-sm-PaddingRight0 text-left u-MarginTop50" id="accordion8">
                        <div class="panel panel-shadow text-left">
                            <div class="panel-heading" id="heading8-1">
                                <div class="panel-title" >
                                    <a role="button" data-toggle="collapse" data-parent="#accordion8" href="#collapse8-1" aria-expanded="true" aria-controls="collapse8-1">
                                        Localization
                                    </a>
                                </div>
                            </div>
                            <div id="collapse8-1" class="panel-collapse collapse  in" role="tabpanel" aria-labelledby="heading8-1">
                                <div class="panel-body text-left">
                                <h5  class="fs-125 u-Margin0">Taxiappz solution supports multiple languages, currencies, payment gateways and promo codes for discounts all over the world</h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-shadow">
                            <div class="panel-heading" role="tab" id="heading8-2">
                                <div class="panel-title text-left">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion8" href="#collapse8-2" aria-expanded="false" aria-controls="collapse8-2">
                                        On-Demand Taxi solution
                                    </a>
                                </div>
                            </div>
                            <div id="collapse8-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading8-2">
                                <div class="panel-body text-left">
                                <h5  class="fs-125 u-Margin0">We give on-demand taxi solution so that you can customize total dispatching settings, driver permissions, billing preferences, website and mobile app layouts & add extra features.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-shadow">
                            <div class="panel-heading" role="tab" id="heading8">
                                <div class="panel-title ">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion8" href="#collapse8-3" aria-expanded="false" aria-controls="collapse8-3">
                                        E-billing & Invoicing
                                    </a>
                                </div>
                            </div>
                            <div id="collapse8-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading8-3">
                                <div class="panel-body text-left">
                                <h5  class="fs-125 u-Margin0">Once the trip and its payment are completed, passenger gets e-bill and invoice to its registered email id for future references</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--accordion end-->

    <div class="col-md-12 u-MarginBottom10" style="display:contents">
        <div class="Shortcode-button text-center" style="margin-top: 25px;">
        <a href="" class=" pricing_btn " data-toggle="modal" data-target="#contact-modal">Request Demo</a>
        </div>
    </div>
    
    <!--welcome start-->
    <section class=" u-BoxShadow40 u-PaddingTop70 wel-taxiappz u-PaddingBottom70 u-xs-PaddingTop20 u-xs-PaddingBottom20" style="background-image: url('assets/imgs/demo/bg1.png');background-size: auto 130% ;background-repeat: no-repeat;margin-top:8%">
        <div class="container fonts">
            <div class="row u-FlexCenter u-sm-Block wel-taxiappz ">
                <div class="col-md-5 col-md-offset-0 col-sm-10 col-sm-offset-1 text-white">
                    <div class="Heading" data-title="Welcome to Taxiappz">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h2 class="text-uppercase u-Weight700 u-Margin0">Welcome to Taxiappz
                        </h2>
                    </div>
                    <h5 class="u-LineHeight2 u-MarginBottom50 fs-125">Our Main focus is to attain the high quality in no matter the size of the customer and their respective business.
                                        </h5>
                    <a href="" data-toggle="modal" data-target="#contact-modal" class="pricing_btn pricing_gn">Get Now</a>
                    <!-- <img src="assets/imgs/text-john-lenon.png" alt="..." width="160"> -->
                </div>
                <div class="col-md-6 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 u-sm-MarginTop50">
                    <div class="row">
                        <div class="col-sm-6 col-xs-6 text-center u-MarginBottom60 u-xs-MarginBottom30">
                            <div class="u-PaddingRight10">
                                <div class="Blurb Blurb--wrapper30 Blurb--hoverBg u-BoxShadow40 u-Rounded">
                                    <h2 class="js-CountTo Blurb__hoverText u-Weight300 u-MarginTop0 u-MarginBottom10" data-to="8">0</h2>
                                    <small class="Blurb__hoverText text-uppercase u-LetterSpacing2 ">No of years</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6 text-center u-MarginBottom60 u-xs-MarginBottom30">
                            <div class="u-PaddingLeft10">
                                <div class="Blurb Blurb--wrapper30 Blurb--hoverBg u-BoxShadow40 u-Rounded">
                                    <h2 class="js-CountTo Blurb__hoverText u-Weight300 u-MarginTop0 u-MarginBottom10" data-to="500">0</h2>
                                    <small class="Blurb__hoverText text-uppercase u-LetterSpacing2 ">Happy
                                        Clients</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6 text-center u-xs-MarginBottom30">
                            <div class="u-PaddingRight10">
                                <div class="Blurb Blurb--wrapper30 Blurb--hoverBg u-BoxShadow40 u-Rounded">
                                    <h2 class="js-CountTo Blurb__hoverText u-Weight300 u-MarginTop0 u-MarginBottom10" data-to="185">0</h2>
                                    <small class="Blurb__hoverText text-uppercase u-LetterSpacing2 ">Total Users</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6 text-center u-xs-MarginBottom30">
                            <div class="u-PaddingLeft10">
                                <div class="Blurb Blurb--wrapper30 Blurb--hoverBg u-BoxShadow40 u-Rounded">
                                    <h2 class="js-CountTo Blurb__hoverText u-Weight300 u-MarginTop0 u-MarginBottom10" data-to="1000000">0</h2>
                                    <small class="Blurb__hoverText text-uppercase u-LetterSpacing2 ">Total
                                        Downloads</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--welcome end-->

    <!--feature start-->
    <!-- <section class="u-PaddingTop50 u-PaddingBottom50 u-xs-PaddingTop30 u-xs-PaddingBottom30 app-screens" data-overlay="8">
        <div class="container">
            <div class="row">
                <div class="col-md-4 "> -->
    <!-- <h1 class="u-ize50 u-Weight800 u-MarginBottom15 text-uppercase">Heading</h1> -->
    <!-- <small class="text-uppercase u-Weight700 u-LetterSpacing2">— User —</small>
                </div>
                <div class="col-md-4 text-center">
                    <div class="Heading" data-title="screens">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h1 class="text-uppercase u-Weight800 u-Margin0">App screens<span class="Dot"></span></h1>
                    </div> -->
    <!-- <small class="text-uppercase u-Weight700 u-LetterSpacing2">— Left Align —</small> -->
    <!-- </div>
                <div class="col-md-4 text-right text-sm-left"> -->
    <!-- <h1 class="u-ize50 u-Weight800 u-MarginBottom15 text-uppercase">Heading</h1> -->
    <!-- <small class="text-uppercase u-Weight700 u-LetterSpacing2">— Driver —</small>
                </div> -->
    <!-- </div>
            <div class="row u-FlexCenter u-sm-Block u-MarginTop10 app-screen-div">
                <div class="col-md-4 col-md-push-4 col-md-offset-0 col-sm-12  col-xs-12  u-sm-MarginBottom60">
                    <section id="iphone8" class="silver">
                        <div class="wrap">
                            <div class="marvel-device iphone8">
                                <div class="top-bar"></div>
                                <div class="sleep"></div>
                                <div class="volume"></div>
                                <div class="sensor"></div>
                                <div class="speaker"></div>
                                <div class="screen box">
                                    <img src="assets/imgs/screen/splash.png" alt="" />
                                </div>
                                <div class="home"></div>
                                <div class="bottom-bar"></div>
                            </div>
                        </div>
                    </section> -->

    <!-- </div>
                <div class="col-md-4 col-sm-6 col-md-pull-4 left_screen_detail">
                    <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible ">
                            <div class="media-body">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot" id="user_screen_1"> 01.</h4>
                                <div class="media-left media-middle-- u-PaddingTop15">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon  Icon-map-pin Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="media-body text-left u-PaddingTop10 u-PaddingBottom10 ">
                                    <p class="u-LineHeight2 screen_shot_text"> Riders can set the location. Where they want to go.</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
    <!-- <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible">
                            <div class="media-body">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot" id="user_screen_2">02.</h4>
                                <div class="media-left media-middle-- u-PaddingTop15">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon  Icon-money Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="media-body text-left u-PaddingTop10 u-PaddingBottom10 ">
                                    <p class="u-LineHeight2 screen_shot_text">They have an option of cash or card payments.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> -->
    <!-- <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible">
                            <div class="media-body">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot" id="user_screen_3">03.</h4>
                                <div class="media-left media-middle-- u-PaddingTop15">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon  Icon-bargraph  Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="media-body text-left u-PaddingTop10 u-PaddingBottom10 ">
                                    <p class="u-LineHeight2 screen_shot_text">With the help of ETA, customers get the Fare Estimate of payment details.</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
    <!-- <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible">
                            <div class="media-body">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot" id="user_screen_4">04.</h4>
                                <div class="media-left media-middle-- u-PaddingTop15">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon  Icon-money Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="media-body text-left u-PaddingTop10 u-PaddingBottom10 ">
                                    <p class="u-LineHeight2 screen_shot_text">Riders have a convenient payment method to pay the charges</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
    <!-- <div class="col-md-4  col-sm-6 right_screen_detail">
                    <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible text-right">
                            <div class="media-body u-PaddingLeft5">
                                <h4 id="driver_screen_1" class="u-MarginTop0 u-MarginBottom15 screen_shot_right"> 05.
                                </h4>
                                <div class="media-body u-PaddingTop10 u-PaddingBottom10">
                                    <p class="u-LineHeight2 text-right screen_shot_text">If Driver is Idle for any reason, he goes offline automatically.</p>
                                </div>
                                <div class="media-left media-middle--  u-PaddingTop10">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon  Icon-sunny-cloud Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
    <!-- <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible text-right">
                            <div class="media-body u-PaddingLeft5">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot_right" id="driver_screen_2">06.
                                </h4>
                                <div class="media-body u-PaddingTop10 u-PaddingBottom10">
                                    <p class="u-LineHeight2 text-right screen_shot_text">They can accept the request if they want to Ride a trip.</p>
                                </div>
                                <div class="media-left media-middle--  u-PaddingTop10">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon Icon-form Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
    <!-- <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible text-right">
                            <div class="media-body u-PaddingLeft5">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot_right" id="driver_screen_3"> 07.
                                </h4>
                                <div class="media-body u-PaddingTop10 u-PaddingBottom10">
                                    <p class="u-LineHeight2 text-right screen_shot_text"> Drivers have a facility see the real-time car transition on the map.</p>
                                </div>
                                <div class="media-left media-middle--  u-PaddingTop10">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon Icon-document Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="u-MarginTop5">
                        <div class="media u-OverflowVisible text-right">
                            <div class="media-body u-PaddingLeft5">
                                <h4 class="u-MarginTop0 u-MarginBottom15 screen_shot_right" id="driver_screen_4">08.
                                </h4>
                                <div class="media-body u-PaddingTop10 u-PaddingBottom10">
                                    <p class="u-LineHeight2 text-right screen_shot_text">In the Invoice details, drivers can see all the details related to the trip.</p>
                                </div>
                                <div class="media-left media-middle--  u-PaddingTop10">
                                    <div class="Thumb">
                                        <i class="Blurb__hoverText Icon Icon-document Icon--32px" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!--feature end-->

    <section class="u-PaddingTop10 u-PaddingBottom10 u-xs-PaddingTop20 u-xs-PaddingBottom20">
        <div class="container fonts">
            <div class="row text-center u-MarginBottom35">
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 text-center">
                    <div class="Heading" data-title="TaxiAppz on">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h2 class="text-uppercase u-Weight700 u-Margin0">TaxiAppz on Customer View</h2>
                    </div>
                    <h5 class="text-center fs-125 u-Margin0">Visual representation of our happy customers, considered as an stepping stone to our services</h5>
                </div>
            </div>
            <div class="owl-carousel owl-theme">
                <div class="item client-tes-one">
                   <a href="https://www.youtube.com/watch?v=IWBZIeCLcV0&ab_channel=TaxiAppz" target="_blank"> <img src="assets/imgs/client-testimonial-one.png" alt="Testimonial-client" class="lazyload"></a>
                </div>
                <div class="item client-tes-four">
                <a href="https://youtube.com/shorts/OZdZn8kZZtI?feature=share" target="_blank"> <img src="assets/imgs/testi-4.png" alt="Testimonial-client" lt="Testimonial-client" class="lazyload"></a>
                </div>
                <div class="item client-tes-two">
                <a href="https://www.youtube.com/watch?v=HIem1CL0jYk&ab_channel=TaxiAppz" target="_blank">  <img src="assets/imgs/client-testimonial-two.png" alt="Testimonial-client" class="lazyload"></a>
                </div>
                <div class="item client-tes-three">
                <a href="https://www.youtube.com/watch?v=mHOE9e_hxkU&ab_channel=TaxiAppz" target="_blank"> <img src="assets/imgs/client-testimonial-three.png" alt="Testimonial-client" class="lazyload"></a>
                </div>
                <div class="item client-tes-five">
                <a href="https://www.youtube.com/watch?v=m-pCoS07d6I&ab_channel=TaxiAppz" target="_blank"> <img src="assets/imgs/testi-5.png" alt="Testimonial-client" lt="Testimonial-client" class="lazyload"></a>
                </div>
            </div>
        </div>
    </section>

   
    
                    
    <section>
            <div class="container fonts">
                <div class="Heading Heading--center Heading--shadow Heading--ff-Anton u-MarginBottom50" data-title="Frequently Asked Questions" style="margin-top: 50px;">
                    <h2 class="u-ize75 u-xs-ize50 u-MarginTop0 u-PaddingTop20 u-xs-PaddingTop5 ff-Playball text-uppercase u-Weight700 u-Margin0">FAQ</h2>
                </div>
                <div class="row">
                    <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1">
                        <div class="panel-group u-PaddingRight20 u-sm-PaddingRight0" id="accordion3">
                            <div class="panel panel-info">
                                <div class="panel-heading" id="heading3-1">
                                    <div class="panel-title ">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-1" aria-expanded="true" aria-controls="collapse3-1">
                                        What is the Best taxi dispatch platform? 
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading3-1">
                                    <div class="panel-body">
                                   <h5 class="fs-125 u-Margin0">Get a bug-free app and 24/7 client support. With 250+ satisfied clients nationwide, we offer top-tier taxi dispatch app development services. Contact us for support.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-2">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-2" aria-expanded="false" aria-controls="collapse3-2">
                                        What are the Advantages of Taxiappz? 
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-2">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">A ready-made software for businesses to customize their concepts, facilitating a quick and cost-effective business launch.</h5>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-3">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-3" aria-expanded="false" aria-controls="collapse3-3">
                                        How much does it cost to Build an App? 
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-3">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">The cost of an app varies based on individual business needs. Contact our sales team for a customized cost estimate tailored to your taxi business requirements.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-4">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-4" aria-expanded="false" aria-controls="collapse3-3">
                                        How can I purchase an App from Taxiappz? 
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-4">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Submit your details on our website, and we'll send you a customized demo of your potential taxi booking app, based on your requirements.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-5">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-5" aria-expanded="false" aria-controls="collapse3-3">
                                        Can we get the Full Source Code?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-5">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Yes, we provide the full source code of the Admin panel, Android (Rider & Driver), and iOS (Rider & Driver). Also, with API documents.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-6">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-6" aria-expanded="false" aria-controls="collapse3-3">
                                        Is your application Native?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-6">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Yes, our application is Native. Android as Kotlin & Java (Android Studio), and iOS in Swift language.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-7">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-7" aria-expanded="false" aria-controls="collapse3-3">
                                        Can we able to customize the code?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-7">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Yes, we provide white-label applications so that you can customize the code with easy understanding.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-8">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-8" aria-expanded="false" aria-controls="collapse3-3">
                                        Is our Uber Clone fit for Entrepreneurs?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-8">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Our app effortlessly serves taxi companies of all sizes with minimal guidance needed. It's ready to use for all types of taxi services while prioritizing user-friendliness and affordability.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-9">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-9" aria-expanded="false" aria-controls="collapse3-3">
                                        How can you ensure good quality in apps?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-9">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Our dedicated team of professionals guarantees top-notch quality. The Quality Assurance (QA) team ensures a glitch-free and reliable app.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-10">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-10" aria-expanded="false" aria-controls="collapse3-3">
                                        Can you help to host the application?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-10">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Yes, we help to host the application on your server and upload the mobile app in Playstore and Appstore. We’ll provide the complete services to make your app publish.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading3-11">
                                    <div class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-11" aria-expanded="false" aria-controls="collapse3-3">
                                        If I require further assistance, what should I do?
                                        </a>
                                    </div>
                                </div>
                                <div id="collapse3-11" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-11">
                                    <div class="panel-body">
                                    <h5 class="fs-125 u-Margin0">Please don't hesitate to contact us for any inquiries through various communication channels, or you can connect with us at sales@taxiappz.com.</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    <section class="testimonial-one-div close-div">
        <div class="testimonial-inner-div">
            <!-- <span class="popup-close-one">X</span> -->
            <!-- <iframe width="560" height="315"  id="iframe-one" src="https://www.youtube.com/embed/IWBZIeCLcV0" autoplay=0 controls></iframe> -->
            <a href=""></a>
        </div>
    </section>
    <section class="testimonial-two-div close-div">
        <div class="testimonial-inner-div">
            <!-- <span class="popup-close-two">X</span> -->
            <!-- <iframe width="560" height="315" id="iframe-two" src="https://www.youtube.com/embed/HIem1CL0jYk" autoplay=0 controls></iframe> -->
        </div>
    </section>
    <section class="testimonial-three-div close-div">
        <div class="testimonial-inner-div">
            <!-- <span class="popup-close-three">X</span> -->
            <!-- <iframe width="560" height="315" id="iframe-three" src="https://www.youtube.com/embed/mHOE9e_hxkU" autoplay=0 controls></iframe> -->
        </div>
    </section>
    <section class="testimonial-fourth-div close-div">
        <div class="testimonial-inner-div">
            <!-- <span class="popup-close-four">X</span> -->
            <!-- <iframe width="560" height="315" id="iframe-four" src="https://www.youtube.com/embed/OZdZn8kZZtI" autoplay=0 controls></iframe> -->
        </div>
    </section>
    <section class="testimonial-five-div close-div">
        <div class="testimonial-inner-div">
            <!-- <span class="popup-close-five">X</span> -->
            <!-- <iframe width="560" height="315" id="iframe-five" src="https://www.youtube.com/embed/m-pCoS07d6I" autoplay=0 controls></iframe> -->
        </div>
    </section>


     <!-- <section class="testimonial-one-div close-div">
        <div class="testimonial-inner-div">
            <span class="popup-close-one">X</span>
            <iframe width="560" height="315" id="iframe-one" src="https://www.youtube.com/embed/IWBZIeCLcV0" autoplay=0 controls></iframe>
        </div>
    </section>
    <section class="testimonial-two-div close-div">
        <div class="testimonial-inner-div">
            <span class="popup-close-two">X</span>
            <iframe width="560" height="315" id="iframe-two" src="https://www.youtube.com/embed/HIem1CL0jYk" autoplay=0 controls></iframe>
        </div>
    </section>
    <section class="testimonial-three-div close-div">
        <div class="testimonial-inner-div">
            <span class="popup-close-three">X</span>
            <iframe width="560" height="315" id="iframe-three" src="https://www.youtube.com/embed/mHOE9e_hxkU" autoplay=0 controls></iframe>
        </div>
    </section>
    <section class="testimonial-fourth-div close-div">
        <div class="testimonial-inner-div">
            <span class="popup-close-four">X</span>
            <iframe width="560" height="315" id="iframe-four" src="https://www.youtube.com/embed/OZdZn8kZZtI" autoplay=0 controls></iframe>
        </div>
    </section>
    <section class="testimonial-five-div close-div">
        <div class="testimonial-inner-div">
            <span class="popup-close-five">X</span>
            <iframe width="560" height="315" id="iframe-five" src="https://www.youtube.com/embed/m-pCoS07d6I" autoplay=0 controls></iframe>
        </div>
    </section> -->
    
    <!--footer start-->
    <footer class="bg-darker u-PaddingTop30 u-MarginTop30">
        <div class="container text-sm " style="padding-left: 60px;padding-right: 0px;">
            <div class="row">
                <div class="col-md-4 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight700 u-LetterSpacing2 u-MarginTop0" >Sitemap</h5>
                    <ul class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0" style="font-size:18px">
                        <li> <a href="https://www.taxiappz.com/" class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Home</a></li>
                        <li> <a href="features"  class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Features</a></li>
                        <li> <a href="portfolio"  class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Portfolio</a></li>
                        <!-- <li> <a href="demo"><i class="fa fa-angle-right u-MarginRight10" aria-hidden="true"></i>Demo</a></li> -->
                        <li> <a href="contact"  class="fs-125"><i class="fa fa-angle-right u-MarginRight10" aria-hidden="true"></i>Contact Us</a></li>
                        <li> <a href="privacy-policy" class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Privacy Policy</a></li>
                        
                    </ul>
                </div>
                <div class="col-md-4 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight700 u-LetterSpacing2 u-MarginTop0" >Our Locations</h5>
                    <ul class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0" style="font-size:18px">
                        <li>
                            <a target="_blank"  class="fs-125" href="https://www.google.com/maps/place/Taxi+Appz/@11.0148674,76.9803806,17z/data=!4m13!1m7!3m6!1s0x0:0x2149702fe300260f!2sTaxi+Appz!3b1!8m2!3d11.0148674!4d76.9825693!3m4!1s0x0:0x2149702fe300260f!8m2!3d11.0148674!4d76.9825693?hl=en-IN"><img src="assets/imgs/flags/flag.png" width="25px" class=" u-MarginRight10">India</a>
                        </li>
                        <li>
                            <a target="_blank"  class="fs-125" href="https://www.google.com/maps/place/Taxi+Appz/@25.2518854,51.5578418,17z/data=!3m1!4b1!4m5!3m4!1s0x0:0x68cbeedd3e141567!8m2!3d25.2518854!4d51.5600305?hl=en-IN"><img src="assets/imgs/flags/flag2.png" width="25px" class=" u-MarginRight10">Middle East
                            </a>
                        </li>
                        <!-- <li>
                            <a target="_blank" href="https://www.google.co.in/maps/place/5+Belfast+Ave,+Slough+SL1+3HE,+UK/@51.5214442,-0.6100953,17z/data=!3m1!4b1!4m5!3m4!1s0x4876652f2af0016d:0xafc9bb93be413149!8m2!3d51.5214442!4d-0.6079066?hl=en"><img src="assets/imgs/flags/flag3.png" width="25px" class=" u-MarginRight10">United Kingdom
                            </a>
                        </li> -->
                        <li>
                            <a target="_blank"  class="fs-125" href="https://www.google.co.in/maps/place/17193+Castello+Cir,+San+Diego,+CA+92127,+USA/@33.0251485,-117.1233497,17z/data=!3m1!4b1!4m5!3m4!1s0x80dbf6f98dd3bf77:0x483a9f5fe67bb82f!8m2!3d33.025144!4d-117.121161?hl=en"><img src="assets/imgs/flags/flag1.png" width="25px" class=" u-MarginRight10">USA</a>
                        </li>
                        <!-- <li>
                            <a target="_blank" href="https://www.google.co.in/maps/place/Calle+Central+2,+Santo+Domingo,+Dominican+Republic/@18.4519434,-69.9537494,15.17z/data=!4m5!3m4!1s0x8ea5621ec99ae383:0x45c147faab9f5573!8m2!3d18.4468506!4d-69.9495871?hl=en"><img src="assets/imgs/flags/flag4.png" width="25px" class=" u-MarginRight10">Dominican Republic
                            </a>
                        </li> -->
                    </ul>
                </div>
                <div class="col-md-4 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight700 u-LetterSpacing2 u-MarginTop0" >Contact Us</h5>
                    <ul class="light-gray-link list-unstyled u-MarginBottom0">
                        <li class="u-MarginBottom15">
                        <p  class="fs-125">1A, Spectrum building Phase - 2,<br> Pappanaicken palayam,<br> Coimbatore-641037, <br> Tamil Nadu, India.</p>
                        </li>
                    </ul>
                    <p  class="fs-125">© 2016 Taxiappz. <br /> Email: <a href="mailto:sales@taxiappz.com">sales@taxiappz.com</a>
                    </p>
                </div>
               

            </div>
        </div>
        <div class="text-center u-MarginTop30">
            <div class="footer-separator"></div>
            <p class="text-center u-PaddingTop10 u-PaddingBottom10 u-MarginBottom0 fs-125">Copyright 2016 @ Taxiappz.</p>
        </div>
    </footer>
    <!-- <script src="//code.tidio.co/qzbtcmhutpom6t0lxbwrtvp4zzhnoyvw.js" async></script> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<a  class="whats-app" href="https://api.whatsapp.com/send?phone=+919940909625&text=Hello!." target="_blank"><i class="fa fa-whatsapp"></i>
</a>

<style>

.whats-app {
            position: fixed;
            width: 50px;
            height: 50px;
            bottom: 25px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 35px;
            box-shadow: 3px 4px 3px #999;
            right: 30px;
            z-index: 100;
            bottom: 23%;
            left: 95%;
        }

</style>

<style>
    
    

/* entypo */
[class*="entypo-"]:before {
   font-family: "entypo", sans-serif;
}
a { 
   text-decoration: none;
}
ul {
   list-style: none;
   margin: 0;
   padding: 0;
}

#sticky-social {
   left: 0;
   position: fixed;
   top: 350px;
   z-index: 1000;
}
#sticky-social a {
   background: #333;
   color: #fff;
   display: block;
   height: 50px;
   font: 16px "Open Sans", sans-serif;
   line-height: 60px;
   position: relative;
   text-align: center;
   width: 50px;
}
#sticky-social a span {
   line-height: 35px;
   left: -120px;
   position: absolute;
   text-align:center;
   width:120px;
}
#sticky-social a:hover span {
   left: 100%;
}
#sticky-social a[class*="whatsapp"],
#sticky-social a[class*="whatsapp"]:hover,
#sticky-social a[class*="whatsapp"] span { background: #25d366; }

#sticky-social a[class*="skype"],
#sticky-social a[class*="skype"]:hover,
#sticky-social a[class*="skype"] span { background: #00aff0; }

    </style>
    <!--footer end-->
    <!-- <div class="window-loader col-12">
        <div class="loader-div">
            <img src="assets/imgs/loader.gif" width="100%" alt="">
        </div>
    </div> -->

    <!-- inject:js -->
    <script src="assets/vendor/jquery/jquery-1.12.0.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/bootsnav/js/bootsnav.js"></script>
    <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/vendor/jquery.countTo/jquery.countTo.min.js"></script>
    <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="assets/vendor/jquery.appear/jquery.appear.js"></script>
    <script src="assets/vendor/parallax.js/parallax.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendor/switchery/switchery.min.js"></script>
    <script src="assets/vendor/swiper/js/swiper.min.js"></script>
    <script src="assets/js/alien.js"></script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCVkU7YnGfown4_i_sm6X36HP2jWTv54&callback=initMap ">
    </script>

    <!-- <script>
       
        var init = 0; //used to make text blink on page load and still permit user click

        $('#heading').click(function() {
            if (init == 0) {
                init++;
                //fn call INTENTIONALLY missing 4th param
                blink(this, 1300, 8);
            } else {
                //alert('Not document load, so process the click event');
            }
        });

        function blink(selector, blink_speed, iterations, counter) {
            counter = counter | 0; //this line is reason why external call lacks 4th param
            $(selector).animate({
                opacity: 0
            }, 50, "linear", function() {
                $(this).delay(blink_speed);
                $(this).animate({
                    opacity: 1
                }, 50, function() {
                    counter++;
                    if (iterations == -1) {
                        
                        blink(this, blink_speed, iterations, counter);
                    } else if (counter >= iterations) {
                        counter = 0;
                        blink(this, blink_speed, iterations, counter);
                        //return false;
                    } else {
                        blink(this, blink_speed, iterations, counter);
                    }
                    // if (counter == 2) {
                    //     $('#heading').text("Fleet Tracking");
                    //     $('#heading').css('color', '#4F1271');
                    // }
                    if (counter == 2) {
                        $('#heading').text("Taxi Booking");
                        $('#heading').css('color', 'deeppink');

                    } else if (counter == 3) {
                        $('#heading').text("Scheduling");
                        $('#heading').css('color', '#58641D');

                    } else if (counter == 4) {
                        $('#heading').text("Dispatching");
                        $('#heading').css('color', '#9B5094');

                    } 
                });
                $(this).delay(blink_speed);
            });
        }

        window.load($('#heading').trigger('click'));

    </script> -->

    <script>
        $(window).load(function() {
            $('.window-loader').fadeOut("slow");
        });
    </script>
    <script>
        function initMap() {
            var location, map, marker;
            location = {
                lat: 23.810332,
                lng: 90.41251809999994
            };
            map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 10,
                scrollwheel: false
            });
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    </script>

<script>
  function myAction() {
    window.lintrk('track', { conversion_id: 8994516 });
  }
  function action() {
    window.lintrk('track', { conversion_id: 8994516 });
  }
</script>

<script src="//www.google.com/recaptcha/api.js" async defer></script>

<script>
    setTimeout(() => {
        $('.showError').hide();
    }, 10000);

    function validateForm() {
        var response = grecaptcha.getResponse();
        if (response.length == 0) {
            document.getElementById('g-recaptcha-error').innerHTML = '<h3 style="color:#ec4139">Verify that you are a human..</h3>';
            return false;
        }
        return true;
    }

    function getnowForm() {
        var response = grecaptcha.getResponse();
        if (response.length == 0) {
            document.getElementById('g-recaptcha-error').innerHTML = '<h3 style="color:#ec4139">Verify that you are a human..</h3>';
            return false;
        }
        return true;
    }

    function verifyCaptcha() {
        document.getElementById('g-recaptcha-error').innerHTML = '';
    }

    function setstyle(getsize) {
        this.size = getsize;
    }
    populateCountries("countries");
    countrySelect();

    function countrySelect() {
        $.ajax({
            url: "//ip-api.com/json",
            success: function(data) {
                if (data != null) {
                    $("#countries").val(data.country);
                }

            }
        });
    }

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>

<script type="text/javascript" async="async" src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/2.5.0/parsley.min.js"></script>
<!--<script type="text/javascript" async="async" defer="defer" data-cfasync="false" src="https://mylivechat.com/chatinline.aspx?hccid=87634492"></script>
-->
<script src="build/js/intlTelInput.js"></script>

<script>
    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        // allowDropdown: false,
        // autoHideDialCode: false,
        autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        geoIpLookup: function(callback) {
          $.get("//ipinfo.io", function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
          });
        },
        // hiddenInput: "full_number",
        initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        // nationalMode: false,
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        // placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
        separateDialCode: true,
        utilsScript: "build/js/utils.js",
    });

    // function getNumber() {
    //
    //     var code = iti.selectedCountryData.dialCode;
    //
    //     document.getElementById("dialcodes").value = "+" + code;
    //
    // }
</script>

   
    <!-- endinject -->
    <script>
        $(window).load(function() {
            $('.window-loader').fadeOut("slow");
        });
    </script>
    <script>
        $('.owl-carousel').owlCarousel({
            items: 2,
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
        $('#owl-carousel').owlCarousel({
            items: 2,
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    </script>
    <script src="assets/vendor/slider/js/main.js" defer></script>
    <!-- Resource JavaScrip -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
       $(document).ready(function(){
            $("#tabs1").mouseover(function(){
                $("#img1").show();
                $("#img2").hide();
                $("#img3").hide();
                $("#img4").hide();
            });
            $("#tabs2").mouseover(function(){
                $("#img1").hide();
                $("#img2").show();
                $("#img3").hide();
                $("#img4").hide();
            });
            $("#tabs3").mouseover(function(){
                $("#img1").hide();
                $("#img2").hide();
                $("#img3").show();
                $("#img4").hide();
            });
            $("#tabs4").mouseover(function(){
                $("#img1").hide();
                $("#img2").hide();
                $("#img3").hide ();
                $("#img4").show();
            });
            
        });

       
        $("#tab1").click(function(){
            console.log('ghgh');
            $("#img1").show();
            $("#img2").hide();
            $("#img3").hide();
            $("#img4").hide();
        });
        $("#tab2").click(function(){
            
            console.log('ghgh');
            $("#img1").hide();
            $("#img2").show();
            $("#img3").hide();
            $("#img4").hide();
        });
        $("#tabs3").click(function(){
            $("#img1").hide();
            $("#img2").hide();
            $("#img3").show();
            $("#img4").hide();
        });
        $("#tabs4").click(function(){
            $("#img1").hide();
            $("#img2").hide();
            $("#img3").hide ();
            $("#img4").show();
        });
            
        

        

    </script>
    
    <!-- <script>
        $(document).ready(function() {
            $("#my-welcome-message").is(":visible") && $("header").css("position", "unset")


            $(".client-tes-one img").on('click', function() {
                $(".testimonial-one-div").css('display', 'block');
            });
            $(".client-tes-two img").on('click', function() {
                $(".testimonial-two-div").css('display', 'block');
            });
            $(".client-tes-three img").on('click', function() {
                $(".testimonial-three-div").css('display', 'block');
            });
            $(".client-tes-four img").on('click', function() {
                $(".testimonial-fourth-div").css('display', 'block');
            });
            $(".client-tes-five img").on('click', function() {
                $(".testimonial-five-div").css('display', 'block');
            });
            $(".popup-close-one").on('click', function() {
                var frame_src = document.getElementById('iframe-one').src
                $(this).parents('section.close-div').fadeOut();
                $(".testimonial-one-div iframe").attr('src', frame_src);
            });
            $(".popup-close-two").on('click', function() {
                var frame_src_two = document.getElementById('iframe-two').src
                $(this).parents('section.close-div').fadeOut();
                $(".testimonial-two-div iframe").attr('src', frame_src_two);
            });
            $(".popup-close-three").on('click', function() {
                var frame_src_three = document.getElementById('iframe-three').src
                $(this).parents('section.close-div').fadeOut();
                $(".testimonial-three-div iframe").attr('src', frame_src_three);
            });
            $(".popup-close-four").on('click', function() {
                var frame_src_four = document.getElementById('iframe-four').src
                $(this).parents('section.close-div').fadeOut();
                $(".testimonial-fourth-div iframe").attr('src', frame_src_four);
            });
            $(".popup-close-five").on('click', function() {
                var frame_src_five = document.getElementById('iframe-five').src
                $(this).parents('section.close-div').fadeOut();
                $(".testimonial-five-div iframe").attr('src', frame_src_five);
            });
        })
    </script> -->

     <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/63f6f5084247f20fefe223cc/1gpuaqan5';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>

</html>
