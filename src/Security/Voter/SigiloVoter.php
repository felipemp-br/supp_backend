<?php

declare(strict_types=1);
/**
 * /src/Security/Voter/SigiloVoter.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Voter;

use DateTime;
use function in_array;
use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Sigilo;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RolesServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SigiloVoter.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SigiloVoter implements VoterInterface
{
    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return true;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class instanceof Processo || $class instanceof Documento;
    }

    protected RolesServiceInterface $rolesService;

    /**
     * @param RolesServiceInterface $rolesService
     */
    public function __construct(RolesServiceInterface $rolesService)
    {
        $this->rolesService = $rolesService;
    }

    /**
     * Checks whether or not the current user can edit a comment.
     *
     * Users with the role ROLE_COMMENT_MODERATOR may always edit.
     * A comment's author can only edit within 5 minutes of it being posted.
     *
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes): int
    {
        if (!($object instanceof Processo) && !($object instanceof Documento)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        /** @var Usuario|ApiKey $user */
        $user = $token->getUser();

        if (!($user instanceof UserInterface)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $dateTime = new DateTime();

        $roles = $this->rolesService->getUserRolesNames($user);

        /** @var Sigilo $sigilo */
        foreach ($object->getSigilos() as $sigilo) {
            if (!$sigilo->getDesclassificado() &&
                ($sigilo->getDataHoraValidadeSigilo() > $dateTime) &&
                $sigilo->getTipoSigilo()->getLeiAcessoInformacao() &&
                (0 === $user->getNivelAcesso())
            ) {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_GRANTED;
    }
}
