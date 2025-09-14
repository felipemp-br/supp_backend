<?php

declare(strict_types=1);
/**
 * /src/Document/Mapper/TeseIndexMapper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document\Mapper;

use SuppCore\AdministrativoBackend\Document\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Document\EspecieDocumento;
use SuppCore\AdministrativoBackend\Document\EspecieProcesso;
use SuppCore\AdministrativoBackend\Document\EspecieSetor;
use SuppCore\AdministrativoBackend\Document\GeneroDocumento;
use SuppCore\AdministrativoBackend\Document\GeneroProcesso;
use SuppCore\AdministrativoBackend\Document\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Document\ModalidadeUrn;
use SuppCore\AdministrativoBackend\Document\RamoDireito;
use SuppCore\AdministrativoBackend\Document\Tema;
use SuppCore\AdministrativoBackend\Document\Tese;
use SuppCore\AdministrativoBackend\Document\Urn;
use SuppCore\AdministrativoBackend\Document\VinculacaoMetadados;
use SuppCore\AdministrativoBackend\Document\VinculacaoOrgaoCentralMetadados;
use SuppCore\AdministrativoBackend\Entity\Tese as TeseEntity;

/**
 * Class TeseIndexMapper.
 */
class TeseIndexMapper
{
    public function map(TeseEntity $tese): Tese
    {
        $teseDocument = (new Tese())
            ->setId($tese->getId())
            ->setNome($tese->getNome())
            ->setSigla($tese->getSigla())
            ->setEmenta($tese->getEmenta())
            ->setKeywords($tese->getKeywords())
            ->setEnunciado($tese->getEnunciado())
            ->setAtivo($tese->getAtivo())
            ->setCriadoEm($tese->getCriadoEm())
            ->setAtualizadoEm($tese->getAtualizadoEm())
            ->setTema(
                (new Tema())
                    ->setId($tese->getTema()->getId())
                    ->setNome($tese->getTema()->getNome())
                    ->setSigla($tese->getTema()->getSigla())
                    ->setAtivo($tese->getTema()->getAtivo())
                    ->setRamoDireito(
                        (new RamoDireito())
                            ->setId($tese->getTema()->getRamoDireito()->getId())
                            ->setNome($tese->getTema()->getRamoDireito()->getNome())
                            ->setSigla($tese->getTema()->getRamoDireito()->getSigla())
                            ->setAtivo($tese->getTema()->getRamoDireito()->getAtivo())
                    )
            );

        $tese->getVinculacoesMetadados()->map(fn ($vinculacaoMetadado) => $teseDocument->setVinculacoesMetadados(
            (new VinculacaoMetadados())
                ->setId($vinculacaoMetadado->getId())
                ->setIdDispositivo($vinculacaoMetadado->getIdDispositivo())
                ->setTextoDispositivo($vinculacaoMetadado->getTextoDispositivo())
                ->setUrn(
                    (new Urn())
                        ->setId($vinculacaoMetadado->getUrn()->getId())
                        ->setTituloDispositivo($vinculacaoMetadado->getUrn()->getTituloDispositivo())
                        ->setUrn($vinculacaoMetadado->getUrn()->getUrn())
                        ->setAtivo($vinculacaoMetadado->getUrn()->getAtivo())
                        ->setModalidadeUrn(
                            (new ModalidadeUrn())
                                ->setId($vinculacaoMetadado->getUrn()->getModalidadeUrn()->getId())
                                ->setValor($vinculacaoMetadado->getUrn()->getModalidadeUrn()->getValor())
                                ->setDescricao(
                                    $vinculacaoMetadado->getUrn()->getModalidadeUrn()->getDescricao()
                                )
                                ->setAtivo($vinculacaoMetadado->getUrn()->getModalidadeUrn()->getAtivo())
                        )
                )
        ));

        foreach ($tese->getVinculacoesOrgaoCentralMetadados() as $vinculacaoOrgaoCentralMetadados) {
            $vinculacaoOrgaoCentralMetadadosDocument = (new VinculacaoOrgaoCentralMetadados())
                ->setId($vinculacaoOrgaoCentralMetadados->getId())
                ->setModalidadeOrgaoCentral(
                    (new ModalidadeOrgaoCentral())
                        ->setId($vinculacaoOrgaoCentralMetadados->getModalidadeOrgaoCentral()->getId())
                        ->setValor($vinculacaoOrgaoCentralMetadados->getModalidadeOrgaoCentral()->getValor())
                );

            if ($vinculacaoOrgaoCentralMetadados->getAssuntoAdministrativo()) {
                $teseDocument->setVinculacoesOrgaoCentralMetadados(
                    $vinculacaoOrgaoCentralMetadadosDocument
                        ->setAssuntoAdministrativo(
                            (new AssuntoAdministrativo())
                                ->setId(
                                    $vinculacaoOrgaoCentralMetadados->getAssuntoAdministrativo()->getId()
                                )
                                ->setNome(
                                    $vinculacaoOrgaoCentralMetadados->getAssuntoAdministrativo()->getNome()
                                )
                        )
                );
                continue;
            }

            if ($vinculacaoOrgaoCentralMetadados->getEspecieProcesso()) {
                $teseDocument->setVinculacoesOrgaoCentralMetadados(
                    $vinculacaoOrgaoCentralMetadadosDocument
                        ->setEspecieProcesso(
                            (new EspecieProcesso())
                                ->setId($vinculacaoOrgaoCentralMetadados->getEspecieProcesso()->getId())
                                ->setNome($vinculacaoOrgaoCentralMetadados->getEspecieProcesso()->getNome())
                                ->setGeneroProcesso(
                                    (new GeneroProcesso())
                                        ->setId(
                                            $vinculacaoOrgaoCentralMetadados->getEspecieProcesso()
                                                ->getGeneroProcesso()
                                                ->getId()
                                        )
                                        ->setNome(
                                            $vinculacaoOrgaoCentralMetadados->getEspecieProcesso()
                                                ->getGeneroProcesso()
                                                ->getNome()
                                        )
                                )
                        )
                );
                continue;
            }

            if ($vinculacaoOrgaoCentralMetadados->getEspecieDocumento()) {
                $teseDocument->setVinculacoesOrgaoCentralMetadados(
                    $vinculacaoOrgaoCentralMetadadosDocument
                        ->setEspecieDocumento(
                            (new EspecieDocumento())
                                ->setId($vinculacaoOrgaoCentralMetadados->getEspecieDocumento()->getId())
                                ->setNome($vinculacaoOrgaoCentralMetadados->getEspecieDocumento()->getNome())
                                ->setDescricao($vinculacaoOrgaoCentralMetadados->getEspecieDocumento()->getDescricao())
                                ->setSigla($vinculacaoOrgaoCentralMetadados->getEspecieDocumento()->getSigla())
                                ->setAtivo($vinculacaoOrgaoCentralMetadados->getEspecieDocumento()->getAtivo())
                                ->setGeneroDocumento(
                                    (new GeneroDocumento())
                                        ->setId(
                                            $vinculacaoOrgaoCentralMetadados->getEspecieDocumento()
                                                ->getGeneroDocumento()
                                                ->getId()
                                        )
                                        ->setNome(
                                            $vinculacaoOrgaoCentralMetadados->getEspecieDocumento()
                                                ->getGeneroDocumento()
                                                ->getNome()
                                        )
                                        ->setDescricao(
                                            $vinculacaoOrgaoCentralMetadados->getEspecieDocumento()
                                                ->getGeneroDocumento()
                                                ->getDescricao()
                                        )
                                        ->setAtivo(
                                            $vinculacaoOrgaoCentralMetadados->getEspecieDocumento()
                                                ->getGeneroDocumento()
                                                ->getAtivo()
                                        )
                                )
                        )
                );

                continue;
            }

            if ($vinculacaoOrgaoCentralMetadados->getEspecieSetor()) {
                $teseDocument->setVinculacoesOrgaoCentralMetadados(
                    $vinculacaoOrgaoCentralMetadadosDocument
                        ->setEspecieSetor(
                            (new EspecieSetor())
                                ->setId($vinculacaoOrgaoCentralMetadados->getEspecieSetor()->getId())
                                ->setNome($vinculacaoOrgaoCentralMetadados->getEspecieSetor()->getNome())
                        )
                );
            }
        }

        return $teseDocument;
    }
}
