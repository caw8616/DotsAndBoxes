///////////////////////////////Utilities////////////////////////////////////////
//	use - util.getPiece(), util.getTransform(), etc...
var util={
	////get Piece/////
	//	get the piece (object) from the id and return it...
	//	id looks like "piece_0|3"
	////////////////
//	getPiece:function(id){
//		return game.pieceArr[parseInt(id.substr((id.search(/\_/)+1),1))][parseInt(id.substring((id.search(/\|/)+1),id.length))];
//	},
    getHorizLine:function(id){
    	return game.horizLines[parseInt(id.substring((id.search(/\|/)+1),id.length))][id.substring(id.search(/\|/)+1)];
	},
    getVertLine:function(id){
    	return game.vertLines[parseInt(id.substring((id.search(/\|/)+1),id.length))][id.substring(id.search(/\|/)+1)];
	},
    getDot:function(id){
        return game.dotArr[parseInt(id.substring((id.search(/\|/)+1),id.length))][parseInt(id.substr((id.search(/\_/)+1),1))];
	},

		
	////get Transform/////
	//	look at the id of the piece sent in and work on it's transform
	////////////////
	getTransform:function(id){
        var dot = util.getDot(id);
        retVal[0] = dot.getCenterX();
        retVal[1] = dot.getCenterY();
		return retVal;
	},

	////change turn////
	//	change who's turn it is
	//////////////////
	changeTurn:function(){
		//locally
		turn=Math.abs(turn-1);
		//how about for the server (and other player)?
		//send JSON message to server, have both clients monitor server to know who's turn it is...
		//document.getElementById('output2').firstChild.data='playerId '+playerId+ ' turn '+turn;
		ajax.changeServerTurnAjax('changeTurn',gameId);
	},
     orderDots: function(firstDot, newDot) {
        var nextNum = newDot.getMyNum();
        var firstNum = firstDot.getMyNum();
        var dots = new Array();
        
        if(firstNum< nextNum) {
            dots[0] = firstDot;
            dots[1] = newDot;
        } else {
            dots[0] = newDot;
            dots[1] = firstDot;
        }
        return dots;
    },
    

	/////////////////////////////////Messages to user/////////////////////////////////
	////nytwarning (not your turn)/////
	//	tell player it isn't his turn!
	////////////////
	nytwarning:function(){
		if(document.getElementById('nyt').getAttributeNS(null,'display') == 'none'){
			document.getElementById('nyt').setAttributeNS(null,'display','inline');
			setTimeout(util.nytwarning,2000);
		}else{
			document.getElementById('nyt').setAttributeNS(null,'display','none');
		}
	},

	////nypwarning (not your piece)/////
	//	tell player it isn't his piece!
	////////////////
	nypwarning:function(){
		if(document.getElementById('nyp').getAttributeNS(null,'display') == 'none'){
			document.getElementById('nyp').setAttributeNS(null,'display','inline');
			setTimeout(util.nypwarning,2000);
		}else{
			document.getElementById('nyp').setAttributeNS(null,'display','none');
		}
	}
}