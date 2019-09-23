<h5>Add Category</h5>
<form method="POST" action="<?php echo site_url(); ?>main/add_category">
	<div class="form-group"><input type="text" name="category_name" class="form-control" placeholder="Category Name"></div>
	<div class="form-group"><textarea class="form-control" placeholder="Description..." name="description"></textarea></div>
	<div class="form-group"><input class="btn btn-primary" type="submit" value="Add New Category"></div>
</form>

<h5>List of Categories</h5>
<table class="table table-striped">
<tr><th width="25%" class="text-right">Product Name</th><th>Description</th></tr>
<?php foreach($categories as $category): ?>
<tr>
	<td class="text-right"><?php echo $this->security->xss_clean($category['category_name']); ?></td>
	<td><?php echo $this->security->xss_clean($category['description']); ?></td>
</tr>
<?php endforeach; ?>
</table>