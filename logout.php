<?php

include '/var/www/html/novoEmpreendimento/classes/Login.php';

try {
    $login = new Login();

    $retorno = $login->fazerLogout();

    if ($retorno === false) {
        throw new Exception($login->getMensagem());
    }

    header('location: /novoEmpreendimento/index.php');
    exit();
} catch (Exception $ex) {
    $mensagem = $ex->getMessage();
}
echo $mensagem;
?>
