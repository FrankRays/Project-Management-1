<div class="page-header">
  <h2><?php echo  $this_category['name'] ?> <small> - A list of products that fall under this category</small></h2>
</div>

<ul class="unstyled">
  <?php foreach( $products as $product) { ?>
    <li class="row">
      <div class="span1">
        <a class="category-browse-image" href="/products/show/<?php echo  $product['pid'] ?>"> 
          <img style="width:50px;" src="<?php echo  $product['location'] ?>" /> 
        </a>
      </div>
      <div class="span5">
        <h4>
          <a href="/products/show/<?php echo  $product['pid'] ?>"><?php echo  $product['Name'] ?></a> <br/>
          <small> - <?php echo  $product['Description'] ?></small>
        </h4>
      </div>
    </li>
  <?php } ?>
</ul>
