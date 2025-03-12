<?php
session_start();

// Hủy bỏ tất cả session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>
