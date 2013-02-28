<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 10.02.2013 9:35
 */?>
<section id="login-dialog" style="display: none">
	<form action="/ajax.php" method="post">
		<fieldset>
			<input type="hidden" name="action" value="authLogin" />
		</fieldset>
		<fieldset>
			<div><label for="login-login">Login:</label><input type="text" name="login" id="login-login" /></div>
			<div><label for="login-password">Password:</label><input type="password" name="password" id="login-password" /></div>
		</fieldset>
	</form>
</section>
<script>
	jQuery(function($){
        $('#login-dialog').dialog({
            autoOpen: true,
            modal: true,
            title: "Login",
            width: 370,
            position: 'center',
			buttons: {
				'Login': function(){
					$('form', this).submit();
					return false;
				}
			}
        });
		f.setupAjaxSubmit($('form', '#login-dialog'), {}, {} );
	})
</script>