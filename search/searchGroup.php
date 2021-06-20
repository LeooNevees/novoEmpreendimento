<?php
include __DIR__ . '/../sistema.php';
include_once '/var/www/html/novoEmpreendimento/classes/Card.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/GroupsRepository.php';
$pesquisa = isset($_GET['pesq']) ? mb_strtoupper(filter_input(INPUT_GET, 'pesq', FILTER_SANITIZE_STRING)) : '';
?>

<html>

    <head>
        <title>Ipeças - Faça seu pedido</title>
        <script src="/novoEmpreendimento/js/searchGroup.js" type="text/javascript"></script>
    </head>

    <body>
        <?php
        try {
            if (
                !(include __DIR__ . '/../navbar.php')
                || !(include __DIR__ . '/../carousel-index.php')
            ) {
                throw new Exception('Erro ao carregar o NavBar');
            }

            if(empty($pesquisa)){
                throw new Exception('Nenhum grupo fornecido. Por favor refaça o procedimento');    
            }

            $dados = ['nome' => $pesquisa];
            $repository = new GroupsRepository;
            $retorno = $repository->getGroups($dados);
            if ($retorno === false) {
                throw new Exception($repository->mensagem);
            }

            if ($repository->encontrados < 1) {
                throw new Exception('Nenhum Grupo com o nome ' . $pequisa . ' encontrado');
            }

            $resultado = json_decode($retorno);
            $id = $resultado[0]->id;
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('Erro ao identificar o ID do grupo');
            }
            unset($dados);
            $dados['grupo'] = $id;

            $card = new Card(ucfirst(mb_strtolower($pesquisa)) . ' mais acessado');
            $retornoCard = $card->gerarEstrutura($dados, 3);

            if ($retornoCard === false) {
                throw new Exception('Não encontrado nenhum Produto para esse grupo');
            }

            echo $card->getMensagem();
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
        ?>
    </body>

</html>