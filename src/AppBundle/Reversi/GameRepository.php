<?php

namespace AppBundle\Reversi;

use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function findLastActiveGameByPlayerOriginAndToken($origin, $token)
    {
        $dql = '
          SELECT g FROM App:Game g
          LEFT JOIN g.firstPlayer fp
          LEFT JOIN g.secondPlayer sp
          WHERE g.isFinished = false
          AND (
            (fp.token = :token AND fp.origin = :origin)
            OR
            (sp.token = :token AND sp.origin = :origin)
          )
          ORDER BY g.startAt DESC
        ';

        $query = $this->_em->createQuery($dql);
        $query->setParameters(['origin' => $origin, 'token' => $token]);
        $query->setMaxResults(1);

        return $query->getOneOrNullResult();
    }
}
