<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends CI_Controller {
  public $layout = 'admin';
  public $auth = array(); // don't need to fill auth since this all requires admin access
  public $admin = array(
    'dashboard' => array('message' => 'Please login'),
    'index' => array(),
    'add' => array(),
    'edit' => array(),
    'update' => array(),
    'delete' => array(),
    'create' => array()
  );

  // GET - 200
  function index() {
    $this->load->model('User');
    $data = array();
    $data['employees'] = $this->User->employees(50);
    $data['js'] = '/libs/jquery.tablesorter.min.js';
    $this->load->view('employees/index', $data);
  }

  // GET - 200
  function add($id = 0) {
    $this->load->view('employees/new');
  }

  // GET - 200
  function edit($id = 0) {
    $this->load->model('User');
    $data = array();
    $data['employee'] = $this->User->find($id);
    $this->load->view('employees/edit', $data);
  }

  // GET - 200
  function login() {
    $this->load->view('employees/login');
  }

  // POST - 302 redirect
  function delete($id) {
    $this->load->model('User');
    $this->User->destroy($id);
    set_message('Employee deleted successfully', 'success');
    header('Location: /employees');
  }
  
  // POST - 302 redirect
  function update($id) {
    $rules = array(
      array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'),
      array('field' => 'firstname', 'label' => 'First Name', 'rules' => 'required'),
      array('field' => 'lastname', 'label' => 'Last Name', 'rules' => 'required'),
      array('field' => 'dob', 'label' => 'Date of Birth', 'rules' => 'required')
    );
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == TRUE) {

      list($m, $d, $y) = explode('/', $this->input->post('dob'));

      $data = array(
        'FirstName' => $this->input->post('firstname'),
        'LastName' => $this->input->post('lastname'),
        'Email' => $this->input->post('email'),
        'DOB' => $y . '-' . $m . '-' . $d
      );
      $this->load->model('User');
      $user_id = $this->User->update($id, $data);
      if ($user_id) {
        set_message('Employee updated successfully', 'success');
        header('Location: /employees/');
      } else {
        set_message('Employee could not be updated.', 'error');
        header('Location: /employees/edit/' . $id);
      }
    } else {
      set_message(validation_errors(), 'error');
      header('Location: /employees/edit/' . $id);
    }
  }

  // POST - 302 redirect
  function create() {
    $rules = array(
      array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'),
      array('field' => 'firstname', 'label' => 'First Name', 'rules' => 'required'),
      array('field' => 'lastname', 'label' => 'Last Name', 'rules' => 'required'),
      array('field' => 'dob', 'label' => 'Date of Birth', 'rules' => 'required')
    );
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == TRUE) {

      $this->load->model('User');
      $firstname = $this->input->post('firstname');
      $lastname = $this->input->post('lastname');
      $email = $this->input->post('email');
      list($m, $d, $y) = explode('/', $this->input->post('dob'));
      $dob = $y . '-' . $m . '-' . $d;

      $exists = $this->User->find_by(array('Email' => $email));

      // if the user already exists, just update them to an employee
      if (count($exists) > 0) {

        $user_id = $this->User->update($exists[0]['uid'], array('Employee' => 1));

      } else {
        // otherwise, create a new user as an employee
        $newPassword = $this->User->randomPassword();
        $user_id = $this->User->create($lastname, $firstname, $email, $newPassword, 1);
      }

      if ($user_id) {
        set_message('Employee created successfully', 'success');
        header('Location: /employees/');
      } else {
        set_message('Employee could not be created.', 'error');
        header('Location: /employees/add');
      }
    } else {
      set_message(validation_errors(), 'error');
      header('Location: /employees/add');
    }
  }

  // GET
  // Admin
  function dashboard() {
    $this->layout = 'admin';
    $data = array();
    $data['js'] =array('libs/highcharts.js', 'admin_dashboard.js');
    $this->load->view('employees/dashboard', $data);
  }
}

?>
