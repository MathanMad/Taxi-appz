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
                $sender = "dhanabal.nplus@gmail.com";
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

                $from = "Taxiappz <dhanabal.nplus@gmail.com>";
                //    $to       = "packiyaraj.nplus@gmail.com";
                $to = "Sales <dhanabal.nplus@gmail.com>";
                $subject = "Lead #000" . $m . " - " . $Contact_name;
                $body = $Body;
                $host = "ssl://smtp.gmail.com";
                $username = "dhanabal.nplus@gmail.com";
                $password = "snbkfoxdsrudbkli";
                //              $username = "testersmail2014@gmail.com";
//              $password = "nplustech2014";
                $headers = array(
                    'From' => $from,
                    'To' => $to,
                    'Subject' => $subject,
                    'MIME-Version' => 1,
                    'Content-type' => 'text/html;charset=iso-8859-1'
                );
                $smtp = Mail::factory('smtp', array(
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

    <title>Taxiappz :: Demo</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
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


    <!-- revolution css -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Poppins:600%2C300%2C700">
    <style>
        .cd-hero__slider {
            height: 650px;
        }

        @media (max-width: 640px) {
            .cd-hero.js-cd-hero.js-cd-autoplay {
                height: 390px;
            }

            .cd-hero__slider {
                height: 350px;
            }

            .u-xs-MarginTop30,
            .col-md-4.col-sm-7.u-xs-MarginTop60 {
                margin-top: 0px;
                text-align: center;
            }

            .col-md-4.col-sm-7.u-xs-MarginTop30 img,
            .col-md-4.col-sm-7.u-xs-MarginTop60 img {
                width: 55%;
            }

            .u-xs-PaddingTop70 {
                padding-top: 0px;
            }

            .app_demo a img {
                width: 35%;
            }

            .u-PaddingTop30 {
                padding-top: 0px;
            }

            .u-MarginTop30 {
                margin-top: 0px;
            }

            .dropdown img {
                cursor: pointer;
                width: 50%;
            }
        }

        nav.navbar.bootsnav ul.nav>li>a {
            font-weight: bold !important;
            font-size: 15px !important;
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
                    <a class="navbar-brand" href="https://www.taxiappz.com/"><img src="assets/imgs/logo.png"
                            class="logo logo-scrolled" alt=""></a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="" data-out="">
                        <li><a href="https://www.taxiappz.com/">Home</a></li>
                        <li><a href="features">Features</a></li>
                        <li class="active"><a href="demo">Demo</a></li>
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
                            <label for="name">Name <span style="color:#ff0000;">*</span></label>
                            <input type="text" style="color:black !important;" placeholder="Name" name="contact_name"
                                data-error="You must enter name" required class="form-control">
                            <?php if (isset($_SESSION['nameErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['nameErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="email">Email <span style="color:#ff0000;">*</span></label>
                            <input type="email" style="color:black !important;" name="contact_email"
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
                            <label for="name">Mobile Number <span style="color:#ff0000;">*</span></label>
                            <input id="phone" class="form-control" onkeypress="return isNumberKey(event)"
                                name="contact_mobile_number" type="tel" onchange="getNumber()">
                            <?php if (isset($_SESSION['phoneErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['phoneErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="message">Message <span style="color:#ff0000;">*</span></label>
                            <textarea placeholder="Enter Your Message" rows="2" cols="3" name="contact_message"
                                data-parsley-required="true" required style="color:black !important;"
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="form_submit" class="btn btn-default">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <section class="cd-hero js-cd-hero js-cd-autoplay">
        <ul class="cd-hero__slider">
            <li
                class="cd-hero__slide cd-hero__slide--video js-cd-slide cd-hero__slide--selected cd-hero__slide--from-right">
                <div class="cd-hero__content cd-hero__content--full-width text-center" style="padding-top:0px;">
                    <h1 class="type_head flim_strip">The Taxi Software <br>You Have Been Waiting For.<br>
                        <span>Robust back office management tools that automates bookings, dispatching, billing &
                            ratings. </span>
                        <br>
                        <i class="fa fa-arrow-down arrow bounce"></i>
                    </h1>
                </div>
                <!-- .cd-hero__content -->

                <div class="cd-hero__content cd-hero__content--bg-video js-cd-bg-video" style="background:rgba(0, 0, 0, 0.5);z-index: 6;
                mix-blend-mode: screen;">
                    <img src="assets/video/ezgif.com-video-to-gif.gif" class="animate_img" width="100%" height="100%">
                </div>
                <!-- .cd-hero__content -->
            </li>
        </ul>
        <!-- .cd-hero__slider -->

    </section>
    <!-- .cd-hero -->

    <!-- <section class="ImageBackground ImageBackground--gray v-align-parent u-height200">
        <div class="ImageBackground__holder">
            <img src="assets/imgs/banner/banner-request-demo.jpg" alt="...">
        </div>
        <div class="v-align-child">
            <div class="container">
                <div class="row  text-white">
                    <div class="col-md-8 col-xs-12">
                        <h3 class="text-uppercase u-Margin0 u-Weight300">Portfolio External</h3>
                        <p class="u-LineHeight2 text-muted u-Margin0">Description of the portfolio image.</p>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <ol class="breadcrumb u-MarginTop5 u-MarginBottom0 pull-right  text-white">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Library</a></li>
                            <li class="active"><span>Data</span></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </section> -->

    <!--image block start-->
    <div class="container">
        <section class="u-PaddingTop30 u-PaddingBottom0 u-xs-PaddingTop70 u-xs-PaddingBottom70">
            <div class="u-MarginTop0 u-xs-MarginTop0">
                <div class="row u-FlexCenter u-xs-Block">
                    <div class="col-md-4 col-sm-7 u-xs-MarginTop30">
                        <img src="assets/imgs/demo/mockup_right.png" width="100%">
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="Heading text-center" data-title="Customer">
                            <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                            <h1 class="text-uppercase u-Weight800 u-Margin0">Customer App<span class="Dot"></span></h1>
                        </div>
                        <p class="u-MarginTop30 u-MarginBottom30 text-justify">Customer app is downloaded from play
                            store by passengers on their device who would like to take the services of a particular taxi
                            company. Any smartphone supporting mobile data and Wi-Fi will serve as passenger device.
                            Passenger
                            app will have its taxi company brand name.
                        </p>
                        <!-- <div class="app_demo">
                            <a href="https://play.google.com/store/apps/details?id=com.taxiappz.client&hl=en">
                                <img src="assets/imgs/icons/google-store-badge.png" height="60px">
                            </a>
                            <a href="https://itunes.apple.com/us/app/taxiappz-customer/id1159675902">
                                <img src="assets/imgs/icons/app-store-badge.png" height="60px">
                            </a>
                        </div> -->
                    </div>
                    <div class="col-md-4 col-sm-7 u-xs-MarginTop30">
                        <img src="assets/imgs/demo/mockup_left.jpg" width="100%">
                    </div>
                </div>
            </div>
        </section>
        <hr>
        <section class="u-PaddingTop00 u-PaddingBottom30 u-xs-PaddingTop70 u-xs-PaddingBottom70">
            <div class="u-MarginTop10 u-xs-MarginTop0">
                <div class="row u-FlexCenter u-xs-Block">
                    <div class="col-md-4 col-sm-7 u-xs-MarginTop60">
                        <img src="assets/imgs/demo/mockup_driver_right.png" width="100%">
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="Heading text-center" data-title="Driver">
                            <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                            <h1 class="text-uppercase u-Weight800 u-Margin0">Driver App<span class="Dot"></span></h1>
                        </div>
                        <p class="u-MarginTop30 u-MarginBottom30">Driver app is downloaded from play store by drivers on
                            their device who would like to serve particular taxi company. Any smartphone supporting
                            mobile data and Wi-Fi will serve as driver device. Driver app will have its taxi company
                            brand name. Driver has to register themselves using email id to access the services.</p>
                        <!-- <div class="app_demo">
                            <a href="https://play.google.com/store/apps/details?id=com.taxiappz.driver&hl=en">
                                <img src="assets/imgs/icons/google-store-badge.png" height="60px">
                            </a>
                            <a href="https://itunes.apple.com/us/app/taxiappz-driver/id1159695717">
                                <img src="assets/imgs/icons/app-store-badge.png" height="60px">
                            </a>
                        </div> -->
                    </div>

                    <div class="col-md-4 col-sm-7 u-xs-MarginTop60">
                        <img src="assets/imgs/demo/mockup_driver_leftt.png" width="100%">
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--image block end-->

    <div class="row">
        <div class="col-md-12">
            <section class="u-PaddingTop0 u-PaddingBottom30">
                <div class="col-md-6 col-sm-12 u-Margin50 text-center">
                    <div class="Heading text-center" data-title="Admin">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h1 class="text-uppercase u-Weight800 u-Margin0">Admin Panel<span class="Dot"></span></h1>
                    </div>
                    <br>
                    <img src="assets/imgs/demo/admin.png" width="90%">
                    <br>
                    <!-- <a class="pricing_btn" href="#">
                        REQUEST DEMO
                    </a> -->
                </div>
                <div class="col-md-6 col-sm-12 u-Margin50 text-center">
                    <div class="Heading text-center" data-title="Dispatcher">
                        <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div>
                        <h1 class="text-uppercase u-Weight800 u-Margin0">Dispatcher Panel<span class="Dot"></span></h1>
                    </div>
                    <br>
                    <img src="assets/imgs/demo/dispatcher_admin.png" width="90%">
                    <br>
                </div>
                <center>
                    <a class="btn btn-primary" data-toggle="modal" data-target="#contact-modal">GET A FREE REQUEST
                        DEMO</a>
                </center>
            </section>
        </div>
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
                            <label for="name">Name <span style="color:#ff0000;">*</span></label>
                            <input type="text" style="color:black !important;" placeholder="Name" name="contact_name"
                                data-error="You must enter name" required class="form-control">
                            <?php if (isset($_SESSION['nameErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['nameErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="email">Email <span style="color:#ff0000;">*</span></label>
                            <input type="email" style="color:black !important;" name="contact_email"
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
                            <label for="name">Mobile Number <span style="color:#ff0000;">*</span></label>
                            <input id="phone" class="form-control" onkeypress="return isNumberKey(event)"
                                name="contact_mobile_number" type="tel" onchange="getNumber()">
                            <?php if (isset($_SESSION['phoneErr'])) { ?>
                                <div class="help-block with-errors">
                                    <?= $_SESSION['phoneErr']; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label for="message">Message <span style="color:#ff0000;">*</span></label>
                            <textarea placeholder="Enter Your Message" rows="2" cols="3" name="contact_message"
                                data-parsley-required="true" required style="color:black !important;"
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="form_submit" class="btn btn-default">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <a href="https://api.whatsapp.com/send?phone=9940909625&text=Hello!." class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>

    <a href="skype:Taxiappz?chat" class="floats" target="_blank"><i class="fa fa-skype" aria-hidden="true"></i></a>


    <style>
        .float {
            position: fixed;
            width: 50px;
            height: 50px;
            bottom: 40px;
            left: 5px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
        }

        .my-float {
            margin-top: 10px;
        }



        .floats {
            position: fixed;
            width: 50px;
            height: 50px;
            bottom: 40px;
            top: 480px;
            left: 5px;
            background-color: #00aff0;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
        }

        .my-floats {
            margin-top: 10px;
        }
    </style>

    <!--footer start-->
    <footer class="bg-darker u-PaddingTop30 u-MarginTop30">
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
                        <li> <a href="demo"><i class="fa fa-angle-right u-MarginRight10" aria-hidden="true"></i>Demo</a>
                        </li>
                        <li> <a href="contact"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Contact Us</a></li>

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
    <!-- <div class="window-loader col-12">
        <div class="loader-div">
            <img src="assets/imgs/loader.gif" width="100%" alt="">
        </div>
    </div> -->

    <!-- inject:js -->
    <script src="assets/vendor/jquery/jquery-1.12.0.min.js "></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js "></script>
    <script src="assets/vendor/bootsnav/js/bootsnav.js "></script>
    <script src="assets/vendor/waypoints/jquery.waypoints.min.js "></script>
    <script src="assets/vendor/jquery.countTo/jquery.countTo.min.js "></script>
    <script src="assets/vendor/owl.carousel/owl.carousel.min.js "></script>
    <script src="assets/vendor/jquery.appear/jquery.appear.js "></script>
    <script src="assets/vendor/parallax.js/parallax.min.js "></script>
    <script src="assets/vendor/isotope/isotope.pkgd.min.js "></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.js "></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js "></script>
    <script src="assets/vendor/switchery/switchery.min.js "></script>
    <script src="assets/vendor/swiper/js/swiper.min.js "></script>
    <script src="assets/js/alien.js "></script>
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
    <script>
        $(window).load(function () {
            $('.window-loader').fadeOut("slow");
        });
    </script>

    <script type="text/javascript">
        window.__lc = window.__lc || {};
        window.__lc.license = 8233301;
        (function () {
            var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
            lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
        })();
    </script>
</body>

</html>