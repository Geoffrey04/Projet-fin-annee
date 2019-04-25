<?php

namespace App\Repository;

use App\Entity\SearchUserInfluences;
use App\Entity\SearchUserStyles;
use App\Entity\SearchUsername;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Users::class);
    }

    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } */

    /**
     * @param SearchUsername $searchUser
     * @return Query
     */
    public function FindUserByName(SearchUsername $searchUser) : Query
    {
        $query = $this->createQueryBuilder('a');


        if ($searchUser->getSearchUsername()) {
            $query = $query
                ->Where('a.username like :searchUser')
                ->setParameter('searchUser','%'.$searchUser->getSearchUsername().'%');
        }
        return $query->getQuery();
    }


    /**
     * @param SearchUserInfluences $searchInfluences
     * @return Query
     */
    public function FindUserByInfluences(SearchUserInfluences $searchInfluences) : Query
    {
        $query = $this->createQueryBuilder('p');


        if ($searchInfluences->getSearchInfluence())
        {
            $query = $query
                ->Where('p.influences like :searchInfluences')
                ->setParameter('searchInfluences','%'.$searchInfluences->getSearchInfluence().'%');

        }
        return $query->getQuery();
    }

    /**
     * @param SearchUserStyles $searchStyles
     * @return Query
     */
    public function FindUserByStyles(SearchUserStyles $searchStyles) : Query
    {
        $query = $this->createQueryBuilder('x');


        if($searchStyles->getSearchStyle())
        {
            $query = $query
                ->Where('x.styles like :searchStyles')
                ->setParameter('searchStyles','%'.$searchStyles->getSearchStyle().'%');
        }
        return $query->getQuery();
    }


        }







