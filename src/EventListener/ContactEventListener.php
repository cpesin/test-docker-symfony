<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'setCreatedAtEvent', entity: Contact::class)]
class ContactEventListener
{
    public function setCreatedAtEvent(Contact $contact, PrePersistEventArgs $event): void
    {
        if (null === $contact->getCreatedAt()) {
            $contact->setCreatedAt(new \DateTimeImmutable());
        }
    }
}
