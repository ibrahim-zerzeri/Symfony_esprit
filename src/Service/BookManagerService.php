<?php

namespace App\Service;

use App\Entity\Author;

class BookManagerService
{
    public function countBooksByAuthor(Author $author): int
    {
        // Si l’auteur a une relation avec Book, on peut compter :
        return count($author->getBooks());
    }

    public function bestAuthors(array $authors): array
    {
        // Retourne les auteurs ayant publié plus de 3 livres
        return array_filter($authors, function (Author $author) {
            return $author->getNbBooks() > 3;
        });
    }
}
