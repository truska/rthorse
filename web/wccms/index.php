<?php
// Turn off error reporting
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$pageType = '' ; // Used to prevent controller/formField.php from Loading on Preference editing page(s)
require_once (__DIR__ . '/setting/main-top-files.php');

if (isset($_POST['submitReset'])) { // Reset password
   $toast = [];
   $email = filter_input(INPUT_POST, 'resetEmail', FILTER_VALIDATE_EMAIL);

   if ($email) {
      $pdo = Database::pdo();
      $userStatement = $pdo->prepare('SELECT `id` FROM `cms_adminlogin` WHERE `username` = ? LIMIT 1');
      $userStatement->execute([$email]);

      if ($userStatement->fetch()) {
         $resetCode = bin2hex(random_bytes(32));
         $pdo->prepare('DELETE FROM `recoverpassword` WHERE `email` = ?')->execute([$email]);
         $pdo->prepare('INSERT INTO `recoverpassword` (`email`, `emailcode`) VALUES (?, ?)')->execute([$email, $resetCode]);

         // Send recovery email
         $to = $email;
         $subject = "{$prefs['prefCompanyName']} - Password Reset Email";
         $headers = "MIME-Version: 1.0" . "\r\n";
         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
         $headers .= "From: " . $prefs['prefManagerEmail'];

         $message = "<h3>Click the link below to reset your CMS password.</h3><h4><a href='$BASE_URL/wccms/reset.php?token=$resetCode'>Reset password</a></h4><h6>Thank you<br>wITeCanvas Admin Team</h6>";

         mail($to, $subject, $message, $headers);
      }
   }

   // Do not disclose whether an account exists.
   $toast[] = array(
      "message" => "If that email address is registered, a reset link has been sent.",
      "type" => "success",
      "redirect" => "$BASE_URL/wccms"
   );
}

