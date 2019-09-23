
<?php if($solo){ ?>
<h5>Editing User Details: <?php echo $users['email']; ?></h5>
<form method="POST" action="<?php echo site_url(); ?>main/manage_users/<?php echo $users['id']; ?>">
	<input type="hidden" name="id" value="<?php echo $users['id']; ?>">
	<div class="form-group">
		<input type="text" class="form-control" value="<?php echo $this->security->xss_clean($users['email']); ?>" name="email">
	</div>
	<div class="form-group">
		<input type="text" class="form-control" value="<?php echo $this->security->xss_clean($users['first_name']); ?>" name="first_name">
	</div>
	<div class="form-group">
		<input type="text" class="form-control" value="<?php echo $this->security->xss_clean($users['last_name']); ?>" name="last_name">
	</div>
	<div class="form-group">
		<select name="role" class="form-control">
			<optgroup label="Select role">
				<option value="admin">Admin</option>
				<option value="buyer">Buyer</option>
				<option value="seller">Seller</option>
			</optgroup>
		</select>
	</div>
	<input type="submit" class="btn btn-primary" value="Edit User Information">
</form>
<?php } else { ?>
<h5>Manage Users</h5>
<table class="table table-sm table-striped">
	<tr><th>User ID</th><th>Email</th><th>First Name</th><th>Last Name</th><th>Role</th></tr>
	<?php foreach($users as $user): ?>
	<tr>
		<td><?php echo $user['id']; ?> <a class="btn btn-sm btn-warning" href="<?php echo site_url(); ?>main/admin/manage_users/<?php echo $user['id'];?>">EDIT</a></td>
		<td><?php echo $this->security->xss_clean($user['email']); ?></td>
		<td><?php echo $this->security->xss_clean($user['first_name']); ?></td>
		<td><?php echo $this->security->xss_clean($user['last_name']); ?></td>
		<td><?php echo $this->security->xss_clean($user['role']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php } ?>