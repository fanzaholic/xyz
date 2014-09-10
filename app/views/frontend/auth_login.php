<h4>LOGIN</h4>

<?php if(isset($msg) && $msg!=''): ?>
<p><?php echo $msg; ?></p>
<?php endif; ?>

<p>
	<form method="post" action="<?php echo base_url('auth/login_process'); ?>">
		<p><input type="text" name="provider_uid" /></p>
		<p><input type="password" name="token" /></p>
		<p><input type="submit" /></p>
		<input type="hidden" name="provider" value="email" />
	</form>
</p>

<p>
	<a href="<?php echo $fb_login_url; ?>">FB Login</a>
</p>