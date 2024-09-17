<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Rougin\Wildfire\Model;
use Rougin\Wildfire\Traits\ValidateTrait;

class User extends Model
{
    use ValidateTrait;

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

        // Show if --timestamps is enabled --------
        $input['created_at'] = date('Y-m-d H:i:s');
        // ----------------------------------------

        $this->db->insert($this->table, $data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function exists($data)
    {
        return false;
    }
}
