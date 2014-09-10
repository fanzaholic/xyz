<h4>register</h4>

<?php if(isset($msg) && $msg!=''): ?>
<p><?php echo $msg; ?></p>
<?php endif; ?>

<p>
	<form method="post" action="<?php echo base_url('auth/register_process'); ?>">
		<p><input type="text" name="provider_uid" placeholder="username" /></p>
		<p><input type="email" name="email" placeholder="Email" /></p>
		<p><input type="password" name="token" placeholder="Password" /></p>
		<p><input type="submit" /></p>
		<input type="hidden" name="provider" value="email" />
	</form>
</p>