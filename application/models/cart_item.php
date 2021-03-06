<?php

/* CREATE TABLE `CodeIgniter2`.`CartItems` (
 *  `uid` INT NOT NULL REFERENCES `CodeIgniter2`.`Users` (`uid`),
 *  `stockID` INT NOT NULL REFERENCES `CodeIgniter2`.`StockItems` (`stockID`),
 *  `dateAdded` TIMESTAMP NOT NULL ,
 *  `didPurchase` BOOL NOT NULL ,
 *  PRIMARY KEY ( `uid` , `stockID` , `dateAdded` )
 * ) ENGINE = INNODB;
*/

class Cart_Item extends CI_Model
{
  
  function __construct() {
    parent :: __construct();
  }
  
  //This will allow you to add a StockItem to a Customer's cart,
  //as long as we have that Product in stock.
  //NOTE: Add via PID, NOT StockID
  function addItem($pid,$uid) {
    $result = false;
    //Find out if there are any StockItems available.
    $query = $this->db->select('stockID')
                  ->from('StockItems')
                  ->where('Status !=', 'Sold')
              ->where('pid', $pid)
              ->get();
    //If so, store the first one we find
    if($query->num_rows() > 0) {
      $item = $query->row_array();
    } else {
      return "No Items in Stock";
    }
    
    $data = array(
      'uid'     => $uid,
      'stockID' => $item['stockID'],
      'didPurchase' => 0
    );
    
    $this->db->insert('CartItems',$data);
    if(!$this->db->_error_message()) {
          $result = true;
        }
    $query->free_result();
    return $result;    
  }
  
  //Gets items in a customer's cart that have not been purchased yet as an array
  function get($uid) {
    $this->db->select('*');
    $this->db->from('CartItems');
    $this->db->where('uid',$uid);
    $this->db->where('didPurchase',0);
    $query = $this->db->get();

    if ($query->num_rows() == 0) {
      return false;
    }

    $result = $query->result_array();
    $query->free_result();
    return $result;
  }
  
  //Get every single cart.
  function getAll($limit=0) {
    $data = array();
    $query = $this->db->get('CartItems',$limit);
    
    if($query->num_rows() > 0)
    {
      foreach($query->result_array() as $row)
      {
        $data[] = $row;  
      }
    }
    $query->free_result();
    return $data;
  }
  
  //Delete an item from a cart
  function remove($uid,$stockID) {
    $result = false;
    $data = array(
      'uid'     => $uid,
      'stockID'   => $stockID
    );
    
    $this->db->delete('CartItems',$data);

    if(!$this->db->_error_message()) {
      $result = true;
    }    

    return $result;
  }
  
  function updatePurchased($uid) {
    $result = false;
    
    $this->db->update('CartItems', array('didPurchase' => '1'), array('uid' => $uid));
    
    if(!$this->db->_error_message()) {
          $result = true;
        }    
    return $result;
  }
  
// ---------- Convienence functions -----------

  //Runs query in order to get all info to be displayed on the Cart View
  function getDisplayArray($uid) {
    /* 
     * SELECT p.name, p.description, p.PriceUSD, p.pid, i.location
     * FROM Products as p, Images as i, Users as u
     * JOIN  StockItems as s ON ON s.pid = p.pid
       JOIN  CartItems as c ON c.stockID = s.stockID 
       WHERE i.pid = p.pid
     * AND   u.uid = $uid
     */
     
     // select p.name, p.description, p.PriceUSD, p.pid, i.location from Products as p, Images as i join StockItems as s, CartItems as c where s.pid = p.pid and c.stockID = s.stockID and i.pid = p.pid and c.uid = 1 and c.didPurchase = 0 group by p.pid
     
     $result = array();
     $clause = array(
         'CartItems.stockID = StockItems.stockID',
         'StockItems.pid = Products.pid',
         'Users.uid'       => $uid
     );
     
     $query = $this->db->select("Products.description, Products.name, Products.priceUSD, Products.pid, Images.location")->from("Products, Images")->join("StockItems", "StockItems.pid = Products.pid")->join("CartItems", "CartItems.stockId = StockItems.stockId")->where("Products.pid = Images.pid")->where("CartItems.uid", $uid)->where("CartItems.didPurchase", 0)->group_by("Products.pid")->get();
     $result = $query->result_array();
     $moreResult = array();
     foreach($result as $item)
     {
       $item['quantity'] = 0;
       $item['dup'] = 'false';
       array_push($moreResult,$item);
     }
     $result = $this->getQuantities($moreResult);
     return $result;
  }
  
  private function getQuantities($array) {
     $ids = array();
     $result = array();
     $temp = array();
     
     foreach($array as $row)
     {
        $pid = $row['pid'];
        $ids[] = $pid;
        $a = array_count_values($ids);
        $count = $a[$pid];
        $row['quantity'] = $count;
        if($count >= 2)
        foreach($array as $row2)
        {
           if($row2['pid'] == $pid)
           {
              $row['dup'] = 'true';
           }
        }
       array_push($temp,$row);
     }
     
     foreach($temp as $row)
     {
          $pid = $row['pid'];
          $a = array_count_values($ids);
          $count = $a[$pid];
          $row['quantity'] = $count;
          if($row['dup'] != 'true')
            array_push($result,$row);
     }

     return $result;
  }
  
  function numItems($uid) {
      $cart = $this->get($uid);
      return count($cart);
  }
  
  //Returns the total Price of the given customer's cart
  function totalPrice($uid) {
    $totalPriceUSD = current($this->db->select("SUM(Products.priceUSD)")->from("Products")->join("StockItems", "Products.pid = StockItems.pid")->join("CartItems", "CartItems.stockID = StockItems.stockID")->where("CartItems.uid", $uid)->where("didPurchase = 0")->get()->row_array());
    return $totalPriceUSD;
  }

  function remove_by_pid($pid,$uid) {

    $this->db->query("DELETE FROM CartItems WHERE stockID ".
                     "IN ( SELECT si.stockID FROM Products p JOIN StockItems si ON si.pid = p.pid WHERE p.pid = ".$pid." ".
                     ") AND uid =".$uid);

    if(!$this->db->_error_message()) {
      return true;
    } else {
      return false;
    }
  }

  function thisFunctionIsHereBecauseAlecCantWriteQueries($uid) {
    $products  = array();
    $query = $this->db->query("SELECT p.pid as pid, p.catID as catID, p.Name as Name, p.Description as Description, p.PriceUSD as PriceUSD, i.location as imageURL, q.quantity as  quantity ".
                              "FROM CartItems ci ".
                              "JOIN StockItems si ON si.stockID = ci.stockID ".
                              "JOIN Products p ON p.pid = si.pid ".
                              "JOIN ( ".
                              "  SELECT p.pid, COUNT( si.stockID ) AS quantity ".
                              "  FROM CartItems ci ".
                              "  JOIN StockItems si ON si.stockID = ci.stockID ".
                              "  JOIN Products p ON p.pid = si.pid ".
                              "  WHERE ci.uid =".$uid." ".
                              "  AND ci.didPurchase = 0 ".
                              "  GROUP BY p.pid ".
                              ")q ON q.pid = p.pid ".
                              "LEFT JOIN Images i ON p.pid = i.pid ".
                              "WHERE ci.uid =".$uid." ".
                              "AND ci.didPurchase = 0 ".
                              "GROUP BY p.pid");
   

    foreach( $query->result_array() as $product) {
      $products[] = $product;
    }
      
    return $products;
  }
}

?>
