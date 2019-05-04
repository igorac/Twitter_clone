<?php
namespace App\Models;

use MF\Model\Model;

class Tweet extends Model
{	

	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

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
		$query = 'INSERT INTO tweets(id_usuario,tweet) VALUES (:id_usuario,:tweet)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(":id_usuario", $this->__get('id_usuario'));
		$stmt->bindValue(":tweet", $this->__get('tweet'));
		$stmt->execute();

		return $this;
	}

	//recuperar
	public function getAll()
	{
		//$query = "SELECT id, id_usuario, tweet, DATE_FORMAT(data, '%d/%m/%Y %H:%i') as data FROM tweets WHERE id_usuario = :id_usuario ORDER BY data DESC";

		$query = "SELECT 
					u.nome, t.id, t.id_usuario, t.tweet, DATE_FORMAT(t.data,'%d/%m/%Y %H:%i') as data 
				  FROM tweets AS t 
				  LEFT JOIN usuarios AS u ON(t.id_usuario = u.id)
			      WHERE(t.id_usuario = :id_usuario) OR t.id_usuario IN (SELECT id_usuario_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario)
			      ORDER BY t.data DESC";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(":id_usuario", $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function excluirTweet()
	{
		$query = 'DELETE FROM tweets WHERE id = :id_tweet';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_tweet', $this->__get('id'));
		$stmt->execute();
	}

	public function getTweet()
	{
		$query = "SELECT id FROM tweets WHERE id_usuario = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}

?>