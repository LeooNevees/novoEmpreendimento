<?php
// require_once '/var/www/html/novoEmpreendimento/classes/Xmongo.php';
require_once 'classes/Login.php';

try {
    $login = isset($_POST['usuario']) ? mb_strtoupper(trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING))) : '';
    $senha = isset($_POST['senha']) ? mb_strtoupper(trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING))) : '';

    $classeLogin = new Login();

    $retorno = $classeLogin->validarLogin($login, $senha);

    if ($retorno === false || $classeLogin->getEncontrados() < 1) {
        throw new Exception($classeLogin->getMensagem());
    }

    $mensagem = array(
        'status' => 'SUCESSO',
        'redirecionamento' => isset($_SESSION['redirecionamento']) ? $_SESSION['redirecionamento'] : '/novoEmpreendimento/index.php',
        'mensagem' => utf8_encode($classeLogin->getMensagem())
    );
    $retornoMensagem = json_encode($mensagem);
    echo $retornoMensagem;
} catch (Exception $ex) {
    $mensagem = array(
        'status' => 'ERRO',
        'mensagem' => utf8_encode($ex->getMessage())
    );
    $retornoMensagem = json_encode($mensagem);
    $_SESSION['nao_autenticado'] = $ex->getMessage();
    echo $retornoMensagem;
}
