<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @return Page[] Returns an array of Page objects
     */
    public function findPagesSansParent()
    {
        return $this->createQueryBuilder('p')
            ->where('p.parent IS NULL')
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Page[] Returns an array of Page objects
     */
    public function findPagesALaUne()
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
     * @return Page[] Returns an array of Page objects
     */
    public function findPagesVieDesProjets()
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
     * @return Page[] Returns an array of Page objects
     */
    public function findPagesAgenda()
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

    public function findLastPages()
    {
        $query= "select * from page order by created_at desc limit 3";
              $stmt = $this->getEntityManager()
              ->getConnection()->prepare($query);
                $stmt->execute();
              return $stmt->fetchAll();
                ;
            }

     }

