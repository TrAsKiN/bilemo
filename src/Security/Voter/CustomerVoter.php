<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerVoter extends Voter
{
    public const IS_MINE = 'CUSTOMER_IS_MINE';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute == self::IS_MINE && $subject instanceof Customer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($attribute == self::IS_MINE) {
            return $subject->getOwner() == $user;
        }

        return false;
    }
}
