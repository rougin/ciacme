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
     * Checks if the specified data exists in the database.
     *
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
     * Updates the payload to be passed to the entity.
     *
     * @param array<string, mixed> $data
     * @param \User                $entity
     * @param integer|null         $id
     *
     * @return \User
     */
    public function set($data, $entity, $id = null)
    {
        // List editable fields from table ---
        /** @var string */
        $name = $data['name'];
        $entity->set_name($name);

        /** @var string */
        $email = $data['email'];
        $entity->set_email($email);

        if ($id)
        {
            $entity->set_updated_at();
        }
        else
        {
            $entity->set_created_at();
        }
        // -----------------------------------

        return $entity;
    }
}
