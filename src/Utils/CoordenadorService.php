<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use Doctrine\ORM\PersistentCollection;
use SuppCore\AdministrativoBackend\Entity\Setor;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Coordenador.
 *
 * @author Lucas Campelo <lucas.campelo@agu.gov.br>
 */
class CoordenadorService
{
    private array|PersistentCollection|null $coordenadores = null;

    private ?string $origen = null;

    /**
     * Coordenador constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RequestStack $requestStack
     */
    public function __construct(
        protected TokenStorageInterface $tokenStorage,
        protected AuthorizationCheckerInterface $authorizationChecker,
        private readonly RequestStack $requestStack
    ) {
        $this->coordenadores = $this->tokenStorage->getToken()?->getUser()?->getCoordenadores();
        $this->origen = $this->getRouteOrigin();
    }

    /**
     * Verifica se o usuário autenticado é `Coordenador` de um dos setores recebidos.
     *
     * @param Setor[] $setores
     *
     * @return bool
     */
    public function verificaUsuarioCoordenadorSetor(
        array $setores
    ): bool {
        $isCoordenador = false;


        foreach ($setores as $setor) {
            if ($setor->getId()) {
                $roleCoordenador = sprintf('ROLE_COORDENADOR_SETOR_%s', $setor->getId());
                $isCoordenador |= $this->authorizationChecker->isGranted($roleCoordenador);
            }
        }

        if ($this->origen && $this->coordenadores && $isCoordenador) {
            $menusAllowed = $this->extractMenuCoordenador($this->coordenadores, 'setor');
            $isCoordenador = in_array($this->origen, $menusAllowed);
        }

        return (bool)$isCoordenador;
    }

    /**
     * Verifica se o usuário autenticado é `CoordenadorUnidade` de um dos setores recebidos.
     *
     * @param Setor[] $unidades
     *
     * @return bool
     */
    public function verificaUsuarioCoordenadorUnidade(
        array $unidades
    ): bool {
        $isCoordenador = false;

        foreach ($unidades as $unidade) {
            if ($unidade->getId()) {
                $roleCoordenador = sprintf('ROLE_COORDENADOR_UNIDADE_%s', $unidade->getId());
                $isCoordenador |= $this->authorizationChecker->isGranted($roleCoordenador);
            }
        }

        if ($this->origen && $this->coordenadores && $isCoordenador) {
            $menusAllowed = $this->extractMenuCoordenador($this->coordenadores, 'unidade');
            $isCoordenador = in_array($this->origen, $menusAllowed);
        }


        return (bool)$isCoordenador;
    }

    /**
     * Verifica se o usuário autenticado é `CoordenadorOrgaoCentral` de uma das unidades recebidas.
     *
     * @param Setor[] $orgaosCentrais
     *
     * @return bool
     */
    public function verificaUsuarioCoordenadorOrgaoCentral(
        array $orgaosCentrais,
    ): bool {
        $isCoordenador = false;


        foreach ($orgaosCentrais as $orgaoCentral) {
            if ($orgaoCentral->getId()) {
                $roleCoordenador = sprintf(
                    'ROLE_COORDENADOR_ORGAO_CENTRAL_%s',
                    $orgaoCentral->getId()
                );
                $isCoordenador |= $this->authorizationChecker->isGranted($roleCoordenador);
            }
        }

        if ($this->origen && $this->coordenadores && $isCoordenador) {
            $menusAllowed = $this->extractMenuCoordenador($this->coordenadores, 'orgaoCentral');
            $isCoordenador = in_array($this->origen, $menusAllowed);
        }

        return (bool)$isCoordenador;
    }

