<?php
	
	namespace App\Controllers;

	//Os recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

	use App\Models\Usuario;


	class IndexController extends Action {

		public function index()
		{
			$this->render('index','layout',true);
		}

		public function inscreverse()
		{
			//Controle de mensagem na view
			$this->view->erroCadastro = false;

			//Caso não tenha nenhum dado, retorne vazio
			$this->view->usuario = array(
					'nome' => '',
					'email' => '',
					'senha' => ''

				);
			
			$this->render('inscreverse','layout',true);
		}

		public function registrar()
		{

			//receber os dados do usuarios
			$usuario = Container::getModel('Usuario');
			$usuario->__set('nome', $_POST['nome']);
			$usuario->__set('email', $_POST['email']);
			$usuario->__set('senha', md5($_POST['senha']));


			//countGetEmails - retorna a qtd de email, caso seja 0, quer dizer que não existe essa pessoa no DB ainda.
			if($usuario->validarCadastro() && count($usuario->getEmails()) == 0)
			{
				//sucesso
				//$usuario->salvar();
				$this->render('cadastro','layout',true);

			}else{
				
				//Erro
				$this->view->usuario = array(
					'nome' => $_POST['nome'],
					'email' => $_POST['email'],
					'senha' => $_POST['senha']

				);

				//Controle de mensagem na view
				$this->view->erroCadastro = true;
				header("location: /inscreverse");
			}	
		}

		
	}
?>