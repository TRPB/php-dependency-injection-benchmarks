<?php

use DI\Scope;

return [

    'A' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'B' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'C' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'D' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'E' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'F' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'G' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'H' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'I' => \DI\object()
        ->scope(Scope::PROTOTYPE()),
    'J' => \DI\object()
        ->scope(Scope::PROTOTYPE()),

];