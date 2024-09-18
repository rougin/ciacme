<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\SparkPlug\Controller;
use Rougin\Wildfire\Wildfire;

/**
 * Sample CI Controller using Wildfire.
 *
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

        // Show if --with-view enabled ----
        $this->load->helper('form');

        $this->load->helper('url');

        $this->load->library('pagination');

        $this->load->library('session');
        // --------------------------------

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

        // Specify logic here if applicable ---------
        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $this->user->validate($input);

        if ($valid && ! $exists)
        {
            $this->user->create($input);

            $text = 'Item successfully created!';

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

        // Skip if provided empty input -----------
        /** @var array<string, mixed> */
        $input = $this->input->post(null, true);

        if (! $input)
        {
            $this->load->view('users/edit', $data);

            return;
        }
        // ----------------------------------------

        // Show 404 page if not using "PUT" method ---
        $method = $this->input->post('_method', true);

        if ($method !== 'PUT')
        {
            show_404();
        }
        // -------------------------------------------

        $exists = $this->user->exists($input, $id);

        // Specify logic here if applicable ---------
        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $this->user->validate($input);

        if ($valid && ! $exists)
        {
            $this->user->update($id, $input);

            $text = 'Item successfully updated!';

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

        $text = 'Item successfully deleted!';

        $this->session->set_flashdata('alert', $text);

        redirect('users');
    }

    /**
     * @return void
     */
    public function index()
    {
        $total = (int) $this->user->total();

        $limit = $this->input->get('l', true) ?? 5;

        $result = $this->user->paginate($limit, $total);

        $data = array('links' => $result[1]);

        $items = $this->user->get($limit, (int) $result[0]);

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
