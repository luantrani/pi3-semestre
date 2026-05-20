<?php
spl_autoload_register(function ($classe) {

    $pastas = ['controller', 'model', 'dao'];

    foreach ($pastas as $pasta) {

        $arquivo = __DIR__ . DIRECTORY_SEPARATOR . $pasta . DIRECTORY_SEPARATOR . $classe . '.php';

        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }
});