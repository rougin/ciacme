<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Rougin\Credo\Credo;
use Rougin\SparkPlug\Controller;

/**
 * Sample CI Controller using Credo.
 *
 * @property \CI_DB_query_builder $db
 * @property \CI_Input            $input
 * @property \MY_Loader           $load
 * @property \CI_Session          $session
 * @property \User                $user
 * @property \User_repository     $user_repository
 */
class Users extends Controller
{
    /**
     * @var \Rougin\Credo\Credo
     */
    private $credo;

    /**
     * Table associated with the controller.
     *
     * @var string
     */
    private $table = 'users';

    /**
     * Number of items to show per page.
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * Loads the helpers, libraries, and models.
     */
    public function __construct()
    {
        parent::__construct();

        // Initialize the Credo instance ---
        $this->load->database();

        $this->credo = new Credo($this->db);
        // ---------------------------------

        // Show if --with-view enabled ----
        $this->load->helper('form');

        $this->load->helper('url');

        $this->load->library('pagination');

        $this->load->library('session');
        // --------------------------------

        // Load multiple models if required ---
        $this->load->model('user');

        $this->load->repository('user');
        // ------------------------------------
    }

    /**
     * Returns the form page for creating a user.
     * Creates a new user if receiving payload.
     *
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

        /** @var \User_repository */
        $user = $this->credo->get_repository('User');

        $exists = $user->exists($input);

        // Specify logic here if applicable ---------
        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $user->validate($input);

        if ($valid && ! $exists)
        {
            // $this->user->create($input);

            $text = 'Item successfully created!';

            $this->session->set_flashdata('alert', $text);

            redirect('users');
        }

        $this->load->view('users/create', $data);
    }

    /**
     * Returns the form page for updating a user.
     * Updates the specified user if receiving payload.
     *
     * @param integer $id
     *
     * @return void
     */
    public function edit($id)
    {
        /** @var \User_repository */
        $user = $this->credo->get_repository('User');

        if (! $item = $user->find($id))
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

        $exists = $user->exists($input, $id);

        // Specify logic here if applicable ---------
        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $user->validate($input);

        if ($valid && ! $exists)
        {
            // $this->user->update($id, $input);

            $text = 'Item successfully updated!';

            $this->session->set_flashdata('alert', $text);

            redirect('users');
        }

        // Show if --with-view enabled --------
        $this->load->view('users/edit', $data);
        // ------------------------------------
    }

    /**
     * Deletes the specified user.
     *
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

        /** @var \User_repository */
        $user = $this->credo->get_repository('User');

        $user->delete($id);

        $text = 'Item successfully deleted!';

        $this->session->set_flashdata('alert', $text);

        redirect('users');
    }

    /**
     * Returns the list of paginated users.
     *
     * @return void
     */
    public function index()
    {
        /** @var \User_repository */
        $user = $this->credo->get_repository('User');

        $total = $user->total();

        // $result = $user->paginate($this->limit, $total);

        $data = array('links' => '');

        // $offset = $result[0];

        $items = $user->get($this->limit);

        $data['items'] = $items;

        if ($alert = $this->session->flashdata('alert'))
        {
            $data['alert'] = $alert;
        }

        // Show if --with-view enabled ---------
        $this->load->view('users/index', $data);
        // -------------------------------------
    }
}
