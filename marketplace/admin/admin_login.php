<?php
/**
 * ADMIN LOGIN REDIRECTS TO MARKETPLACE LOGIN
 * Now admins use the same login form as users
 * This keeps everything in one place and reduces confusion
 */
header("Location: ../user/login.php");
exit();
?>
