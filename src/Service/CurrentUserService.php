<?php

namespace App\Service;



use App\Entity\User2;
use Symfony\Component\Security\Core\Security;

class CurrentUserService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getCurrentUser(): ?User2
    {
        return $this->security->getUser();
    }
}
