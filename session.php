<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!$_SESSION['login']) {
    header('location: /novoEmpreendimento/index.php');
    exit();
}

