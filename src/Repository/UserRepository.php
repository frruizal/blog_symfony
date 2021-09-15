<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;
/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function paginate($sql, $page = 1, $limit = 2)
    {
        $paginator = new Paginator($sql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function getAllPaginado($idCategoria = null, $slugCategoria=null, $page=1, $limit=2){
        $qb = $this->createQueryBuilder('n'); //SELECT n.* FROM noticia n
        
        if(($idCategoria!=null && $idCategoria>0) || ($slugCategoria!=null && $slugCategoria!="")) {
            $qb->innerJoin(Categoria::class, 'c', Join::WITH, 'c.id = n.categoria');
            
            if(($idCategoria!=null && $idCategoria>0)) {
                $qb->andWhere('c.id = :idCategoria')
                ->setParameter('idCategoria', $idCategoria);
            }
            if(($slugCategoria!=null && $slugCategoria!="")) {
                $qb->andWhere('c.slug = :slug')
                ->setParameter('slug', $slugCategoria);
            }
        }

        $query= $qb->getQuery();
        $paginador= $this->paginate($query, $page, $limit);
        $nmaxPages=  ceil($paginador->count()/$limit);

        return ["paginador"=>$paginador, "nmaxPages"=>$nmaxPages, "res" => $query->getResult()];
    }
}
