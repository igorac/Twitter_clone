<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
	public function timeline()
	{	
		$this->validaAutenticacao();

		//Fazer a recuperação dos tweets
		$tweet = Container::getModel('Tweet');
			
		$tweet->__set('id_usuario', $_SESSION['id']);
		$tweets = $tweet->getAll();

		
		//print_r($tweets);
	
		$this->view->tweets = $tweets;

		$usuario = Container::getModel('Usuario');

		$usuario->__set('id', $_SESSION['id']);

		$this->getInfoUsuarioPainel($usuario);
		$this->render('timeline', 'layout', true);
		
		
	}

	public function tweet()
	{	

		$this->validaAutenticacao();
		
		$tweet = Container::getModel('Tweet');
		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->__set('id_usuario', $_SESSION['id']);

		$tweet->salvar();

		header('Location: /timeline');
		
	}


	public function validaAutenticacao()
	{
		session_start();

		//Evita que entre na url /tweet /quem_seguir /timeline direto sem a autenticação de login e senha.
		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
			header('Location:/?login=erro');
		}

	}



	public function quemSeguir()
	{
		$this->validaAutenticacao();
		
		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

		//Apenas em caso de bug, retornar um array vazio em casos de pessoas não encontradas
		$usuarios = array();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);
		
		if($pesquisarPor != '')
		{	
			$usuario->__set('nome', $pesquisarPor);
			$usuarios = $usuario->getAll();
		}

		$this->view->usuarios = $usuarios;
		$this->getInfoUsuarioPainel($usuario);
		$this->render('quemSeguir','layout',true);

	}


	public function acao()
	{
		$this->validaAutenticacao();

		//acao
		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		//id_usuario do get passado por parâmetro
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
	
		$usuario = Container::getModel('Seguidor');
		//id_usuario -> é um attr do classe Seguidor, diferente do $_GET['id_usuario'] que vem do parâmetro.
		$usuario->__set('id_usuario', $_SESSION['id']);

		if($acao == 'seguir')
		{
			$usuario->seguirUsuario($id_usuario_seguindo);
			
		}else{
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);
			
		}

		header('Location:/quem_seguir');
	}

	public function deletar()
	{
		$this->validaAutenticacao();


		$id_tweet = $_GET['id_tweet'];
		$id_usuario = $_SESSION['id'];		

		

		$tweet = Container::getModel('Tweet');
		$tweet->__set('id_usuario', $id_usuario);
		$valores = $tweet->getTweet();
		
		

		foreach ($valores as $key => $value) {
			if($value['id'] == $id_tweet){
				$tweet->__set('id', $id_tweet);
				$tweet->excluirTweet();

			}
		}

		header('Location:/timeline');
		

	}

	public function getInfoUsuarioPainel($model)
	{
		$this->view->nome_usuario = $model->getNomeUsuario();
		$this->view->total_tweets = $model->getDadosPainel('total_tweets','tweets');
		$this->view->total_seguindo = $model->getDadosPainel('total_seguindo','usuarios_seguidores');
		$this->view->total_seguidores = $model->getCountSeguidores();	
	}

}

?>