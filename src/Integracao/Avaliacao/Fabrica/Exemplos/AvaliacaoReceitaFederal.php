<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Avaliacao\Fabrica\Exemplos;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EnderecoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Integracao\Avaliacao\AvaliacaoInterface;
use SuppCore\AdministrativoBackend\Integracao\Avaliacao\AvaliacaoManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AvaliacaoReceitaFederal implements AvaliacaoInterface
{
    public function __construct(
        private AvaliacaoManager $avaliacaoManager,
        private EnderecoResource $enderecoResouce,
        private PessoaResource $pessoaResouce
    ) {
    }

    public function supports(string $class): array
    {
        return [
            'SuppCore\AdministrativoBackEnd\Entity\Endereco',
            'SuppCore\AdministrativoBackEnd\Entity\Pessoa',
        ];
    }

    public function getOrder(string $class): int
    {
        return 1;
    }

    public function getValorInicial(EntityInterface $objetoAvaliado, array $fabricasVisitadas): float
    {
        $origemDados = self::getOrigemDados($objetoAvaliado);

        // Toda fabrica que for utilizar recursão deve passar o parâmetro fabricasvisitasdas para os métodos
        // getAvaliacao(), getValorInicial() e getValorAvaliacaoResultante().
        // Se não passar as fábricas excluídas anterioremente retornam para a lista de fabricas a serem visitadas.
        return 'RECEITA_FEDERAL' == $origemDados?->getFonteDados() ?
            90 :
            $this
                ->avaliacaoManager
                ->getAvaliacao($objetoAvaliado->getClasse(), [...$fabricasVisitadas, $this::class])
                ->getValorInicial($objetoAvaliado, [...$fabricasVisitadas, $this::class]);
    }

    public function getValorAvaliacaoResultante(EntityInterface $objetoAvaliado, Avaliacao $avaliacao, array $fabricasVisitadas): float
    {
        // define a nova avaliacao do objeto
        return (
                ($objetoAvaliado->getAvaliacaoResultante() * $objetoAvaliado->getQuantidadeAvaliacoes()
                ) + $avaliacao->getAvaliacao())
            / ($objetoAvaliado->getQuantidadeAvaliacoes() + 1);
    }

    public function getOrigemDados(EntityInterface $objetoAvaliado): null|OrigemDados
    {
        try {
            $origemDados = match ($objetoAvaliado->getClasse()) {
                'SuppCore\AdministrativoBackEnd\Entity\Endereco' => $this->enderecoResouce->findOne(
                    $objetoAvaliado->getObjetoId()
                )?->getOrigemDados(),
                'SuppCore\AdministrativoBackEnd\Entity\Pessoa' => $this->pessoaResouce->findOne(
                    $objetoAvaliado->getObjetoId()
                )?->getOrigemDados(),
                default => null
            };
        } catch (NotFoundHttpException $e) {
            $origemDados = null;
        }

        return $origemDados;
    }
}
