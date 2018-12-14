<?php
    require_once('BizDataLayer/chatData.php');
    require_once('BizDataLayer/loginData.php');
    require_once("lib_utils.php"); 

    session_name("playerSession");
    session_start();

	function getChat(){
        $data = array();          

        if ( isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > 1800)) {
            logoutPlayer($_SESSION['player_id']);
            session_unset();     
            session_destroy();
            setcookie ("player_id", "", time() - 3600);
            setcookie ("username", "", time() - 3600);
            setcookie ("room", "", time() - 3600);
            unset($_COOKIE['username']);
            unset($_COOKIE['room']);
            $data['loggedOut'] = 'Session Expired';
            return returnJsonEncode($data);
        } else {
            if ($_SESSION['loggedIn'] == 0) {
                $data['loggedOut'] = 'No user logged in';
                return returnJsonEncode($data);            
            } else {
               return getChatData($_SESSION['room']);
            }
        }
	}
    function sendChat($myData){
        
        $data = array();
        $message = sanitize($myData);
        
        if ( isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > 1800)) {
            logoutPlayer($_SESSION['player_id']);
            session_unset();     
            session_destroy();
            setcookie ("player_id", "", time() - 3600);
            setcookie ("username", "", time() - 3600);
            setcookie ("room", "", time() - 3600);
            unset($_COOKIE['username']);
            unset($_COOKIE['room']);
            $data['loggedOut'] = 'Session Expired';
        } else {
            if ($_SESSION['loggedIn'] == 0) {
                $data['loggedOut'] = 'No user logged in';
            } else {
                $_SESSION['LAST_ACTIVITY']= time();
               $json = sendChatData($_SESSION['player_id'],$message, $_SESSION['room']);
               $array = json_decode($json, true);
                if ($array['success'] == 1) {
                    $data['username'] = $_SESSION['username'];
                    $data['message'] = $message;
                    $data['timestamp'] = $_SESSION['LAST_ACTIVITY'];
                } else {
                    $data['error'] = "Could not send message at this time please try again later";
                }
            }
        }
        return returnJsonEncode($data);            

	}
function getChatRoom($myData){
        $data = array();          

        if ( isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > 1800)) {
            logoutPlayer($_SESSION['player_id']);
            session_unset();     
            session_destroy();
            setcookie ("player_id", "", time() - 3600);
            setcookie ("username", "", time() - 3600);
            setcookie ("room", "", time() - 3600);
            unset($_COOKIE['username']);
            unset($_COOKIE['room']);
            $data['loggedOut'] = 'Session Expired';
            return returnJsonEncode($data);
        } else {
            if ($_SESSION['loggedIn'] == 0) {
                $data['loggedOut'] = 'No user logged in';
                return returnJsonEncode($data);            
            } else {
               return getChatData($myData);
            }
        }
	}
    function sendChatRoom($myData){
        
        $data = array();
        $h=explode('|',$myData);
        $room = $h[0];
        $message = sanitize($h[1]);
        
        if ( isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > 1800)) {
            logoutPlayer($_SESSION['player_id']);
            session_unset();     
            session_destroy();
            setcookie ("player_id", "", time() - 3600);
            setcookie ("username", "", time() - 3600);
            setcookie ("room", "", time() - 3600);
            unset($_COOKIE['username']);
            unset($_COOKIE['room']);
            $data['loggedOut'] = 'Session Expired';
        } else {
            if ($_SESSION['loggedIn'] == 0) {
                $data['loggedOut'] = 'No user logged in';
            } else {
                $_SESSION['LAST_ACTIVITY']= time();
               $json = sendChatData($_SESSION['player_id'],$message, $room);
               $array = json_decode($json, true);
                if ($array['success'] == 1) {
                    $data['username'] = $_SESSION['username'];
                    $data['message'] = $message;
                    $data['timestamp'] = $_SESSION['LAST_ACTIVITY'];
                } else {
                    $data['error'] = "Could not send message at this time please try again later";
                }
            }
        }
        return returnJsonEncode($data);            

	}
    function logoutPlayer($myData) {
        return logoutData($myData);
    }
?>