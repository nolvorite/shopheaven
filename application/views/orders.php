<div class="col-sm-12">
	<h3>Orders</h3>

	<div class="container-fluid">
		<div class="row">
			<?php if($id !== NULL){ ?>
			<div class="col-md-4">
				<h5>Preview</h5>
				<img src="<?php echo site_url(); ?>images/<?php echo $this->security->xss_clean($content['picture_id']); ?>" class="img-fluid" alt="<?php echo $this->security->xss_clean($content['product_name']); ?>">
			</div>
			<div class="col-sm-12 col-md-8">
				<form method="POST" action="<?php echo site_url(); ?>main/complete_order">
					<input type="hidden" name="product_id" value="<?php echo $this->security->xss_clean($id); ?>">
					<h5>Ordering: <?php echo $this->security->xss_clean($content['product_name']); ?></h5>	
					<div class="form-order">
						<div class="form-group row">
							<label class="col-lg-3 col-form-label text-right">Product ID</label>
							<div class="col-lg-9">
								<input name="product_id" class="form-control" disabled value="<?php echo $this->security->xss_clean($id); ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label text-right">Quantity</label>
							<div class="col-lg-9">
								<input name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity..." type="number">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label text-right">Price</label>
							<div class="col-lg-9">
								<input class="form-control" id="price" value="PHP <?php echo $content['price']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label text-right">Total</label>
							<div class="col-lg-9">
								<input class="form-control" id="total" value="PHP 0.00" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label text-right"></label>
							<div class="col-lg-9">
								<input type="submit" class="btn btn-primary" value="Confirm Your Order">
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php } else { ?>

			<div class="col-sm-12">
				<table class="table table-striped">
					<tr>
						<th>Product Name</th>
						<th>Quantity</th>
						<th>Total</th>
						<th>Order Date</th>
					</tr>
					<?php foreach($orders as $order): ?>
					<tr>
						<td><?php echo $order['product_name']; ?></td>
						<td><?php echo $order['quantity']; ?></td>
						<td>PHP <?php echo (floatval($order['quantity'])*floatval($order['price'])); ?></td>
						<td><?php echo $order['order_date']; ?></td>
					</tr>
					<?php endforeach; ?>
				</table>
			</div>

			<?php } ?>

		</div>
	</div>
</div>