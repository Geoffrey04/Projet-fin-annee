<?php

namespace App\Repository;

use App\Entity\Parts;

use App\Entity\SearchPartsAuthor;
use App\Entity\SearchPartsGroup;
use App\Entity\SearchPartsStyles;
use App\Entity\SearchPartsTitle;
use App\Entity\SearchPartsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Parts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parts[]    findAll()
 * @method Parts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Parts::class);
    }

    // /**
    //  * @return Parts[] Returns an array of Parts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Parts
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param SearchPartsTitle $searchPartsTitle
     * @return Query
     */
    public function findPartsByTitle(SearchPartsTitle $searchPartsTitle) : Query
    {
        $query = $this->createQueryBuilder('p');


        if($searchPartsTitle->getSearchTitle())
        {
            $query = $query
                ->Where('p.title like  :searchTitle')
                ->setParameter('searchTitle','%' . $searchPartsTitle->getSearchTitle().'%');
        }
        return $query->getQuery();
    }

    public function findPartsByGroup(SearchPartsGroup $searchPartsGroup) : Query
    {
        $query = $this->createQueryBuilder('p');

        if($searchPartsGroup->getSearchGroup())
        {
            $query = $query
                ->where('p.Groupe like :searchGroup')
                ->setParameter('searchGroup', '%'. $searchPartsGroup->getSearchGroup().'%');
        }
        return $query->getQuery();
    }

    public function findPartsByAuthor(SearchPartsAuthor $searchPartsAuthor) :Query
    {
        $query = $this->createQueryBuilder('p');

        if($searchPartsAuthor->getSearchAuthor())
        {
            $query = $query
                ->where('IDENTITY(p.author) = :searchAuthor')
                ->setParameter('searchAuthor', $searchPartsAuthor->getSearchAuthor());
        }
        return $query->getQuery();
    }

    public function findPartsByStyles(SearchPartsStyles $searchPartsStyles) : Query
    {
        $query = $this->createQueryBuilder('p');

        if($searchPartsStyles->getSearchStyles())
        {
            $query = $query
                ->where('p.styles like :searchStyles')
                ->setParameter('searchStyles','%'. $searchPartsStyles->getSearchStyles(). '%');
        }
        return $query->getQuery();
    }

    public function findPartsByType(SearchPartsType $searchPartsType ) : Query
    {
        $query = $this->createQueryBuilder('p');

        if($searchPartsType->getSearchType())
        {
            $query = $query
                ->where('p.type  like :searchType' )
                ->setParameter('searchType', $searchPartsType->getSearchType());

        }
        return $query->getQuery();
    }
}
