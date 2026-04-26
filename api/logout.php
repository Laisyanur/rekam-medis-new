<?php
setcookie('user_session', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
]);
header("Location: login.php");
exit;
?>