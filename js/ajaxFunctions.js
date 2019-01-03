//////////////ajax //////////////////
// use - ajax.ajaxCall(), ajax.changeServerTurnAjax()...
var ajax = {
	////ajax util/////
	//d is data sent, looks like {name:value,name2:val2}
	////////////////
	ajaxCall: function (GetPost, d) {
		return $.ajax({
			type: GetPost,
			async: true,
			cache: false,
			url: "mid.php",
			data: d,
			dataType: "json"
		});
	},
		////initGameAjax/////
	//d is data sent, looks like {name:value,name2:val2}
	//this is my starter call
	//goes out and gets all pertinant information about the game (FOR ME)
	////////////////
	initGameAjax: function (whatMethod, val){
		//data is gameId
        MyXHR('POST',{method:whatMethod,a:'game',data:val}).done(function(jsonObj){
//            console.log(json);
			turn = jsonObj[0].turn;
			if (player == jsonObj[0].player0_name) {
                player = jsonObj[0].player0_name;
				playerId = 0;
                id_me = jsonObj[0].player0;
                player2 = jsonObj[0].player1_name;
                oppId = 1;
                id_opp = jsonObj[0].player0;
			} else {
                player = jsonObj[0].player1_name;
                playerId = 1;
                id_me = jsonObj[0].player1;
				player2 = jsonObj[0].player0_name;
                oppId = 0;
                id_opp = jsonObj[0].player0;
			}
            
			game.init();
//            console.log("OPPONENT "+jsonObj[0]['moves_'+oppId]);
//            console.log("MINE "+jsonObj[0]['moves_'+playerId]);
//
//            var opponentMoves = [];
//            var myMoves = [];
//             if(jsonObj[0]['moves_'+oppId]) {
//                opponentMoves = jsonObj[0]['moves_'+oppId].split("&");
//            }
//            if(jsonObj[0]['moves_'+playerId]) {
//                myMoves = jsonObj[0]['moves_'+playerId].split("&");
//            }
//            var numMoves = 0
//            if(myMoves.length > opponentMoves.length) {
//                numMoves = myMoves.length;
//            } else {
//                numMoves = opponentMoves.length;
//            }
//            if(playerId == 0) {
//                for(var i=0; i<numMoves-1; i++) {
//                    var mMoves = myMoves[i].split(",");
//                    console.log("My"+i+" "+mMoves);
//                    rules.updateMyBoard(mMoves[0], mMoves[1]);
//                    
//                    var oppMoves = opponentMoves[i].split(",");
//                    console.log("Opp"+i+" "+oppMoves);
//                    rules.updateBoard(oppMoves[0], oppMoves[1]);
//                }
//            } else {
//                for(var i=0; i<numMoves-1; i++) {
//                    
//                    var oppMoves = opponentMoves[i].split(",");
//                    console.log("Opp"+i+" "+oppMoves);
//                    rules.updateBoard(oppMoves[0], oppMoves[1]);
//                    
//                    var mMoves = myMoves[i].split(",");
//                    console.log("My"+i+" "+mMoves);
//                    rules.updateMyBoard(mMoves[0], mMoves[1]);
//                }
//            }
//            
            
            if(jsonObj[0]['moves_'+oppId]) {
                opponentMoves = jsonObj[0]['moves_'+oppId].split("&");
                    var sets = jsonObj[0]['moves_'+oppId].split("&");
                    for(var i=0; i<sets.length-1; i++) {
                        var moves = sets[i].split(",");
                        rules.updateBoard(moves[0], moves[1]);
                    }
            }
             if(jsonObj[0]['moves_'+playerId]) {
                    var mysets = jsonObj[0]['moves_'+playerId].split("&");
                    for(var i=0; i<mysets.length-1; i++) {
                        var moves = mysets[i].split(",");
                        rules.updateMyBoard(moves[0], moves[1]);
                    }
                }
     	});
        
	},
    	////changeServerTurnAjax/////
	//change the turn on the server
	//no callback
	////////////////
	changeServerTurnAjax: function (whatMethod, val) {
//        console.log("Change Server Turn");
        MyXHR('POST',{method:whatMethod,a:'game',data:val});

//		ajax.ajaxCall("POST", {method: whatMethod, a: "game", data: val});
		//change the color of the names to be the other guys turn
//		document.getElementById('youPlayer').setAttributeNS(null,'fill',"black");
//        document.getElementById('opponentPlayer').setAttributeNS(null,'fill',"green");
		document.getElementById('youPlayRect').setAttributeNS(null,'fill',"white");
        document.getElementById('oppPlayRect').setAttributeNS(null,'fill',"black");  
	},
	
	////changeBoardAjax/////
	//change the board on the server
	//no callback
	////////////////
	changeBoardAjax: function (pieceCoords, whatMethod, val) {
//        console.log("Change Board");
        MyXHR('POST',{method:whatMethod,a:'game',data:val+"~"+pieceCoords+"~"+playerId});

//        console.log(val+"~"+pieceCoords+"~"+playerId);
		//data: gameId~pieceCoords~playerId
//		ajax.ajaxCall("POST", {method: whatMethod, a: "game", data: val+"~"+pieceCoords+"~"+playerId});
	},

	
	////checkTurnAjax/////
	//check to see whose turn it is
	////////////////
	checkTurnAjax: function (whatMethod, val) {
		if(turn!=playerId) {
//            console.log("Check Turn");
        MyXHR('GET',{method:whatMethod,a:'game',data:val}).done(function(jsonObj){

//			ajax.ajaxCall("GET",{method:whatMethod,a:"game",data:val}).done(function(jsonObj){
				if(jsonObj[0].turn == playerId){
					//switch turns
                    if(turn != jsonObj[0].turn) {
                        
					   turn=jsonObj[0].turn;
//                        document.getElementById('youPlayer').setAttributeNS(null,'fill',"green");
//                        document.getElementById('opponentPlayer').setAttributeNS(null,'fill',"black");
		              document.getElementById('youPlayRect').setAttributeNS(null,'fill',"black");
                    document.getElementById('oppPlayRect').setAttributeNS(null,'fill',"white");  
					   ajax.getMoveAjax('getMove',gameId);
//                        ajax.clearOpponentMovesAjax('clearMoves', gameId);
                        
                    }
				}
			});
		}
		setTimeout( function() {ajax.checkTurnAjax('checkTurn', gameId)},3000);
	},
	
	////getMoveAjax/////
	//get the last move
	//-called after I find out it is my turn
	////////////////
	getMoveAjax:function(whatMethod,val){
        if(turn!=playerId) {
            MyXHR('GET',{method:whatMethod,a:'game',data:val}).done(function(json){

//            ajax.ajaxCall("GET",{method:whatMethod,a:"game",data:val}).done(function(json){
//                console.log("GET MOVE");

//                console.log(json);
//                if(playerId == 0) {
//                    game.p0_score = json[0]['score_0']; 
//                     document.getElementById('score0').firstChild.data=game.p0_score;
//                    game.p1_score = json[0]['score_1'];
//                     document.getElementById('score1').firstChild.data=game.p1_score;
//                 } else {
//                    game.p1_score = json[0]['score_1']; 
//                    game.p0_score = json[0]['score_1'];
//                    document.getElementById('score0').firstChild.data=game.p0_score;
//                     document.getElementById('score1').firstChild.data=game.p1_score;
//                }
//                       
          
                
                if(json[0]['moves_'+oppId]) {
                    var sets = json[0]['moves_'+oppId].split("&");
                    for(var i=0; i<sets.length-1; i++) {
                        var moves = sets[i].split(",");
                        rules.updateBoard(moves[0], moves[1]);
                    }
                }
            });
        }
        setTimeout( function() {ajax.getMoveAjax('getMove', gameId)},3000);
	},
    clearOpponentMovesAjax:function(whatMethod,val, id){
//        console.log("Clear Oppon");
        MyXHR('POST',{method:whatMethod,a:'game',data:val+"~"+oppId});

//        ajax.ajaxCall("POST", {method: whatMethod, a: "game", data: val+"~"+oppId});

    }, 
    addToScoreAjax:function(whatMethod,val){
//        console.log("Add To Score");
        MyXHR('POST',{method:whatMethod,a:'game',data:val+"~"+playerId});

        //TODO: Add one to score in database for your player
//        ajax.ajaxCall("POST", {method: whatMethod, a: "game", data: val+"~"+playerId});

     
    },
    addWinnerAjax:function(whatMethod, val, winnerId) {
//        console.log("Add Winner");
        MyXHR('POST',{method:whatMethod,a:'game',data:val+"~"+winnerId});

//        ajax.ajaxCall("POST", {method: whatMethod, a: "game", data: val+"~"+winnerId});
        //function addWinner($gameId, $playerId, $id){

    }

    
}