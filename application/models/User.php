<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Wildfire\Model;
use Rougin\Wildfire\Traits\ValidateTrait;
use Rougin\Wildfire\Traits\WildfireTrait;

/**
 * @property \CI_DB_query_builder $db
 */
class User extends Model
{
    use ValidateTrait, WildfireTrait;

    /**
     * NOTE: Set to "true" if "--timestamps" is specified.
     * TODO: Add this to Credo, Wildfire.
     *
     * @var boolean
     */
    protected $timestamps = true;

    /**
     * @link https://codeigniter.com/userguide3/libraries/form_validation.html#setting-rules-using-an-array
     *
     * @var array<string, string>[]
     */
    protected $rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
        array('field' => 'email', 'label' => 'Email', 'rules' => 'required'),
    );

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function create($data)
    {
        $input = $this->payload($data);

        if ($this->timestamps)
        {
            $input['created_at'] = date('Y-m-d H:i:s');
        }

        $table = $this->table;

        return $this->db->insert($table, $input);
    }

    /**
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return boolean
     */
    public function exists($data, $id = null)
    {
        // Specify logic here if applicable ------
        $this->db->from($this->table);

        $this->db->where('email', $data['email']);

        if ($id)
        {
            $this->db->where_not_in('id', [$id]);
        }

        $count = $this->db->count_all_results();
        // ---------------------------------------

        return $count > 0;
    }

    /**
     * @param  boolean $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where($this->primary, $id);

        $result = $this->db->delete($this->table);

        return $result ? true : false;
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
        $input = $this->payload($data);

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
    protected function payload($data, $id = null)
    {
        $input = array();

        // List the specified fields from table ---
        $input['name'] = $data['name'];

        $input['email'] = $data['email'];
        // ----------------------------------------

        return $input;
    }
}
