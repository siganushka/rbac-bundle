<?php

namespace Siganushka\RBACBundle\Security\Voter;

use Siganushka\RBACBundle\Node\NodeCollection;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\Role;

class NodeVoter implements VoterInterface
{
    private $authenticationTrustResolver;
    private $nodeCollection;

    public function __construct(AuthenticationTrustResolverInterface $authenticationTrustResolver, NodeCollection $nodeCollection)
    {
        $this->authenticationTrustResolver = $authenticationTrustResolver;
        $this->nodeCollection = $nodeCollection;
    }

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        foreach ($attributes as $attribute) {
            if (null === $node = $this->nodeCollection->get($attribute)) {
                continue;
            }

            if ($node['is_authenticated_fully']
                && $this->authenticationTrustResolver->isFullFledged($token)) {
                return VoterInterface::ACCESS_GRANTED;
            }

            if ($node['is_authenticated_remembered']
                && ($this->authenticationTrustResolver->isRememberMe($token)
                    || $this->authenticationTrustResolver->isFullFledged($token))) {
                return VoterInterface::ACCESS_GRANTED;
            }

            if ($node['is_authenticated_anonymously']
                && ($this->authenticationTrustResolver->isAnonymous($token)
                    || $this->authenticationTrustResolver->isRememberMe($token)
                    || $this->authenticationTrustResolver->isFullFledged($token))) {
                return VoterInterface::ACCESS_GRANTED;
            }

            // in Symfony 4.3.x
            $roles = method_exists($token, 'getRoleNames')
                ? $token->getRoleNames()
                : array_map(function (Role $role) { return $role->getRole(); }, $token->getRoles(false));

            $result = VoterInterface::ACCESS_DENIED;
            foreach ($roles as $role) {
                if ($attribute === $role) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        return $result;
    }
}
