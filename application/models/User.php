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
        $input = array();

        // List the specified fields from table ---
        $input['name'] = $data['name'];

        $input['email'] = $data['email'];
        // ----------------------------------------

        if ($this->timestamps)
        {
            $input['created_at'] = date('Y-m-d H:i:s');
        }

        $table = $this->table;

        return $this->db->insert($table, $data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function exists($data)
    {
        // Should be defined manually ------------
        $this->db->from($this->table);

        $this->db->where('email', $data['email']);

        $count = $this->db->count_all_results();
        // ---------------------------------------

        return $count > 0;
    }

    /**
     * @param array<string, mixed> $input
     *
     * @return boolean
     */
    public function is_valid($input)
    {
        return $this->validate($input);
    }
}
