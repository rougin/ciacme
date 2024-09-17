<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\SparkPlug\Controller;
use Rougin\Wildfire\Wildfire;

/**
 * @property \CI_DB_query_builder $db
 * @property \User                $user
 */
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
        $this->load->helper('form');

        $this->load->library('session');

        $this->load->helper('url');
        // -------------------------------

        // Load multiple models if required ---
        $this->load->model('user');
        // ------------------------------------
    }

    /**
     * @return void
     */
    public function create()
    {
        $data = array('table' => $this->table);

        /** @var array<string, mixed> */
        $input = $this->input->post(null, true);

        if (! $input)
        {
            $this->load->view('users/create', $data);

            return;
        }

        $exists = $this->user->exists($input);

        $valid = $this->user->is_valid($input);

        if ($exists)
        {
            $data['alert'] = 'Email already exists.';
        }

        if ($valid && ! $exists)
        {
            $this->user->create($input);

            $text = 'User has been successfully created!';

            $this->session->set_flashdata('alert', $text);

            redirect('users');
        }

        $this->load->view('users/create', $data);
    }

    /**
     * @param integer $id
     *
     * @return void
     */
    public function edit($id)
    {
    }

    /**
     * @return void
     */
    public function index()
    {
        $data = array('table' => $this->table);

        $query = $this->query->get($this->table);

        $data['items'] = $query->result();

        if ($alert = $this->session->flashdata('alert'))
        {
            $data['alert'] = $alert;
        }

        // Show if --with-view enabled ---------
        $this->load->view('users/index', $data);
        // -------------------------------------
    }
}
