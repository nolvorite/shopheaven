
<div class="col-sm-12" id="products">
	<h3>Products</h3>
	<div class="container-fluid">
		<div class="row">
			<?php foreach($products as $product):  ?>
			<div class="col-md-3 colcenter">
				<div class="text-center">
					<?php if($product['picture_id'] === NULL){ ?>
					<img class="img-fluid" src="https://via.placeholder.com/400x260.png?text=No Image Available" alt="Products">
					<?php } else { ?>
					<img class="img-fluid" src="<?php echo site_url(); ?>images/<?php echo $product['picture_id']; ?>" alt="Products">
					<?php } ?>
				</div>
				<div class="text-justify product-desc">
					<p class="text-center"><?php echo $product['product_name']; ?></p>
					<p><strong>Category: </strong><?php echo $product['category_name']; ?></p>
					<p><strong>Price: </strong>P<?php echo $product['price']; ?></p>
					<p><?php echo $product['description']; ?></p>
				</div>
				<?php if(!empty($this->session->userdata['email'])): ?>
				<div class="text-center">
				
				<a href="<?php echo site_url(); ?>main/orders/<?php echo $product['product_id']; ?>" class="btn btn-success">Order This Product!</a>

				</div><?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>