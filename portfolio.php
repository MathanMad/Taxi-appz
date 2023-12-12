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
    <title>TaxiAppz Portfolio</title>
    <meta name="description" content="Choose your preferred package that suits to your taxi business. One time payments with no monthly burdens or extra charges. ">
    <meta name="keywords" content="Choose your preferred package that suits to your taxi business. One time payments with no monthly burdens or extra charges. ">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Heebo:100%7COpen+Sans:300,400,400i,600,700,800">
    <link rel="canonical" href="https://www.taxiappz.com/portfolio" />
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:600%2C300%2C700">
    <link rel="stylesheet" href="assets/vendor/slider/css/style.css">
    <!-- endinject -->



    <!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'ede1c9cdfd9fe4792fdf952da007b4b624d05309';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>
    <style>
        nav.navbar.bootsnav ul.nav>li>a {
            font-weight: bold !important;
            font-size: 15px !important;
        }
        
        @media (max-width: 640px) {
            .dropdown img {
                cursor: pointer;
                width: 50%;
            }
            .portfolio_screens {
                margin: 0px;
                padding: 0px;
            }
        }

            .popup-btn{
            position: fixed;
            width: 165px;
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


            @media only screen and (max-width: 600px) {

            .popup-btn{
            position: fixed;
            width: 148px;
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
                    <a class="navbar-brand" href="https://www.taxiappz.com/"><img src="assets/imgs/logo.png" class="logo logo-scrolled" alt=""></a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="" data-out="">
                        <li><a href="https://www.taxiappz.com/">Home</a></li>
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
                        <li class="active"><a href="portfolio">Portfolio</a></li>
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
                                                <div class="help-block with-errors"><?= $_SESSION['nameErr']; ?></div>
                            <?php } ?>
                    </div>

                    <div class="form-group" style="margin-bottom:10px;">
                        <label for="email" style="font-size:16px;">Email <span style="color:#ff0000;">*</span></label>
                        <input type="email"  style="color:black !important;  height:30px;" name="contact_email" class="form-control" placeholder="Email" data-parsley-required="true" required>
                        <?php if (isset($_SESSION['emailErr'])) { ?>
                                                <div class="help-block with-errors"><?= $_SESSION['emailErr']; ?></div>
                            <?php } ?>
                    </div>

                    <input id="dialcodes" name="dialcodes" type="hidden" value="">
                    <input id="countryName" name="countryName" type="hidden" value="" >


                    <div class="form-group" style="margin-bottom:10px;">
                    
                        <label for="name" style="font-size:16px;">Mobile Number <span style="color:#ff0000;">*</span></label>
                        <input id="phone"  class="form-control" onkeypress="return isNumberKey(event)" name="contact_mobile_number" type="tel" onchange="getNumber()" >
                            <?php if (isset($_SESSION['phoneErr'])) { ?>
                                                    <div class="help-block with-errors"><?= $_SESSION['phoneErr']; ?></div>
                                    <?php } ?>
                    </div>

                    
                    <div class="form-group" style="margin-bottom:10px;">
                        <label for="message" style="font-size:16px; ">Message <span style="color:#ff0000;">*</span></label>
                        <textarea  placeholder="Enter Your Message" rows="2" cols="3" name="contact_message" data-parsley-required="true" required  style="color:black !important;  min-height:30px;" class="form-control"></textarea>
                        <?php if (isset($_SESSION['messageErr'])) { ?>
                                                    <div class="help-block with-errors"><?= $_SESSION['messageErr']; ?></div>
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

    <!--page title start-->
    <section class="ImageBackground ImageBackground--gray u-height330 v-align-parent ">
        <div class="ImageBackground__holder ">
            <img src="assets/imgs/portfolio.jpg" alt="..." />
        </div>
        <div class="v-align-child">
            <div class="container ">
                <div class="row ">
                    <div class="col-md-8 col-xs-12 text-white">
                        <h1 class=" u-Margin0 u-Weight300" >Portfolio</h1>
                        <h5 class="u-LineHeight2 text-muted u-Margin0 fs-125">Our Portfolio Description</h5>
                    </div>

                    <div class="col-md-4 col-xs-12 ">
                        <ol class="breadcrumb text-white u-MarginTop25 u-MarginBottom0 pull-right" style="font-size:18px">
                            <li><a href="#">Home</a></li>
                            <li class="active"><span>Portfolio</span></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--page title end-->


    <div class="container u-MarginTop30  u-MarginBottom100 u-xs-MarginBottom30 items_group clearfix pagination__list">
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Petra Ride</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row ">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/petra.png" width="80%">
                </div>
                <div class="col-md-8" >
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Petra Ride is a first Jordanian transportation which focused on customer and driver satisfaction at the first step. It is one of the toughest competitor in the Jordan with Uber and careem which are succeeding in their domain very well. This app has all
                            the latest updates and technologies which is paying way to security and satisfaction on both ends.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.PetraRide_Captain" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.PetraRide_User" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://apps.apple.com/us/app/petra-ride-%D8%A8%D8%AA%D8%B1%D8%A7-%D8%B1%D8%A7%D9%8A%D8%AF/id1463809354?ls=1" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://apps.apple.com/us/app/petra-ride-%D8%A8%D8%AA%D8%B1%D8%A7-%D8%B1%D8%A7%D9%8A%D8%AF/id1463809354?ls=1" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/petra/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/petra/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/petra/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/petra/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Fast Taxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row ">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Fast Taxi, as the name suggests, which renders the service to the People which can help them in transportation through its dynamic mobile apps which has awesome UI designs with few improvements in its Interface. The customers can select the point of start
                            and stop locations in the mobile app. The app is integrated with global payment gateway which will give most secure way of transaction.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.fasttaxi.drivers" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.fasttaxi.users" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/fasttaxi-nigeria-driver/id1416635134" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/fasttaxi-nigeria/id1416634537?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fast/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fast/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fast/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fast/4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/fast.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">UI Taxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row ">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/u_taxi/u_taxi.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            U Taxi as the name suggests, which renders the service to the People which can help them in transportation through its dynamic mobile apps which has awesome UI designs with few improvements in its Interface. The customers can select the point of start
                            and stop locations in the mobile app. The app is integrated with global payment gateway which will give most secure way of transaction.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=taxi.u.partner" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=taxi.u.client" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/utaxi-driver/id1188673754" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/utaxi/id1184381076" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/u_taxi/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/u_taxi/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/u_taxi/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/u_taxi/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Domonican Republic Government project – 911 NATIVO
                            </h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            911 Nativo project which has been developed from the interests of the Government ministries of Dominican Republic. We are very glad to develop such a prestigious and a mandate project for the citizens t. It is a completely free application that emerges
                            as an indispensable solution to respond to an aggravated evil such as Delinquency; which expands throughout the planet as if it were an uncontrollable epidemic, affecting people of all ages; no matter what your lifestyle or
                            social level. Nowadays, citizen insecurity is a factor of globalized tendency.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.universal911.hero" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.universal911.user" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/911/911_1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/911/911_2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/911/911_3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/911/911_4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/911/911.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">SahlTaxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/sahltaxi/sahltaxi.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            SahlTaxi as the name suggests it’s a Arabic app with the specialized in RTL interface which can fits the country people to understand the app. The customers can schedule the trip with the driver and get the payment after the trip closes with the help
                            of credit/debit card and also with the virtual wallet also.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.sahltaxi.passenger" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.sahltaxi.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/qa/app/sahl-driver/id1455325535?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/qa/app/sahl-taxi/id1455325390?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/sahltaxi/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/sahltaxi/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/sahltaxi/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/sahltaxi/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Eco Taxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Eco Taxi is a Mexico based taxi booking platform and is one of our innovative taxi booking platform with additional features like a subscription model for the drivers whereas they can engage with the taxi booking services for a certain period and got
                            terminated after it completed.The company has developed the taxi mobile application to create a strong online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.

                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.eco.taxi.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.eco.users" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/eco-ride-driver/id1396499846" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/eco-ride-user/id1396399276?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/eco/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/eco/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/eco/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/eco/4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/eco/eco.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Allocab</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/allocab/allocab.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Allocab is the app which renders the motorbike along with the cab and chauffeur services with an eco friendly design oriented and also has the separate rental package to suits up on and this also helps in achieving growth objectives and brand awareness.
                            And it has created a strong online presence among its passengers.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.allocab.allocabpassenger" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/fr/app/allocab-chauffeur-prive-vtc/id562233261?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/allocab/1.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/allocab/2.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/allocab/3.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/allocab/4.jpg" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">America Taxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            America Taxi, as the name suggests, which renders the service to the America patriots which can help them in transportation through its dynamic mobile apps which has awesome UI designs with few improvements in its Interface. USA based mobile app is developed
                            to make taxi booking easier than ever. The customers can select the point of start and stop locations in the mobile app. The app is integrated with global payment gateway which will give most secure way of transaction.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.americantaxi.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.americantaxi.client" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/america-taxi-driver/id1396397620?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/america-taxi/id1396397608?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/american/American1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/american/American2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/american/American3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/american/American4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/american/American.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">iMove</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/imove/imove.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            iMove is a Singapore based taxi booking platform and is one of our innovative taxi booking platform with additional and custom features like they can have the own way to choose the drivers for the trip and also reward functionality as per their performances.
                            The company has developed the taxi mobile application to create a strong online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.imove.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.imove.client" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/imove/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/imove/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/imove/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/imove/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">DiDi-Rider</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            DiDi, the world’s leading rideshare app for ordering fast and reliable rides in Australia, has landed Down Under. With over 550 million users worldwide taking 30 million rides a day, this app launches with so much attractive offers and bonus for both
                            riders and users to make their presence in online with the strong foundation.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.didiglobal.passenger" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/nz/app/didi-rider/id1362398401?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/didi/1.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/didi/2.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/didi/3.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/didi/4.jpg" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/didi/didi.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">iTaxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/itaxi/itaxi.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            iTaxi is a Switzerland based taxi booking platform and is one of the huge scope product which we worked so far and their innovative taxi booking platform with additional features like multi country and multi-currency with more than 5 languages have integrated
                            and the company has developed the taxi mobile application to create a strong online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.itaxi.drivers" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.itaxi.client" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/itaxi-driver/id1248196891" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/itaxi-customer/id1248258884?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/itaxi/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/itaxi/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/itaxi/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/itaxi/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">obr</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Obr is an Iraq based booking and dispatching platform is one of huge scope product with high potential team towards it and this app has multiple languages and multi-currency support and it has designed improve safety by allowing you to view driver's name
                            and rating as well as the vehicle information before a ride. You can also see as the driver approaches in real time and rate him at the end of the ride.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.multibrains.taxi.driver.obriraq" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.multibrains.taxi.passenger.obriraq" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/gb/app/obdrive/id1334598402" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/gb/app/%D8%A7%D9%88%D8%A8%D8%B1-%D8%AA%D9%83%D8%B3%D9%8A/id1334598950?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/obr/1.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/obr/2.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/obr/3.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/obr/4.jpg" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/obr/obr.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Platinum</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/platinum/platinum.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Platinum is an El Salvador based taxi booking platform and is one of our innovative taxi booking platform with additional features like integrated with web booking engine . The company has developed the taxi mobile application to create a strong online
                            presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.platinum.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.platinum.users" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/platinum/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/platinum/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/platinum/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/platinum/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">cabonline</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Cabonline is an Sweden based taxi booking platform and is one of our innovative taxi booking platform with additional features like integrated with web booking engine it has designed improve safety by allowing you to view driver's name and rating as well
                            as the vehicle information before a ride. You can also see as the driver approaches in real time and rate him at the end of the ride.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.cabonline.cabonline&hl=sv" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/se/app/taxi-cabonline/id495111856?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/taxicab/1.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/taxicab/2.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/taxicab/3.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/taxicab/4.jpg" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/taxicab/taxicab.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Nasaro</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/nasaro/nasaro.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Nasaro is a Mexican based taxi booking platform and is one of our innovative taxi booking platform with additional features like a instant jobs and also surge pricing included with the corporate booking which leads a separate admin for the corporate and
                            manages their own employees and the trip amount too. The company has developed the taxi mobile application to create a strong online presence among its potential customers. This also helps in achieving growth objectives and
                            brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.cntaxi.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.cntaxi.clients" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/nasaro/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/nasaro/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/nasaro/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/nasaro/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">fixutaxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Fixutaxi is a Finland based taxi booking platform and is one of our innovative taxi booking platform with additional features like a instant jobs and also surge pricing included with the corporate booking which leads a separate admin for the corporate
                            and manages their own employees and the trip amount too. This app has enabled with the international payment gateway which the app can accept both credit and debit card payments
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.cabonline.fixutaxi" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/apple-store/id1350409511?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fixutaxi/1.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fixutaxi/2.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fixutaxi/3.jpg" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/fixutaxi/4.jpg" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/fixutaxi/fixutaxi.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">CRT</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/crt/crt.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            CRT is a taxi booking platform and is one of our innovative taxi booking platform with additional features like a multi country and multi currency support. The company has multiple branches around the globe so they need to open the individual branches
                            around the globe without any technical hindrances. The company has developed the taxi mobile application to create a strong online presence among its potential customers.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.crt.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.crt.client" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/crt-driver/id1441617397" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/US/app/id1441617159?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/crt/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/crt/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/crt/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/crt/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Zawar</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Zawar is a Pakistan based taxi booking platform and is one of our innovative taxi booking platform with additional features like integrated with local payment gateway. The company has developed the taxi mobile application to create a strong online presence
                            among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.zawar.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.zawar.user" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/zawar/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/zawar/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/zawar/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/zawar/4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/zawar/zawar.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Stars Go</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/starsgo/starsgo.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Stars Go is an Australia based taxi booking platform and is one of our innovative taxi booking platform with additional features like multi country and multi-currency with additional languages. The company has developed the taxi mobile application to
                            create a strong online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.starsgo.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.starsgo.user" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/starsgo/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/starsgo/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/starsgo/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/starsgo/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Tappz Cars</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Tappz Cars is an United Kingdom based taxi booking platform and is one of our innovative taxi booking platform with fixed fare for certain routes with very user friendly design. The company has developed the taxi mobile application to create a strong
                            online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.tappzcars.drivers" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.tappzcars.users" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/tappz-cars-driver/id1459830220" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/tappz-cars/id1459829286?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/tappz_cars/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/tappz_cars/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/tappz_cars/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/tappz_cars/4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/tappz_cars/tappz_cars.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Bentux</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/bentux/bentux.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Bentux is a Dominican republic taxi booking platform and is one of our rewards after completing the government project with that country. Additionally we have deployed with the bus booking feature and for the further enhancements. The company has developed
                            the taxi mobile application to create a strong online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://play.google.com/store/apps/details?id=com.bentux.driver" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=com.bentux.client" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bentux/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bentux/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bentux/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bentux/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Jayeen</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Jayeen is a Jordan based taxi booking platform and is one of our innovative taxi booking platform with additional features like a webpage that is linked to Greencab website to know more about the taxi company and its services. The company has developed
                            the taxi mobile application to create a strong online presence among its potential customers. This also helps in achieving growth objectives and brand awareness.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/in/app/jayeen-driver/id1326861390?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/in/app/jayeen/id1326857737?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/Jayeen/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/Jayeen/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/Jayeen/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/Jayeen/4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/jayeen.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">Bwite</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/bwite.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            Bwite taxi booking mobile app is deployed in Mexico. The app supports English and Spanish language to give its passengers a localized taxi service. Bwite also has all the main features of taxi apps plus it has an option to add the locations that the passengers
                            would frequently travel to in the favorite list which enables fast booking in the upcoming trips. The estimated ride fare is calculated based on the distance to the selected destination and is displayed to the passenger before
                            ride.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/bwite-driver/id1258070895?ls=1&mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/bwite/id1258068954?ls=1&mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bwite/bwite1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bwite/bwite2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bwite/bwite3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/bwite/bwite4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="heading u-MarginBottom20 portfolio_head" data-title="Domonican Republic Government">
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">BDRide</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="col-md-12" style="border-right: 2px solid #363738 !important;">
                    <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            BDRide is a Bangladesh based cab company for whom we built a cab hailing mobile platform to minimize the inconvenience for passengers in hailing taxis. This app helps taxi owners to make taxi dispatch operations effortless with innovative and intelligent
                            cab software. The passenger can select the nearest available taxi and book a ride in just a few taps. The passengers and drivers can keep track of their ongoing journey and access the upcoming and past trip details in their
                            respective mobile apps.
                        </h5>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 text-left col-sm-6 col-xs-6">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/bdride-driver/id1223019265?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/us/app/bdride/id1222909345?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-md-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/BDRide/BDRide1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/BDRide/BDRide2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/BDRide/BDRide3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/BDRide/BDRide4.png" width="100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/BDRide.png" style="width:80%;" alt="">
                </div>
            </div>
        </div>
        <div class="portfolio_screens">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 text-center">
                    <div class=" u-MarginBottom20 portfolio_head" data-title="America Taxi">
                        <!-- <div class="Split Split--height2 u-MarginTop40 u-MarginBottom5"></div> -->
                        <h4 class="text-uppercase u-Weight800 u-Margin0 " style="font-size:28px">JapanTaxi</h4>
                    </div>
                    <!-- <h3 class="text-center" style="color:  #1d33d4;font-weight: 600;"></h3> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mob-res-photo">
                    <img src="assets/imgs/splash/japantaxi/japan.png" width="80%">
                </div>
                <div class="col-md-8">
                    <div class="col-md-12" style="border-left: 2px solid #363738 !important;">
                         <h5 class="u-Margin0 fs-125 u-MarginBottom15">
                            JapanTaxi as the name suggests, which renders the service to Japanese people with their own localization suits to their nationality and culture. App owner has created a strong online presence among its passengers and riders with attractive offers, inturn
                            it can help in achieving growth objectives and brand awareness.
                        </p>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-right col-sm-6 col-xs-6 ">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/google-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="#" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://play.google.com/store/apps/details?id=jp.co.nikko_data.japantaxi" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 text-left">
                        <div class="dropdown">
                            <img src="assets/imgs/icons/app-store-badge.png" class="dropdown-toggle" data-toggle="dropdown" width="100%">
                            <ul class="dropdown-menu">
                                <li><a href="https://itunes.apple.com/us/app/%E4%B9%97%E5%8B%99%E5%93%A1%E3%82%A2%E3%83%97%E3%83%AA/id1187202310" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;border-bottom: 1px solid #eee;">Driver</a>
                                </li>
                                <li><a href="https://itunes.apple.com/app/id481647073?mt=8" target="_blank" style="text-align:center;padding-top: 10px;padding-bottom: 10px;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 1.05em;">Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 u-MarginTop30">
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/japantaxi/1.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/japantaxi/2.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/japantaxi/3.png" width="100%">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3 p-xs-0">
                            <img src="assets/imgs/splash/japantaxi/4.png" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="pagination__controls">
            <ul class="pagination">
                <li class="prev"><a href="#">«</a></li>
                <li class="active"><a href="#1">1</a></li>
                <li><a href="#2">2</a></li>
                <li><a href="#3">3</a></li>
                <li><a href="#4">4</a></li>
                <li><a href="#5">5</a></li>
                <li class="next"><a href="#">»</a></li>
            </ul>
        </div> -->
    </div>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<a  class="whats-app" href="https://api.whatsapp.com/send?phone=+919940909625&text=Hello!." target="_blank"><i class="fa fa-whatsapp my-float"></i>
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
    bottom: 15%;
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
    <!--footer start-->
    <footer class="bg-darker u-PaddingTop30 u-MarginTop30 ">
        <div class="container text-sm " style="padding-left: 60px;padding-right: 0px;">
            <div class="row">
                <div class="col-md-4 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight700 u-LetterSpacing2 u-MarginTop0">Sitemap</h5>
                    <ul class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0" style="font-size:18px">
                        <li> <a href="https://www.taxiappz.com/" class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Home</a></li>
                        <li> <a href="features" class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Features</a></li>
                        <li> <a href="portfolio" class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Portfolio</a></li>
                        <!-- <li> <a href="demo"><i class="fa fa-angle-right u-MarginRight10" aria-hidden="true"></i>Demo</a></li> -->
                        <li> <a href="contact" class="fs-125"><i class="fa fa-angle-right u-MarginRight10" aria-hidden="true"></i>Contact Us</a></li>
                        <li> <a href="privacy-policy" class="fs-125"><i class="fa fa-angle-right u-MarginRight10"
                                    aria-hidden="true"></i>Privacy Policy</a></li>
                      
                    </ul>
                </div>
                <div class="col-md-4 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight700 u-LetterSpacing2 u-MarginTop0">Our Locations</h5>
                    <ul class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0">
                        <li>
                            <a target="_blank" class="fs-125" href="https://www.google.com/maps/place/Taxi+Appz/@11.0148674,76.9803806,17z/data=!4m13!1m7!3m6!1s0x0:0x2149702fe300260f!2sTaxi+Appz!3b1!8m2!3d11.0148674!4d76.9825693!3m4!1s0x0:0x2149702fe300260f!8m2!3d11.0148674!4d76.9825693?hl=en-IN"><img src="assets/imgs/flags/flag.png" width="25px" class=" u-MarginRight10">India</a>
                        </li>
                        <li>
                            <a target="_blank" class="fs-125" href="https://www.google.com/maps/place/Taxi+Appz/@25.2518854,51.5578418,17z/data=!3m1!4b1!4m5!3m4!1s0x0:0x68cbeedd3e141567!8m2!3d25.2518854!4d51.5600305?hl=en-IN"><img src="assets/imgs/flags/flag2.png" width="25px" class=" u-MarginRight10">Middle East
                            </a>
                        </li>
                        <!-- <li>
                            <a target="_blank" href="https://www.google.co.in/maps/place/5+Belfast+Ave,+Slough+SL1+3HE,+UK/@51.5214442,-0.6100953,17z/data=!3m1!4b1!4m5!3m4!1s0x4876652f2af0016d:0xafc9bb93be413149!8m2!3d51.5214442!4d-0.6079066?hl=en"><img src="assets/imgs/flags/flag3.png" width="25px" class=" u-MarginRight10">United Kingdom
                            </a>
                        </li> -->
                        <li>
                            <a target="_blank" class="fs-125" href="https://www.google.co.in/maps/place/17193+Castello+Cir,+San+Diego,+CA+92127,+USA/@33.0251485,-117.1233497,17z/data=!3m1!4b1!4m5!3m4!1s0x80dbf6f98dd3bf77:0x483a9f5fe67bb82f!8m2!3d33.025144!4d-117.121161?hl=en"><img src="assets/imgs/flags/flag1.png" width="25px" class=" u-MarginRight10">USA</a>
                        </li>
                        <!-- <li>
                            <a target="_blank" href="https://www.google.co.in/maps/place/Calle+Central+2,+Santo+Domingo,+Dominican+Republic/@18.4519434,-69.9537494,15.17z/data=!4m5!3m4!1s0x8ea5621ec99ae383:0x45c147faab9f5573!8m2!3d18.4468506!4d-69.9495871?hl=en"><img src="assets/imgs/flags/flag4.png" width="25px" class=" u-MarginRight10">Dominican Republic
                            </a>
                        </li> -->
                    </ul>
                </div>
                <div class="col-md-4 u-xs-MarginBottom30">
                    <h5 class="text-uppercase u-Weight700 u-LetterSpacing2 u-MarginTop0">Contact Us</h5>
                    <ul class="light-gray-link list-unstyled u-MarginBottom0">
                        <li class="u-MarginBottom15">
                        <p class="fs-125">1A, Spectrum building Phase - 2,<br> Pappanaicken palayam,<br> Coimbatore-641037, <br> Tamil Nadu, India.</p>
                        </li>
                    </ul>
                    <p class="fs-125">© 2016 Taxiappz. <br /> Email: <a href="mailto:sales@taxiappz.com">sales@taxiappz.com</a>
                    </p>
                </div>

            </div>
        </div>
        <div class="text-center u-MarginTop30">
            <div class="footer-separator"></div>
            <p class="text-center u-PaddingTop10 u-PaddingBottom10 u-MarginBottom0 fs-125">Copyright 2016 @ Taxiappz.</p>
        </div>
    </footer>
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
    <script src="assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendor/switchery/switchery.min.js"></script>
    <script src="assets/vendor/swiper/js/swiper.min.js"></script>
    <script src="assets/js/alien.js"></script>
    <script src="assets/js/pagination.js"></script>
    <!-- endinject -->

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCVkU7YnGfown4_i_sm6X36HP2jWTv54&callback=initMap ">
    </script>
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

<script>
  function myAction() {
    window.lintrk('track', { conversion_id: 8994516 });
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
    <script>
        $(window).load(function() {
            $('.window-loader').fadeOut("slow");
        });
    </script>
    <script>
        $('.pagination__list').paginate({
            items_per_page: 6
        });
    </script>
    <script>
        $('.pagination li').click(function() {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    </script>



</body>

</html>
