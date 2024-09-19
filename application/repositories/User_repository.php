<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Credo\Repository;

/**
 * @extends \Rougin\Credo\Repository<\User>
 *
 * @property \CI_DB_query_builder $db
 */
class User_repository extends Repository
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return boolean
     */
    public function exists($data, $id = null)
    {
        // Specify logic here if applicable --------
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $data['email']);

        if ($id)
        {
            $qb = $qb->andWhere('u.id != :id')
                ->setParameter('id', $id);
        }

        /** @var object[] */
        $items = $qb->getQuery()->getResult();
        // -----------------------------------------

        return count($items) > 0;
    }

    /**
     * @param integer $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        $item = $this->find($id);

        if (! $item)
        {
            return false;
        }

        $this->_em->remove($item);

        $this->_em->flush();

        $item = $this->find($id);

        return $item === null;
    }

    /**
     * @param integer|null $limit
     * @param integer|null $offset
     *
     * @return object[]
     */
    public function get($limit = null, $offset = null)
    {
        return $this->findBy(array(), null, $limit, $offset);
    }

    /**
     * @return integer
     */
    public function total()
    {
        return $this->count(array());
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function validate($data)
    {
        return true;
    }
}
