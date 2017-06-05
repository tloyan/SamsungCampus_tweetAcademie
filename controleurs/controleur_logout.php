<?php
App::getAuth()->logout();
Session::getInstance()->setFlash('success', "vous etes maintenant deconecter");
App::Redirection('login.php');