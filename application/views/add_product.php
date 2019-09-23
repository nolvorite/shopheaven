<h5>Add Product</h5>
<form method="POST" action="<?php echo site_url(); ?>main/add_product" id="uploader" enctype="multipart/form-data">
	<div class="form-group"><input type="text" name="product_name" class="form-control" placeholder="Product Name"></div>
	<div class="form-group"><input type="number" name="quantity" class="form-control" placeholder="Stock"></div>
	<div class="form-group"><input type="number" name="price" class="form-control" placeholder="Price"></div>
	<div class="form-group"><select name="category_id" class="form-control">
		<optgroup label="Select category">
			<?php foreach($categories as $category): ?>
			<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
			<?php endforeach; ?>
		</optgroup>
	</select></div>
	<div class="form-group"><textarea class="form-control" placeholder="Description..." name="description"></textarea></div>
	<div class="form-group">
		<label>Upload a picture...</label>
		<input type="file" class="form-control" placeholder="" name="picture" id="file_uploader">
	
	</div>
	<div class="form-group"><input class="btn btn-primary" type="submit" value="Add New Product"></div>
</form>

<h5>List of Products</h5>
<table class="table table-striped">
<tr><th width="25%" class="text-right">Product Name</th><th>Properties</th></tr>
<?php foreach($products as $product):  ?>
<tr>
	<td class="text-right" valign="top"><?php echo $this->security->xss_clean($product['product_name']); ?>
	<?php if($product['picture_id'] !== NULL){ ?>
	<img src="<?php echo site_url(); ?>images/<?php echo $product['picture_id']; ?>" class="img-fluid" alt="Image">
	<?php }else{ ?>
	<img src="https://via.placeholder.com/300x360.png?text=Image%20Not%20Available" class="img-fluid" alt="Image">
	<?php }?>
	</td>
	<td>
		<p><strong>Description: </strong><?php echo $this->security->xss_clean($product['description']); ?></p>
		<p><strong>Category: </strong><?php echo $this->security->xss_clean($product['category_name']); ?></p>
		<p><strong>Currently In Stock: </strong><?php echo $this->security->xss_clean($product['quantity']); ?></p>
		<p><strong>Price: </strong>PHP<?php echo $this->security->xss_clean($product['price']); ?></p>
	</td>
</tr>
<?php endforeach; ?>
</table>