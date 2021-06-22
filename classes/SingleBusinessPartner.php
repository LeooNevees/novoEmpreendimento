<?php

include_once '/var/www/html/novoEmpreendimento/classes/repository/RelationshipBusinessPartnerRepository.php';
include_once '/var/www/html/novoEmpreendimento/classes/repository/BusinessPartnerRepository.php';
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

            // $retornoAvaliacao = $this->avaliacaoVendedor($retornoDados);
			// if($retornoAvaliacao === false || !count($retornoAvaliacao)){
			// 	throw new Exception('Erro ao gerar as avaliações do produto'.$idBusiness.'. Por favor recarregue a página');
			// }			

			// $conteudo = implode("\n", $retornoDetalhesProduto).implode("\n", $retornoDescricao).implode("\n", $retornoAvaliacao);

			$conteudo = implode("\n", $retornoDescricao);

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
			$imagem = file_exists($foto) ? $foto : '/novoEmpreendimento/img/imagemNotFound.png';
			$localizacao = $dados->cidade.'-'.$dados->uf;

			$array = [];
			
			
			$array[] = "<br><div class='row row-cols-1 row-cols-lg-2'>"
				."<div class='col col-lg-6'>"
					."<div class='card shadow-sm' style='height:100%;'>"
						."<div class='card-footer text-muted'>"
						."<h2 class='cor-letra-titulo text-center'>Dados sobre o vendedor</h2>"
							."<img src='$imagem' class='img-thumbnail' style='height:100px; width:100px'>"
							."<div class='card-dados-vendedor'>"
							."<p class='card-font-valor'>$nomeVendedor</p>"
							."<p>$auxCadastro vendendo no iPeças</p>"
							."<p class='color-vermelho'>$localizacao</p>"
							."</div>"
						."</div>"
					."</div>"
				."</div>";

				$retornoRelacaoParceiro = $this->buscaRelacaoParceiroNegocio($this->parceiro);
				$classificacao = isset($retornoRelacaoParceiro->classificacao) ? mb_strtolower($retornoRelacaoParceiro->classificacao) : 'bronze';
				$vendas = isset($retornoRelacaoParceiro->vendas) ? ($retornoRelacaoParceiro->vendas > 1 ? $retornoRelacaoParceiro->vendas.' vendas concretizadas' : $retornoRelacaoParceiro->vendas.' venda concretizada') : 'Nenhuma venda concretizada';

				$array[] = "<div class='col col-lg-6'>"
					."<div class='card shadow-sm'>"
						."<div class='card-footer text-muted'>"
							."<h2 class='cor-letra-titulo text-center'>Avaliações</h2>"
							."<p>Reputação</p>"
							."<span class='cor-letra-$classificacao'><i class='fas fa-medal'></i> $classificacao</span>"
							."<p>$vendas nos últimos tempos</p>"
						."</div>"
					."</div>"
				."</div>";

			return $array;
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}

	private function buscaRelacaoParceiroNegocio($id_pn){
		try {
			$retorno = (new RelationshipBusinessPartnerRepository)->getRelationBusiness(array('id_parceiro' => $id_pn));
			if($retorno === false){
				throw new Exception('Erro na funcao Busca Relacao Parceiro Negocio na busca do Id '.$id_pn);
			}
			$retDecod = json_decode($retorno);
			return $retDecod[0];
		} catch (Exception $ex) {
			$this->mensagem = $ex->getMessage();
			return false;
		}
	}
}