if (isset($_POST["sbt"])) {
   $username = trim((string) ($_POST['username'] ?? ''));
   $password = (string) ($_POST['password'] ?? '');

   // Start new code | salva TDR
   $user = new CMSUser($username);

   // 1. send 2FA code
   $code = random_int(100000, 999999);
   $from = $prefs['prefManagerEmail'];
   $to = $username;
   $isOk = $user->signIn($username, $password);
      
     // $username1 = $_SESSION["useremail"] ;
   
      if ($isOk) { // Check if the user and password are correct

      $logtable = "cms_adminlogin";
      $action = "LogIn" ;
      $sqlquery = "N/A";
      $notes = "User LOGGED IN" ;
      $recordnumber = 0 ;


      if($prefs['pref2fa'] === 'Yes') { // check if 2FA enabled
         $notes = "User LOGGED IN with 2FA" ;
      
         // Proceed with the login with 2FA
         $save2fa = $user->save2fa($to, $code); // Save 2FA code to the database

         
         $response = $user->send2fa($from, $to, $code); // Send 2FA code to the user

         if ($response['status'] == 200) {
            $_SESSION['pending_2fa_user'] = $username;
            $toast[] = array(
               "message" => "2FA code sent to your email",
               "type" => "success",
               "redirect" => "$BASE_URL/wccms/2fa.php"
            );
         } 
         else 
         {
            $toast[] = array(
               "message" => "Error sending 2FA code",
               "type" => "error"
            );
         }
      }
      else
      {
         // Proceed with the login without 2FA
         $notes = "User LOGGED IN without 2FA" ;

         $resuser = $user->getUser();
         $username = $resuser["username"];
         $fname = $resuser["firstname"];
         $lname = $resuser["surname"];
         $recordnumber = $resuser["id"];
         $full = "$fname $lname";

         session_regenerate_id(true);
         $_SESSION["useremail"] = $username;
         $_SESSION["user"] = $full;

         saveLogV2($username, $action, $sqlquery, $logtable, 'SUCCESS', $notes, $recordnumber);

         $toast[] = array(
               "message" => "Login successful [No 2FA], you will be redirected to the dashboard in a few seconds",
               "type" => "success",
               "redirect" => "$BASE_URL/wccms/dashboard.php" // Redirect to the dashboard or desired page
         );

      }
   }
   else 
   {     
      $logtable = "N/A";
      $action = "Login Attempt" ;
      $sqlquery = "N/A";
      $notes = "User Login Failed" ;
      $recordnumber = 0 ;

      saveLogV2($username, $action, $sqlquery, $logtable, 'FAILED', $notes, $recordnumber);
      echo "<script>alert('Invalid Username or Password!')</script>";
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="wITeCanvas">
   <meta name="keyword" content="wITeCanvas CMS">
   <link rel="shortcut icon" href="img/favicon.html">
   <meta name='robots' content='noindex, nofollow'>
   

   <title>WiteCanvas CMS</title>

   <!-- Bootstrap core CSS -->
   <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-reset.css" rel="stylesheet">
   <!--external css-->
   <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <!-- Custom styles for this template -->
   <link href="css/style.css" rel="stylesheet">
   <link href="css/style-responsive.css" rel="stylesheet" />
   <style>
      #forgotPassword {
         border-radius: 10px;
         padding: 2rem;

      }
   </style>
   <script src="/wccms/js/Toast.js"></script>
</head>

<body class="login-body">

   <div class="container">

      <form class="form-signin" method="post">
         <h2 class="form-signin-heading">sign in now</h2>
         <div class="login-wrap">
            <input type="text" class="form-control" name="username" placeholder="User ID" autofocus>
            <input type="password" class="form-control" name="password" placeholder="Password">

            <button class="btn btn-lg btn-login btn-block" type="submit" name="sbt">Sign in</button>
            <p style="font-size:12px; color:#555555;">Forgotten Password? <span
                  style="cursor: pointer; font-weight: bold;"
                  onclick="document.getElementById('forgotPassword').showModal()">CLICK HERE</span><br>or contact your
               manager or site admin</p>
         </div>

         <!-- Modal -->
         <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal"
            class="modal fade">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h4 class="modal-title">Forgot Password ?</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <p>Enter your e-mail address below to reset your password.</p>
                     <input type="text" name="email" placeholder="Email" autocomplete="off"
                        class="form-control placeholder-no-fix">

                  </div>
                  <div class="modal-footer">
                     <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                     <button class="btn btn-success" type="button">Submit</button>
                  </div>
               </div>
            </div>
         </div>
      </form>

      <dialog id="forgotPassword">
         <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h4>Insert your email to get a recovery email</h4>
            <div class="form-group">
               <label>Email:</label>
               <input type="text" class="form-control" name="resetEmail" placeholder="yoremail@domain.com" autofocus="">
            </div>
            <button class="btn btn-primary" type="submit" name="submitReset">Submit</button>
            <button class="btn btn-secondary" type="button"
               onclick="document.getElementById('forgotPassword').close()">Cancel</button>
         </form>
      </dialog>
   </div>



   <!-- js placed at the end of the document so the pages load faster -->
   <script src="js/jquery.js"></script>
   <script src="js/bootstrap.bundle.min.js"></script>

   <!-- Loading overlay -->
   <div id="loadingOverlay" style="
      display:none;
      position:fixed;
      top:0; left:0; width:100%; height:100%;
      background-color:rgba(255,255,255,0.85);
      z-index:9999;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      text-align:center;
      font-family:Arial,sans-serif;">
      <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status"></div>
      <div style="margin-top:15px;font-size:1.1rem;color:#333;">
         Redirecting… <span id="loadingSeconds">0</span>s
      </div>
   </div>

   <script>
      function showLoadingOverlay() {
         const overlay = document.getElementById("loadingOverlay");
         const counter = document.getElementById("loadingSeconds");
         let seconds = 0;
         overlay.style.display = "flex";
         const timer = setInterval(() => {
            seconds++;
            counter.textContent = seconds;
         }, 1000);
         return timer;
      }

      $(document).ready(function () {
         <?php
         // Run only if $toast exists AND has a redirect value
         if (isset($toast) && is_array($toast) && !empty($toast[0]['redirect'])) {
            $msg = addslashes($toast[0]['message']);
            $type = addslashes($toast[0]['type']);
            $redirect = addslashes($toast[0]['redirect']);
            echo "
               const toast = new Toast('{$msg}', '{$type}');
               toast.show();
               setTimeout(() => {
                  showLoadingOverlay();
                  setTimeout(() => {
                     window.location.href = '{$redirect}';
                  }, 2500);
               }, 500);
            ";
         } elseif (isset($toast) && is_array($toast) && !empty($toast[0]['message'])) {
            // Show toast only (no redirect)
            $msg = addslashes($toast[0]['message']);
            $type = addslashes($toast[0]['type']);
            echo "const toast = new Toast('{$msg}', '{$type}'); toast.show();";
         }
         ?>
      });
   </script>
   
</body>
</html>
<!-- END index -->
