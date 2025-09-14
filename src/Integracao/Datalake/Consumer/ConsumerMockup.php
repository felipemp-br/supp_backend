<?php

namespace SuppCore\AdministrativoBackend\Integracao\Datalake\Consumer;

use SuppCore\AdministrativoBackend\Integracao\Datalake\ConsumerInterface;

class ConsumerMockup implements ConsumerInterface
{
    public function start(): void
    {
        // Realizar os procedimentos para iniciar o recebimento de dados
    }

    public function chunk(array $chunk): void
    {
        // Processar os dados como desejado
        foreach($chunk as $dado) {
            print($dado);
        }
    }

    public function end(): void
    {
        // Realizar os procedimentos de encerramento do recebimento de dados

    }

    public function error(string $message): void
    {
        // Escrever no log e tal.
    }

    public function getTopico(): string
    {
        // Tópico que desejo 'escutar'
        return 'mockup';
    }
}
