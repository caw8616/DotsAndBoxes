<?php
//include exceptions
require_once('BizDataLayer/exception.php');
require_once("../../dbInfoPS.inc");
//if we have gotten here - we know:
//-they have permissions to be here
//-we are ready to do something with the database
//-method calling these are in the svcLayer
//-method calling specific method has same name droping 'Data' at end checkTurnData() here is called by checkTurn() in svcLayer



/*************************
	startData
	
*/

function startData($gameId){
    
	global $conn;
//    $sql = "UPDATE game SET moves_0=null, moves_1=null, turn=0, num_moves=0, score_0=0, score_1=0, winner=-1 WHERE game_id=?";
//	try{
//		if($stmt=$conn->prepare($sql)){
//			$stmt->bind_param("i",$gameId);
//			$stmt->execute();
//			$stmt->close();
//		}else{
//        	throw new Exception("An error occurred while setting up data");
//        }
//	}catch (Exception $e) {
//        log_error($e, $sql, null);
//		return false;
//    }
	//get the init of the game
	$sql = "SELECT * FROM game WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			//bind parameters for the markers (s - string, i - int, d - double, b - blob)
			$stmt->bind_param("i",$gameId);
			$data=returnJson($stmt);
            $stmt->close();
//            $conn->close();
            if(json_decode($data, true)[0]['state'] == 'created') {
                updateToStarted($gameId);
            } else {
               $conn->close(); 
            }
            
			return $data;
		}else{
            throw new Exception("An error occurred while fetching record data");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
//    $conn->close();
}

function updateToStarted($gameId) {
    global $conn;
    $sql = "UPDATE game SET state='started' WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("i",$gameId);
			$stmt->execute();
//			$stmt->close();
            $conn->close();
		}else{
        	throw new Exception("An error occurred while setting up data");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
}

function restartData($gameId) {
    global $conn;
    $sql = "UPDATE game SET moves_0=null, moves_1=null, turn=0, num_moves=0, score_0=0, score_1=0, winner=-1 WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("i",$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while setting up data");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
}
/*************************
	checkTurnData
*/
function checkTurnData($gameId){
	global $conn;
	$sql="SELECT turn FROM game WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("i",$gameId);
			$data=returnJson($stmt);
//			$stmt->close();
            $conn->close();
			return $data;
		}else{
        	throw new Exception("An error occurred while checking turn");
        }
    }catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
//    $conn->close();
}
/*************************
	changeTurnData
*/
function changeTurnData($gameId){
	global $conn;
	//ugly, but toggle the turn (if the turn was 0, then make it 1, else make it 0)
	try{
		if($stmt=$conn->prepare("UPDATE game SET turn='2' WHERE game_id=? AND turn='0'")){
			$stmt->bind_param("i",$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while changing turn, step 1");
        }
		if($stmt=$conn->prepare("UPDATE game SET turn='0' WHERE game_id=? AND turn='1'")){
			$stmt->bind_param("i",$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while changing turn, step 2");
        }
		if($stmt=$conn->prepare("UPDATE game SET turn='1' WHERE game_id=? AND turn='2'")){
			$stmt->bind_param("i",$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while changing turn, step 3");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
	$conn->close();
}
/*************************
	changeBoardData
*/
 function changeBoardData($gameId,$pieceCoords,$playerId, $num){
	//update the board
	global $conn;
	$sql="UPDATE game SET num_moves=?, moves_".$playerId."=? WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("isi",$num, $pieceCoords,$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while changeBoard");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
	$conn->close();
}

 function clearMovesData($gameId,$playerId){
	//update the board
	global $conn;
	$sql="UPDATE game SET moves_".$playerId."=null WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("i",$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while changeBoard");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
	$conn->close();
}
/*************************
	getMoveData
*/
function getMoveData($gameId){
	global $conn;
	$sql="SELECT * FROM game WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("i",$gameId);
			$data=returnJson($stmt);
            $stmt->close();
			return $data;
		}else{
			throw new Exception("An error occurred while getMoveData");
		}
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
    $conn->close();
}

function addScoreData($gameId, $playerId, $score){
	global $conn;
	$sql="UPDATE game SET score_".$playerId."=? WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("ii", $score,$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while addScore");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
	$conn->close();
}

function addWinnerData($gameId, $id){
    //set winner to id number (OR MAYBE 0 or 1)
	//active=0
    global $conn;
	$sql="UPDATE game SET state='completed', winner=? WHERE game_id=?";
	try{
		if($stmt=$conn->prepare($sql)){
			$stmt->bind_param("ii", $id,$gameId);
			$stmt->execute();
			$stmt->close();
		}else{
        	throw new Exception("An error occurred while addScore");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
	$conn->close();
}












/*********************************Utilities*********************************/
/*************************
	returnJson
	takes: prepared statement
		-parameters already bound
	returns: json encoded multi-dimensional associative array
*/
function returnJson ($stmt){
	$stmt->execute();
	$stmt->store_result();
 	$meta = $stmt->result_metadata();
    $bindVarsArray = array();
	//using the stmt, get it's metadata (so we can get the name of the name=val pair for the associate array)!
	while ($column = $meta->fetch_field()) {
    	$bindVarsArray[] = &$results[$column->name];
    }
	//bind it!
	call_user_func_array(array($stmt, 'bind_result'), $bindVarsArray);
	//now, go through each row returned,
	while($stmt->fetch()) {
    	$clone = array();
        foreach ($results as $k => $v) {
        	$clone[$k] = $v;
        }
        $data[] = $clone;
    }
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	//MUST change the content-type
	header("Content-Type:text/plain");
	// This will become the response value for the XMLHttpRequest object
    return json_encode($data);
}

?>

