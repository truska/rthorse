<!-- START reset -->
<?php
include('setting/main-top-files.php'); 

$toast = [];
$email = '';

$link = rthCurrentUrl();

$resetCode = $_GET['token'] ?? ($_SERVER['QUERY_STRING'] ?? '');
$statement = Database::pdo()->prepare(
   'SELECT `email` FROM `recoverpassword` WHERE `emailcode` = ? LIMIT 1'
);
$statement->execute([$resetCode]);
$result = $statement->fetch();

if ($result) {
   $email = $result["email"];
} else {
   $toast[] = array(
      "message" => "Invalid reset code",
      "type" => "error",
      "redirect" => "$BASE_URL/wccms",
   );
}

if (isset($_POST['repwdSubmit']) && $email !== '') {
   $toast = [];
   $password = (string) ($_POST['password'] ?? '');
   $rePassword = (string) ($_POST['repassword'] ?? '');

   if ($password != $rePassword) {
      $toast[] = array(
         "message" => "Password and Re-Password are not the same",
         "type" => "error"
      );
   } else {
      $user = new CMSUser($email);

      try {
         $responseUpdate = $user->updatePassword($password);

         if ($responseUpdate['status'] == 200) {
            $deleteResetCode = $user->deleteResetCode($resetCode);

            if ($deleteResetCode['status'] == 200) {
               $toast[] = array(
                  "message" => $responseUpdate['msg'],
                  "type" => "success",
                  "redirect" => "$BASE_URL/wccms"
               );
            } else {
               $toast[] = array(
                  "message" => $deleteResetCode['msg'],
                  "type" => "error"
               );
            }
         } else {
            $toast[] = array(
               "message" => $responseUpdate['msg'],
               "type" => "error"
            );
         }
      } catch (\Exception $e) {
         $toast[] = array(
            "message" => $e->getMessage(),
            "type" => "error"
         );
      }
   }
} elseif (isset($_POST['repwdSubmit'])) {
   $toast[] = array(
      "message" => "Invalid or expired reset link",
      "type" => "error",
      "redirect" => "$BASE_URL/wccms",
   );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="wITeCanvas">
   <meta name="keyword" content="">
   <link rel="shortcut icon" href="img/favicon.html">
   <title>Password Reset | WiteCanvas CMS</title>
   <!-- Bootstrap core CSS -->
   <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/bootstrap-reset.css" rel="stylesheet">
   <!--external css-->
   <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <!-- Custom styles for this template -->
   <link href="css/style.css" rel="stylesheet">
   <link href="css/style-responsive.css" rel="stylesheet" />
   <script></script>
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

      <form autocomplete="off" class="form-signin validate-form" method="post" onsubmit="return validateForm()">
         <h2 class="form-signin-heading">New password</h2>
         <div class="login-wrap">
            <p><?php echo $email; ?></p>
            <label for="password1">Write the new password:</label>
            <input type="password" class="form-control" name="password" placeholder="Secure password" id="password1" autofocus>
            <label for="repassword">Repeat the password:</label>
            <input type="password" class="form-control" name="repassword" placeholder="Repeat the password" id="repassword">

            <button class="btn btn-lg btn-login btn-block" type="submit" name="repwdSubmit">Reset password</button>
         </div>
      </form>
   </div>

   <script src="js/jquery.js"></script>
   <script src="js/bootstrap.bundle.min.js"></script>

   <script>
      function validateForm() {
         const password = document.querySelector('#password1').value;
         const confirm_password = document.querySelector('#repassword').value;

         if (password == "" || confirm_password == "") {
            const toast = new Toast("Password and Retype-Password are required", "error");
            toast.show();
            return false;
         } else {
            if (password != confirm_password) {
               const toast = new Toast("Passwords do not match", "error");
               toast.show();
            return false;
            }
         }
      }

      $(document).ready(function() {
         <?php
         if (count($toast) > 0) {
            echo "const toast = new Toast('" . $toast[0]['message'] . "', '" . $toast[0]['type'] . "');";
            echo "toast.show();";
            if ($toast[0]['redirect']) {
               echo "setTimeout(() => { 
                  window.location.href = '{$toast[0]['redirect']}'; 
               }, 4000);";
            }
         }
         ?>
      });
   </script>
</body>

</html>
<!-- END reset -->
