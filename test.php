
<?php
require "../../dbInfoPS.inc";
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
            if(json_decode($data, true)[0]['state']) {
                updateToStarted($gameId);
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
			$stmt->close();
            $conn->close();
		}else{
        	throw new Exception("An error occurred while setting up data");
        }
	}catch (Exception $e) {
        log_error($e, $sql, null);
		return false;
    }
}
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

echo startData(19);

?>