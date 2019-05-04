<?php

namespace App\Controllers;
 
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{
	public function autenticar()
	{	

		$usuario = Container::getModel('Usuario');
		//Transformo o email para caixa baixa, sendo assim, posso verificar se  o email digitado no campo email está em caixa alta ou baixa.
		//Opção utilizada abaixo não é a melhor para lidar com emails de caixa Alta.
		$usuario->__set('email', strtolower($_POST['email']));
		$usuario->__set('senha', md5($_POST['senha']));
		
		$usuario->autenticarEmailSenha();
		
		if($usuario->__get('id') != '' && $usuario->__get('nome') != '' && $usuario->__get('email') == $_POST['email'])
		{
			session_start();

			$_SESSION['id'] = $usuario->__get('id');
			$_SESSION['nome'] = $usuario->__get('nome');

			header('Location: /timeline');
		}else{
			header('Location: /?login=erro');
		}
	}

	public function sair()
	{
		session_start();
		session_destroy();
		header('Location: /');
	}
}

?>