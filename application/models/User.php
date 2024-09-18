<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Credo\Model;
use Rougin\Credo\Traits\PaginateTrait;
use Rougin\Credo\Traits\ValidateTrait;

/**
 * @Entity
 * @Table(name="users")
 */
class User extends Model
{
    use PaginateTrait;
    use ValidateTrait;

    /**
     * @Id @GeneratedValue
     * @Column(name="id", type="integer", length=10, nullable=FALSE, unique=FALSE)
     *
     * @var integer
     */
    protected $_id;

    /**
     * @Column(name="email", type="string", length=100)
     *
     * @var string
     */
    protected $_email;

    /**
     * @Column(name="name", type="string", length=100)
     *
     * @var string
     */
    protected $_name;

    /**
     * @return string
     */
    public function get_email()
    {
        return $this->_email;
    }

    /**
     * @return integer
     */
    public function get_id()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return $this->_name;
    }
}
