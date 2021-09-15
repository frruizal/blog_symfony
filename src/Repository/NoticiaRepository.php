<?php

namespace App\Repository;

use App\Entity\Noticia;
use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;
/**
 * @method Noticia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noticia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noticia[]    findAll()
 * @method Noticia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Noticia::class);
    }
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
    // /**
    //  * @return Noticia[] Returns an array of Noticia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Noticia
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
