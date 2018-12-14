<?php
	//include dbInfo
	require_once("../../dbInfoPS.inc");
	require_once('./BizDataLayer/exception.php');

	
	function getChatData($room){
		global $conn; 
		$sql = "SELECT message, timestamp, username FROM chat join players on chat.sender = players.player_id WHERE game_id = ?";
		
		try{
			if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('i', $room);
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
	function sendChatData($id, $message,$room){
		global $conn; 
        
        try{
            $stmt = $conn->prepare("INSERT INTO chat (game_id, message, sender) VALUES (?, ?, ?)"); 
            $stmt->bind_param('isi',$room, $message, $id);
            return returnJsonInsert($stmt, $conn);
               
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }
?>