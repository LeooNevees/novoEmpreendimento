<?php

include_once '/var/www/html/novoEmpreendimento/classes/repository/RelationshipBusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/BusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/SingleProduct.php';
include_once '/var/www/html/novoEmpreendimento/classes/Card.php';
include_once '/var/www/html/novoEmpreendimento/classes/CardProducts.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/ProductsRepository.php';
/*
 * Classe para a base da Página de Vendas de cada item
 * OBS: A princípio foi criado para cada ROW(linha no css) uma function trazendo as informações específicas 
 * @author leoneves
 */
class SingleBusinessPartner{
	public $mensagem;
	private $parceiro;

	public function gerarEstrutura($idBusiness){
		try {
			if(empty($idBusiness)){
				throw new Exception('Parâmetros inválidos para a geração da Estrutura');
			}
			$this->parceiro = $idBusiness;

			$retornoDados = $this->buscarParceiroNegocio($idBusiness);
			if ($retornoDados === false || !count($retornoDados)) {
				throw new Exception('Erro ao buscar os dados referente ao Id Parceiro '.$idBusiness);
			}

            $retornoDescricao = $this->descricaoParceiro($retornoDados[0]);
			if($retornoDescricao === false || !count($retornoDescricao)){
				throw new Exception('Erro ao gerar as descrições do produto'.$idBusiness.'. Por favor recarregue a página');
			}

			//Produtos Parceiro Negócio
			$dadosProdutos = array(
				'id_vendedor' => $this->parceiro,
				'status' => 'ATIVO'
			);
			$retornoBuscaProduto = $this->buscaProdutoParceiro($dadosProdutos);
			$retProdutos = "</div><br><br>"
								."<h4 class='text-center card-titulo'>Principais Produtos</h4>"
								."<div class='alert alert-danger text-center' role='alert'>Vendedor não possui mercadoria à venda</div>"
							."</div>";
			if(!empty($retornoBuscaProduto)){
				$classeCardProducts = new CardProducts('Produtos ativos no Mercado');
				$retornoProdutos = $classeCardProducts->gerarDadosEstrutura($retornoBuscaProduto, 6);
				if($retornoProdutos === false){
					throw new Exception($classeCardProducts->getMensagem());
				}
				$auxRetornoProd = $classeCardProducts->getMensagem();
				if(!count($auxRetornoProd)){
					$auxProdutos = '<div class="alert alert-danger" role="alert">Vendedor não possui mercadoria à venda</div>';
				}
				$retProdutos = "</div><br><br>"
									."<h4 class='text-center card-titulo'>Principais Produtos</h4>"
									."<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3'>"
									.implode("\n", $auxRetornoProd)
								."</div>";
			}

			$retAvaliacao = $this->avaliacaoBusiness($this->parceiro);
			if($retAvaliacao === false){
				throw new Exception('Erro ao buscar a Avaliação do vendedor');				
			}
			if(empty($retAvaliacao)){
				$retAvaliacao = "<br><br><div class='container'><h4 class='text-center card-titulo'>Avaliações sobre o Vendedor</h4>"
									."<div class='alert alert-danger text-center' role='alert'>Vendedor não possui avaliações</div>"
								."</div>"
								."</div>";
			}

			$conteudo = implode("\n", $retornoDescricao).$retProdutos.$retAvaliacao;
			$retorno = "<div class='container'>"
							.$conteudo
						."</div><br><br>";

			return $retorno;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function buscarParceiroNegocio($idParceiro){
		try {
			if (empty($idParceiro)) {
				throw new Exception('Parâmetros inválidos');
			}
			$repository = new BusinessPartnerRepository;
            $dados = array('_id' => new MongoDB\BSON\ObjectID($idParceiro));
			$retorno = json_decode($repository->getBusinessPartner($dados));
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

	private function descricaoParceiro($dados){
		try {
			if (empty($dados)) {
				throw new Exception('Parâmetros inválidos na descrição do Pareciro '.$this->parceiro);
			}
			
			$nomeVendedor = ucwords(mb_strtolower($dados->nome_completo));
			$dataCadastro = substr($dados->data_cadastro, 0, 4);
			$dataAtual = date('Y');
			$tempoCadastrado = ((int) date('Y')) - (int) substr($dados->data_cadastro, 0, 4);
			$auxCadastro = $tempoCadastrado > 1 ? $tempoCadastrado.' anos' : '1 ano';
			$foto = isset($dados->foto) ? $dados->foto : '/novoEmpreendimento/img/imagemNotFound.png';
			$imagem = file_exists('/var/www/html'.$foto) ? $foto : '/novoEmpreendimento/img/imagemNotFound.png';
			$localizacao = $dados->cidade.'-'.$dados->uf;

			$array = [];
			
			
			$array[] = "<br><div class='row row-cols-1 row-cols-lg-2'>"
				."<div class='col col-lg-6'>"
					."<div class='card shadow-sm' style='height:100%;'>"
						."<div class='card-footer text-muted' style='height:100%;'>"
						."<h2 class='cor-letra-titulo text-center'>Dados sobre o vendedor</h2>"
							."<img src='$imagem' class='img-thumbnail img-single-business'>"
							."<div class='card-dados-vendedor'>"
							."<p class='card-font-valor'>$nomeVendedor</p>"
							."<p>$auxCadastro vendendo no iPeças</p>"
							."<p class='color-vermelho'>$localizacao</p>"
							."</div>"
						."</div>"
					."</div>"
				."</div>";


				

				$retVendedor = (new SingleProduct)->buscarVendedor($this->parceiro);
				$vendas = isset($retVendedor['vendas']) ? ($retVendedor['vendas'] > 1 ? $retVendedor['vendas'].' concretizadas' : $retVendedor['vendas'].' concretizada') : 'Nenhuma venda concretizada';
				$classificacao = isset($retVendedor['classificacao']) && !empty($retVendedor['classificacao']) ? mb_strtolower($retVendedor['classificacao']) : 'bronze';
				$titulo = ucfirst($classificacao);
				$detalheEntrega = isset($retVendedor['detalheEntrega']) && !empty($retVendedor['detalheEntrega'])? $retVendedor['detalheEntrega'] : 'Sem entregas';
				$classeEntrega = isset($retVendedor['classeEntrega']) && !empty($retVendedor['classeEntrega']) ? $retVendedor['classeEntrega'] : '';
				$detalheAtendimento = isset($retVendedor['detalheAtendimento']) && !empty($retVendedor['detalheAtendimento']) ? $retVendedor['detalheAtendimento'] : 'Sem atendimentos';
				$classeAtendimento = isset($retVendedor['classeAtendimento']) && !empty($retVendedor['classeAtendimento']) ? $retVendedor['classeAtendimento'] : '';
				$classMaiusculo = mb_strtoupper($classificacao);

				$array[] = "<div class='col col-lg-6' style='height:100%'>"
					."<div class='card shadow-sm' style='height:100%'>"
						."<div class='card-footer text-muted text-center' style='height:100%'>"
							."<h2 class='cor-letra-titulo text-center'>Reputação</h2>"
							."<span class='cor-letra-$classificacao text-center'><i class='fas fa-medal'></i> $classMaiusculo</span>"
							."<p>$vendas nos últimos tempos</p>"
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

			return $array;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function buscaProdutoParceiro($dadosBusca){
		try {	
			if(empty($dadosBusca) || !count($dadosBusca)){
				throw new Exception('Parâmetros inválidos para a função Busca Produto Parceiro');
			}

			$retorno = (new ProductsRepository)->getProductLimit($dadosBusca, 6);
			if($retorno === false){
				throw new Exception('Erro ao fazer a busca dos produtos do Parceiro de Negócio');
			}

			return $retorno;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function avaliacaoBusiness($id){
		try {
			if(empty($id)){
				throw new Exception('Parâmetro inválido para a função Avaliacao Business');
			}

			$relacaoBusinessDecod = json_decode($this->BuscarRelacaoParceiro($id));
			if($relacaoBusinessDecod === false){
				throw new Exception('Erro ao buscar o Parceiro '.$id);
			}
			if(empty($relacaoBusinessDecod)){
				return null;
			}
			$retornoRelacaoBusiness = $relacaoBusinessDecod[0];
			$auxOpinioes = isset($retornoRelacaoBusiness->avaliacoes_vendas) ? $retornoRelacaoBusiness->avaliacoes_vendas : '';
				$arrayOpinioes = [];
				$contador = 1;
				foreach ($auxOpinioes as $valor) {
					$atendimento = isset($valor->atendimento) ? $valor->atendimento: 0;
					$entrega = isset($valor->tempo_entrega) ? $valor->tempo_entrega : 0;
					$media = ($atendimento + $entrega) / 2;
					$retornoEstrela = $this->montarEstrela($media);
					$estrelas = $retornoEstrela['arrayEstrela'];
					$titulo = isset($valor->titulo) ? ucfirst(mb_strtolower($valor->titulo)) : 'Sem título';
					$descricao = isset($valor->observacao) ? ucfirst(mb_strtolower($valor->observacao)) : 'Sem descrição';
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

			$retornoAvaliacao = $this->calculaAvaliacaoParceiro($retornoRelacaoBusiness);
			if($retornoRelacaoBusiness === false){
				throw new Exception('Erro ao buscar avaliação do Parceiro');
			}
			$classe = isset($retornoAvaliacao['classe']) && !empty($retornoAvaliacao['classe']) ? $retornoAvaliacao['classe'] : '';
			$mediaEstrela = isset($retornoAvaliacao['mediaEstrela']) ? $retornoAvaliacao['mediaEstrela'] : 0;
			$iconeEstrela = isset($retornoAvaliacao['icone']) ? $retornoAvaliacao['icone'] : '';

			$ret = "<br><br><br><div class='col col-lg-12'>"
						."<div class='card shadow-sm'>"
							."<div class='card-footer text-muted text-center'>"
								."<h2 class='cor-letra-titulo'>Avaliações sobre o Vendedor</h2>"
								."<h3 class='$classe'>Média $mediaEstrela</h3>"
								."$iconeEstrela"
								."<div class='accordion scrollspySite' id='accordionPanelsStayOpenExample'>"
									."$opinioes"
								."</div>"
							."</div>"
						."</div>"
					."</div>";
			
			return $ret;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function BuscarRelacaoParceiro($id){
		try {
			if(empty($id) || is_array($id)){
				throw new Exception('Parâmetro inválido para a função Buscar Relacao Parceiro');
			}
			$dados = ['id_parceiro' => $id];
			$retornoRelation = (new RelationshipBusinessPartnerRepository)->getRelationBusiness($dados);
			if($retornoRelation === false){
				throw new Exception('Erro ao buscar o Parceiro'. $id.' na tabela Relation Business');
			}

			return $retornoRelation;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function calculaAvaliacaoParceiro($registros){
		try {
			$mediaAtendimento = isset($registros->media_atendimento) ? $registros->media_atendimento : 0;
			$mediaEntrega = isset($registros->media_entrega) ? $registros->media_entrega : 0;
			$mediaFinal = ($mediaAtendimento + $mediaEntrega) / 2;

			$retornoEstrela = $this->montarEstrela($mediaFinal);
			
			$retorno = array(
				'mediaEstrela' => $mediaFinal,
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

}
