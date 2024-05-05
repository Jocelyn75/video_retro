<?php

namespace App\Service;

class CommandeRefGenerator
{
    private $codeManager;

    public function __construct(CodeManager $codeManager)
    {
        $this->codeManager = $codeManager;
    }

    public function generateReference(): string
    {
        // Générer une référence de commande en concaténant la date et le code aléatoire
        $date = new \DateTime();
        $code = $this->codeManager->getCode();
        return $date->format('YmdHis') . '-' . $code;
    }
}