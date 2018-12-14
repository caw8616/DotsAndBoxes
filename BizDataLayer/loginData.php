<?php
    require_once("../../dbInfoPS.inc");
    //include exceptions
    require_once('./BizDataLayer/exception.php');

    function checkPlayerData($username) {
        global $conn; //I have to pull in the defined 
        try{
            $sql="SELECT * FROM players where username=?";
            if($stmt=$conn->prepare($sql)){
                $stmt->bind_param('s', $username);
                return returnJsonFetch($stmt);
                $stmt->close();
                $conn->close();
            }else if(!$data){
                throw new Exception("an error occured in the db hookup");
            }

        } catch(Exception $e){
            return $e;
        }
    }

    function createPlayerData($username, $password, $firstName, $lastName) {
        global $conn; //I have to pull in the defined variable 

        try{
            $stmt = $conn->prepare("INSERT INTO players (username, password, first_name, last_name, room, online) VALUES (?, ?, ?, ?, 0, 1)");
            $stmt->bind_param('ssss', $username, $password, $firstName, $lastName);
             return returnJsonInsert($stmt, $conn);
               
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }
  function loginData($id) {
        global $conn; //I have to pull in the defined variable 
        try{
            $stmt = $conn->prepare("UPDATE players SET room=0,online=1 WHERE player_id=?");
            $stmt->bind_param('i', $id);
            
            $stmt->execute();

            if($stmt ->affected_rows == 1) {
                return true;
            } else {
                return false;
            }
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }

    function logoutData($id) {
        global $conn; //I have to pull in the defined variable 

        try{
            $stmt = $conn->prepare("UPDATE players SET room=-1,online=0 WHERE player_id=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            if($stmt ->affected_rows == 1) {
                return true;
            } else {
                return false;
            }               
            $stmt->close();
            $conn->close();
        } catch(Exception $e){
            return $e;
        }
    }
    
?>
