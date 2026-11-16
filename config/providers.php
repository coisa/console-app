<?php

declare(strict_types=1);

use Console\Container\SandboxServiceProvider;
use FastForward\EventDispatcher\ServiceProvider\EventDispatcherServiceProvider;

// Service providers are composed directly, independently of application settings.
return [SandboxServiceProvider::class, EventDispatcherServiceProvider::class];
