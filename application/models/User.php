<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Wildfire\Model;
use Rougin\Wildfire\Traits\PaginateTrait;
use Rougin\Wildfire\Traits\ValidateTrait;
use Rougin\Wildfire\Traits\WildfireTrait;
use Rougin\Wildfire\Traits\WritableTrait;

/**
 * @property \CI_DB_query_builder $db
 */
class User extends Model
{
    use PaginateTrait;
    use ValidateTrait;
    use WildfireTrait;
    use WritableTrait;

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
     * List of validation rules.
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
     * The table associated with the model.
     *
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
        // Specify logic here if applicable ---
        $this->db->from($this->table);

        $this->db->where('email', $data['email']);

        if ($id)
        {
            $this->db->where_not_in('id', [$id]);
        }

        $count = $this->db->count_all_results();
        // ------------------------------------

        return $count > 0;
    }

    /**
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return array<string, mixed>
     */
    protected function input($data, $id = null)
    {
        $input = array();

        // List editable fields from table ---
        $input['name'] = $data['name'];

        $input['email'] = $data['email'];
        // -----------------------------------

        return $input;
    }
}
