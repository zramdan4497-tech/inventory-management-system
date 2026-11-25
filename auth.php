<?php
// Include at the top of every protected page.
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
