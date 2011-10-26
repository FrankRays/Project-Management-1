<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {
  public $layout = 'main';

  // new is a php keyword....
  public function signup() {
    $this->load->view('customers/new');
  }

  // my account page
  public function account() {
    $this->load->view('customers/account');
  }

  public function forgot_password() {
    $this->load->view('customers/forgot_password');
  }
}
