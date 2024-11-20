<?php
session_start();

if (isset($_GET['k']) && isset($_SESSION['r_' . $_GET['k']])) {
    $url = $_SESSION['r_' . $_GET['k']];
    unset($_SESSION['r_' . $_GET['k']]);
    header('Location: ' . $url);
    exit();
} else {
    header('Location: /');
    exit();
}
?>