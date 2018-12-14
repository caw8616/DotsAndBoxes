<?php
    require_once('BizDataLayer/loginData.php');
    require_once("lib_utils.php"); 

    session_name("playerSession");
    session_start();


    function checkPlayer($myData){
		$h=explode('|',$myData);
		$username=sanitize($h[0]);
		$password=sanitize($h[1]);
        $data = array();
        
        $validLogin = validateLogin($username, $password);
        if($validLogin == "" ) {
            $json = checkPlayerData($username);
            $array = json_decode($json, true)[0];
            if($array['player_id'] != null) {
                $hash =  $array['password'];
                if(password_verify($password, $hash)== true) {
                   $rows =  loginData($array['player_id']);
                    if($rows == true){
                        $_SESSION['loggedIn'] = 1;
                        $_SESSION['player_id'] = $array['player_id'];
                        $_SESSION['username'] = $array['username'];
                        $_SESSION['room'] = 0;
                        $_SESSION['CREATED'] = time();
                        $_SESSION['LAST_ACTIVITY'] = time();
                        setcookie('player_id', $array['player_id'] , time()+3600);
                        setcookie('username', $username, time()+3600);
                        setcookie('room', 0, time()+3600);


                        $data['player_id'] = $array['player_id'];
                        $data['username'] = $array['username'];

                    } else{
                        $_SESSION['loggedIn'] = 0;
                        if(isset($_SESSION['player_id'])){
                            unset($_SESSION['player_id']);
                        }
                        if(isset($_SESSION['username'])){
                            unset($_SESSION['username']);
                        }
                        if(isset($_SESSION['room'])){
                            unset($_SESSION['room']);
                        }
                         if(isset($_SESSION['LAST_ACTIVITY'])){
                            unset($_SESSION['LAST_ACTIVITY']);
                        }
                        if(isset($_SESSION['CREATED'])){
                            unset($_SESSION['CREATED']);
                        }
                        setcookie ("player_id", "", time() - 3600);
                        setcookie ("username", "", time() - 3600);
                        setcookie ("room", "", time() - 3600);
                        unset($_COOKIE['username']);
                        unset($_COOKIE['room']);
                        logoutPlayer($array['player_id']);
                        $data['error'] = "Could not complete login at this time please try again later";
                    }
                } else {
                    $data['error'] = "Password is Invalid";
                }
            } else {
                $data['error'] = "Username is Invalid";
            }
        } else {
            $data['error'] = $validLogin; 
        }
        return returnJsonEncode($data);
	}	

  function validateLogin($username, $password) {
        $errors = "";
        if(!validateBlank($username) || !validateBlank($password)) {
            if(!validateBlank($username)) {
                $errors = $errors."<p>Username cannot be blank!</p>";
            }
            if(!validateBlank($password)) {
                $errors = $errors."<p>Password cannot be blank!</p>";
            }
        } else {
            if(!inRange($username, 5, 15)) {
                $errors = $errors."<p>Your Username must be between 5 and 15 characters!</p>";
            }
             if(!inRange($password, 8, 30)) {
                $errors = $errors."<p>Your Password must be between 8 and 30 characters!</p>";
            }
            if(!alphabeticNumericPunct($username)) {
                $errors = $errors."<p>Username can only consist of letters, numbers and certain symbols!</p>";
            }
            if(!alphabeticNumericPunct($password)) {
                $errors = $errors."<p>Password can only consist of letters, numbers and certain symbols!</p>";
            }
        }
        echo $errors;
    }
