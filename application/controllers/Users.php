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
     * @var \User_repository
     */
    private $depot;

    /**
     * Loads the helpers, libraries, and models.
     */
    public function __construct()
    {
        parent::__construct();

        // Initialize the Database loader ---
        $this->load->database();
        // ----------------------------------

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

        // Load the main repository of the model ---
        $credo = new Credo($this->db);

        /** @var \User_repository */
        $depot = $credo->get_repository('User');

        $this->depot = $depot;
        // -----------------------------------------
    }

    /**
     * Returns the form page for creating a user.
     * Creates a new user if receiving payload.
     *
     * @return void
     */
    public function create()
    {
        /** @var array<string, mixed> */
        $input = $this->input->post(null, true);

        if (! $input)
        {
            $this->load->view('users/create');

            return;
        }

        $exists = $this->depot->exists($input);

        $data = array();

        // Specify logic here if applicable ---------
        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $this->user->validate($input);

        if (! $valid || ! $exists)
        {
            // Show if --with-view enabled ----------
            $this->load->view('users/create', $data);
            // --------------------------------------

            return;
        }

        $this->depot->create($input);

        $text = 'Item successfully created!';

        $this->session->set_flashdata('alert', $text);

        redirect('users');
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
        if (! $item = $this->depot->find($id))
        {
            show_404();
        }

        /** @var \User $item */
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

        // Specify logic here if applicable ---------
        $exists = $this->depot->exists($input, $id);

        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $this->user->validate($input);

        if (! $valid || ! $exists)
        {
            // Show if --with-view enabled --------
            $this->load->view('users/edit', $data);
            // ------------------------------------

            return;
        }

        /** @var \User $item */
        $this->depot->update($item, $input);

        $text = 'Item successfully updated!';

        $this->session->set_flashdata('alert', $text);

        redirect('users');
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
        // Show 404 page if not using "DELETE" method ---
        $method = $this->input->post('_method', true);

        $item = $this->depot->find($id);

        if ($method !== 'DELETE' || ! $item)
        {
            show_404();
        }
        // ----------------------------------------------

        /** @var \User $item */
        $this->depot->delete($item);

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
        // Generate the pagination links and its offset ---
        $total = (int) $this->depot->total();

        $result = $this->user->paginate(10, $total);

        $data = array('links' => $result[1]);

        /** @var integer */
        $offset = $result[0];
        // ------------------------------------------------

        $items = $this->depot->get(10, $offset);

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
