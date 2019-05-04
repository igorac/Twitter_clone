<?php
	
namespace App\Models;

use MF\Model\Model;

class Seguidor extends Model
{
	private $id;
	private $id_usuario;
	private $id_usuario_seguindo;

	public function __get($attr)
	{
		return $this->$attr;
	}

	public function __set($attr, $value)
	{
		$this->$attr = $value;
	}


	public function seguirUsuario($id_usuario_seguindo)
	{
		$query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES (:id_usuario, :id_usuario_seguindo)";
		$stmt = $this->db->prepare($query);
		//PS.: o __get('id') vem lá do appController na hora de setar o valor do id do model('Usuario').
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();

		//Retorna verdadeiro para inserção
		return true;
	}

	public function deixarSeguirUsuario($id_usuario_seguindo)
	{
		$query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();

		return true;
	}

	

}


?>