<?php

namespace SuppCore\AdministrativoBackend\Integracao\Datalake\Producer;

use Throwable;
use SuppCore\AdministrativoBackend\Integracao\Datalake\ProducerInterface;

class ProducerMockup implements ProducerInterface
{
    public function getPeriodicidade(): string
    {
        return "10 * * * *";
    }

    public function getTopic(): string
    {
        return "mockup";
    }

    public function run(array $consumers): void
    {
        foreach ($consumers as $consumer) {
            // Verificamos se há dados para enviar aos consumidores
            try {
                // Informamos o início da transferência de dados
                $consumer->start();
                // Iniciamos a transferência de dados
                $consumer->chunk($this->dados);
            } catch (Throwable $e) {
                // Informamos em casso de erro do Produtor
                $consumer->error($e->getMessage());
            } finally {
                // Informamos o fim da transferência de dados
                $consumer->end();
            }

        }
    }

    private array $dados = [
        [
            "name" => "Rylee Blevins",
            "phone" => "1-334-236-5328",
            "email" => "sed.nulla@outlook.edu",
            "country" => "Brazil",
            "address" => "487-2316 Nunc Ave"
        ],
        [
            "name" => "Marcia Carey",
            "phone" => "(901) 878-7854",
            "email" => "morbi.neque@yahoo.net",
            "country" => "Brazil",
            "address" => "P.O. Box 863, 9239 Eget Road"
        ],
        [
            "name" => "Ashely Huber",
            "phone" => "1-647-412-7893",
            "email" => "mollis@google.couk",
            "country" => "Brazil",
            "address" => "Ap #758-4533 Orci. St."
        ],
        [
            "name" => "Aline Bender",
            "phone" => "1-282-444-7152",
            "email" => "sit.amet.metus@protonmail.org",
            "country" => "Brazil",
            "address" => "749 Odio Avenue"
        ],
        [
            "name" => "Elliott Owens",
            "phone" => "(404) 702-7395",
            "email" => "cum@hotmail.org",
            "country" => "Brazil",
            "address" => "5768 Mus. Avenue"
        ],
        [
            "name" => "Clinton Summers",
            "phone" => "1-591-891-5118",
            "email" => "ullamcorper.magna@yahoo.ca",
            "country" => "Brazil",
            "address" => "522-469 Diam. Avenue"
        ],
        [
            "name" => "Gage Baird",
            "phone" => "1-748-273-6156",
            "email" => "semper.cursus@icloud.net",
            "country" => "Brazil",
            "address" => "868-2369 Proin Avenue"
        ],
        [
            "name" => "Clark Kerr",
            "phone" => "(628) 484-5598",
            "email" => "lobortis.ultrices@yahoo.ca",
            "country" => "Brazil",
            "address" => "Ap #607-7095 Vel Road"
        ],
        [
            "name" => "Brenden Hurley",
            "phone" => "(601) 111-7221",
            "email" => "placerat@protonmail.couk",
            "country" => "Brazil",
            "address" => "564-4943 Vivamus Rd."
        ],
        [
            "name" => "Talon Grant",
            "phone" => "(277) 121-5472",
            "email" => "luctus.ipsum.leo@google.ca",
            "country" => "Brazil",
            "address" => "Ap #248-2952 Odio, Street"
        ]
    ];
}
