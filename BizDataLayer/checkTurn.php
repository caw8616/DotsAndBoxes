<?php
	//this is the end, the BizData Layer....
	//will always and ONLY be called from the service layer...
	
		//$gameId - the game Id
		//$userId - the user Id to check in the game if it is their turn
		
		function checkTurnData($gameId,$userId){
			//hard coded - will need to change to a db call eventually
			$t = '[{"gameId":55,"turn":"true"}]';
			return $t;
		}
?>