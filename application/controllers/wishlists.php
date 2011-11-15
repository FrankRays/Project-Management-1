<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WishLists extends CI_Controller {
  public $layout = 'main';

  public function index() {
    $this->load->view('wishlists/index');
  }
  
  public function show() {
    $this->load->view('wishlists/show');
  }
  
  public function test_run() {
      $this->load->library('unit_test');
      $this->load->model('wishLists', 'item');
      
      $date1 = date( 'Y-m-d H:i:s',mktime(0,17,35,11,15,2011));
      //Run some tests against the Fixture Data.
      $this->unit->run($this->item->getCart(1), 'is_array', 'CartItems getCart() General Test', 'Make sure that getCart(1) returns an array.');
      $this->unit->run($this->item->getCart(1), array(array('uid' => 1, 'stockID' => 1, 'dateAdded' => $date1,'didPurchase' => 0)), 'CartItems getCart(1) Test', 'Make sure that the value of using getCart(1) returns the data we expect from the database.');
      $this->unit->run(current($this->item->getAllCarts(100)), array('uid' => 1, 'stockID' => 1, 'dateAdded' => $date1,'didPurchase' => 0), 'Ordered Item all() test', 'Make sure that the value of using all() returns the data we expect from the database.');
      $this->unit->run($this->item->addItemToCart(1,1), true, 'Ordered Item create() General Test', 'Make sure that create() returns true and that it works for batches.');
      
      //Pass a report to the view
      $data['test_result'] = $this->unit->report();
      
      $this->load->view('test_runner', $data);
  } 
  
}

?>
