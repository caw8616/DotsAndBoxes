<?php
//include "DB.class.php";
//
//session_name("adminSession");
//session_start();
    function sanitize($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    function numeric($value){
        $reg = "/^[0-9.]+$/";
        return preg_match($reg, $value);
    }
    function alphabeticSpaceApost($value) {
        $reg = "/^[A-Za-z' ]+$/";
        return preg_match($reg,$value);
    }
    function alphabeticSpace($value) {
        $reg = "/^[A-Za-z ]+$/";
        return preg_match($reg,$value);
    }
    function decimal($value) {
        $reg = "/^[0-9]*\.[0-9]+$/";
        return preg_match($reg,$value);
    }
    function integer($value) {
        $reg = "/(^-?\d\d*$)/";
        return preg_match($reg,$value);
    }
    function alphabeticNumericPunct($value) {
        $reg = "/^[A-Za-z0-9 _.,!?\&\-\"']+$/";
        return( preg_match($reg,$value));
    }
    function alphaNumeric($value) {
        $reg = "/^[A-Za-z0-9]+$/";
        return( preg_match($reg,$value));
    }

    function validateBlank($data) {
        if($data == "") {
            return false;
        } else  {
            return true;
        }
    }
    function inRange($text, $min, $max) {
        if(strlen($text) < $min || strlen($text) > $max) {
            return false;
        } else {
            return true;
        }
    }

function returnJsonFetch($stmt) {
    $stmt->execute();
    $data =array();

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

function returnJsonInsert($stmt, $conn) {
    $stmt->execute();
    $data =array();
    if($stmt ->affected_rows == 1) {
        $data['success'] = 1;
        $data['id'] = $conn->insert_id;

    } else {
        $data['success'] = 0;
        $data['error'] = $stmt->error;
 
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

function returnJsonUpdate($stmt) {
    $stmt->execute();
    $data =array();
    if($stmt ->affected_rows == 1) {
        $data['success'] = 1;
    } else {
        $data['success'] = 0;
        $data['error'] = $stmt->error;

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

function returnJsonDelete($stmt) {
    $stmt->execute();
    $data =array();
    if($stmt ->affected_rows == 1) {
        $data['success'] = 1;
    } else {
        $data['success'] = 0;
        $data['error'] = $stmt->error;

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
function returnJsonEncode($data) {
   
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