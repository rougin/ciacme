<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Rougin\SparkPlug\Controller;
use Rougin\Wildfire\Wildfire;

class Users extends Controller
{
    /**
     * @var string
     */
    private $table = 'users';

    /**
     * @var \Rougin\Wildfire\Wildfire
     */
    private $query;

    public function __construct()
    {
        parent::__construct();

        // Initialize the Wildfire instance ---
        $this->load->helper('inflector');

        $this->load->database();

        $wildfire = new Wildfire($this->db);

        $this->query = $wildfire;
        // ------------------------------------

        // Show if --with-view enabled ---
        $this->load->helper('url');
        // -------------------------------

        // Load multiple models if required ---
        $this->load->model('user');
        // ------------------------------------
    }

    /**
     * @return void
     */
    public function index()
    {
        $query = $this->query->get($this->table);

        $data = array('items' => $query->result());

        $data['table'] = $this->table;

        // Show if --with-view enabled ---------
        $this->load->view('users/index', $data);
        // -------------------------------------
    }
}
