
<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

 require_once('mail/Mail.php');

if( isset($_SESSION['notification_showed']) ) {
    if($_SESSION['notification_showed'] == true) {
        unset($_SESSION['nameErr']);
        unset($_SESSION['emailErr']);
        unset($_SESSION['phoneErr']);
        unset($_SESSION['messageErr']);
    }
}

if( isset($_SESSION['nameErr']) || isset($_SESSION['emailErr']) || isset($_SESSION['phoneErr']) || isset($_SESSION['messageErr'])) {
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
          }else{
            $email = test_input($_POST["contact_email"]);
            // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //     $_SESSION['emailErr'] = "Invalid email format";                
            //   }
          }

          if (empty($_POST["contact_mobile_number"])) {
            $_SESSION['phoneErr'] = "Mobile number is required";            
          }else{
            $phone = test_input($_POST["contact_mobile_number"]);            
            // if (!preg_match("/^[1-9][0-9]{0,15}$/",$phone)) {
            //     $_SESSION['phoneErr'] = "Invalid Mobile number";                
            //   }
          }

          if (empty($_POST["contact_message"])) {
            $_SESSION['messageErr'] = "Message is required";            
          }else{
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
            if (!empty($_POST["contact_name"]) &&  !empty($_POST["dialcodes"]) && !empty($_POST["contact_mobile_number"]) && !empty($_POST["contact_email"]) &&   !empty($_POST["contact_message"])   && !empty($_POST["optradio"])) {

                $Contact_name =  filter_var($_POST["contact_name"], FILTER_UNSAFE_RAW);
                $Contact_email = filter_var($_POST["contact_email"], FILTER_UNSAFE_RAW);
                $Contact_mobile = filter_var($_POST["contact_mobile_number"], FILTER_UNSAFE_RAW);
                $Contact_Message = filter_var($_POST["contact_message"], FILTER_UNSAFE_RAW);
                $dialcodes = filter_var($_POST["dialcodes"], FILTER_UNSAFE_RAW);
                $countryName = filter_var($_POST["countryName"], FILTER_UNSAFE_RAW);
                $Contact_demo = filter_var($_POST["optradio"], FILTER_UNSAFE_RAW);

                $get_country = explode(" (", $countryName);
                $Contact_country = $get_country[0];

                if ($Contact_demo == "BASIC") {
                    $Contact_demo = 'BASIC';
                } elseif ($Contact_demo == "STANDARD") {
                    $Contact_demo = 'STANDARD';
                } else {
                    $Contact_demo = 'ENTERPRISE';
                }
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
                                                                <h2>A New Request From Client</h2>
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
                                                                                            <td>Plan</td>
                                                                                            <td class="alignright">
                                                                                                ' . $Contact_demo . '</td>
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

                $from     = "Taxiappz <sales@taxiappz.com>";
                $to       = "Sales <sales@taxiappz.com>";
                $subject  = "Lead #000" . $m . " - "  . $Contact_name;
                $body     = $Body;
                $host     = "ssl://smtp.gmail.com";
                $username = "sales@taxiappz.com";
                $password = "hdxvjqaycnqqbshw";
                $headers  = array(
                    'From' => $from,
                    'To' => $to,
                    'Subject' => $subject,
                    'MIME-Version' => 1,
                    'Content-type' => 'text/html;charset=iso-8859-1'
                );
                $smtp     = Mail::factory('smtp', array(
                    'host' => $host,
                    'port' => '465',
                    'auth' => true,
                    'username' => $username,
                    'password' => $password
                ));

                if (empty($_SESSION['nameErr']) && empty($_SESSION['emailErr']) && empty($_SESSION['phoneErr'] && empty($_SESSION['messageErr']) ) ) {
                    $mail     = $smtp->send($to, $headers, $body);
                }

                if (PEAR::isError($mail)) {
                    echo "<script>window.location.href='https://www.taxiappz.com/'</script>";
                } else {
                    echo "<script>window.location.href='thankyou'</script>";
                }
            } else {
                echo "<script>alert('All Feilds are required')</script>";
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


function test_input($data) {
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

    <title>Contact us - TaxiAppZ | Taxi App | Choose Plan</title>
    <meta name="description" content="Contact Taxiappz web and mobile dispatching software over Phone - +91 9944820558, Email - info@taxiappz.com, Skype - taxiappz or live chat 24/6.Taxi Service, Cab Service, Cab Hire, Taxi Hire, Free Demo " />
    <meta name="keywords" content= "Taxi App Developers, Uber like app development, Taxi App Development Company, Uber Like App Solution, Taxi App Solutions, Private Taxi App, Taxi Booking Solution, Taxi App White Label, Taxi Company App, Mobile App for Taxi, Taxi Startup, Taxi Management Solution, Taxi Apps, How to Manage Taxi Business, Taxi App Developers India,Clone Uber Taxi App, Taxi Application Development, Taxi App Source Code, Taxi Business Solution,View Solutions on youtube, Taxi Booking App Builder,Review Taxi Driver App" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Heebo:100%7COpen+Sans:300,400,400i,600,700,800">
    <link rel="canonical" href="https://www.taxiappz.com/contact" />
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
    <link rel="stylesheet" href="assets/vendor/slider/css/style.css">
    <!-- endinject -->

    <!-- revolution css -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Raleway%3A100%2C400%2C600">
    <link rel="stylesheet" href="assets/vendor/revolution-slider/revolution/fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/vendor/revolution-slider/revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/vendor/revolution-slider/revolution/css/settings.css">
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:600%2C300%2C700">


    <!-- REVOLUTION LAYERS STYLES -->
    <link rel="stylesheet" href="assets/vendor/revolution-slider/revolution/css/layers.css">

    <!-- REVOLUTION NAVIGATION STYLES -->
    <link rel="stylesheet" href="assets/vendor/revolution-slider/revolution/css/navigation.css">
    <style>
        nav.navbar.bootsnav ul.nav>li>a {
            font-weight: bold !important;
            font-size: 15px !important;
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
                        <li><a href="portfolio">Portfolio</a></li>
                        <li class="active"><a href="contact">Contact Us</a></li>
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

    <section class="ImageBackground ImageBackground--gray u-height330 v-align-parent ">
        <div class="ImageBackground__holder">
            <img src="assets/imgs/pb-2.jpg" alt="..." title="" />
        </div>
        <div class="v-align-child">
            <div class="container ">
                <div class="row ">
                    <div class="col-md-8 col-xs-12 text-white ">
                        <h1 class=" u-Margin0 u-Weight300 ">Contact Us</h1>
                        <h5 class="u-LineHeight2 text-muted u-Margin0 fs-125">If you have any questions, please feel free to ask
</h5>
                    </div>

                    <div class="col-md-4 col-xs-12" >
                        <ol class="breadcrumb text-white u-MarginTop25 u-MarginBottom0 pull-right">
                            <li><a href="" class="fs-125">Home</a></li>
                            <li class="active fs-125"><span>Contact Us</span></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--page title end-->

   
    <div class="u-PaddingTop30 u-xs-PaddingTop30 u-PaddingBottom10 u-xs-PaddingBottom30">
        <div class="container">
            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 ">
                <h5 class="text-center u-Margin0 fs-125">
                    Feel free to get in touch to start the conversation. We're happy to provide value added consulting to existing projects.
                </h5>
            </div>
        </div>

    </div>

    <!--contact start-->
    <div class=" u-MarginTop30 u-MarginBottom30 " style="background: #590707; background-image: url('assets/imgs/vector/bg.jpg');background-repeat: repeat;">
        <div class="container">
            <form action="" method="post" onsubmit="return validateForm()">
                <div class="col-md-6  u-MarginBottom20 u-MarginTop15">

                    <div class="col-md-11 col-sm-6 contact_right">
                        <div class="col-md-3">
                            <img src="assets/imgs/icons/mail.png" width="60%" alt="" title="">
                        </div>
                        <div class="col-md-8 contact_text">
                            <h5 class="fs-125">MAIL TO OUR SALES DEPARTMENT</h5>
                            <p>sales@taxiappz.com</p>
                        </div>
                    </div>
                    <div class="col-md-11 col-sm-6 contact_right">
                        <div class="col-md-3">
                            <img src="assets/imgs/icons/whatsapp.png" width="60%" alt="" title="">
                        </div>
                        <div class="col-md-8 contact_text">
                        <h5 class="fs-125">CONTACT NUMBER (+91)</h5>
                            <p>9940909625, 9944820558</p>
                        </div>
                    </div>
                    <div class="col-md-11 col-sm-6 contact_right">
                        <div class="col-md-3">
                            <img src="assets/imgs/icons/whatsapp.png" width="60%" alt="" title="">
                        </div>
                        <div class="col-md-8 contact_text">
                        <h5 class="fs-125">LANDLINE NUMBER</h5>
                            <p>0422-4982903</p>
                        </div>
                    </div>
                    <a href="skype:Taxiappz?chat"><div class="col-md-11 col-sm-6 contact_right">
                        <div class="col-md-3">
                            <img src="assets/imgs/icons/skype.png" width="60%" alt="" title="">
                        </div>
                        <div class="col-md-8 contact_text">
                        <h5 class="fs-125">OUR SKYPE ID</h5>
                            <p>Taxiappz</p>
                        </div>
                    </div></a>
                    <div class="col-md-10 col-md-offset-1">
                        <p class="text-white text-lg u-Weight600 " style="font-size:20px">
                            Taxiappz can help you with
                        </p>
                        <ul style="list-style: circle;color: white;" class="fs-125">
                            <li>
                                <h5 style="margin: 4px 0;">Product Discovery (Blueprint)</h5>
                            </li>
                            <li>
                                <h5 style="margin: 4px 0;">Software Consultation</h5>
                            </li>
                            <li>
                                <h5 style="margin: 4px 0;">Comprehensive Mobile Application</h5>
                            </li>
                            <li>
                                <h5 style="margin: 4px 0;">User Centric Design (UX/ UI)</h5>
                            </li>
                        </ul>
                    </div>
                    <!-- <div class="com-md-12" style="font-size:20px">
                        <div class="col-md-4 news_letter">
                            <h5>Follow Us</h5>
                            <br>
                            <a href="#"><img src="assets/imgs/icons/facebook.png" width="40px" alt="" title=""></a>
                            <a href="#"><img src="assets/imgs/icons/google-plus.png" width="40px" alt="" title=""></a>
                            <a href="#"><img src="assets/imgs/icons/twitter.png" width="40px" alt="" title=""></a>
                        </div>
                        <div class="col-md-6 news_letter">
                            <h5>Signup For Newsletter</h5>
                            <br>
                            <div class="input-group mt10 hwenewsletter">
                                <input  name="newsleterEmail" class="form-control input-md" placeholder="Enter Email" type="email">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit"
                                        style="padding: 0 20px;border: 1px solid #b10303">
                                        <span>
                                            <i class="fa fa-paper-plane"></i>
                                        </span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="col-md-6 contact_form">
                    <div class="col-md-12">

                        <h3 class="u-weight300 u-MarginTop30 u-MarginBottom40 text-center">
                            <span style="color: rgba(0, 0, 0, .55);text-align: justify;font-size: 18px;">Let's
                                Talk</span> Conatct Us
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name" name="contact_name" data-error="You must enter name" required>
                            <?php if(isset($_SESSION['nameErr'])){?>
                            <div class="help-block with-errors"><?=$_SESSION['nameErr'];?></div>
                            <?php }  ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="email" name="contact_email" class="form-control" placeholder="Email" data-parsley-required="true" required />
                            <?php if(isset($_SESSION['emailErr'])){?>
                            <div class="help-block with-errors"><?=$_SESSION['emailErr'];?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <input id="dialcodes" name="dialcodes" type="hidden" value="">
                    <input id="countryName" name="countryName" type="hidden" value="" >

                    <div class="col-md-12">
                        <div class="form-group">
                        <input id="phone"  class="form-control" onkeypress="return isNumberKey(event)" name="contact_mobile_number" type="tel" onchange="getNumber()">
                            <?php if(isset($_SESSION['phoneErr'])){?>
                                <div class="help-block with-errors"><?=$_SESSION['phoneErr'];?></div>
                                    <?php }  ?>
                        </div>
                    </div>
                   
                   
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control " rows="2" cols="5" placeholder="Enter Your Message" name="contact_message" data-parsley-required="true" required></textarea>
                            <?php if(isset($_SESSION['messageErr'])){?>
                                <div class="help-block with-errors"><?=$_SESSION['messageErr'];?></div>
                                    <?php }  ?>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                        <input type="radio" name='optradio' value="BASIC" required>&nbsp;&nbsp;BASIC&nbsp;&nbsp;<input type="radio" name='optradio' value="STANDARD" required>&nbsp;&nbsp;STANDARD&nbsp;&nbsp;<input type="radio" name='optradio' value="ENTERPRISE" required>&nbsp;&nbsp;ENTERPRISE

                        </div>
                    </div>
                    <!-- <div class="row  u-PaddingLeft20">
                        <div class="col-md-3 offset-1 pd_0">
                            <div class="form-check-inline">
                                <label class="form-check-label" style="font-size: 17px;padding: 0 15px;text-transform: uppercase;color: #504d4d;font-weight: 600;">
                                <input type="radio" name='optradio' value="BASIC" required>&nbsp;Basic&nbsp;<input type="radio" name='optradio' value="STANDARD" required>&nbsp;STANDARD&nbsp;<input type="radio" name='optradio' value="ENTERPRISE" required>ENTERPRISE
                                 
                                
                           
                                </label>
                            </div>
                        </div>
                        
                       
                    </div> -->
                    <div class="col-md-12">
                    <div class="g-recaptcha" data-sitekey="6LfOyCkUAAAAAH-vwFiO6WYFXg1prSnFheMAlffe" data-callback="verifyCaptcha"></div>
                    <div id="g-recaptcha-error" class="contact-info"></div>
                     <br>
                    </div>
                    <div class="col-md-12 u-Padding20  ">
                        <button type="submit" name="form_submit" onclick="myAction()" class="btn-block pricing_btn" style="font-size:20px">Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--contact end-->

    <!-- intro start-->
    <div class="container u-MarginTop30 u-xs-MarginTop10 u-MarginBottom30 u-xs-MarginBottom20 ">
        <div class="row text-center ">
            <div class="col-md-12">
                <h1 class="u-weight300 u-MarginTop0" >
                    Our Global Presence
                </h1>
            </div>
        </div>
        <div class="row u-MarginTop10 u-xs-MarginTop10 ">
            <div class="col-md-4 col-sm-6 u-MarginBottom35 text-center">
                <img src="assets/imgs/vector/usa.png" width="80%" alt="" title="">
                <br>
                <h2 class=" u-MarginBottom0 u-MarginTop20 ">Middle East</h2>
                <h5 class="u-LineHeight2 text-center fs-125 u-MarginTop10 u-Margin0">No 45, Al diwaniya street,<br /> Old airport, Doha, <br /> Qatar +97455971077
                            </h5>
            </div>
            <div class="col-md-4 col-sm-6 u-MarginBottom35 text-center">
                <img src="assets/imgs/vector/uk.png" width="80%" alt="" title="">
                <br>
                <h2 class=" u-MarginBottom0 u-MarginTop20 ">USA</h2>
                <h5 class="u-LineHeight2 text-center u-MarginTop10 fs-125 u-Margin0">17193 Castello Circle, <br /> San Diego CA 92127,<br />USA.</h5>
            </div>
            <div class="col-md-4 col-sm-6 u-MarginBottom35 text-center">
                <img src="assets/imgs/vector/hawa.png" width="80%" alt="" title="">
                <br>
                <h3 class=" u-MarginBottom0 u-MarginTop20">India</h2>
                <h5 class="u-LineHeight2 text-center u-MarginTop10 fs-125 u-Margin0">#54 LMS Street,<br /> Papanaickenpalayam,
                    <br /> Coimbatore - 641037.</h5>
            </div>
        </div>

    </div>
    <!-- intro end-->

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

.my-float {
    margin-top: 15px;
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
        <div class="container text-sm" style="padding-left: 60px;padding-right: 0px;">
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
                    <ul class="light-gray-link border-bottom-link list-unstyled u-LineHeight2 u-PaddingRight40 u-xs-PaddingRight0" style="font-size:18px">
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
            <img src="assets/imgs/loader.gif" width="100%" alt="" title="">
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

    <!--google map-->
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
    function skypecal() {
        window.location.assign("skype:taxiappz")
    }
</script>



    <!-- <div class="row ">
        <div class="col-md-6 pricing_table_body ">
            <ul>
                <li>Hosting on Your Server</li>
            </ul>
        </div>
        <div class="col-md-2 pricing_table_body ">
            <ul class="bs ">
                <li><img src="assets/imgs/icon/null.png "></li>
            </ul>
        </div>
        <div class="col-md-2 pricing_table_body ">
            <ul class="st ">
                <li><img src="assets/imgs/icon/null.png "></li>
            </ul>
        </div>
        <div class="col-md-2 pricing_table_body ">
            <ul class="en ">
                <li><img src="assets/imgs/icon/null.png "></li>
            </ul>
        </div>
    </div> -->
   
   
</body>

</html>
