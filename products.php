<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once('mail/Mail.php');

if (isset($_SESSION['notification_showed'])) {
    if ($_SESSION['notification_showed'] == true) {
        unset($_SESSION['nameErr']);
        unset($_SESSION['emailErr']);
        unset($_SESSION['phoneErr']);
        unset($_SESSION['messageErr']);
    }
}



if (isset($_SESSION['nameErr']) || isset($_SESSION['emailErr']) || isset($_SESSION['phoneErr']) || isset($_SESSION['messageErr'])) {
    $_SESSION['notification_showed'] = true;
}
if (isset($_POST["form_submit"])) {
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) { // check capcha submitted

        $secret = '6LfOyCkUAAAAALZXPK8Cpr4Y5PBurthGMIVVGUhm';
        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if ($responseData->success) {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                unset($_SESSION['nameErr']);
                unset($_SESSION['emailErr']);
                unset($_SESSION['phoneErr']);
                unset($_SESSION['messageErr']);

                $_SESSION['notification_showed'] = false;

                if (empty($_POST["contact_name"])) {
                    $_SESSION['nameErr'] = "Name is required";
                } else {
                    $name = test_input($_POST["contact_name"]);
                    // check if name only contains letters and whitespace
                    // if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                    //   $_SESSION['nameErr'] = "Only letters and white space allowed";              
                    // }
                }

                if (empty($_POST["contact_email"])) {
                    $_SESSION['emailErr'] = "Email is required";
                } else {
                    $email = test_input($_POST["contact_email"]);
                    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    //     $_SESSION['emailErr'] = "Invalid email format";                
                    //   }
                }

                if (empty($_POST["contact_mobile_number"])) {
                    $_SESSION['phoneErr'] = "Mobile number is required";
                } else {
                    $phone = test_input($_POST["contact_mobile_number"]);
                    // if (!preg_match("/^[1-9][0-9]{0,15}$/",$phone)) {
                    //     $_SESSION['phoneErr'] = "Invalid Mobile number";                
                    //   }
                }

                if (empty($_POST["contact_message"])) {
                    $_SESSION['messageErr'] = "Message is required";
                } else {
                    $message = test_input($_POST["contact_message"]);
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
            if (!empty($_POST["contact_name"]) && !empty($_POST["dialcodes"]) && !empty($_POST["contact_mobile_number"]) && !empty($_POST["contact_email"]) && !empty($_POST["contact_message"])) {

                $Contact_name = filter_var($_POST["contact_name"], FILTER_UNSAFE_RAW);
                $Contact_email = filter_var($_POST["contact_email"], FILTER_UNSAFE_RAW);
                $Contact_mobile = filter_var($_POST["contact_mobile_number"], FILTER_UNSAFE_RAW);
                $Contact_Message = filter_var($_POST["contact_message"], FILTER_UNSAFE_RAW);
                $dialcodes = filter_var($_POST["dialcodes"], FILTER_UNSAFE_RAW);
                $countryName = filter_var($_POST["countryName"], FILTER_UNSAFE_RAW);

                $get_country = explode(" (", $countryName);
                $Contact_country = $get_country[0];


                $Body = "Hi," . $Contact_Message;
                $sender = "sales@taxiappz.com";
                $myfile_read = fopen("mail_count.php", "r");
                $m = fgets($myfile_read);
                fclose($myfile_read);
                $myfile_write = fopen("mail_count.php", "w");
                $s = strval($m + 1);
                $r = fwrite($myfile_write, $s);
                //echo $s;
                fclose($myfile_write);

                $Body = '<!DOCTYPE html>
        <html lang="en">
        
        <head>
            <meta charset="utf-8">
            <!--  This file has been downloaded from https://bootdey.com  -->
            <!--  All snippets are MIT license https://bootdey.com/license -->
            <title>Bootdey.com</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
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
                    $Contact_name . '</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Email</td>
                                                                                            <td class="alignright">
                                                                                                ' . $Contact_email . '</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Mobile Number</td>
                                                                                            <td class="alignright">' .
                    $dialcodes . '' . $Contact_mobile . '
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Country</td>
                                                                                            <td class="alignright">
                                                                                                ' . $Contact_country . '</td>
                                                                                        </tr>
                                                                                       
                                                                                        <tr>
                                                                                            <td>Message</td>
                                                                                            <td class="alignright">
                                                                                                ' . $Contact_Message . '</td>
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
                                                <td class="aligncenter content-block">Copyright © ' . date("Y") . ' <a
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

                $from = "Taxiappz <sales@taxiappz.com>";
                //    $to       = "packiyaraj.nplus@gmail.com";
                $to = "Sales <sales@taxiappz.com>";
                $subject = "Lead #000" . $m . " - " . $Contact_name;
                $body = $Body;
                $host = "ssl://smtp.gmail.com";
                $username = "sales@taxiappz.com";
                $password = "hdxvjqaycnqqbshw";
                //              $username = "testersmail2014@gmail.com";
//              $password = "nplustech2014";
                $headers = array(
                    'From' => $from,
                    'To' => $to,
                    'Subject' => $subject,
                    'MIME-Version' => 1,
                    'Content-type' => 'text/html;charset=iso-8859-1'
                );
                $smtp = Mail::factory(
                    'smtp',
                    array(
                        'host' => $host,
                        'port' => '465',
                        'auth' => true,
                        'username' => $username,
                        'password' => $password
                    )
                );

                if (empty($_SESSION['nameErr']) && empty($_SESSION['emailErr']) && empty($_SESSION['phoneErr'] && empty($_SESSION['messageErr']))) {
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

    <title>TaxiAppz Products | Food Ordering and Delivery System</title>
    <meta name="description"
        content="Food online ordering system is designed to order their food online with extensive delivery systems and tracking system." />
    <meta name="keywords"
        content="Online Food Ordering and Delivery System, Food online ordering system, Online ordering and delivery systems, Food Ordering System" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Heebo:100%7COpen+Sans:300,400,400i,600,700,800">
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootsnav/css/bootsnav.css">
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendor/alien-icon/css/style.css">
    <link rel="stylesheet" href="assets/vendor/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/vendor/switchery/switchery.min.css">
    <link rel="stylesheet" href="assets/vendor/animate.css/animate.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/alien.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="build/css/intlTelInput.css">

    <!-- endinject -->

    <link rel="stylesheet" href="assets/vendor/slider/css/style.css">
    <!-- Resource style -->

    <!-- TYPEWRITER ADDON -->
    <link rel="stylesheet" type="text/css"
        href="assets/vendor/revolution-slider/revolution-addons/typewriter/css/typewriter.css">

    <style>
        .media-body.non-scroll p {
            margin-top: 0px;
        }

        @media (max-width: 640px) {

            .nav-tabs--style1>li.active::after,
            .nav-tabs--style1.nav_sticky1>li.active::after {
                border-right: 10px solid transparent !important;
                border-left: 10px solid transparent !important;
                border-top: 10px solid #ff0101 !important;
                top: 75px !important;
                right: 40% !important;
            }

            .nav-tabs>li>a>img {
                padding-bottom: 0px !important;
            }

            .feature_text {
                min-height: auto;
            }

            .media p {
                margin-top: 5px;
            }

            .Thumb {
                width: 100%;
                height: 35px;
            }

            ul.cd-hero__slider {
                height: 330px;
            }

            .u-MarginTop100 {
                margin-top: 25px;
            }

            .media-body.non-scroll p {
                margin-top: 0px;
            }

            .u-MarginTop150 {
                margin-top: 25px;
            }

            .passeneger_content_left {
                padding-left: 1em;
            }

            .passeneger_content_right {
                padding-right: 1em;
            }

            .bg_right_feat {
                height: 350px;
            }

            .u-PaddingBottom50 {
                padding-bottom: 0px;
            }

            .u-PaddingBottom20 {
                padding-bottom: 0px;
            }

            .Heading::before {
                top: 0px;
            }

            .u-MarginBottom50 {
                margin-bottom: 20px;
            }

            .passeneger_img_right {
                padding-right: 1em;
            }

            .passeneger_img_left {
                padding-left: 0em;
            }

            .u-PaddingTop20 {
                padding-top: 10px;
            }

            #Passenger div.Heading.u-MarginBottom30 {
                margin-bottom: 10px;
            }

            #Passenger div.u-,
            #Passenger div.u-MarginTop150,
            #Passenger div.u-MarginTop100 {
                margin-top: 0px;
            }

            .features-passenger-app h4 {
                margin-top: 10px;
                margin-bottom: 10px;
            }
        }

        @media (min-width: 768px) and (max-width: 992px) {
            .u-MarginTop100 {
                margin-top: 25px;
            }

            .media-body.non-scroll p {
                margin-top: 0px;
            }

            .u-MarginTop150 {
                margin-top: 25px;
            }

            .passeneger_content_left {
                padding-left: 1em;
            }

            .passeneger_content_right {
                padding-right: 1em;
            }

            .bg_right_feat {
                height: 250px;
            }

            .u-PaddingBottom50 {
                padding-bottom: 0px;
            }

            .u-PaddingBottom20 {
                padding-bottom: 0px;
            }

            .Heading::before {
                top: 0px;
            }

            .u-MarginBottom50 {
                margin-bottom: 20px;
            }
        }

        .ImageBackground__holder {
            background-position: initial !important;
        }

        nav.navbar.bootsnav ul.nav>li>a {
            font-weight: bold !important;
            font-size: 15px !important;
        }

        .Icon--32px {
            font-size: 28px;
        }

        .nav-tabs--style1>li.active::after {
            width: 0;
            height: 0;
            border-right: 20px solid transparent;
            border-left: 20px solid transparent;
            border-top: 20px solid #ff0101;
            content: " ";
            position: absolute;
            top: 70px;
            right: 45%;
        }

        .nav-tabs--style1.nav_sticky1>li.active::after {
            top: 50px;
        }

        .popup-btn {
            position: fixed;
            width: 165px;
            height;
            100px;
            left: 0.5%;
            padding-left: 50px;
            top: 30%;
            z-index: 1000;
            padding: 1%;
            border-radius: 5px 5px 5px 5px;
            background: rgb(131, 58, 180);
            background: linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(246, 7, 7, 1) 50%, rgba(252, 176, 69, 1) 100%);
            transform-origin: 0% 0%;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            transform: rotate(-90deg) translateX(-50%);
        }



        @media only screen and (max-width: 600px) {

            .popup-btn {
                position: fixed;
                width: 148px;
                height;
                100px;
                left: 0.5%;
                padding-left: 50px;
                top: 30%;
                z-index: 1000;
                padding: 1%;
                border-radius: 5px 5px 5px 5px;
                background: rgb(131, 58, 180);
                background: linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(246, 7, 7, 1) 50%, rgba(252, 176, 69, 1) 100%);
                transform-origin: 0% 0%;
                -webkit-transform: rotate(90deg);
                -moz-transform: rotate(90deg);
                -o-transform: rotate(90deg);
                -ms-transform: rotate(90deg);
                transform: rotate(-90deg) translateX(-50%);
            }


        }
    </style>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-10811920151"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'AW-10811920151');
    </script>
</head>

<body>

    <div class="se-pre-con"></div>

    <!--header start-->
    <header>
        <!-- Start Navigation -->



        <nav class="navbar navbar-default navbar-sticky bootsnav">

            <div class="container">

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars icon-font"></i>
                    </button>
                    <a class="navbar-brand" href="https://www.taxiappz.com/"><img src="assets/imgs/logo.png"
                            class="logo logo-scrolled" alt=""></a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="" data-out="">
                        <li><a href="https://www.taxiappz.com/">Home</a></li>
                        <li class="active"><a href="features">Features</a></li>
                        <li><a href="pricing">Pricing</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Our Products
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="food-ordering-and-delivery-system"> <img
                                        src="assets/imgs/food-ordering.svg" width="20px" alt="taxi booking">&nbsp;Food
                                    Ordering and Delivery System</a>
                            </div>
                        </li>
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
            </div>

        </nav>
        <!-- End Navigation -->
        <div class="clearfix"></div>
    </header>
    <!--header end-->


    <div class="popup-btn">
        <a href="" data-toggle="modal" data-target="#contact-modal" style="color:#fff; font-weight:bold;">Get a Free
            Quote</a>
    </div>


    <div id="contact-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding:0px !important; padding-left:15px !important;">
                    <a class="close" style="padding:10px !important;" data-dismiss="modal">×</a>
                    <h4>GET A FREE REQUEST DEMO</h4>
                </div>
                <form action="" method="post" onsubmit="return validateForm()">
                    <div class="modal-body">
                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="name" style="font-size:14px;">Name <span style="color:#ff0000;">*</span></label>
                            <input type="text" style="color:black !important; height:30px;" placeholder="Name"
                                name="contact_name" data-error="You must enter name" required class="form-control">
                            <?php if (isset($_SESSION['nameErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['nameErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="email" style="font-size:14px;">Email <span
                                    style="color:#ff0000;">*</span></label>
                            <input type="email" style="color:black !important;  height:30px;" name="contact_email"
                                class="form-control" placeholder="Email" data-parsley-required="true" required>
                            <?php if (isset($_SESSION['emailErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['emailErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <input id="dialcodes" name="dialcodes" type="hidden" value="">
                        <input id="countryName" name="countryName" type="hidden" value="">


                        <div class="form-group" style="margin-bottom:10px;">

                            <label for="name" style="font-size:14px;">Mobile Number <span
                                    style="color:#ff0000;">*</span></label>
                            <input id="phone" class="form-control" onkeypress="return isNumberKey(event)"
                                name="contact_mobile_number" type="tel" onchange="getNumber()">
                            <?php if (isset($_SESSION['phoneErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['phoneErr']; ?>
                                </div>
                            <?php } ?>
                        </div>


                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="message" style="font-size:14px; ">Message <span
                                    style="color:#ff0000;">*</span></label>
                            <textarea placeholder="Enter Your Message" rows="2" cols="3" name="contact_message"
                                data-parsley-required="true" required style="color:black !important;  min-height:30px;"
                                class="form-control"></textarea>
                            <?php if (isset($_SESSION['messageErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['messageErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <div class="g-recaptcha" data-sitekey="6LfOyCkUAAAAAH-vwFiO6WYFXg1prSnFheMAlffe"
                                data-callback="verifyCaptcha"></div>
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


    <a name="prominent"></a>

    <section class="cd-hero js-cd-hero js-cd-autoplay"
        style="background: black;min-width: 100%; min-height: 80%; opacity: 0.8; ">
        <ul class="cd-hero__slider">
            <li
                class="cd-hero__slide cd-hero__slide--video js-cd-slide cd-hero__slide--selected cd-hero__slide--from-right">
                <div class="cd-hero__content cd-hero__content--full-width text-center" style="padding-top:50px;">
                    <h1 class="type_head">Our Products</h1>
                    <!-- <p class="typewrite text-center" data-period="1000" data-type='[ "Hi, Welcome To Taxiappz.", "We here To Help You", "Scroll Down to Know more" ]'>
                        <span class="wrap"></span>
                    </p> 
                    <a href="#0" class="cd-hero__btn">Read More</a>-->
                </div>

                <!-- <div class="cd-hero__content cd-hero__content--bg-video js-cd-bg-video" style="background:rgba(0, 0, 0, 0.5);">
                    <video poster="assets/video/Dancing-Bulbs.webm" id="bgvid" playsinline autoplay muted loop style="opacity:0.8em;">
                      <source src="assets/video/Dancing-Bulbs.webm" type="video/webm">
                      <source src="assets/video/Dancing-Bulbs.mp4" type="video/mp4">
                      </video>
                </div> -->
            </li>
        </ul>
        <!-- .cd-hero__slider -->

    </section>
    <!-- .cd-hero -->

    <div class="container-fluid">
        <div class="row">


            <section class="u-PaddingTop70 u-PaddingBottom70 u-xs-PaddingTop20 u-xs-PaddingBottom20">
                <div class="container">
                    <div class="row u-MarginBottom20 u-xs-MarginBottom50 text-center">
                        <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1">
                            <div class="Heading" data-title="Food Ordering Delivery System">
                                <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                                <h1 class="text-uppercase u-Weight800 u-Margin0">Food Ordering and Delivery System<span
                                        class="Dot"></span></h1>
                                <P style="margin: 20px 15px 0 15px">As the world is heading towards a new era which is
                                    modern, vibrant and fast, so do
                                    the people who live in it. People are so dynamic and vibrant; they need everything
                                    in a snap. And even more, many are not willing to wait for a long time. Food is one
                                    of the main things where people lose their patience, especially when it comes to
                                    waiting for prolonged hours. Food delivery management software is designed to order
                                    their favorite food online and to get it delivered anywhere the customer wish. And
                                    also they can pay online or manually at the time of delivery. This software can be
                                    installed in mobile phones and can be used whenever and wherever the user wishes.
                                </P>
                            </div>
                        </div>
                    </div>
                    <div class="row u-FlexCenter u-sm-Block">
                        <div
                            class="col-md-4 col-md-push-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 u-sm-MarginBottom30 u-xs-MarginBottom0">
                            <img class="img-responsive lazyload" src="assets/imgs/product3.png" alt="...">
                        </div>
                        <div class="col-md-4 col-md-pull-4 col-sm-6 col-xs-12">
                            <div class="panel-group u-PaddingRight20 u-sm-PaddingRight0 text-right text-left--xs u-MarginTop50"
                                id="accordion7">
                                <div class="panel panel-shadow">
                                    <div class="Blurb ">
                                        <div class="media feature_text" style="min-height:150px !important;">
                                            <div class=" media-middle--">
                                                <div class="Thumb" style="text-align:left;">
                                                    <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                        aria-hidden="true"></i>
                                                    <a class="Blurb__hoverText u-MarginTop5">User Registration </a>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="u-LineHeight2">Simple and easy registration steps to taste the
                                                    delicious food.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="panel panel-shadow">
                                    <div class="Blurb ">
                                        <div class="media feature_text" style="min-height:150px !important;">
                                            <div class=" media-middle--">
                                                <div class="Thumb" style="text-align:left;">
                                                    <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                        aria-hidden="true"></i>
                                                    <a class="Blurb__hoverText u-MarginTop5">Order Tracking </a>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="u-LineHeight2">Track food orders and get real-time status of
                                                    the order.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel panel-shadow">
                                    <div class="Blurb ">
                                        <div class="media feature_text" style="min-height:150px !important;">
                                            <div class=" media-middle--">
                                                <div class="Thumb" style="text-align:left;">
                                                    <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                        aria-hidden="true"></i>
                                                    <a class="Blurb__hoverText u-MarginTop5"> Delivery Tracking </a>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="u-LineHeight2">Once the food is approved by the restaurant it
                                                    will pass to the nearest delivery boy who can take the order and
                                                    delivery to the customer respectively.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-shadow">


                                    <div class="Blurb ">
                                        <div class="media feature_text" style="min-height:150px !important;">
                                            <div class=" media-middle--">
                                                <div class="Thumb" style="text-align:left;">
                                                    <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                        aria-hidden="true"></i>
                                                    <a class="Blurb__hoverText u-MarginTop5"> Reviews and Ratings </a>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="u-LineHeight2">Customers can give reviews and ratings
                                                    according to restaurant service, food quality, pricing and other
                                                    factors.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="panel-group u-PaddingRight20 u-sm-PaddingRight0 text-left u-MarginTop50"
                                id="accordion8">
                                <div class="panel panel-shadow">


                                    <div class="Blurb ">
                                        <div class="media feature_text" style="min-height:150px !important;">
                                            <div class=" media-middle--">
                                                <div class="Thumb">
                                                    <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                        aria-hidden="true"></i>
                                                    <a class="Blurb__hoverText u-MarginTop5"> Select
                                                        Restaurant/Food/Cuisines </a>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="u-LineHeight2">Pick nearest based on the preferable locations,
                                                    food choices, and suitable timings.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-shadow">

                                <div class="Blurb ">
                                    <div class="media feature_text" style="min-height:150px !important;">
                                        <div class=" media-middle--">
                                            <div class="Thumb">
                                                <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                    aria-hidden="true"></i>
                                                <a class="Blurb__hoverText u-MarginTop5"> List of Categories </a>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="u-LineHeight2">Select a restaurant by checking customers’ reviews
                                                and ratings.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-shadow">

                                <div class="Blurb ">
                                    <div class="media feature_text" style="min-height:150px !important;">
                                        <div class=" media-middle--">
                                            <div class="Thumb">
                                                <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                    aria-hidden="true"></i>
                                                <a class="Blurb__hoverText u-MarginTop5"> Discounts and Offers </a>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="u-LineHeight2">Attractive discounts and offers can be integrated
                                                for the customers to get the best deals.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="panel panel-shadow">

                                <div class="Blurb ">
                                    <div class="media feature_text" style="min-height:150px !important;">
                                        <div class=" media-middle--">
                                            <div class="Thumb">
                                                <i class="Blurb__hoverText Icon Icon-form Icon--32px"
                                                    aria-hidden="true"></i>
                                                <a class="Blurb__hoverText u-MarginTop5"> Manage Profile </a>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="u-LineHeight2">Manage payment details, profiles, notifications,
                                                addresses for a better experience.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
    <div class="spacing" style="background:#a70303
                url('assets/imgs/bg_earth.png') no-repeat right
                bottom;background-size: 50%;height:180px; ">
        <div class="container">
            <div class="purch-wrp text-center">
                <div class="purch-inr">
                    <h3 itemprop="headline" class="text-white animatable fadeInUp">Lets talk about how we can help you
                        to grow.
                    </h3>
                    <div style="margin-top:40px">
                    <a class="theme-btn hrz theme-bf-bg text-white bg-dark" href="contact-us.php" style="padding:8px; border-radius: 8px;" title=""
                        itemprop="url">Let's Talk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>
    </div>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a class="whats-app" href="https://api.whatsapp.com/send?phone=+919940909625&text=Hello!." target="_blank"><i
            class="fa fa-whatsapp my-float"></i>
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
            text-align: center;
            width: 120px;
        }

        #sticky-social a:hover span {
            left: 100%;
        }

        #sticky-social a[class*="whatsapp"],
        #sticky-social a[class*="whatsapp"]:hover,
        #sticky-social a[class*="whatsapp"] span {
            background: #25d366;
        }

        #sticky-social a[class*="skype"],
        #sticky-social a[class*="skype"]:hover,
        #sticky-social a[class*="skype"] span {
            background: #00aff0;
        }
    </style>

    <!-- <div style=" position: fixed;top: 0px;left: 0px;width: 100%;height: 100vh;background: #fff;z-index: 200;" class="window-loader col-12">
        <div class="loader-div" style="width: 22%;margin: 0px auto;margin-top: 10%;text-align: center;"><img src="https://www.nplustechnologies.com/taxiappz-new/assets/imgs/loader.gif" width="100%" alt=""></div>
    </div> -->
    <!-- <div class="window-loader col-12">
        <div class="loader-div">
            <img src="assets/imgs/loader.gif" width="100%" alt="">
        </div>
    </div> -->
    <!--footer start-->
    <footer class="bg-darker u-PaddingTop30">
        <div class="container text-sm">
            <div class="row">
                <div class="col-md-3 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight800 u-LetterSpacing2 u-MarginTop0">Sitemap</h5>
                    <ul
                        class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0">
                        <li> <a href="https://www.taxiappz.com/"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Home</a></li>
                        <li> <a href="features"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Features</a></li>
                        <li> <a href="portfolio"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Portfolio</a></li>
                        <!-- <li> <a href="demo"><i class="fa fa-angle-right u-MarginRight10" aria-hidden="true"></i>Demo</a></li> -->
                        <li> <a href="contact"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Contact Us</a></li>
                                    <li> <a href="privacy-policy"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Privacy Policy</a></li>

                    </ul>
                </div>
                <div class="col-md-3 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight800 u-LetterSpacing2 u-MarginTop0">Our Locations</h5>
                    <ul
                        class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0">
                        <li>
                            <a target="_blank"
                                href="https://www.google.com/maps/place/Taxi+Appz/@11.0148674,76.9803806,17z/data=!4m13!1m7!3m6!1s0x0:0x2149702fe300260f!2sTaxi+Appz!3b1!8m2!3d11.0148674!4d76.9825693!3m4!1s0x0:0x2149702fe300260f!8m2!3d11.0148674!4d76.9825693?hl=en-IN"><img
                                    src="assets/imgs/flags/flag.png" width="25px" class=" u-MarginRight10">India</a>
                        </li>
                        <li>
                            <a target="_blank"
                                href="https://www.google.com/maps/place/Taxi+Appz/@25.2518854,51.5578418,17z/data=!3m1!4b1!4m5!3m4!1s0x0:0x68cbeedd3e141567!8m2!3d25.2518854!4d51.5600305?hl=en-IN"><img
                                    src="assets/imgs/flags/flag2.png" width="25px" class=" u-MarginRight10">Middle East
                            </a>
                        </li>
                        <!-- <li>
                            <a target="_blank" href="https://www.google.co.in/maps/place/5+Belfast+Ave,+Slough+SL1+3HE,+UK/@51.5214442,-0.6100953,17z/data=!3m1!4b1!4m5!3m4!1s0x4876652f2af0016d:0xafc9bb93be413149!8m2!3d51.5214442!4d-0.6079066?hl=en"><img src="assets/imgs/flags/flag3.png" width="25px" class=" u-MarginRight10">United Kingdom
                            </a>
                        </li> -->
                        <li>
                            <a target="_blank"
                                href="https://www.google.co.in/maps/place/17193+Castello+Cir,+San+Diego,+CA+92127,+USA/@33.0251485,-117.1233497,17z/data=!3m1!4b1!4m5!3m4!1s0x80dbf6f98dd3bf77:0x483a9f5fe67bb82f!8m2!3d33.025144!4d-117.121161?hl=en"><img
                                    src="assets/imgs/flags/flag1.png" width="25px" class=" u-MarginRight10">USA</a>
                        </li>
                        <!-- <li>
                            <a target="_blank" href="https://www.google.co.in/maps/place/Calle+Central+2,+Santo+Domingo,+Dominican+Republic/@18.4519434,-69.9537494,15.17z/data=!4m5!3m4!1s0x8ea5621ec99ae383:0x45c147faab9f5573!8m2!3d18.4468506!4d-69.9495871?hl=en"><img src="assets/imgs/flags/flag4.png" width="25px" class=" u-MarginRight10">Dominican Republic
                            </a>
                        </li> -->
                    </ul>
                </div>
                <div class="col-md-3 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight800 u-LetterSpacing2 u-MarginTop0">Contact Us</h5>
                    <ul class="light-gray-link list-unstyled u-MarginBottom0">
                        <li class="u-MarginBottom15">
                        <p>1A, Spectrum building Phase - 2,<br> Pappanaicken palayam,<br> Coimbatore-641037, <br> Tamil Nadu, India.</p>
                        </li>
                    </ul>
                    <p>© 2016 Taxiappz. <br /> Email: <a href="mailto:sales@taxiappz.com">sales@taxiappz.com</a>
                    </p>
                </div>
                <div class="col-md-3">
                    <h5 class="text-uppercase u-Weight800 u-LetterSpacing2 u-MarginTop0">Subscribe</h5>

                    <form action="">
                        <div class="input-group mt10 hwenewsletter">
                            <input required="" name="newsleterEmail" class="form-control input-md"
                                placeholder="Enter Email" type="email">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit"
                                    style="padding: 0 20px;border: 1px solid #b10303">
                                    <span>
                                        <i class="fa fa-paper-plane"></i>
                                    </span>
                                </button>
                            </span>
                        </div>
                    </form>

                    <h5 class="text-uppercase u-Weight800 u-LetterSpacing2 u-MarginTop50">We are Social</h5>
                    <div class="social-links sl-default gray-border-links border-link circle-link colored-hover">
                        <a href="#" class="facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" class="g-plus">
                            <i class="fa fa-google-plus"></i>
                        </a>
                        <!-- <a href="#" class="youtube">
                            <i class="fa fa-youtube"></i>
                        </a>
                        <a href="#" class="dribbble">
                            <i class="fa fa-dribbble"></i>
                        </a> -->
                    </div>
                </div>

            </div>
        </div>
        <div class="text-center u-MarginTop30">
            <div class="footer-separator"></div>
            <p class="text-center u-PaddingTop10 u-PaddingBottom10 u-MarginBottom0">Copyright 2016 @ Taxiappz.</p>
        </div>
    </footer>
    <!--footer end-->

    <!-- inject:js -->
    <script src="assets/vendor/jquery/jquery-1.12.0.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/bootsnav/js/bootsnav.js"></script>
    <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/vendor/jquery.countTo/jquery.countTo.min.js"></script>
    <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="assets/vendor/jquery.appear/jquery.appear.js"></script>
    <script src="assets/vendor/parallax.js/parallax.min.js"></script>
    <script src="assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendor/switchery/switchery.min.js"></script>
    <script src="assets/vendor/swiper/js/swiper.min.js"></script>
    <script src="assets/js/alien.js"></script>
    <!-- endinject -->


    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCVkU7YnGfown4_i_sm6X36HP2jWTv54&callback=initMap ">
        </script>
    <script>
        $(window).load(function () {
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


    <script src="//www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function myAction() {
            window.lintrk('track', { conversion_id: 8994516 });
        }
    </script>


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
                success: function (data) {
                    if (data != null) {
                        $("#countries").val(data.country);
                    }

                }
            });
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>

    <script type="text/javascript" async="async"
        src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/2.5.0/parsley.min.js"></script>
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
            geoIpLookup: function (callback) {
                $.get("//ipinfo.io", function () { }, "jsonp").always(function (resp) {
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


    <script src="assets/vendor/slider/js/main.js"></script>
    <!-- Resource JavaScrip -->

    <!-- <script type="text/javascript">
        jQuery("body").prepend(
            '<div style=" position: fixed;top: 0px;left: 0px;width: 100%;height: 100vh;background: #fff;z-index: 200;" class="window-loader col-12"><div class="loader-div" style="width: 22%;margin: 0px auto;margin-top: 10%;text-align: center;" ><img src="https://www.nplustechnologies.com/taxiappz-new/assets/imgs/loader.gif" width="100%" alt=""></div></div>'
        );
        $(window).load(function() {
            jQuery(".window-loader").css("display", "block");
        });
    </script> -->
    <script>
        $(window).load(function () {
            $('.window-loader').fadeOut("slow");
        });


    </script>
    <script>
        window.onscroll = function () {
            myFunction()
        };

        var header = document.getElementById("nav_sticky");
        var sticky = header.offsetTop;

        function myFunction() {
            if (document.body.scrollTop > 800 || document.documentElement.scrollTop > 800) {
                header.classList.add("nav_sticky1");
            } else {
                header.classList.remove("nav_sticky1");
            }
        }


        // $(document).ready(function() {
        //     $(".window-loader").css("display", "block");
        // });
    </script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/63f6f5084247f20fefe223cc/1gpuaqan5';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->

</body>

</html>
