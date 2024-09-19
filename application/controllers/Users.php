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

        // Change items per page if specified ----
        if ($limit = $this->input->get('l', true))
        {
            /** @var string $limit */
            $this->limit = (int) $limit;
        }
        // ---------------------------------------

        // Initialize the Credo instance ---
        $this->load->database();

        $credo = new Credo($this->db);
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

        // Load the main repository of the model ---
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

        // Specify logic here if applicable ------------------
        if ($exists)
        {
            $data = array('error' => 'Email already exists.');
        }
        // ---------------------------------------------------

        $valid = $this->user->validate($input);

        if ($valid && ! $exists)
        {
            $this->depot->create($input);

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

        $exists = $this->depot->exists($input, $id);

        // Specify logic here if applicable ---------
        if ($exists)
        {
            $data['error'] = 'Email already exists.';
        }
        // ------------------------------------------

        $valid = $this->user->validate($input);

        if ($valid && ! $exists)
        {
            /** @var \User $item */
            $this->depot->update($item, $input);

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
        // Show 404 page if not using "DELETE" method --------
        $method = $this->input->post('_method', true);

        if ($method !== 'DELETE' || ! $this->depot->find($id))
        {
            show_404();
        }
        // ---------------------------------------------------

        $this->depot->delete($id);

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
        // Generate the pagination links and its offset ------
        $total = (int) $this->depot->total();

        $result = $this->user->paginate($this->limit, $total);

        $data = array('links' => $result[1]);

        /** @var integer */
        $offset = $result[0];
        // ---------------------------------------------------

        $items = $this->depot->get($this->limit, $offset);

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
