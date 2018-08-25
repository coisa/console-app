<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 */

declare(strict_types=1);

use Console\Command;

return function (Scheduler $scheduler) {
    $scheduler
        ->call(function () {
            return true;
        })
        ->at('* * * * *');
};
