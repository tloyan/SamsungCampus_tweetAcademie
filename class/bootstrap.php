<?php
spl_autoload_register('app_autoload');

function app_autoload($class){
	if(substr($class, 0 , 5) != 'Swift') {
		if ($class == "tweet") {
			$class .= "_class";
		}
		require_once "$class.php";
	}

}