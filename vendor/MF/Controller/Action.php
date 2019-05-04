<?php
	namespace MF\Controller;



	abstract class Action {

		//O motivo da visibilidade protected - é para que ele possa ser herdado.
		protected $view;

		public function __construct(){
			//stdClass - é uma classe padrão e vazia do PHP
			$this->view = new \stdClass();
		}

		protected function render($view, $layoutStyle, $layout=true){
			$this->view->page = $view;

			if($layout == true  && file_exists("../App/Views/".$layoutStyle.".phtml")){
				require_once "../App/Views/".$layoutStyle.".phtml";
			}else{
				$this->content();
			}
			
		}

		protected function content(){
			$classAtual = get_class($this);

			//Primeiro parâmetro - O texto que será substituído
			//Segundo parâmetro - O texto que irá substituir
			//Terceiro parâmetro - De que atribuito é o texto
			$classAtual =  str_replace('App\\Controllers\\', '', $classAtual);

			$classAtual = strtolower(str_replace('Controller', '' , $classAtual));

			//echo $classAtual;

			require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";
		}


	}
?>