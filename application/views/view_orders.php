<div class="col-sm-12">
	<h3>All Orders</h3>

	<table class="table table-striped">
		<tr>
			<th>Product Name</th>
			<th>User ID</th>

			<th>Quantity</th>
			<th>Total</th>
			<th>Order Date</th>
		</tr>
		<?php foreach($orders as $order): ?>
		<tr>
			<td><?php echo $order['product_name']; ?></td>
			<td><?php echo $order['user_id']; ?></td>
			<td><?php echo $order['quantity']; ?></td>
			<td>PHP <?php echo (floatval($order['quantity'])*floatval($order['price'])); ?></td>
			<td><?php echo $order['order_date']; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>

</div>