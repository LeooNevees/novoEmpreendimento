<?php
require_once '/var/www/html/novoEmpreendimento/vendor/autoload.php';
/*
 * Classe para a base da Página de Vendas de cada item
 *
 * @author leoneves
 */
class Sale{
	public $mensagem;
	private $produto;

	public function gerarEstrutura($idProduto){
		try {
			if(empty($idProduto)){
				throw new Exception('Parâmetros inválidos para a geração da Estrutura');
			}

			$this->produto = $idProduto;

			$retornoDados = $this->buscarDados($idProduto);

			if ($retornoDados === false || !count($retornoDados)) {
				throw new Exception($this->mensagem);
			}

			$retornoEstrutura = $this->gerarDadosEstrutura($retornoDados);

			if ($retornoEstrutura === false) {
				throw new Exception($this->getMensagem());
			}

			if (count($this->getMensagem()) < 1) {
				throw new Exception('Erro ao gerar a estrutura');
			}

			$conteudo = implode("\n", $this->getMensagem());

			$retorno = "<div class='album py-5 bg-index'>"
				. "<h4 class='text-center card-titulo'>" . $this->getTitulo() . "</h4>"
				. "<div class='container'>"
				. "<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3'>"
				. $conteudo
				. "</div>"
				. "</div>"
				. "</div>";

			$this->setMensagem($retorno);
			return true;
		} catch (Exception $ex) {
			$this->setMensagem($ex->getMessage());
			return false;
		}
	}

	// ARRAY $dados = Informações que pretende buscar no banco de dados
	private function buscarDados($idProduto){
		try {
			if (empty($idProduto)) {
				throw new Exception('Parâmetros inválidos');
			}
			$conexao = new Xmongo();

			$requisicao = array(
				'tabela' => 'produtos',
				'acao' => 'pesquisar',
				'dados' => array(
					'_id' => new ('')
				)
			);

			$retorno = $conexao->requisitar($requisicao);
			if ($retorno === false) {
				throw new Exception($conexao->getMensagem());
			}

			if ($conexao->getEncontrados() < 1) {
				throw new Exception('Nenhum registro encontrado');
			}

			$teste = $conexao->getMensagem();

			return $conexao->getMensagem();
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}


	private function gerarDadosEstrutura($dados){
		try {
			if (!count($dados)) {
				throw new Exception('Erro ao carregar a estrutura do produto '.$this->produto);
			}

			$array = [];
			foreach ($dados as $registros) {
				$variavelId = $registros->_id;
				foreach ($variavelId as $value) {
					$id = $value;
				}
				$onclick = "onclick=abrirCard('" . $id . "')";
				$nome = $registros->nome;
				$descricao = $registros->descricao;
				$cor = isset($registros->cor) ? $registros->cor : '';
				$auxImg = isset($registros->imagens) ? $registros->imagens : '';
				$urlImagem = !empty($auxImg) ? $auxImg->link_1 : '';
				$imagem = file_exists('/var/www/html' . $urlImagem) ? $urlImagem : '/novoEmpreendimento/img/imagemNotFound.png';
				$quantidadeEstoque = isset($registros->quantidade_estoque) ? $registros->quantidade_estoque : '';
				$quantidadeVendida = isset($registros->quantidade_vendida) ? $registros->quantidade_vendida : '';
				$valor = isset($registros->valor) ? 'R$ ' . number_format($registros->valor, 2, ',', '.') : '';
				$tipo = isset($registros->tipo) ? $registros->tipo : '';
				$opinioes = isset($registros->opinioes) ? $registros->opinioes : '';
				$status = isset($registros->status) ? $registros->status : '';
				$dataCadastro = isset($registros->data_cadastro) ? $registros->data_cadastro : '';
				$visualizacao = isset($registros->visualizacao) ? ($registros->visualizacao > 1 ? $registros->visualizacao . ' Visualizações' : $registros->visualizacao . ' Visualização') : '';

				if (isset($registros->porcentagem_promocao) && !empty($registros->porcentagem_promocao) && $registros->porcentagem_promocao > 0) {
					$porcPromocao = $registros->porcentagem_promocao;
					$valorSemDesconto = isset($registros->valor) ? 'R$ ' . number_format((($registros->valor / 100 * $porcPromocao) + $registros->valor), 2, ',', '.') : '';
					$estruturaProduto = "<div class='col'>"
						. "<div class='card shadow-sm' style='cursor:pointer;' $onclick>"
						. "<img class='tamanho-imagem-card' src='$imagem'>"
						. "<div class='card-footer text-muted'>"
						. "<p class='card-text'>" . $nome . "</p>"
						. "<p class='card-font-valor-desconto'>De " . $valorSemDesconto . " por</p>"
						. "<p class='card-font-valor'>" . $valor . "</p>"
						. "<p class='card-font-promocao'>" . $porcPromocao . "% OFF</p>"
						. "<div class='d-flex justify-content-between align-items-center'>"
						. "<small class='color-vermelho'>" . $tipo . "</small>"
						. "<small class='text-muted'>" . $visualizacao . "</small>"
						. "</div>"
						. "</div>"
						. "</div>"
						. "</div>";
					continue;
				}
			}
			return $estruturaProduto;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}
}
