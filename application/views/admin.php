<div class="col-sm-12">
<h3>Admin</h3>
</div>

<div class="col-sm-12">
	<div class="row">
		<div class="col-md-5 col-lg-3" id="admin_menu">
			<h5>Menu</h5>
			<a href="<?php echo site_url(); ?>main/admin/add_product">Add Product</a>
			<a href="<?php echo site_url(); ?>main/admin/add_category">Add Category</a>
			<a href="<?php echo site_url(); ?>main/admin/manage_users">Manage Users</a>
			<a href="<?php echo site_url(); ?>main/admin/view_orders">View Orders</a>
		</div>
		<div class="col-md-7 col-lg-9">
			<?php if(isset($panel)){ ?>

			<?php $this->load->view($panel); ?>

			<?php } else { ?>

			<h5>Admin Panel</h5>
			<p>Select from the menu on the left side to select your option.</p>

			<?php } ?>
		</div>
	</div>
</div>
