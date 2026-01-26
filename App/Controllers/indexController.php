<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action{

    public function index(){
        $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
        $this->render('index');
    }

    public function inscreverse(){
        $this->view->erroCadastro = false;
        $this->view->usuario = array(
            'nome' => '',
            'email' => '',
            'senha' => ''
        );
        $this->render('inscreverse');
    }

    public function registrar(){

        // receber os dados do formulario

        $usuario = Container::getModel('Usuario');

        $usuario->nome = $_POST['nome'];
        $usuario->email = $_POST['email'];
        $usuario->senha = $_POST['senha'];

        // sucesso

        if(
            $usuario->validarCadastro() &&
            count($usuario->getUsuarioPorEmail()) == 0
        ){

            $usuario->salvar();

            $this->render('cadastro');

        }else{

            $this->view->usuario = array(
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha']
            );

            $this->view->erroCadastro = true;
            $this->render('inscreverse');
        }

        // erro

    }
    
}

?>