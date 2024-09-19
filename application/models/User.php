<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Credo\Model;

/**
 * @Entity(repositoryClass="User_repository")
 *
 * @Table(name="users")
 */
class User extends Model
{
    /**
     * @Id @GeneratedValue
     *
     * @Column(name="id", type="integer", length=10, nullable=FALSE, unique=FALSE)
     *
     * @var integer
     */
    protected $id;

    /**
     * @Column(name="email", type="string", length=100)
     *
     * @var string
     */
    protected $email;

    /**
     * @Column(name="name", type="string", length=100)
     *
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function get_email()
    {
        return $this->email;
    }

    /**
     * @return integer
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }
}
