<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 */

declare(strict_types=1);

use Console\Command;

return [
    'console:index [text] [-y|--yell]' => Command\IndexCommand::class,
];
