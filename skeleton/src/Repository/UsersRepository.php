<?php

namespace App\Repository;

use App\Entity\SearchInfluences;
use App\Entity\SearchStyles;
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
                ->setParameter('searchUser', '%' . $searchUser->getSearchUsername() . '%');
        }
        return $query->getQuery();
    }


    /**
     * @param SearchInfluences $searchInfluences
     * @return Query
     */
    public function FindUserByInfluences(SearchInfluences $searchInfluences) : Query
    {
        $query = $this->createQueryBuilder('a');


        if ($searchInfluences->getSearchInfluence())
        {
            $query = $query
                ->Where('a.influences like :searchUser')
                ->setParameter('searchUser', '%' . $searchInfluences->getSearchInfluence() . '%');

        }
        return $query->getQuery();
    }

    /**
     * @param SearchStyles $searchStyles
     * @return Query
     */
    public function FindUserByStyles(SearchStyles $searchStyles) : Query
    {
        $query = $this->createQueryBuilder('a');


        if($searchStyles->getSearchStyle())
        {
            $query = $query
                ->Where('a.styles like :searchUser')
                ->setParameter('searchUser', '%'.$searchStyles->getSearchStyle().'%');
        }
        return $query->getQuery();
    }


        }







