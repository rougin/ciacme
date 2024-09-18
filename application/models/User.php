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
        // Should be defined manually ------------
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

        $id = array('id' => $id);

        $table = $this->table;

        return $this->db->update($table, $input, $id);
    }

    /**
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return array<string, mixed>
     */
    protected function payload($data, $id = null)
    {
        $load = array();

        // List the specified fields from table ---
        $load['name'] = $data['name'];

        $load['email'] = $data['email'];
        // ----------------------------------------

        return $load;
    }
}
