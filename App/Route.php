<?php
	
	namespace App;

	use MF\Init\BootStrap;

	class Route extends Bootstrap{

		protected function initRoutes(){
			$routes['home'] = array('route' => '/', 'controller' => 'IndexController','action' => 'index');

			$routes['inscrevese'] = array('route' => '/inscreverse', 'controller' => 'IndexController', 'action' => 'inscreverse');
			$routes['registrar'] = array('route' => '/registrar', 'controller' => 'IndexController', 'action' => 'registrar');


			$routes['autenticar'] = array('route' => '/autenticar', 'controller' => 'AuthController', 'action' => 'autenticar');
			$routes['sair'] = array('route' => '/sair', 'controller' => 'AuthController', 'action' => 'sair');

			$routes['timeline'] = array('route' => '/timeline', 'controller' => 'AppController', 'action' => 'timeline');


			$routes['tweet'] = array('route'=>'/tweet', 'controller' => 'AppController', 'action' => 'tweet');


			$routes['quem_seguir'] = array('route' => '/quem_seguir', 'controller' => 'AppController', 'action' => 'quemSeguir');

			//Para seguir/deixar de seguir uma pessoa
			$routes['acao'] = array('route' =>  '/acao', 'controller' => 'AppController', 'action' => 'acao');



			$routes['deletar'] = array('route' => '/deletar', 'controller' => 'AppController', 'action' => 'deletar');

			$this->setRoutes($routes);
		}



	}

?>