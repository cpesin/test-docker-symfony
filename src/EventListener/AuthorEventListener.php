<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'setCreatedAtEvent', entity: Author::class)]
class AuthorEventListener
{
    public function setCreatedAtEvent(Author $author, PrePersistEventArgs $event): void
    {
        if (null === $author->getCreatedAt()) {
            $author->setCreatedAt(new \DateTimeImmutable());
        }
    }
}
