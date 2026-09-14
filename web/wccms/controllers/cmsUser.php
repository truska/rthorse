<?php

/**
 * CMS user authentication and one-time-code operations.
 *
 * Existing MD5 password hashes are accepted once and transparently upgraded to
 * PHP's current password hash on a successful sign-in.
 */
class CMSUser
{
   public $user;
   public $userRole;

   public function __construct($username)
   {
      $statement = Database::pdo()->prepare(
         'SELECT * FROM `cms_adminlogin` WHERE `username` = ? LIMIT 1'
      );
      $statement->execute([$username]);
      $user = $statement->fetch();

      if (!$user) {
         $this->user = null;
         $this->userRole = null;
         return;
      }

      $this->user = $user;
      $roleStatement = Database::pdo()->prepare(
         'SELECT * FROM `cms_userrole` WHERE `name` = ? LIMIT 1'
      );
      $roleStatement->execute([$user['userrole']]);
      $this->userRole = $roleStatement->fetch() ?: null;
   }

   public function getUser()
   {
      return $this->user;
   }

   public function getUserRole()
   {
      return $this->userRole;
   }

   public function signIn($username, $password)
   {
      if (!$this->user || !hash_equals($this->user['username'], $username)) {
         return false;
      }

      $storedHash = (string) $this->user['password'];
      $isModernHash = password_get_info($storedHash)['algo'] !== null;
      $isValid = $isModernHash
         ? password_verify($password, $storedHash)
         : hash_equals($storedHash, md5($password));

      if (!$isValid) {
         return false;
      }

      if (!$isModernHash || password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
         $newHash = password_hash($password, PASSWORD_DEFAULT);
         $statement = Database::pdo()->prepare(
            'UPDATE `cms_adminlogin` SET `password` = ? WHERE `id` = ?'
         );
         $statement->execute([$newHash, $this->user['id']]);
         $this->user['password'] = $newHash;
      }

      return true;
   }

   public function updatePassword($password)
   {
      try {
         $newHash = password_hash($password, PASSWORD_DEFAULT);
         $statement = Database::pdo()->prepare(
            'UPDATE `cms_adminlogin` SET `password` = ? WHERE `id` = ?'
         );
         $statement->execute([$newHash, $this->user['id']]);
         $this->user['password'] = $newHash;

         return ['status' => 200, 'msg' => 'Password changed successfully'];
      } catch (Throwable $exception) {
         return ['status' => 400, 'msg' => 'Unable to change password.'];
      }
   }

   public function deleteResetCode($resetCode)
   {
      try {
         Database::pdo()->prepare(
            'DELETE FROM `recoverpassword` WHERE `emailcode` = ?'
         )->execute([$resetCode]);

         return ['status' => 200, 'msg' => 'Reset code deleted successfully'];
      } catch (Throwable $exception) {
         return ['status' => 400, 'msg' => 'Unable to remove reset code.'];
      }
   }

   public function send2fa($from, $to, $code)
   {
      $subject = 'wITeCanvas 2FA Code';
      $message = 'Your wITeCanvas CMS 2FA code is:<br><strong>' . $code . '</strong>'
         . '<br>Please enter this code where prompted.'
         . '<br>Do not share this code with anyone.';
      $headers = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8\r\n";
      $headers .= 'From: ' . $from;

      if (mail($to, $subject, $message, $headers, '-f ' . $from)) {
         return ['status' => 200, 'msg' => '2FA code sent successfully'];
      }

      return ['status' => 400, 'msg' => 'Error sending 2FA code'];
   }

   public function save2fa($email, $code)
   {
      try {
         $pdo = Database::pdo();
         $pdo->prepare('DELETE FROM `cms_2fa` WHERE `email` = ?')->execute([$email]);
         $statement = $pdo->prepare(
            'INSERT INTO `cms_2fa` (`email`, `code`, `valid_until`) VALUES (?, ?, ?)'
         );
         $statement->execute([$email, $code, date('Y-m-d H:i:s', strtotime('+15 minutes'))]);

         return ['status' => 200, 'msg' => '2FA code saved successfully'];
      } catch (Throwable $exception) {
         return ['status' => 400, 'msg' => 'Unable to save 2FA code.'];
      }
   }

   public function delete2fa($email)
   {
      try {
         Database::pdo()->prepare('DELETE FROM `cms_2fa` WHERE `email` = ?')->execute([$email]);
         return ['status' => 200, 'msg' => '2FA code deleted successfully'];
      } catch (Throwable $exception) {
         return ['status' => 400, 'msg' => 'Unable to remove 2FA code.'];
      }
   }

   public function check2fa($email, $code)
   {
      try {
         $statement = Database::pdo()->prepare(
            'SELECT `id` FROM `cms_2fa` WHERE `email` = ? AND `code` = ? AND `valid_until` > NOW() LIMIT 1'
         );
         $statement->execute([$email, $code]);

         if ($statement->fetch()) {
            return ['status' => 200, 'msg' => '2FA code verified. Redirecting to the dashboard.'];
         }
      } catch (Throwable $exception) {
         // Do not disclose internal database details to a login visitor.
      }

      return ['status' => 400, 'msg' => 'Invalid or expired 2FA code.'];
   }
}
