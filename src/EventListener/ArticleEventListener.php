<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'setCreatedAtEvent', entity: Article::class)]
class ArticleEventListener
{
    public function setCreatedAtEvent(Article $article, PrePersistEventArgs $event): void
    {
        if (null === $article->getCreatedAt()) {
            $article->setCreatedAt(new \DateTimeImmutable());
        }

        $article->setUpdatedAt(new \DateTime());
    }
}
