<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Credo\Repository;

/**
 * @extends \Rougin\Credo\Repository<\User>
 *
 * @property \CI_DB_query_builder $db
 *
 * @method \User|null find(integer $id)
 */
class User_repository extends Repository
{
    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function create($data)
    {
        $model = new User;

        $model = $this->set($data, $model);

        $this->_em->persist($model);

        $this->_em->flush();
    }

    /**
     * @param \User $model
     *
     * @return void
     */
    public function delete(User $model)
    {
        $this->_em->remove($model);

        $this->_em->flush();
    }

    /**
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return boolean
     */
    public function exists($data, $id = null)
    {
        // Specify logic here if applicable ---
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $data['email']);

        if ($id)
        {
            $qb = $qb->andWhere('u.id != :id')
                ->setParameter('id', $id);
        }

        /** @var \User[] */
        $items = $qb->getQuery()->getResult();
        // ------------------------------------

        return count($items) > 0;
    }

    /**
     * @param integer|null $limit
     * @param integer|null $offset
     *
     * @return \User[]
     */
    public function get($limit = null, $offset = null)
    {
        return $this->findBy(array(), null, $limit, $offset);
    }

    /**
     * @param array<string, mixed> $data
     * @param \User                $model
     * @param integer|null         $id
     *
     * @return \User
     */
    public function set($data, User $model, $id = null)
    {
        // List editable fields from table ---
        /** @var string */
        $name = $data['name'];
        $model->set_name($name);

        /** @var string */
        $email = $data['email'];
        $model->set_email($email);

        if ($id)
        {
            $model->set_updated_at();
        }
        else
        {
            $model->set_created_at();
        }
        // -----------------------------------

        return $model;
    }

    /**
     * @return integer
     */
    public function total()
    {
        return $this->count(array());
    }

    /**
     * @param \User                $model
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function update(\User $model, $data)
    {
        $model = $this->set($data, $model);

        $this->_em->flush();
    }
}
