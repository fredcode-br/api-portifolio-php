<?php

    define ('HOST', 'localhost');
    define ('BANCO', 'portifolio');
    define ('USUARIO', 'root');
    define ('SENHA', '');

    define ('DS', DIRECTORY_SEPARATOR);
    define ('DIR_APP', __DIR__);
    define ('DIR_PROJETO', 'api-portifolio');

    if(file_exists('autoload.php')){
        include 'autoload.php';
    }else{
        echo 'Erro ao incluir bootstrap';
        exit;
    }