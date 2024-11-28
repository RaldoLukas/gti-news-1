<?php

    echo "Executando composer install...\n";
    passthru('composer install --ansi', $return_var);
    $return_var = 0;


    if ($return_var === 0) {
        echo "Composer install concluído com sucesso.\n";
        passthru('php artisan inicio-aula --ansi');
    } else {
        echo "Erro ao executar composer install.\n";
    }

?>


,