<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\SparkPlug\Controller;
use Rougin\Wildfire\Wildfire;

/**
 * @property \CI_DB_query_builder $db
 * @property \CI_Input            $input
 * @property \CI_Session          $session
 * @property \User                $user
 */
class Users extends Controller
{
    /**
     * @var string
     */
    private $table = 'users';

    public function __construct()
    {
        parent::__construct();

        // Initialize the Wildfire instance ---
        $this->load->helper('inflector');

        $this->load->database();

        $wildfire = new Wildfire($this->db);
        // ------------------------------------

        // Show if --with-view enabled ---
        $this->load->helper('form');

        $this->load->library('session');

        $this->load->helper('url');
        // -------------------------------

        // Load multiple models if required ---
        $this->load->model('user');

        $this->user->wildfire($wildfire);
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

        $valid = $this->user->validate($input);

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
        if (! $item = $this->user->find($id))
        {
            show_404();
        }

        $data = array('item' => $item);

        /** @var array<string, mixed> */
        $input = $this->input->post(null, true);

        if (! $input)
        {
            $this->load->view('users/edit', $data);

            return;
        }

        // Show 404 page if not using "PUT" method ---
        $method = $this->input->post('_method', true);

        if ($method !== 'PUT')
        {
            show_404();
        }
        // -------------------------------------------

        $exists = $this->user->exists($input, $id);

        $valid = $this->user->validate($input);

        if ($exists)
        {
            $data['alert'] = 'Email already exists.';
        }

        if ($valid && ! $exists)
        {
            $this->user->update($id, $input);

            $text = 'User has been successfully updated!';

            $this->session->set_flashdata('alert', $text);

            redirect('users');
        }

        // Show if --with-view enabled --------
        $this->load->view('users/edit', $data);
        // ------------------------------------
    }

    /**
     * @param integer $id
     *
     * @return void
     */
    public function delete($id)
    {
        // Show 404 page if not using "DELETE" method -------
        $method = $this->input->post('_method', true);

        if ($method !== 'DELETE' || ! $this->user->find($id))
        {
            show_404();
        }
        // --------------------------------------------------

        $this->user->delete($id);

        $text = 'User successfully deleted!';

        $this->session->set_flashdata('alert', $text);

        redirect('users');
    }

    /**
     * @return void
     */
    public function index()
    {
        $data = array('table' => $this->table);

        $items = $this->user->get();

        $data['items'] = $items->result();

        if ($alert = $this->session->flashdata('alert'))
        {
            $data['alert'] = $alert;
        }

        // Show if --with-view enabled ---------
        $this->load->view('users/index', $data);
        // -------------------------------------
    }
}
