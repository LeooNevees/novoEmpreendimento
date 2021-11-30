<?php
include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/RelationshipBusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/BusinessPartnerRepository.php';
/*
 * Classe para a base da Página de Vendas de cada item
 * OBS: A princípio foi criado para cada ROW(linha no css) uma function trazendo as informações específicas 
 * @author leoneves
 */
class SingleProduct{
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
			$repository = new ProductsRepository;
			$retorno = $repository->getProduct($idProduto);
			if ($retorno === false) {
				throw new Exception($repository->mensagem);
			}

			if ($repository->encontrados < 1) {
				throw new Exception('Nenhum registro encontrado');
			}

			return $retorno;
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
				$onclickCompra = "onclick=efetuarCompra('".$this->produto."')";
				$onclickCarrinho = "onclick=adicionarCarrinho('".$this->produto."')";
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

				$auxButton[] = "<button class='bg-vermelho' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='0' aria-label='Slide 0'></button>";
				$auxDiv[] = "<div class='carousel-item width-100 height-100'><img src='/novoEmpreendimento/img/imagemNotFound.png' class='d-block w-100' alt='...'></div>";

				if(isset($registros->imagens)){//TRATAMENTO DAS IMAGENS
					$contadorButton = 0;
					unset($auxButton);
					unset($auxDiv);
					foreach ($registros->imagens as $value) {
						if($contadorButton == 0){
							if(count((array)$registros->imagens) == 1){
								$auxButton[] = "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='$contadorButton' class='active bg-vermelho' aria-current='true' aria-label='Slide $contadorButton'></button>";
								$auxDiv[] = "<div class='carousel-item active'><img src='$value' class='d-block w-100 width-100 height-100' alt='...'></div>";
								$contadorButton++;
								continue;
							}
							$auxButton[] = "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='$contadorButton' class='active bg-vermelho' aria-current='true' aria-label='Slide $contadorButton'></button>";
							$auxDiv[] = "<div class='carousel-item active'><img src='$value' class='d-block w-100 width-100 height-100' alt='...'></div>";
							$contadorButton++;
							continue;
						}
						$auxButton[] = "<button class='bg-vermelho' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='$contadorButton' aria-label='Slide $contadorButton'></button>";
						$auxDiv[] = "<div class='carousel-item width-100 height-100'><img src='$value' class='d-block w-100 width-100 height-100' alt='...'></div>";
						$contadorButton++;
					}
				}
				$button = isset($auxButton) ? implode("\n", $auxButton) : '';
				$div = implode("\n", $auxDiv);	
				//ESTRUTURA
				$array[] = "<br>"
				."<div class='row row-cols-1 row-cols-lg-2'>"
					."<div class='col col-lg-8'>"
						."<div class='card shadow-sm width-100 height-100' style='cursor:pointer;'>"
							."<div id='carouselExampleIndicators' class='carousel slide width-100 height-100' data-bs-ride='carousel'>"
								."<div class='carousel-indicators'>"
									."$button"
								."</div>"
								."<div class='carousel-inner'>"
									."$div"
								."</div>"
								."<button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide='prev'>"
									."<i class='fas fa-angle-left color-vermelho' style='margin-left:0%;'></i>"
									."<span class='visually-hidden'>Previous</span>"
								."</button>"
								."<button class='carousel-control-next' type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide='next'>"
									."<i class='fas fa-angle-right color-vermelho' style='margin-left:0%;'></i>"
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
									."<span> Entrega prevista para <font><b>Quinta-Feira 2 de Dezembro<b></font></span><br>"
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
									."<input type='number' class='form-control text-center' value='1' id='quantidade_compra' name='quantidade_compra' min='1'>"
									."<span class='input-group-text'>$quantidadeEstoque Disponíveis</span>"
								."</div>"
								."<div class='d-grid gap-2 div-botao-comprar'>"
									."<button class='btn btn-success' type='button' $onclickCompra>Comprar Agora</button>"
									."<button class='btn btn-secondary' type='button' $onclickCarrinho>Adicionar ao Carrinho</button>"
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
			$mediaEstrela = isset($registros->media) ? $registros->media : 0;

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
				
