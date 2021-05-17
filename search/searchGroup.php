<?php
include __DIR__ . '/../sistema.php';
include '/var/www/html/novoEmpreendimento/classes/Card.php';
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

            $dados = ['status' => 'ATIVO'];

            if (!empty($pesquisa)) {
                $conexao = new Xmongo;
                $requisicao = array(
                    'tabela' => 'grupos',
                    'acao' => 'pesquisar',
                    'dados' => ['nome' => $pesquisa]
                );
                $retorno = $conexao->requisitar($requisicao);
                if ($retorno === false) {
                    throw new Exception($conexao->getMensagem());
                }

                if ($conexao->getEncontrados() < 1) {
                    throw new Exception('Nenhum Grupo com o nome ' . $pequisa . ' encontrado');
                }

                $resultado = json_decode($conexao->getMensagem());
                $id = $resultado[0]->id;
                if (empty($id) || !is_numeric($id)) {
                    throw new Exception('Erro ao identificar o ID do grupo');
                }
                $dados['grupo'] = $id;
            }

            $card = new Card(ucfirst(mb_strtolower($pesquisa)) . ' mais acessado');
            $retornoCard = $card->gerarEstrutura($dados, 3);

            if ($retornoCard === false) {
                throw new Exception('Não encontrado nenhum Produto para esse grupo');
            }

            echo $card->getMensagem();
        } catch (\Throwable $ex) {
            echo $ex->getMessage();
            return false;
        }
        ?>
    </body>

</html>