<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 22/03/2018
 * Time: 16:03
 */

namespace AppBundle\Repository;


class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEvent($data){

        $rawSql = 'select * from event where lieu LIKE '.'\'%'.$data['lieu'].'%\''.' AND categorie LIKE '.'\'%'.$data['categorie'].'%\' AND nbPlacesDispo > 0';
        dump($rawSql);
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
        return $stmt->fetchAll();

    }
}