    /**
     * @param array|PersistentCollection|null $coordenadores
     * @param string $tipoVerificacao
     * @return array
     */
    private function extractMenuCoordenador(
        array|PersistentCollection|null $coordenadores,
        string $tipoVerificacao
    ): array {
        $extractedData = [];

        foreach ($coordenadores as $coordenador) {
            $vinculacaoMenu = $coordenador->getVinculacaoMenuCoordenador();

            // Verifica de acordo com o tipo de verificação
            switch ($tipoVerificacao) {
                case 'orgaoCentral':
                    if ($coordenador->getOrgaoCentral() !== null && $coordenador->getUnidade(
                    ) === null && $coordenador->getSetor() === null) {
                        $extractedData = $this->extrairVinculacoes($vinculacaoMenu);
                    }
                    break;

                case 'unidade':
                    if ($coordenador->getUnidade() !== null && $coordenador->getOrgaoCentral(
                    ) === null && $coordenador->getSetor() === null) {
                        $extractedData = $this->extrairVinculacoes($vinculacaoMenu);
                    }
                    break;

                case 'setor':
                    if ($coordenador->getSetor() !== null && $coordenador->getOrgaoCentral(
                    ) === null && $coordenador->getUnidade() === null) {
                        $extractedData = $this->extrairVinculacoes($vinculacaoMenu);
                    }
                    break;

                default:
                    break;
            }
        }

        return $extractedData;
    }

    /**
     * @param $vinculacaoMenu
     * @return array
     */
    private function extrairVinculacoes($vinculacaoMenu): array
    {
        if ($vinculacaoMenu) {
            return array_filter([
                'modelos' => $vinculacaoMenu->getModelos() ? 'modelos' : null,
                'repositorios' => $vinculacaoMenu->getRepositorios() ? 'repositorios' : null,
                'etiquetas' => $vinculacaoMenu->getEtiquetas() ? 'etiquetas' : null,
                'usuarios' => $vinculacaoMenu->getUsuarios() ? 'usuarios' : null,
                'unidades' => $vinculacaoMenu->getUnidades() ? 'unidades' : null,
                'avisos' => $vinculacaoMenu->getAvisos() ? 'avisos' : null,
                'teses' => $vinculacaoMenu->getTeses() ? 'teses' : null,
                'coordenadores' => $vinculacaoMenu->getCoordenadores() ? 'coordenadores' : null,
                'setores' => $vinculacaoMenu->getSetores() ? 'setores' : null,
                'contasEmails' => $vinculacaoMenu->getContasEmails() ? 'contasEmails' : null,
                'competencias' => $vinculacaoMenu->getCompetencias() ? 'competencias' : null,
                'dominios' => $vinculacaoMenu->getDominios() ? 'dominios' : null,
                'gerenciamentoTarefas' => $vinculacaoMenu->getGerenciamentoTarefas() ? 'gerenciamentoTarefas' : null,
            ]);
        }

        return [];
    }

    public function getCurrentRoute(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        return $request->attributes->get('_route');
    }

    public function getRouteOrigin(): ?string
    {
        $route = $this->getCurrentRoute();

        if (!$route) {
            return null;
        }

        // Define padrões para verificar a origem
        $patterns = [
            'modelo' => 'modelos',
            'repositorio' => 'repositorios',
            'etiqueta' => 'etiquetas',
            'usuario' => 'usuarios',
            'unidade' => 'unidades',
            'aviso' => 'avisos',
            'tese' => 'teses',
            'coordenador' => 'coordenadores',
            'setor' => 'setores',
            'contaEmail' => 'contasEmails',
            'competencia' => 'competencias',
            'dominio' => 'dominios',
            'atividade' => 'gerenciamentoTarefas',
            'afastamento' => 'gerenciamentoTarefas',
            'compartilhamento' => 'gerenciamentoTarefas',
            'componentedigital' => 'gerenciamentoTarefas',
            'documento_avulso' => 'gerenciamentoTarefas',
            'lembrete' => 'gerenciamentoTarefas',
            'localizador' => 'gerenciamentoTarefas',
            'lotacao' => 'gerenciamentoTarefas',
            'tarefa' => 'gerenciamentoTarefas',
        ];

        // Verifica se a rota contém algum dos padrões
        foreach ($patterns as $key => $value) {
            if (strpos($route, $key) !== false) {
                return $value;
            }
        }

        return null;
    }
}
