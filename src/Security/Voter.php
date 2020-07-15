<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

abstract class Voter implements VoterInterface
{
    abstract protected function supports($attribute, $subject);

    abstract protected function voteOnAttribute($attribute, $subject, TokenInterface $token);
}
