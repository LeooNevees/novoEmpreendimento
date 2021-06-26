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
			$classeCardProducts = new CardProducts('Produtos ativos no Mercado');
			$retornoProdutos = $classeCardProducts->gerarDadosEstrutura($retornoBuscaProduto, 6);
			if($retornoProdutos === false){
				throw new Exception($classeCardProducts->getMensagem());
			}
			$auxRetornoProd = $classeCardProducts->getMensagem();
			if(!count($auxRetornoProd)){
				$retProdutos = 'Vendedor não possui mercadoria à venda';
			}
			$retProdutos = "</div>"
							."<h4 class='text-center card-titulo'>Produtos ativos no Mercado</h4>"
							."<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3'>"
								.implode("\n", $auxRetornoProd)
							."</div>";
            // $retornoAvaliacao = $this->avaliacaoVendedor($retornoDados);
			// if($retornoAvaliacao === false || !count($retornoAvaliacao)){
			// 	throw new Exception('Erro ao gerar as avaliações do produto'.$idBusiness.'. Por favor recarregue a página');
			// }			

			// $conteudo = implode("\n", $retornoDetalhesProduto).implode("\n", $retornoDescricao).implode("\n", $retornoAvaliacao);

			$conteudo = implode("\n", $retornoDescricao).$retProdutos;

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

				$array[] = "<div class='col col-lg-6'>"
					."<div class='card shadow-sm'>"
						."<div class='card-footer text-muted text-center'>"
							."<h2 class='cor-letra-titulo text-center'>Avaliações</h2>"
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
}
