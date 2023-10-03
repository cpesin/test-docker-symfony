<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'setCreateUserEvent', entity: User::class)]
class UserEventListener
{
    public function setCreateUserEvent(User $user, PrePersistEventArgs $event): void
    {
        if (null === $user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTimeImmutable());
        }
    }
}
