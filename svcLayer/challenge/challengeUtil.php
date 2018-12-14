<?php
    require_once('BizDataLayer/gameData.php');
    require_once('BizDataLayer/loginData.php');
    require_once("lib_utils.php"); 
    session_name("playerSession");
    session_start();

    function getPlayersOn(){
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
               return getPlayersOnData($_SESSION['player_id']);
            }
        } 
	}
   function getChallenges(){
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
//                $data = array(0=>array(), 1=>array());
               return getChallengesData($_SESSION['player_id']);
            }
        }
	}

    function challengePlayer($myData){
        $h=explode('|',$myData);
		$player_id=sanitize($h[0]);
		$board_size=sanitize($h[1]);
        
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
            $_SESSION['LAST_ACTIVITY'] = time();

            if ($_SESSION['loggedIn'] == 0) {
                $data['loggedOut'] = 'No user logged in';
                return returnJsonEncode($data);            
            } else {
                $playerJson = getPlayerNameData($player_id);
                $playerData = json_decode($playerJson, true)[0];
                
               return challengePlayerData($_SESSION['player_id'], $_SESSION['username'], $player_id, $playerData['username'],$board_size);
            }
        }
	}
    
 function acceptChallenge($myData){
        $challenge_id = sanitize($myData);
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
               $challenge = getThisChallengeData($challenge_id);
               $challengeData = json_decode($challenge, true)[0];
                if($challengeData['challenge_id']) {
                    $game = createGameData($challengeData['board_size'], $challengeData['challenger'], $challengeData['challenger_name'], $challengeData['challenged'], $challengeData['challenged_name'], createGameBoard($challengeData['board_size'])); 
                    
//                    "INSERT INTO game (board_size, player_0, player_1, player0_name, player1_name, num_moves,   turn, active
                    $gameData = json_decode($game, true);
                    if($gameData['success'] == 1) {
                        $data['game_id'] = $gameData['id'];
                        $accept = acceptChallengeData($challenge_id, $gameData['id']);
                        echo $accept;
                        $acceptData = json_decode($accept, true);
                        if ($acceptData['success'] == 0) {
                            $data['error'] = 'Could not accept challenge at this time.  Please try again later.'; 
                        }
                    } else {
                        $data['error'] = 'Could not create a new game at this time.  Please try again later.';   
                    }
                } else {
                    $data['error'] = 'Could not locate challenge.  Please try again later.';
                }
                $_SESSION['LAST_ACTIVITY'] = time();
                return returnJsonEncode($data);  
            }
        }
    }

    function createGameBoard($size) {       
        $rows = $size;
        $cols = $size+1;
        $num = ($size * 2)-1;
        $boardstring = "";
        for($i = 1; $i<=$num; $i++) {
            if(($i%2) == 0) {
                $boardstring .= "|";
                for($j = 0; $j<$cols; $j++) {
                    $boardstring .= "0";   
                }
            } else {
                if($i!=1) {
                    $boardstring .= "|";
                }
                for($j = 0; $j<$rows; $j++) {
                     $boardstring .= "0";   
                }                
            }
        }
//        echo nl2br($boardstring." \n");
        return $boardstring;
    }


    

    function declineChallenge($myData){
        $challenge_id = sanitize($myData);
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
                $_SESSION['LAST_ACTIVITY'] = time();
                
               return declineChallengeData($challenge_id);
            }
        }
    }



     function getGames(){
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
               return getGamesData($_SESSION['player_id']);
            }
        }
        
	}

  
	
?>








