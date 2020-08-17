<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArticlesSansParent()
    {
        return $this->createQueryBuilder('p')
            ->where('p.parent IS NULL')
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArticlesALaUne()
    {
        return $this->createQueryBuilder('p')
            ->join('p.parent', 'parent')
            ->join('parent.categorie', 'c')
            ->where('c.titre = :aLaUne')->setParameter('aLaUne', Categorie::A_LA_UNE)
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArticlesVieDesProjets()
    {
        return $this->createQueryBuilder('p')
            ->join('p.parent', 'parent')
            ->join('parent.categorie', 'c')
            ->where('c.titre = :vieDesProjets')->setParameter('vieDesProjets', Categorie::VIE_DES_PROJETS)
            ->andWhere('p.visible <> :visible')->setParameter('visible', 'false')
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArticlesAgenda()
    {
        return $this->createQueryBuilder('p')
            ->join('p.parent', 'parent')
            ->join('parent.categorie', 'c')
            ->where('c.titre = :agenda')->setParameter('agenda', Categorie::AGENDA)
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLastArticles()
    {
        $query= "select * from Article order by created_at desc limit 3";
              $stmt = $this->getEntityManager()
              ->getConnection()->prepare($query);
                $stmt->execute();
              return $stmt->fetchAll();
                ;
            }

     }

