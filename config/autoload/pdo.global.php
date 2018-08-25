<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

use Console\Container\Factory\PdoFactory;

return [
    'dependencies' => [
        'factories' => [
            \PDO::class => PdoFactory::class
        ]
    ]
];
