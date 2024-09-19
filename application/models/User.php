<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Credo\Model;
use Rougin\Credo\Traits\PaginateTrait;
use Rougin\Credo\Traits\ValidateTrait;

/**
 * @Entity(repositoryClass="User_repository")
 *
 * @Table(name="users")
 */
class User extends Model
{
    use PaginateTrait;
    use ValidateTrait;

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
     * @Column(name="created_at", type="string", length=19)
     *
     * @var string
     */
    protected $created_at;

    /**
     * @Column(name="updated_at", type="string", length=19, nullable=true)
     *
     * @var string
     */
    protected $updated_at;

    /**
     * Additional configuration to Pagination Class.
     *
     * @link https://codeigniter.com/userguide3/libraries/pagination.html?highlight=pagination#customizing-the-pagination
     *
     * @var array<string, mixed>
     */
    protected $pagee = array(
        'page_query_string' => true,
        'use_page_numbers' => true,
        'query_string_segment' => 'p',
        'reuse_query_string' => true,
    );

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form Validation library.
     *
     * @link https://codeigniter.com/userguide3/libraries/form_validation.html#setting-rules-using-an-array
     *
     * @var array<string, string>[]
     */
    protected $rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
        array('field' => 'email', 'label' => 'Email', 'rules' => 'required'),
    );

    /**
     * @return string
     */
    public function get_created_at()
    {
        return $this->created_at;
    }

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

    /**
     * @return string
     */
    public function get_updated_at()
    {
        return $this->updated_at;
    }

    /**
     * @return self
     */
    public function set_created_at()
    {
        $this->created_at = date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function set_email($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function set_name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return self
     */
    public function set_updated_at()
    {
        $this->updated_at = date('Y-m-d H:i:s');

        return $this;
    }
}
