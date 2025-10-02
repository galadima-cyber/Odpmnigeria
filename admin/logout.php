<?php
require_once dirname(__DIR__) . '/includes/auth.php';
logout();
header('Location: /ODPM/admin/index.php');
exit;
