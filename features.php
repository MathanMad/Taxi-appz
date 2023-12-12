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

    <title>TaxiAppz Features | Taxi App Development Company | Taxi App</title>
    <meta name="description" content="Taxiappz is a cloud based auto dispatch system with features like mobile apps, e-billing, invoicing, social sharing, ratings, GPS tracking, online payments and more." />
    <meta name="keywords" content= "Uber Like App Development, Uber Like App Developer , Uber Like Taxi App Development, Uber Clone Script, Best Uber Clone Script, Uber Like App Source Code, Uber App Development Cost, Best Uber Clone, Uber Like App Template, Uber Taxi App Cost, Uber Script, Uber App Clone,Uber Like App Development for iOS & Android with Uber Like App Source Code. Uber Like Taxi App Development | Uber Like App Template | Best Uber Clone | Uber Clone Script" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Heebo:100%7COpen+Sans:300,400,400i,600,700,800">
    <link rel="canonical" href="https://www.taxiappz.com/features" />
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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-bold-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-medium-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-regular-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-display-semibold-webfont.woff2">
    <link rel="preload" href="assets\fonts\sf_pro\sf-pro-text-heavy-webfont.woff">

    <!-- endinject -->

    <link rel="stylesheet" href="assets/vendor/slider/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:600%2C300%2C700">
    <!-- Resource style -->

    <!-- TYPEWRITER ADDON -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/revolution-slider/revolution-addons/typewriter/css/typewriter.css">


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
.our_head{
    color:white
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
                    <a class="navbar-brand" href="https://www.taxiappz.com/"><img src="assets/imgs/logo.png" class="logo logo-scrolled" alt=""></a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="" data-out="">
                        <li><a href="https://www.taxiappz.com/">Home</a></li>
                        <li class="active"><a href="features">Features</a></li>
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
            </div>

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
                    <button type="submit" name="form_submit"  onclick="myAction()" class="pop_btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <a name="prominent"></a>

    <!-- <section class="cd-hero js-cd-hero js-cd-autoplay" style="background: black;">
        <ul class="cd-hero__slider">
            <li class="cd-hero__slider cd-hero__slide--video js-cd-slide cd-hero__slide--selected cd-hero__slide--from-right">
                <div class="cd-hero__slider cd-hero__content cd-hero__content--full-width text-center" style="padding-top:50px;">
                    <h1 class="our_head">Our Features</h1> -->
                    <!-- <p class="typewrite text-center" data-period="1000" data-type='[ "Hi, Welcome To Taxiappz.", "We here To Help You", "Scroll Down to Know more" ]'>
                        <span class="wrap"></span>
                    </p> -->
                    <!-- <a href="#scrolls" class="cd-hero__btn u-Margin0">Read More</a>
                </div> -->

                <!-- <div class="cd-hero__content cd-hero__content--bg-video js-cd-bg-video" style="background:rgba(0, 0, 0, 0.5);">
                    <video poster="assets/video/Dancing-Bulbs.webm" id="bgvid" playsinline autoplay muted loop style="opacity:0.8em;">
                      <source src="assets/video/Dancing-Bulbs.webm" type="video/webm">
                      <source src="assets/video/Dancing-Bulbs.mp4" type="video/mp4">
                      </video>
                </div> -->
            <!-- </li>
        </ul>

    </section> -->
    <section class="cd-hero js-cd-hero js-cd-autoplay" style="background: black;">
       <h1 class="our_head text-center u-PaddingTop20">Our Features</h1>
       <div class="text-center u-PaddingBottom30"><a href="#scrolls" class="cd-hero__btn">Read More</a></div>
       
    </section>
    <!-- .cd-hero -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 pd_0">
                <div class="sticky-height" id="scrolls">
                    <ul class="nav nav-tabs nav-tabs--style1" id="nav_sticky" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#prominent"  aria-controls="prominent" role="tab" data-toggle="tab">
                                <img src="assets/imgs/icon/feature.png"><span class="fs-125 ml-10">Prominent Features</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#Passenger" aria-controls="Passenger" role="tab" data-toggle="tab">
                                <img src="assets/imgs/icon/passenger.png"><span class="fs-125 ml-10">Passenger App</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#Driver" aria-controls="Driver" role="tab" data-toggle="tab">
                                <img src="assets/imgs/icon/driving-test.png"><span class="fs-125 ml-10">Driver App</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#Admin" aria-controls="Admin" role="tab" data-toggle="tab">
                                <img src="assets/imgs/icon/admin.png"><span class="fs-125 ml-10">Admin Panel</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content--style1" style="padding: 0px 30px;">
                    <div role="tabpanel" class="tab-pane fade in active" id="prominent">
                        <!--offers start-->
                        <div class=" u-PaddingTop10 u-PaddingBottom10">
                            <div class="container-fluid">

                                <div class="row u-MarginTop10 u-MarginBottom20">
                                    <div class="col-md-12 text-center">
                                        <div class="Heading  u-MarginBottom30" data-title="FEATURES">
                                            <div class="Split Split--height2 u-MarginTop40 u-MarginBottom0"></div>
                                            <h2 class="text-uppercase u-Weight800 u-Margin0"> Prominent Features</h2>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-sm-12 ">
                                        <h2 class="text-uppercase u-MarginTop5 u-MarginBottom0 feature_head">Passenger App
                                        </h2>
                                        <div class="Split"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-key Icon--22px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Set Language</a>
                                                    </div> 
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Rider can set this own preferred language on starting accessing the mobile application.</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-googleplus Icon--32px" aria-hidden="true"></i> 
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Login or Register</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">To ride from anywhere, Login is required. Semple set of registration is with the mobile number verification and the personal details.
                                                    </p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-map-pin Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Set The Location</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">After doing login to the app, Riders can set the location. Where they want to go? Set that location with the address and set the location on the map.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-profile-male Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Favorite Location</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders can add Workplace & Homeplace as a favorite location. And also book any location from the favorite location.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-car1 Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Vehicle Selection Option
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders have an option to select the vehicle as per their comfortability like mini, luxury or sedan. Vehicle service depends on admin the provide service. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-clock Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Ride Now or Later</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders can ride instantly with one click or they can also fix the schedule for their future ride with the option of a ride later. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-bargraph  Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Fare Estimate and ETA</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">With the help of ETA, customers see the details of nearby drivers and also get the Fare Estimate of payment of pickup location to the destination location.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-money Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Cash and Card Payment
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders have a convenient payment method to pay the charges of riding. They have an option of Cash , Card Or Wallet payments.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-pricetags Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Apply the Promo
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Rider can use promo code to grab an extra discount on their total trip amount this promo code detail is been managed by an administrative person.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-clipboard Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Push Notification
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin message will be displayed with text and the image. Message can be to specific person or common to all. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-cross1 Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Cancel Trip</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders can cancel the trip with the cancellation reason. It’s may possible that they have to pay some charges for the canceling Trip.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-document Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Real-Time Driver’s Status
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders can get the real-time driver status like, on the way, arrived, etc. Also, track the drivers and know that when a driver will come.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-profile-male Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Chat or Call Driver
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Through the app, Customer can call to the taxi driver and supported taxi agents for any queries regarding the taxi services.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-stop-watch Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Wait time charges
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">If drivers have to do wait for the riders, Then Additional charges will be applied on waiting time. Riders have to pay that extra charges.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-grid1 Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Invoice Details
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">In the Invoice, riders can see all the details related to the trip. Like Base Fare , Total Distance , Waiting Time , Promo Discount , Cancel charges etc.. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-heart Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Reviews and Rating</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Riders can write the reviews and give the rating at the end of the trip as per their experience of the taxi services and drivers.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--offers end-->

                        <!--offers start-->
                        <div class=" u-PaddingTop10 u-PaddingBottom10">
                            <div class="container-fluid">
                                <div class="row u-MarginTop10 u-MarginBottom20">
                                    <div class="col-md-9  col-sm-12  text-right">
                                        <h1  class="text-uppercase u-MarginTop5 u-MarginBottom0 feature_head_left ">DRIVER APP </h1>
                                        <div class="Split"></div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-key Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Set Language</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Rider can set this own preferred language on starting accessing the mobile application </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-googleplus Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Login or Register</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">To ride from anywhere, Login is required. Semple set of registration is with the mobile number verification and the personal details. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-profile-male Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Create Profile</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Driver can create their profile with the details like name, contact details, email, profile photo, Select Service Location and Document Upload. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-documents Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Document Uploading</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Before starting the service as a driver, it must to upload an essential document. Because Identity proof is required to do work as a driver. Admin will verify and approve him. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-man-marker Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">On-line and Off-line </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Driver will have the options to be in duty On-line and Off-line. The request of the trip will come only if the driver is on online.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-target Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Interactive Map</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Drivers have a facility real-time car transition on the map. It will help them to find out the rider’s location and reach service location as possible as quick.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-sunny-cloud Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Push Notification</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin message will be displayed with text and the image. Message can be to specific person or common to all. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-form Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Wallet</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Driver have to Maintain the minimum balance. So, the amount of the admin commission will automatically be deducted from wallet.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-cross1 Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Cancel the trip
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Drivers can also cancel the trip with the cancellation reason like a wrong address on the map, ETA is too long and can’t do proper content to the riders.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-man-marker-area Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Dashboard
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Driver can see his earnings report in daily, weekly and monthly base in the App with all details of the trip and Other Transaction.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--offers end-->
                        <!--offers start-->
                        <div class=" u-PaddingTop10 u-PaddingBottom10">
                            <div class="container-fluid">
                                <div class="row u-MarginTop10 u-MarginBottom20">
                                    <div class="col-md-9 col-md-offset-3 col-sm-12 text-left">
                                        <h1 class="text-uppercase u-MarginTop5 u-MarginBottom0 feature_head ">ADMIN PANEL
                                        </h1>
                                        <div class="Split"></div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-blog Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Dashboard</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">A powerful and advanced dashboard with complete features highlights with real-time data changing. All essential features highlight with graphical insights.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-man-marker Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Admin</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Managing all the super admin and admin by creating the role and set privileges to specific login with secure user name and password.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-form Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Manage Role </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Each login created by the super admin have its own role and privileges to access the admin panel. By this each admin can monitor his own work in the system.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-carousal Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Rider</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Manage all the users in the module. Admin can check the total users list with the active and blocked users with the reason. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-clipboard Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Driver </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin can check the status of the driver. Here driver will be approved after verifying the document. And all the transaction is be managed by here like earnings, wallet, account etc...
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-map Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Zone </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Here by creating the zone by drawing the boundaries and giving the name. Also manage the zone operations here by set currency, type of vehicle etc...
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-user-group Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Multiple Service Type</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Add multiple vehicle service types like SUV, Sedan, Tesla, Luxury etc and manage its details like Type Name, service type with option of adding pricing, type of image in a map with map pin image with
                                                        detail of aspect ration.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon   Icon-user-id Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Review and Rate</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin can see rate and review with details like Trip ID, date, rider name , a rating given by a rider, a rating of providers. And know that what are the users and providers experience.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-sunny-cloud Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Map View</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin can track real-time drivers with their location details in google map. In provider map, search all provider details with an option like active provider, inactive provider, waiting for a trip status.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-search Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Multiple Countries</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Add multiple countries for your business and make it on / off for business anytime with manage detail like Currency, currency sign, country code, business in a country on/off option.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-trophy Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Notification </a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin can manage notification setting with start and stop rights settings like SMS, Email notification with on / off option. Sending notification to all or to specific rider or driver. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-map Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Trip Earning</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">In trip earning details, admin can see total trip details with a completed trip and their earning details, payment method with a separate calculation of cash and card payment, referral and promotional
                                                        details, wallet, total admin and provider earning details with currency option.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon  Icon-layers Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText  u-MarginTop5" style="font-size:18px">Bulk Push Notification
                                                        </a>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin can send any news of update regarding offer, imp business news to all user with a mass push notification option. admin can separately manage user and provider push notification details with country wise option.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 u-MarginBottom20">
                                        <div class="Blurb ">
                                            <div class="media feature_text">
                                                <div class=" media-middle--">
                                                    <div class="Thumb">
                                                        <i class="Blurb__hoverText Icon Icon-lens Icon--32px" aria-hidden="true"></i>
                                                        <a class="Blurb__hoverText u-MarginTop5" style="font-size:18px">Promocode</a>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="fs-125">Admin can manage promo code detail with Code, Country, Type like a percentage or absolute, a value of Promo code, uses, states active or not, is an expired date with option button to edit, promo code
                                                        used info and inactive.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--offers end-->


                        <section>
                            <div class="container ">
                                <div class="Heading Heading--center Heading--shadow Heading--ff-Anton u-MarginBottom50" data-title="Frequently Asked Questions">
                                    <h1 class="u-FontSize75 u-xs-FontSize50 u-MarginTop0 u-PaddingTop20 u-xs-PaddingTop5 ff-Playball " style="font-weight: 700;color: black;top: 30px;font-family: sans-serif;font-size: 45px;">FAQ</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1">
                                        <div class="panel-group u-PaddingRight20 u-sm-PaddingRight0" id="accordion3">
                                            <div class="panel panel-info">
                                                <div class="panel-heading" id="heading3-1">
                                                    <div class="panel-title ">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-1" aria-expanded="true" aria-controls="collapse3-1">
                                                            CAN WE GET THE FULL SOURCE CODE? 
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapse3-1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading3-1">
                                                    <div class="panel-body">
                                                        <h5 class="fs-125 u-Margin0">Yes, we will provide the full source code of Admin Panel, Android (Rider and Driver) and iOS ( Rider and Driver). Also, with API document.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading" role="tab" id="heading3-2">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-2" aria-expanded="false" aria-controls="collapse3-2">
                                                            IS THAT YOUR APPLICATION NATIVE? 
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapse3-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-2">
                                                    <div class="panel-body">
                                                    <h5 class="fs-125 u-Margin0">Yes, our application is native. Android as Kotlin and Java (Android Studio) and iOS are in Swift Language.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading" role="tab" id="heading3-3">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-3" aria-expanded="false" aria-controls="collapse3-3">
                                                            CAN WE ABLE TO CUSTOMIZE THE CODE 
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapse3-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-3">
                                                    <div class="panel-body">
                                                    <h5 class="fs-125 u-Margin0">Yes, we provide the white label application. So that you can able to customize the code with easy understanding the structural comment in the code.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading" role="tab" id="heading3-4">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-4" aria-expanded="false" aria-controls="collapse3-3">
                                                            CAN YOU ABLE TO PROVIDE THE SUPPORT TEAM? 
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapse3-4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-4">
                                                    <div class="panel-body">
                                                    <h5 class="fs-125 u-Margin0">Yes, We can able to provide the support team as monthly or yearly terms.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading" role="tab" id="heading3-5">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapse3-5" aria-expanded="false" aria-controls="collapse3-3">
                                                            ARE YOU HELP TO HOST THE APPLICATION?
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapse3-5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3-5">
                                                    <div class="panel-body">
                                                    <h5 class="fs-125 u-Margin0"> Yes, we will help to host the application on your server and Upload the Mobile app in Play store and App store. We will Provide the Complete services to make your app publish.</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="Passenger">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="Heading u-MarginBottom30" data-title="Passenger">
                                    <div class="Split Split--height2 u-MarginTop40 u-MarginBottom0"></div>
                                    <h2 class="text-uppercase u-Weight700 u-Margin0"> Passenger App</h2>
                                </div>
                            </div>
                            <section class=" u-PaddingTop20 u-PaddingBottom30 features-passenger-app">
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-7 col-md-12 col-sm-12  col-xs-12 text-left passeneger_img_left bg_left_feat">
                                        <img src="assets/screen/screen_2.png" width="100%" alt="">
                                    </div>
                                    <div class="col-md-12 col-lg-5  col-sm-12 col-xs-12 u-MarginTop100">
                                        <h4 class="u-Weight600">Registration & Login </h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                            <h5 class="text-lg fs-125 u-Margin0">Rider app is downloaded from play and app store by rider on their device who would like to take the services of a particular taxi company. Any smartphone supporting mobile data and Wi-Fi will
                                                                serve as rider device. Rider app will have its taxi company brand name.</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Login is the first step of the App. If you want taxi services.
                                                        </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0"> After mobile number verification only, the rider can able to access the app.
                                                        </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 u-MarginTop150 passeneger_content_left">
                                        <h4 class="u-Weight600">
                                            Rider Request to Driver</h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Rider after set his destination list of vehicles with ETA and Fare Details. Rider can choose the any of the vehicle to confirm the booking either ride now or ride later. </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Rider have the options to choose the payment method as (cash, card or Wallet), applying promocode and note to driver before confirm the booking.
                                                        </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-left passeneger_img_right ">
                                        <img src="assets/screen/screen_1.png" width="100%" alt="">
                                    </div>
                                </div>
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-left passeneger_img_left">
                                        <img src="assets/screen/screen_3.png" width="100%" alt="">
                                    </div>
                                    <div class="col-md-12 col-lg-5  col-sm-12 col-xs-12 u-MarginTop100 passeneger_content_right bg_right_feat">
                                        <h4 class="u-Weight600">Rider on the trip </h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Request accepted by the driver. Rider can see the status of accept notification with the driver information like name, rating, phone number and time to teach the location. </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Once the driver arrived notification will come to the rider. Also trip verification code will display on the screen. While sharing the trip id driver can able to start the trip. In rider app
                                                                live meter and live tracking also available.
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 u-MarginTop150 passeneger_content_left">
                                        <h4 class="u-Weight600">
                                            Invoice and Rating the trip</h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">In the Rider screen while ending the trip by the driver invoice will displayed in the app screen.
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">After confirming the payment trip rating page will display. Rider can able to rate the driver and comment for this trip.
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-left passeneger_img_right" style="padding-left:60px; padding-top:30px;">
                                        <img src="assets/screen/user_5.png" style="width:270px; height:auto;" alt="">
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="Driver">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="Heading u-MarginBottom30" data-title="Driver">
                                    <div class="Split Split--height2 u-MarginTop40 u-MarginBottom0"></div>
                                    <h2 class="text-uppercase u-Weight700 u-Margin0"> Driver App<span class="Dot"></span></h2>
                                </div>
                            </div>
                            <section class=" u-PaddingTop20 u-PaddingBottom30">
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-7 col-md-12 col-sm-12  col-xs-12 text-left passeneger_img_left bg_left_feat">
                                        <img src="assets/screen/screen_5.png" width="100%" alt="">
                                    </div>
                                    <div class="col-md-12 col-lg-5  col-sm-12 col-xs-12 u-MarginTop100 passeneger_content_right">
                                        <h4 class="u-Weight600">Registration & Login</h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Driver app is downloaded from play and app store by driver on their device who would like to give the services of a particular taxi company. Any smartphone supporting mobile data and Wi-Fi will
                                                                serve as driver device. Driver app will have its taxi company brand name.</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Login is the first step of the App. If you want to give taxi services.
                                                         </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 u-MarginTop150 passeneger_content_left">
                                        <h4 class="u-Weight600">
                                            Admin verification</h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">After mobile number verification only, the Driver needs to update the necessary details and Selecting location of service. Also needs to upload the document that necessary from the taxi company.
                                                        </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">After verify the document admin will approve the driver for accessing the app. </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-left passeneger_img_right ">
                                        <img src="assets/screen/screen_6.png" width="100%" alt="">
                                    </div>
                                </div>
                                <div class="row u-PaddingTop20">
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-left passeneger_img_left">
                                        <img src="assets/screen/screen_7.png" width="100%" alt="">
                                    </div>
                                    <div class="col-md-12 col-lg-5  col-sm-12 col-xs-12 u-MarginTop100 passeneger_content_right bg_right_feat">
                                        <h4 class="u-Weight600">Driver Receive request</h4>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Driver have the option to get the request in online. If driver sign off, Driver has to be offline not to receive the request. </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">On request screen driver will see the Location of Pickup and Drop off. Also, with the option accept and reject the request and with the time count in the same screen.
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="u-MarginTop5">
                                                    <div class="media u-OverflowVisible  ">
                                                        <div class="media-left u-PaddingRight15">
                                                            <img src="assets/imgs/icons/arrow.png" width="25px;">
                                                        </div>
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">In the time count is completed the request will automatically trigger to the next driver nearby.
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="Admin">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="Heading u-MarginBottom30" data-title="Admin">
                                    <div class="Split Split--height2 u-MarginTop40 u-MarginBottom0"></div>
                                    <h2 class="text-uppercase u-Weight700 u-Margin0"> Admin Panel</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <section class="u-PaddingTop20 u-PaddingBottom50">
                                <div class="container">
                                    <div class="ImageBlock ImageBlock--switch ImageBlock--creative">
                                        <div class="ImageBlock__image col-md-7 col-sm-5">
                                            <div class="ImageBackground ImageBackground--overlay u-BoxShadow100" data-overlay="0">
                                                <div class="ImageBackground__holder">
                                                    <img src="assets/imgs/admin_1.jpg" alt="" />
                                                </div>
                                            </div>
                                            <span class="ImageBlock__image__title">Dashboard</span>
                                        </div>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="ImageBlock__rope"></div>
                                                    <h3 class="u-Weight800 text-uppercase u-MarginTop10 u-MarginBottom10 u-xs-MarginBottom10">
                                                        Dashboard</h3>
                                                    <div class="media feature_text">
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">In a Dashboard, admin can get all the details related to taxi app. In which he can see that how many countries are connected with the app. tringal Admin can get all information related to the
                                                                payment and earning from the chart and also get the information related to the registered user and request related details.</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="row">
                            <section class="u-PaddingTop20 u-PaddingBottom20">
                                <div class="container">
                                    <div class="ImageBlock ImageBlock--switch- ImageBlock--creative">
                                        <div class="ImageBlock__image col-md-7 col-sm-5">
                                            <div class="ImageBackground ImageBackground--overlay u-BoxShadow100" data-overlay="0">
                                                <div class="ImageBackground__holder">
                                                    <img src="assets/imgs/admin_2.jpg" alt="" />
                                                </div>
                                            </div>
                                            <span class="ImageBlock__image__title">Requests</span>
                                        </div>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="ImageBlock__rope"></div>
                                                    <h3 class="u-Weight800 text-uppercase u-MarginTop10 u-MarginBottom10 u-xs-MarginBottom10">
                                                        Requests</h3>
                                                    <div class="media feature_text">
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Today’s Request Check Today’s Taxi booking details along with details of User ID, User Name, Provider ID, Service Type, Created Date, Completed Date, Status, Amount details Payment status with
                                                                Action Button to View to and from Map and Invoice details. Completed and Scheduled Requests Check Complted and Scheduled Taxi booking details along with details of User ID, User Name, Provider
                                                                ID, Service Type, Created Date, Completed Date, Status, Amount details Payment status with Action Button to View to and from Map and Invoice details</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="row">
                            <section class="u-PaddingTop20 u-PaddingBottom50">
                                <div class="container">
                                    <div class="ImageBlock ImageBlock--switch ImageBlock--creative">
                                        <div class="ImageBlock__image col-md-7 col-sm-5">
                                            <div class="ImageBackground ImageBackground--overlay u-BoxShadow100" data-overlay="0">
                                                <div class="ImageBackground__holder">
                                                    <img src="assets/imgs/admin_1.jpg" alt="" />
                                                </div>
                                            </div>
                                            <span class="ImageBlock__image__title">User Reviews</span>
                                        </div>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="ImageBlock__rope"></div>
                                                    <h3 class="u-Weight800 text-uppercase u-MarginTop10 u-MarginBottom10 u-xs-MarginBottom10">
                                                        User Reviews </h3>
                                                    <div class="media feature_text">
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">Check Customer review for the trip with Details of Trip ID, Date, User Email, Rating, Provider Email and Provider rating details with Advanced Filter Option and Export data in excel sheet option.
                                                                See a booking cancellation reason with details like trip ID, user ID, cancellation date, username, provider id, provider name, cancellation reason and a cancel by person name. Filter option
                                                                with export data in excel sheet.</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="row">
                            <section class="u-PaddingTop20 u-PaddingBottom20">
                                <div class="container">
                                    <div class="ImageBlock ImageBlock--switch- ImageBlock--creative">
                                        <div class="ImageBlock__image col-md-7 col-sm-5">
                                            <div class="ImageBackground ImageBackground--overlay u-BoxShadow100" data-overlay="0">
                                                <div class="ImageBackground__holder">
                                                    <img src="assets/imgs/admin_2.jpg" alt="" />
                                                </div>
                                            </div>
                                            <span class="ImageBlock__image__title">
                                                Provider Map View</span>
                                        </div>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="ImageBlock__rope"></div>
                                                    <h3 class="u-Weight800 text-uppercase u-MarginTop10 u-MarginBottom10 u-xs-MarginBottom10">
                                                        Requests</h3>
                                                    <div class="media feature_text">
                                                        <div class="media-body non-scroll">
                                                        <h5 class="text-lg fs-125 u-Margin0">See service provider real-time location on google map with details like provider name, car model and their service rating with a status of waiting for the trip, active or Inactive for service
                                                                detail. Admin can track countries, Cities wise delivery provider separately with details of their name, service vehicle type and rating details.</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    <!-- <div style=" position: fixed;top: 0px;left: 0px;width: 100%;height: 100vh;background: #fff;z-index: 200;" class="window-loader col-12">
        <div class="loader-div" style="width: 22%;margin: 0px auto;margin-top: 10%;text-align: center;"><img src="https://www.nplustechnologies.com/taxiappz-new/assets/imgs/loader.gif" width="100%" alt=""></div>
    </div> -->
    <!-- <div class="window-loader col-12">
        <div class="loader-div">
            <img src="assets/imgs/loader.gif" width="100%" alt="">
        </div>
    </div> -->
    <!--footer start-->
    <footer class="bg-darker u-PaddingTop30 u-MarginTop30">
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
                    <ul class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0" style="font-size:18px" >
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
                        <li class="u-MarginBottom15 fs-125">
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
        $(window).load(function() {
            $('.window-loader').fadeOut("slow");
        });


    </script>
    <script>
        window.onscroll = function() {
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
<!-- <script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/63f6f5084247f20fefe223cc/1gpuaqan5';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script> -->
<!--End of Tawk.to Script-->

</body>

</html>
