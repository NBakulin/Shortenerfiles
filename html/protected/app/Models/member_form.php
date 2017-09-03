<?php
	session_start();
	
@	$username = $_POST['username'];
@	$passwd = $_POST['passwd'];
	if ($username && $passwd) {
		try
		{
			login($username, $passwd);
			$_SESSION['valid_user'] = $username;
		}
		catch (Exception $e) {
			do_html_header('Проблема');
			echo $e->getMessage();
			do_html_url('login_form.php','Повторить попытку');
			do_html_footer();
			exit;
		}
	}
	
	do_html_header('Домашняя страница.');
	check_valid_user();
	
	if ($url_array = get_user_urls($_SESSION['valid_user'])) {
		display_user_urls($url_array);
	}

	display_user_menu();
	
	do_html_footer();
?>