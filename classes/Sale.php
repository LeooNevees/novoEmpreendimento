<?php
/*
 * Classe para a base da Página de Vendas de cada item
 * OBS: A princípio foi criado para cada ROW(linha no css) uma function trazendo as informações específicas 
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

			$retornoDados = json_decode($this->buscarDados($idProduto));
			if ($retornoDados === false || !count($retornoDados)) {
				throw new Exception('Erro ao buscar os dados referente ao Id Produto '.$idProduto);
			}

			$retornoDetalhesProduto = $this->detalhesProduto($retornoDados);
			if ($retornoDetalhesProduto === false || !count($retornoDetalhesProduto)) {
				throw new Exception('Erro ao gerar os detalhes do produto '.$idProduto.'. Por favor recarregue a página');
			}

			$retornoDescricao = $this->descricaoProduto($retornoDados);
			if($retornoDescricao === false || !count($retornoDescricao)){
				throw new Exception('Erro ao gerar as descrições do produto'.$idProduto.'. Por favor recarregue a página');
			}

			$retornoAvaliacao = $this->avaliacaoProdutoVendedor($retornoDados);
			if($retornoAvaliacao === false || !count($retornoAvaliacao)){
				throw new Exception('Erro ao gerar as avaliações do produto'.$idProduto.'. Por favor recarregue a página');
			}

			$conteudo = implode("\n", $retornoDetalhesProduto).implode("\n", $retornoDescricao).implode("\n", $retornoAvaliacao);

			$retorno = "<div class='container'>"
							.$conteudo
						."</div><br><br>";

			return $retorno;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

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
					'_id' => new MongoDB\BSON\ObjectID($idProduto)
				)
			);

			$retorno = $conexao->requisitar($requisicao);
			if ($retorno === false) {
				throw new Exception($conexao->getMensagem());
			}

			if ($conexao->getEncontrados() < 1) {
				throw new Exception('Nenhum registro encontrado');
			}

			return $conexao->getMensagem();
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function detalhesProduto($dados){
		try {
			if (!count($dados)) {
				throw new Exception('Parâmetros Inválidos no Detalhes do Produto '.$this->produto);
			}

			$array = [];
			foreach ($dados as $registros) {
				//Imagem
				$auxImg = isset($registros->imagens) ? $registros->imagens : '';
				$urlImagem = !empty($auxImg) ? $auxImg->link_1 : '';
				$imagem = file_exists('/var/www/html' . $urlImagem) ? $urlImagem : '/novoEmpreendimento/img/imagemNotFound.png';
				//Detalhes
				$tipo = isset($registros->tipo) ? ucfirst(mb_strtolower($registros->tipo)) : '';
				$quantidadeVendida = isset($registros->quantidade_vendida) ? ($registros->quantidade_vendida > 1 ? $registros->quantidade_vendida.' Vendidos' : $registros->quantidade_vendida.' Vendido')  : '';
				$nome = isset($registros->nome) ? ucfirst(mb_strtolower($registros->nome)) : '';
				$valor = isset($registros->valor) ? 'R$ ' . number_format($registros->valor, 2, ',', '.') : '';
				$valorParcelado = isset($registros->valor) ? 'R$ ' . number_format(($registros->valor/12), 2, ',', '.') : '';
				$cor = isset($registros->cor) ? ucfirst(mb_strtolower($registros->cor)) : 'Indisponível';
				$quantidadeEstoque = isset($registros->quantidade_estoque) ? $registros->quantidade_estoque : '';
				setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
				date_default_timezone_set('America/Sao_Paulo');
				$dataEntrega = strftime('%A - %d de %B', strtotime('today'));

				$retornoAvaliacao = $this->calculaAvaliacaoProduto($registros);
				$quantidadeAvaliacao = isset($retornoAvaliacao['quantidadeOpiniao']) ? ($retornoAvaliacao['quantidadeOpiniao'] > 1 ? $retornoAvaliacao['quantidadeOpiniao'].' opiniões' : $retornoAvaliacao['quantidadeOpiniao']. ' opinião') : 0;
				$iconeEstrela = isset($retornoAvaliacao['icone']) ? $retornoAvaliacao['icone'] : '';

				//VALORES
				$divValores = "<div class='valores'>"
								."<label class='letra-tamanho-130'>$valor</label><br>"
								."<span>em até <font class='cor-letra-promocao'>12x $valorParcelado sem juros</font></span><br><br>"
							."</div>";

				if(isset($registros->porcentagem_promocao) && !empty($registros->porcentagem_promocao) && $registros->porcentagem_promocao > 0){	
					$porcPromocao = $registros->porcentagem_promocao;
					$valorSemDesconto = isset($registros->valor) ? 'R$ '.number_format((($registros->valor/100*$porcPromocao)+$registros->valor), 2, ',', '.') : '';
					$divValores =  "<div class='valores'>"
									."<span class='cor-letra-cinza letra-tamanho-80 letra-riscado'>$valorSemDesconto</span><br>"
									."<label class='letra-tamanho-130'>$valor</label><span class='letra-promocao'>$porcPromocao% OFF</span><br>"
									."<span>em até <font class='cor-letra-promocao'>12x $valorParcelado sem juros</font></span><br><br>"
								."</div>";
				}

				//ESTRUTURA
				$array[] = "<br>"
				."<div class='row row-cols-1 row-cols-lg-2'>"
					."<div class='col col-lg-8'>"
						."<div class='card shadow-sm' style='cursor:pointer;'>"
							."<div id='carouselExampleIndicators' class='carousel slide' data-bs-ride='carousel'>"
								."<div class='carousel-indicators'>"
									."<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='0' class='active' aria-current='true' aria-label='Slide 1'></button>"
									."<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='1' aria-label='Slide 2'></button>"
									."<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='2' aria-label='Slide 3'></button>"
								."</div>"
								."<div class='carousel-inner'>"
									."<div class='carousel-item active'>"
										."<img src='/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg' class='d-block w-100' alt='...'>"
									."</div>"
									."<div class='carousel-item'>"
										."<img src='/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg' class='d-block w-100' alt='...'>"
									."</div>"
									."<div class='carousel-item'>"
										."<img src='/novoEmpreendimento/files/60516e7e069c1f1d248b4569/20210422202305.jpg' class='d-block w-100' alt='...'>"
									."</div>"
								."</div>"
								."<button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide='prev'>"
									."<span class='carousel-control-prev-icon' aria-hidden='true'></span>"
									."<span class='visually-hidden'>Previous</span>"
								."</button>"
								."<button class='carousel-control-next' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide='next'>"
									."<span class='carousel-control-next-icon' aria-hidden='true'></span>"
									."<span class='visually-hidden'>Next</span>"
								."</button>"
							."</div>"
						."</div>"
					."</div>"
					."<div class='col col-lg-4'>"
						."<div class='card shadow-sm' style='height:100%;'>"
							."<div class='card-footer text-muted' style='height:100%'>"
								."<div>"
									."<span class='cor-letra-cinza'>$tipo | $quantidadeVendida</span>"
									."<h2>$nome</h2>"
									."$iconeEstrela"
									."<span class='cor-letra-cinza'> $quantidadeAvaliacao</span>"
								."</div>"
								."$divValores"
								."<div>"
									."<i class='fas fa-truck'></i>"
									."<span> Entrega prevista para <font><b>$dataEntrega<b></font></span><br>"
								."</div><br>"
								."<div>"
									."<label>Cor: <font><b>$cor</b></font></label>"
								."</div><br>"
								."<div>"
									."<label>"
										."<font class='cor-letra-promocao'>Estoque Disponível</font>"
									."</label>"
								."</div>"
								."<div class='input-group'>"
									."<input type='number' class='form-control text-center' value='1' aria-label='Dollar amount (with dot and two decimal places)'>"
									."<span class='input-group-text'>$quantidadeEstoque Disponíveis</span>"
								."</div>"
								."<div class='d-grid gap-2 div-botao-comprar'>"
									."<button class='btn btn-success' type='button'>Comprar Agora</button>"
									."<button class='btn btn-secondary' type='button'>Adicionar ao Carrinho</button>"
								."</div><br>"
								."<div class='cor-letra-promocao text-center'>"
									."<i class='fas fa-shield-alt'></i>"
									."<span class='letra-tamanho-80'>Compra Garantida</span><br>"
								."</div>"
							."</div>"
						."</div>"
					."</div>"
				."</div><br>";
			}
			return $array;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function calculaAvaliacaoProduto($registros){
		try {
			$auxOpn = isset($registros->opinioes) ? (array) $registros->opinioes : '';
			$quantidadeOpiniao = is_array($auxOpn) ? count($auxOpn) : 0;
			$somaEstrela = 0;
			foreach ($auxOpn as $registro) {
				$somaEstrela += $registro->estrela;
			}
			$mediaEstrela = $somaEstrela/$quantidadeOpiniao;

			$retornoEstrela = $this->montarEstrela($mediaEstrela);
			
			$retorno = array(
				'quantidadeOpiniao' => $quantidadeOpiniao,
				'mediaEstrela' => $mediaEstrela,
				'classe' => $retornoEstrela['classe'],
				'icone' => $retornoEstrela['arrayEstrela']
			);
		
			return $retorno;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function montarEstrela($qtdeEstrela){
		try {
			if(!is_numeric($qtdeEstrela)){
				throw new Exception('Parâmetro inválido para o Montar Estrela do produto '.$this->produto);
			}

			$classe = 'cor-letra-vermelho';
			if($qtdeEstrela >= 3){
				$classe = 'cor-letra-laranja';
			}
			if($qtdeEstrela >= 4){
				$classe = 'cor-letra-promocao';
			}
			
			$auxMed = $qtdeEstrela;
			$arrayEstrela = [];
			for ($i=0; $i < 5; $i++) {
				$icone = 'fas fa-star';
				if($auxMed > 0 && $auxMed < 1){
					$icone = 'fas fa-star-half-alt';
				}
				if($auxMed <= 0){
					$icone = 'far fa-star';
				}
				$auxMed--;
				$arrayEstrela[] = "<i class='$classe $icone'></i>";
			}
			$retorno = array(
				'classe' => $classe,
				'arrayEstrela' => implode("\n", $arrayEstrela)
			);

			return $retorno;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function descricaoProduto($dados){
		try {
			if (!count($dados)) {
				throw new Exception('Parâmetros Inválidos na Descrição do produto '.$this->produto);
			}

			$array = [];
			foreach ($dados as $registros) {
				$descricao = isset($registros->descricao) ? ucfirst(mb_strtolower($registros->descricao)) : '';

				$array[] = "<div class='row row-cols-1'>"
					."<div class='col-12'>"
						."<div class='card shadow-sm' style='cursor:pointer;'>"
							."<div class='card-footer text-muted text-center'>"
								."<h2 class='cor-letra-titulo'>Descrição</h2>"
								."<p class='lead fw-normal'>$descricao</p>"
							."</div>"
						."</div>"
					."</div>"
				."</div><br>";
			}
			return $array;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function avaliacaoProdutoVendedor($dados){
		try {
			if (!count($dados)) {
				throw new Exception('Parâmetros inválidos na Avaliação do Produto '.$this->produto);
			}

			$array = [];
			foreach ($dados as $registros) {
				$retornoAvaliacao = $this->calculaAvaliacaoProduto($registros);
				$mediaEstrela = isset($retornoAvaliacao['mediaEstrela']) ? number_format($retornoAvaliacao['mediaEstrela'], 1, '.', '') : '0.0';
				$iconeEstrela = isset($retornoAvaliacao['icone']) ? $retornoAvaliacao['icone'] : '';
				$classe = isset($retornoAvaliacao['classe']) ? $retornoAvaliacao['classe'] : '';
				
				$auxOpinioes = isset($registros->opinioes) ? $registros->opinioes : '';
				$arrayOpinioes = [];
				$contador = 1;
				foreach ($auxOpinioes as $valor) {
					$retornoEstrela = $this->montarEstrela($valor->estrela);
					$estrelas = $retornoEstrela['arrayEstrela'];
					$titulo = isset($valor->titulo) ? ucfirst(mb_strtolower($valor->titulo)) : 'Sem título';
					$descricao = isset($valor->descricao) ? ucfirst(mb_strtolower($valor->descricao)) : 'Sem descrição';
					$autor = isset($valor->nome_parceiro) ? ucfirst(mb_strtolower(strstr($valor->nome_parceiro, ' ', true))) : 'Autor Desconhecido';
					$auxData = isset($valor->data_hora) ? new DateTime($valor->data_hora) : '';
					$data = !empty($auxData) ? $auxData->format('d-m-Y H:i') : '';

					$arrayOpinioes[] = "<div class='accordion-item'>"
						."<h2 class='accordion-header' id='panelsStayOpen-heading$contador'>"
							."<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#panelsStayOpen-collapse$contador' aria-expanded='false' aria-controls='panelsStayOpen-collapse$contador'>
							$estrelas &nbsp;<i class='cor-letra-cinza fas fa-minus'></i>&nbsp; $titulo
							</button>"
						."</h2>"
						."<div id='panelsStayOpen-collapse$contador' class='accordion-collapse collapse' aria-labelledby='panelsStayOpen-heading$contador' style='background-color:white;'>"
							."<div class='accordion-body' >"
								."$descricao"
								."<footer class='blockquote-footer espaco-2 text-right' title='$data'>$autor</footer>"
							."</div>"
						."</div>"
					."</div>";

					$contador++;
				}

				$opinioes = implode("\n", $arrayOpinioes);

				// $retornoVendedor = $this->avaliacaoVendedor($registro);

				$array[] = "<div class='row row-cols-1 row-cols-lg-2'>"
					."<div class='col col-lg-6'>"
						."<div class='card shadow-sm'>"
							."<div class='card-footer text-muted text-center'>"
								."<h2 class='cor-letra-titulo'>Avaliações sobre o produto</h2>"
								."<h3 class='$classe'>Média $mediaEstrela</h3>"
								."$iconeEstrela"
								."<div class='accordion scrollspySite' id='accordionPanelsStayOpenExample'>"
									."$opinioes"
								."</div>"
							."</div>"
						."</div>"
					."</div>"
					."<div class='col col-lg-6'>"
						."<div class='card shadow-sm' style='height:100%;'>"
							."<div class='card-footer text-muted text-left' style='height:100%;'>"
								."<h2 class='cor-letra-titulo text-center'>Dados sobre o vendedor</h2><br>"
								."<h4 class='cor-letra-ouro text-center'><i class='fas fa-medal'></i> Leonardo Neves - Ouro</h4><br>"
								."<div>"
									."<table class='table table-borderless text-center'>"
										."<thead>"
											."<tr>"
												."<th class='col-4'><i class='fas fa-shopping-bag fa-2x'></i></th>"
												."<th class='col-4'><i class='cor-letra-vermelho fas fa-truck fa-2x'></i></th>"
												."<th class='col-4'><i class='cor-letra-promocao far fa-comment-dots fa-2x'></i></th>"
											."</tr>"
										."</thead>"
										."<tbody>"
											."<tr>"
												."<td>625 Vendas</td>"
												."<td>Entrega dos produsos dentro do prazo (Ruim)</td>"
												."<td>Presta bom atendimento (Bom)</td>"
											."</tr>"
										."</tbody>"
									."</table>"
								."</div>"
							."</div>"
						."</div>"
					."</div>";
			}
			return $array;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	// private function avaliacaoVendedor($dados){
	// 	try {
	// 		$id = $dados->id_vendedor;
	// 		$retornoVendedor = $this->buscarVendedor($id);
	// 		if($retornoVendedor === false || !count($retornoVendedor)){
	// 			throw new Exception('Erro ao buscar o vendedor '.$id);
	// 		}
			
	// 		$qtdeVendas = isset($retornoVendedor->vendas) ? $retornoVendedor->vendas : 0;
	// 		$auxOpin
	// 		$array = [];
	// 		foreach ($retornoVendedor as $valores) {
	// 			$array[] = 
	// 		}

	// 		return $array;

	// 	} catch (Exception $ex) {
	// 		$this->mensagem = $ex->getMessage();
	// 		return false;
	// 	}
	// }

	private function buscarVendedor($id){
		try {
			$requisicao = array(
				'tabela' => 'parceiroNegocio',
				'acao' => 'pesquisar',
				'dados' => array(
					'id_vendedor' => $id
				)
			);
			$retorno = $conexao->requisitar($requisicao);
			if ($retorno === false) {
				throw new Exception($conexao->getMensagem());
			}

			if ($conexao->getEncontrados() < 1) {
				throw new Exception('Nenhum registro encontrado');
			}

			return $conexao->getMensagem();
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}
}
