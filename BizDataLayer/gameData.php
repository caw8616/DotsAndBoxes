<?php
	require_once("../../dbInfoPS.inc");
	require_once('./BizDataLayer/exception.php');

    function getPlayersOnData($id){
		global $conn; 
		$sql = "SELECT player_id, username, room FROM players WHERE online = 1 and player_id != ?";
        
//        $sql = "SELECT player_id, username, room, challenge_id  FROM players LEFT JOIN challenges on players.player_id = challenges.challenged WHERE online = 1 and player_id != ? and status = 0";
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('i', $id);
				return returnJsonFetch($stmt);
				$stmt->close();
				$conn->close();
			}else if(!$data){
				throw new Exception("an error occured in the db hookup");
			}
		}catch (Exception $e){
			log_error($e, $sql, null);
			//return false;
			return 'fail';
		}
	}

    function getPlayerNameData($id){
		global $conn; 
		$sql = "SELECT username FROM players WHERE  player_id = ?";
        
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('i', $id);
				return returnJsonFetch($stmt);
				$stmt->close();
				$conn->close();
			}else if(!$data){
				throw new Exception("an error occured in the db hookup");
			}
		}catch (Exception $e){
			log_error($e, $sql, null);
			//return false;
			return 'fail';
		}
	}
    function getChallengesData($id) {
        global $conn; 
        $sql = "SELECT challenge_id, timestamp, challenger, challenged, first_name, last_name, online, room, board_size, status, username FROM challenges JOIN players on challenges.challenger = players.player_id WHERE challenged = ? and status = 'created' ORDER BY timestamp";
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('i', $id);
				return returnJsonFetch($stmt);
				$stmt->close();
				$conn->close();
			}else if(!$data){
				throw new Exception("an error occured in the db hookup");
			}
		}catch (Exception $e){
			log_error($e, $sql, null);
			//return false;
			return 'fail';
		}
    }
    function getChallengedData($id) {
        global $conn; 
        $sql = "SELECT challenge_id, timestamp, challenger, challenged, first_name, last_name, online, room, board_size, status, username FROM challenges JOIN players on challenges.challenged = players.player_id WHERE challenger = ? and status = 'created' ORDER BY timestamp";
        
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('i', $id);
				return returnJsonFetch($stmt);
				$stmt->close();
				$conn->close();
			}else if(!$data){
				throw new Exception("an error occured in the db hookup");
			}
		}catch (Exception $e){
			log_error($e, $sql, null);
			//return false;
			return 'fail';
		}
    }
  
//    function getChallengesData($id) {
//        global $conn; 
//        $sql = "SELECT challenge_id, timestamp, challenger, challenged, first_name, last_name, online, room, board_size, status, username FROM challenges JOIN players on challenges.challenger = players.player_id WHERE challenged = ? and status = 0 UNION SELECT challenge_id, timestamp, challenger, challenged, first_name, last_name, online, room, board_size, status, username FROM challenges JOIN players on challenges.challenged = players.player_id WHERE challenger = ? and status = 0 ORDER BY timestamp";
//		try{
//			if($stmt=$conn->prepare($sql)){
//                $stmt->bind_param('ii', $id, $id);
//				return returnJsonFetch($stmt);
//				$stmt->close();
//				$conn->close();
//			}else if(!$data){
//				throw new Exception("an error occured in the db hookup");
//			}
//		}catch (Exception $e){
//			log_error($e, $sql, null);
//			//return false;
//			return 'fail';
//		}
//    }

     function getThisChallengeData($challenge_id) {
        global $conn; 
        $sql = "SELECT * FROM challenges WHERE challenge_id = ? and status = 0";
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('i', $challenge_id);
				return returnJsonFetch($stmt);
				$stmt->close();
				$conn->close();
			}else if(!$data){
				throw new Exception("an error occured in the db hookup");
			}
		}catch (Exception $e){
			log_error($e, $sql, null);
			//return false;
			return 'fail';
		}
    }

    function challengePlayerData($challenger, $challenger_name,$challenged,$challenged_name,  $board_size){
        global $conn; //I have to pull in the defined variable 

        try{
            $stmt = $conn->prepare("INSERT INTO challenges (challenger, challenged, challenger_name, challenged_name, board_size, status, game_id) VALUES (?, ?, ?, ?, ?, 'created',0)");
            $stmt->bind_param('iissi', $challenger, $challenged,$challenger_name, $challenged_name, $board_size);
            return returnJsonInsert($stmt, $conn);
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }

 
     function acceptChallengeData($challenge_id, $game_id){
        global $conn; //I have to pull in the defined variable 

        try{
            $stmt = $conn->prepare("UPDATE challenges SET status = 'accepted', game_id=? WHERE challenge_id=?");
            $stmt->bind_param('ii', $game_id, $challenge_id);
            return returnJsonUpdate($stmt);
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }


    function declineChallengeData($challenge_id){
        global $conn; //I have to pull in the defined variable 

        try{
            $stmt = $conn->prepare("UPDATE challenges SET status = 'declined' WHERE challenge_id=?");
            $stmt->bind_param('i', $challenge_id);
            return returnJsonUpdate($stmt);
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }
    

    function createGameData($board_size, $player_1, $player1_name, $player_2, $player2_name, $board){
        global $conn; //I have to pull in the defined variable 
        try{
            $stmt = $conn->prepare("INSERT INTO game (board_size, player_0, player_1, player0_name, player1_name, num_moves,   turn, state, winner, board, score_0, score_1) VALUES (?, ?, ?, ?, ?, 0, 0, 'created',-1,?,0,0)");
            $stmt->bind_param('iiisss', $board_size, $player_1, $player_2, $player1_name, $player2_name, $board);
            return returnJsonInsert($stmt, $conn);
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }



    function getGamesData($id) {
        global $conn; 
//		$sql = "SELECT game_id, player_1, player_2, score_1, score_2, turn, winner, username FROM game JOIN players on game.player_2 = players.player_id WHERE player_1 = ? UNION SELECT game_id, player_1, player_2, score_1, score_2, turn, winner, username FROM game JOIN players on game.player_1 = players.player_id WHERE player_2 = ?";
        $sql ="SELECT * FROM game where player_0 = ? and state= 'created' OR state= 'started' UNION SELECT * FROM game where player_1 = ? and state= 'created' OR state= 'started'";
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('ii', $id, $id);
				return returnJsonFetch($stmt);
				$stmt->close();
				$conn->close();
			}else if(!$data){
				throw new Exception("an error occured in the db hookup");
			}
		}catch (Exception $e){
			log_error($e, $sql, null);
			//return false;
			return 'fail';
		}   

    }
    function getTurnData($id) {
        
    }

?>