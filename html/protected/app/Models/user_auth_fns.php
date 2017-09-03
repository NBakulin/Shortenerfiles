<?php
function login($username, $passwd) {
	$conn = db_connect();
	
	$result = $conn->query("select * from user
						    where username ='".$username."' 
							and passwd = sha1('".$passwd."')");
	if (!$result) {
		throw new Exception('Невозможно подключиться к базе данных.');
	}
	
	if ($result->num_rows > 0) {
		return true;
	} else {
		throw new Exception('Неверное имя пользователя или пароль.');
	}
}

function check_valid_user() {
	if (isset($_SESSION['valid_user'])) {
		echo "Вы вошли в систему под именем ".$_SESSION['valid_user'].".<br />";
	} else {
		//do_html_heading('Проблема:'); //heading??
		echo 'Вы не вошли в систему.<br />';
		do_html_url('login_form.php','Войти');
		do_html_footer();
		exit;
	}
}