function createPlayer($myData) {

        $h=explode('|',$myData);
        $username=sanitize($h[0]);
        $password=sanitize($h[1]);
        $firstname=sanitize($h[2]);
        $lastname=sanitize($h[3]);
         
        $data = array();

        $validLogin = validateSignUp($username, $password, $firstname, $lastname);

        if($validLogin == "" ) {
            $json = checkPlayerData($username);
            $array = json_decode($json, true)[0];
            if ($array['player_id']) {
                $data['error'] = "Username is taken please choose another!";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $jsonCreate = createPlayerData($username,$hash, $firstname, $lastname);
                $arrayCreate = json_decode($jsonCreate, true);

                if($arrayCreate['id']) {
                    $_SESSION['loggedIn'] = 1;
                    $_SESSION['player_id'] = $arrayCreate['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['room'] = 0;
                    $_SESSION['CREATED'] = time();
                    $_SESSION['LAST_ACTIVITY'] = time();
                    
//                    setcookie('loggedIn', 1, time()+1800); 
                    setcookie('player_id', $array['player_id'] , time()+3600);
                    setcookie('username', $username, time()+3600);
                    setcookie('room', 0, time()+3600);


                    $data['player_id'] = $arrayCreate['id'];
                    $data['username'] = $username;
                } else {
                    $_SESSION['loggedIn'] = 0;
                    if(isset($_SESSION['player_id'])){
                        unset($_SESSION['player_id']);
                    }
                    if(isset($_SESSION['username'])){
                        unset($_SESSION['username']);
                    }
                    if(isset($_SESSION['room'])){
                        unset($_SESSION['room']);
                    }
                    if(isset($_SESSION['LAST_ACTIVITY'])){
                        unset($_SESSION['LAST_ACTIVITY']);
                    }
                    if(isset($_SESSION['CREATED'])){
                        unset($_SESSION['CREATED']);
                    }
                    setcookie ("player_id", "", time() - 3600);
                    setcookie ("username", "", time() - 3600);
                    setcookie ("room", "", time() - 3600);
                    unset($_COOKIE['username']);
                    unset($_COOKIE['room']);
                    $data['error'] = "Could not complete sign up at this time please try again later";
                }
            }

        } else {
            $data['error'] = $validLogin; 
        }
        return returnJsonEncode($data);
     }	

 function validateSignUp($username, $password, $firstname, $lastname) {
        $errors = "";
        if(!validateBlank($username) || !validateBlank($password) || !validateBlank($firstname) || !validateBlank($lastname)) {
            if(!validateBlank($username)) {
                $errors = $errors."<p>Username cannot be blank!</p>";
            }
            if(!validateBlank($password)) {
                $errors = $errors."<p>Password cannot be blank!</p>";
            }
            if(!validateBlank($firstname)) {
                $errors = $errors."<p>First Name cannot be blank!</p>";
            }
            if(!validateBlank($lastname)) {
                $errors = $errors."<p>Last Name cannot be blank!</p>";
            }
        } else {
            if(!inRange($username, 5, 15)) {
                $errors = $errors."<p>Your Username must be between 5 and 15 characters!</p>";
            }
             if(!inRange($password, 8, 30)) {
                $errors = $errors."<p>Your Password must be between 8 and 30 characters!</p>";
            }
            if(!inRange($firstname, 3, 15)) {
                $errors = $errors."<p>Your First Name must be between 3 and 15 characters!</p>";
            }
             if(!inRange($lastname, 3, 25)) {
                $errors = $errors."<p>Your Last Name must be between 3 and 25 characters!</p>";
            }
            if(!alphabeticNumericPunct($username)) {
                $errors = $errors."<p>Username can only consist of letters, numbers and certain symbols!</p>";
            }
            if(!alphabeticNumericPunct($password)) {
                $errors = $errors."<p>Password can only consist of letters, numbers and certain symbols!</p>";
            }
            if(!alphabeticSpaceApost($firstname)) {
                $errors = $errors."<p>First Name can only consist of letters and certain symbols!</p>";
            }
            if(!alphabeticSpaceApost($lastname)) {
                $errors = $errors."<p>Last Name can only consist of letters and certain symbols!</p>";
            }
        }
        echo $errors;
    }

    function logoutPlayer($myData) {
        return logoutData($myData);
    }

    function loginPlayer($myData) {
        return loginData($myData);
    }

    function checkSession() {
        
        $data = array();
        if ( isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > 1800)) {
            session_unset();     
            session_destroy();
            setcookie ("player_id", "", time() - 3600);
            setcookie ("username", "", time() - 3600);
            setcookie ("room", "", time() - 3600);
            unset($_COOKIE['username']);
            unset($_COOKIE['room']);
            $data['error'] = 'Session Expired';

        } else {
            if ($_SESSION['loggedIn'] == 1) {
                $data['player_id'] = $_SESSION['player_id'];
                $data['username'] = $_SESSION['username'];
                $data['room'] = $_SESSION['room'];
            } else {
                $data['error'] = 'No user logged in';
            }
        }
        return returnJsonEncode($data);
    }

    function logMeOut() {
        $data = array();
        $logout = logoutData($_SESSION['player_id']);
        if($logout) {
            $data['success'] = 1;
        
            $_SESSION['loggedIn'] = 0;
            if(isset($_SESSION['player_id'])){
                unset($_SESSION['player_id']);
            }
            if(isset($_SESSION['username'])){
                unset($_SESSION['username']);
            }
            if(isset($_SESSION['room'])){
                unset($_SESSION['room']);
            }
            if(isset($_SESSION['LAST_ACTIVITY'])){
                unset($_SESSION['LAST_ACTIVITY']);
            }
            if(isset($_SESSION['CREATED'])){
                unset($_SESSION['CREATED']);
            }
            setcookie ("player_id", "", time() - 3600);
            setcookie ("username", "", time() - 3600);
            setcookie ("room", "", time() - 3600);
            unset($_COOKIE['username']);
            unset($_COOKIE['room']);
        } else {
            $data['success'] = 0;
            $data['error'] = "Could not complete logout at this time please try again later";
        }
        
        return returnJsonEncode($data);
    }

?>

