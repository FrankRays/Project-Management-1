<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {
  public $layout = 'main';

  public function index() {
    $this->load->view('welcome_message');
  }
  
  public function show($order_num = 0) {
      $data = array();
      $this->load->model('Order', 'order');
      $order = $this->order->find($order_num);
      
      if($order && $order['uid'] === get_current_user_stuff('uid')) {
          $products = $this->order->get_products_in_order($order_num);
          $data['order'] = $order;
          $data['products'] = $products;
      }
      
      $this->load->view('orders/show', $data);
  }

  public function checkout() {
    $this->load->model('Cart_Item', 'item');
    $this->load->model('Shipping_Address', 'address');
    
    $data['shipping_addresses'] = $this->address->find_by(array('uid' => get_current_user_stuff('uid')));
    $data['totalPrice'] = $this->item->totalPrice(get_current_user_stuff('uid'));
    $data['shippingCost'] = number_format($data['totalPrice'] * 0.06, 2);
    
    $this->load->view('orders/checkout', $data);
  }

  public function complete() {
    $this->load->view('orders/complete');
  }

  public function admin_browse() {
    $this->layout = "admin";
    $data = array();
    $this->load->model('Order', 'order');
    
    $data['js'] = '/libs/jquery.tablesorter.min.js';
    $data['orders'] = $this->order->all(50);
    
    $this->load->view('orders/admin_browse', $data);
  }
  public function admin_show($order_num = 0) {
    $this->layout = "admin";
    $data = array();
    $this->load->model('Order', 'order');
    $order = $this->order->find($order_num);

    if($order) {
      $products = $this->order->get_products_in_order($order_num);
      $data['order'] = $order;
      $data['products'] = $products;
    }
    $this->load->view('orders/admin_show', $data);
  }
  
  public function test_run() {
    $this->load->library('unit_test');
    $this->load->model('Order', 'order');

    //Run some tests against the Fixture Data.
    $this->unit->run(current($this->order->get_products_in_order(1)), array("pid"=>"10", "name"=>"Carved Black  Bead and Yellow Agate Ribbon Necklace", "priceUSD"=>"25", "location"=>"http://img1.etsystatic.com/il_fullxfull.279457005.jpg"), 'Orders get_products_in_order() Return Test', 'Checks to makes sure what is returned is what is the expected result.');
    $this->unit->run($this->order->convert_cart_to_order(1, 1), true, 'Orders convert_cart_to_order() Return Type Test', 'Make sure that convert_cart_to_order() doesn\'t fail.');
    
    //Pass a report to the view
    $data['test_result'] = $this->unit->report();
          
    $this->load->view('test_runner', $data);
  }

}