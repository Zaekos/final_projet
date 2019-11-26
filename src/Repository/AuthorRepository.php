<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function getByBiography($word)
    {
        // - Récupérer le query builder (car c'est le query builder qui permet de faire la requete SQL)
        // Le createQueryBuilder permet de créer des requêtes SQL
        $queryBuilder = $this->createQueryBuilder('a');

        // - Construire la requete façon SQL, mais en PHP
        // - Traduire la requête en véritable requête SQL
        $query = $queryBuilder->select('a')
            ->where('a.biography LIKE :word')
            ->setParameter('word', '%' . $word . '%')
            ->getQuery();
        // - Executer la requête SQL en base de données pour récupérer les bons livres
        $authors = $query->getResult();

        return $authors;

    }
}