				$arrayOpinioes = [];
				$contador = 1;
				if(isset($registros->opinioes)){
					foreach ($registros->opinioes as $valor) {
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
				}
				
				$opinioes = implode("\n", $arrayOpinioes);

				$retVendedor = $this->buscarVendedor($registros->id_vendedor);
				$nomeVendedor = ucwords(mb_strtolower($retVendedor['nome']));
				$vendas = isset($retVendedor['vendas']) && (int) $retVendedor['vendas'] > 0 ? $retVendedor['vendas'] : 'Sem Vendas';
				$classificacao = isset($retVendedor['classificacao']) && !empty($retVendedor['classificacao']) ? mb_strtolower($retVendedor['classificacao']) : 'bronze';
				$titulo = ucfirst($classificacao);
				$detalheEntrega = isset($retVendedor['detalheEntrega']) && !empty($retVendedor['detalheEntrega'])? $retVendedor['detalheEntrega'] : 'Sem entregas';
				$classeEntrega = isset($retVendedor['classeEntrega']) && !empty($retVendedor['classeEntrega']) ? $retVendedor['classeEntrega'] : '';
				$detalheAtendimento = isset($retVendedor['detalheAtendimento']) && !empty($retVendedor['detalheAtendimento']) ? $retVendedor['detalheAtendimento'] : 'Sem atendimentos';
				$classeAtendimento = isset($retVendedor['classeAtendimento']) && !empty($retVendedor['classeAtendimento']) ? $retVendedor['classeAtendimento'] : '';				

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
								."<h4 class='cor-letra-$classificacao text-center'><i class='fas fa-medal'></i> $nomeVendedor - $titulo</h4><br>"
								."<div>"
									."<table class='table table-borderless text-center'>"
										."<thead>"
											."<tr>"
												."<th class='col-4'><i class='fas fa-shopping-bag fa-2x'></i></th>"
												."<th class='col-4'><i class='$classeEntrega fas fa-truck fa-2x'></i></th>"
												."<th class='col-4'><i class='$classeAtendimento far fa-comment-dots fa-2x'></i></th>"
											."</tr>"
										."</thead>"
										."<tbody>"
											."<tr>"
												."<td>$vendas</td>"
												."<td>$detalheEntrega</td>"
												."<td>$detalheAtendimento</td>"
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

	public function buscarVendedor($id){
		try {
			if(empty($id)){
				throw new Exception('Parâmetros para Buscar Vendedor inválidos');
			}

			$repository = new RelationshipBusinessPartnerRepository;
			$dados = array('id_parceiro' => $id);

			$retorno = $repository->getRelationBusiness($dados);
			if ($retorno === false) {
				throw new Exception($repository->mensagem);
			}

			$auxVendedor = json_decode($retorno);
			if(!count((array) $auxVendedor)){
				$repoBusiness = new BusinessPartnerRepository;
				$dadosBusiness = array('_id' => new MongoDB\BSON\ObjectID($id));
				$newRetorno = $repoBusiness->getBusinessPartner($dadosBusiness);
				if ($newRetorno === false) {
					throw new Exception($repoBusiness->mensagem);
				}
				$ret = json_decode($newRetorno);
				$pn = $ret[0];
				$arrayRetorno = array(
					'nome' => isset($pn->nome_fantasia) && !empty($pn->nome_fantasia) ? $pn->nome_fantasia : $pn->nome_completo
				);
				return $arrayRetorno;
			}

			$avalVendas = $auxVendedor[0];
			$arrayRetorno = [];
			if(isset($avalVendas->media_entrega)){
				$classeEntrega = 'cor-letra-vermelho';
				$detalheEntrega = 'Não costuma entregar os produtos dentro do prazo';
				if($avalVendas->media_entrega >= 3){
					$classeEntrega = 'cor-letra-laranja';
					$detalheEntrega = 'Entrega parcialmente seus produtos dentro do prazo';
				}
				if($avalVendas->media_entrega >= 4){
					$classeEntrega = 'cor-letra-promocao';
					$detalheEntrega = 'Sempre entrega seus produtos dentro do prazo';
				}
				$arrayRetorno['detalheEntrega'] = $detalheEntrega;
				$arrayRetorno['classeEntrega'] = $classeEntrega;
			}

			if(isset($avalVendas->media_atendimento)){
				$classeAtendimento = 'cor-letra-vermelho';
				$detalheAtendimento = 'Péssimo atendimento';
				if($avalVendas->media_atendimento >= 3){
					$classeAtendimento = 'cor-letra-laranja';
					$detalheAtendimento = 'Atendimento mediano';
				}
				if($avalVendas->media_atendimento >= 4){
					$classeAtendimento = 'cor-letra-promocao';
					$detalheAtendimento = 'Ótimo atendimento';
				}				
			}
			
			$arrayRetorno = array(
				'nome' => isset($avalVendas->nome_fantasia) && !empty($avalVendas->nome_fantasia) ? $avalVendas->nome_fantasia : $avalVendas->nome_completo,
				'classificacao' => isset($avalVendas->classificacao) ? $avalVendas->classificacao : '',
				'vendas' => isset($avalVendas->vendas) ? ($avalVendas->vendas > 1 ? $avalVendas->vendas.' Vendas' : $avalVendas->vendas.' Venda') : '',
				'detalheEntrega' => isset($detalheEntrega) ? $detalheEntrega : '',
				'classeEntrega' => isset($classeEntrega) ? $classeEntrega : '',
				'detalheAtendimento' => isset($detalheAtendimento) ? $detalheAtendimento : '',
				'classeAtendimento' => isset($classeAtendimento) ? $classeAtendimento : ''
			);

			return $arrayRetorno;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}
}
