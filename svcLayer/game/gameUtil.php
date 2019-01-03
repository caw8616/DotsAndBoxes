<?php

    require_once('BizDataLayer/gameBizData.php');

    session_name("playerSession");
    session_start();

/*************************
	start
	takes: 		gameId
	uses in bizLayer: gameBiz.php->startData
	returns:	gameInfo
				[{"game_id":38,"whoseTurn":1,"player0_name":"Dan","player0_pieceID":null,"player0_boardI":null,"player0_boardJ":null,"player1_name":"Fred","player1_pieceID":null,"player1_boardI":null,"player1_boardJ":null,"last_updated":"0000-00-00 00:00:00"}]
*/
    function start($d){
        //Should they be here?  (check)
        //if true:
        return startData($d);
    }
/*************************
	changeTurn
	takes: gameId
	uses in bizLayer: gameBiz.php->changeTurnData
	returns:	Nothing
*/
    function changeTurn($d){
        //can they change the turn?
        //if true:
        changeTurnData($d);
    }
/*************************
	checkTurn
	takes: gameId
	uses in bizLayer: gameBiz.php->checkTurnData
	returns:	whoseTurn
				[{"whoseTurn":1}]
*/
function checkTurn($d){
	//Can they check is it my turn yet?
	//if true:
	return checkTurnData($d);
	
}
/*************************
	changeBoard
	takes: gameId~pieceId~boardI~boardJ~playerId
	uses in bizLayer: gameBiz.php->changeBoardData
	returns:	Nothing
*/
function changeBoard($d){
//    echo "IN CHANGE BOARD";
	$h=explode('~',$d);
    $json = getMoveData($h[0]);
    $prev = json_decode($json, true)[0];
    $moves = $prev['moves_'.$h[2]].$h[1]."&";
    $moveNum = $prev['num_moves'];
    changeBoardData($h[0],$moves, $h[2] ,$moveNum+1);
}
/*************************
	getMove
	takes: gameId
	uses in bizLayer: gameBiz.php->getMoveData
	returns:	gameInfo
				[{"game_id":38,"whoseTurn":1,"player0_name":"Dan","player0_pieceID":"piece_0|10","player0_boardI":"6","player0_boardJ":"2","player1_name":"Fred","player1_pieceID":"piece_1|3","player1_boardI":"0","player1_boardJ":"2","last_updated":"0000-00-00 00:00:00"}]
*/
function getMove($d){
	//if it is my turn and I should be here, get the other players move	
	return getMoveData($d);
}

// function clearMovesData($gameId,$playerId){
function clearMoves($d){
	$h=explode('~',$d);
    clearMovesData($h[0],$h[1]);
}
//function addScore($gameId, $playerId, $score){
function addScore($d){
    $h=explode('~',$d);
    $json = getMoveData($h[0]);
    $prev = json_decode($json, true)[0];
    $score = $prev['score_'.$h[1]];
    
	addScoreData($h[0],$h[1], $score+1);
}

//function addWinnerData($gameId, $id){
    
function addWinner($d){
	 $h=explode('~',$d);
	addWinnerData($h[0],$h[1]);
}
?>