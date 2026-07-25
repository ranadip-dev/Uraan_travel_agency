<?php

declare(strict_types=1);

require_once '../includes/auth.php';

$_SESSION = [];

session_destroy();

header('Location: login.php');
exit;