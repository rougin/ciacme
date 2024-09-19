<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Wildfire\Model;
use Rougin\Wildfire\Traits\PaginateTrait;
use Rougin\Wildfire\Traits\ValidateTrait;
use Rougin\Wildfire\Traits\WildfireTrait;

/**
 * @property \CI_DB_query_builder $db
 */
class User extends Model
{
    use PaginateTrait;
    use ValidateTrait;
    use WildfireTrait;

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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Allows updating of timestamp fields ("created_at", "updated_at").
     *
     * @var boolean
     */
    protected $timestamps = true;

    /**
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function create($data)
    {
        $input = $this->input($data);

        if ($this->timestamps)
        {
            $input['created_at'] = date('Y-m-d H:i:s');
        }

        $table = $this->table;

        return $this->db->insert($table, $input);
    }

    /**
     * @param integer $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where($this->primary, $id);

        $result = $this->db->delete($this->table);

        return $result ? true : false;
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
     * @return integer
     */
    public function total()
    {
        return $this->db->from($this->table)->count_all_results();
    }

    /**
     * @param integer              $id
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function update($id, $data)
    {
        $input = $this->input($data, $id);

        if ($this->timestamps)
        {
            $input['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where($this->primary, $id);

        return $this->db->update($this->table, $input);
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
