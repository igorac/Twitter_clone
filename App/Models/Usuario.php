<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{

	private $id;
	private $nome;
	private $email;
	private $senha;
	

	public function __get($attr)
	{
		return $this->$attr;
	}

	public function __set($attr, $value)
	{
		$this->$attr = $value;
	}

	//salvar
	public function salvar()
	{
		$query = "INSERT INTO usuarios (nome,email,senha) VALUES (:nome,:email,:senha)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(":nome", $this->__get('nome'));
		$stmt->bindValue(":email", $this->__get('email'));
		$stmt->bindValue(":senha", $this->__get('senha')); //md5 - Criptografar em um hash de 32 caracter
		$stmt->execute();

		return $this;
	}

	//validar se o cadastro pode ser feito
	public function validarCadastro()
	{
		$valido = true;

		if(strlen($this->__get('nome')) < 3 )
		{
			$valido = false;
		}

		if(strlen($this->__get('email')) < 3)
		{
			$valido = false;
		}


		if(strlen($this->__get('senha')) < 3)
		{
			$valido = false;
		}

		return $valido;
	}



	//recuperar um usuário pelo e-mail
	public function getEmails()
	{	

		$query = "SELECT nome, email FROM usuarios WHERE email = :email";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email',$this->__get('email'));
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


	public function autenticarEmailSenha()
	{
		$query = "SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		if($usuario['id'] != '' && $usuario['nome'] != '')
		{
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
		}

		return $this;
	}


	public function getAll()
	{
		//A o sub select feito entre aspas retornara 1 caso a pessoa com o id_usuario tenha seguido a pessoa do id_usuario, e caso ao contrário, retornará 0.
		$query = "
			SELECT 
				u.id, u.nome, u.email, 
				(
					SELECT count(*) FROM usuarios_seguidores as us
					WHERE us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
				) as seguindo_sn
			FROM 
				usuarios as u
			WHERE 
				u.nome like :nome and u.id != :id_usuario";
		

		$stmt = $this->db->prepare($query);
		//Os caracteres % antes e depois do nome é para servir como coringa na pesquisa, devido ao uso do like.
		//Os operadores & - Considera qualquer coisa a esquerda e a direita do nome pesquisado.
		$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


	//Recuperar o nome do usuario
	public function getNomeUsuario()
	{
		$query = "SELECT nome FROM usuarios WHERE id = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		//Uso de apenas fetch, pois é esperado um único registro/array.
		return $stmt->fetch(\PDO::FETCH_ASSOC);

	}


	//Contar tweets
	public function getCountTweets()
	{
		$query = 'SELECT COUNT(*) as total_tweets FROM tweets WHERE id_usuario = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}


	//Recuperar o total de pessoas seguindo
	public function getCountSeguindo()
	{
		$query = "SELECT COUNT(*) AS total_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//Recuperar o total de seguidores
	public function getCountSeguidores()
	{
		$query = "SELECT COUNT(*) AS total_seguidores FROM usuarios_seguidores WHERE id_usuario_seguindo = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}



	//Reaproveita os método getCountSeguindo e GetCountTweets
	public function getDadosPainel($alias, $table)
	{
		$query = 'SELECT COUNT(*) AS '.$alias.' FROM '.$table.' WHERE id_usuario = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}






//Métodos seguirUsuario e DeixarSeguirUsuario foram implementados a parte em um model Seguidor
	public function seguirUsuario($id_usuario_seguindo)
	{
		$query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES (:id_usuario, :id_usuario_seguindo)";
		$stmt = $this->db->prepare($query);
		//PS.: o __get('id') vem lá do appController na hora de setar o valor do id do model('Usuario').
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();

		//Retorna verdadeiro para inserção
		return true;
	}

	public function deixarSeguirUsuario($id_usuario_seguindo)
	{
		$query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();

		return true;
	}


}

?>