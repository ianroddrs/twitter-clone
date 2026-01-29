<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    // salvar
    public function salvar(){
        
        $query = "INSERT INTO usuarios(nome, email, senha) VALUES(?,?,?)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(1, $this->__get('nome'));
        $stmt->bindValue(2, $this->__get('email'));
        $stmt->bindValue(3, $this->__get('senha'));

        $stmt->execute();
        return $this;
    }

    //validar se um usuário pode ser feito
    public function validarCadastro(){
        $valido = true;

        if(
            strlen($this->__get('nome')) < 3 or
            strlen($this->__get('email')) < 3 or
            strlen($this->__get('senha')) < 3
        ){
            $valido = false;
        }

        return $valido;
    }

    public function getUsuarioPorEmail(){
        $query = "SELECT nome, email FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(1, $this->__get('email'));

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function autenticar(){
        $query = "SELECT  id, nome, email FROM usuarios WHERE email = ? AND senha = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('email'));
        $stmt->bindValue(2, $this->__get('senha'));

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!empty($usuario['id']) && !empty($usuario['nome'])){
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
        }

        return $this;
    }

    public function getALL(){
        $query = "
            SELECT u.id, u.nome, u.email, (
                SELECT COUNT(*)
                FROM usuarios_seguidores AS us
                WHERE us.id_usuario = :id_usuario AND us.id_usuario_seguindo = u.id
            ) as seguindo_sn
            FROM usuarios AS u WHERE nome LIKE :nome AND id != :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo){
        $query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES(?,?)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->bindValue(2, $id_usuario_seguindo);

        $stmt->execute();
        return true;
    }
    
    public function DeixarSeguirUsuario($id_usuario_seguindo){
        $query = "DELETE FROM usuarios_seguidores WHERE id_usuario = ? AND id_usuario_seguindo = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->bindValue(2, $id_usuario_seguindo);

        $stmt->execute();
        return true;
    }

}

?>