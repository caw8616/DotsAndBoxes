var game={
    xhtmlns:"http://www.w3.org/1999/xhtml",
	svgns:"http://www.w3.org/2000/svg",
	BOARDX:75,				//starting pos of board
	BOARDY:75,				//look above
    boardArr:new Array(),	
	dotArr:new Array(),		//2d array [row][col]
	pieceArr:new Array(),		//2d array [player][piece] (player is either 0 or 1)
	BOARDSIZE:4,				//how many squares across
	CELLSIZE:110,
    vertLines:new Array(),
    horizLines:new Array(),
    p0_score:0,
    p1_score:0,
    

	init:function(){
		//create a parent to stick board in...
		var gEle=document.createElementNS(game.svgns,'g');
		gEle.setAttributeNS(null,'transform','translate('+game.BOARDX+','+game.BOARDY+')');

		gEle.setAttributeNS(null,'id','gId_'+gameId);
		//stick g on board
		document.getElementsByTagName('svg')[0].insertBefore(gEle,document.getElementsByTagName('svg')[0].childNodes[5]);
		//create the board...
		//var x = new Cell(document.getElementById('someIDsetByTheServer'),'cell_00',CELLSIZE,0,0);
        var rectEle=document.createElementNS(game.svgns,'rect');
		rectEle.setAttributeNS(null,'x','50px');
		rectEle.setAttributeNS(null,'y','50px');
		rectEle.setAttributeNS(null,'width','500px');
		rectEle.setAttributeNS(null,'height','500px');
        rectEle.setAttributeNS(null,'fill','white');
        rectEle.setAttributeNS(null,'stroke','black');
        rectEle.setAttributeNS(null,'stroke-width','1px');
        rectEle.setAttributeNS(null,'stroke-opacity','0.5');
		rectEle.setAttributeNS(null,'id','gIdBoard_'+gameId);
        document.getElementsByTagName('svg')[0].insertBefore(rectEle,document.getElementsByTagName('svg')[0].childNodes[5]);
        
        for(i=0;i<game.BOARDSIZE;i++){
			game.boardArr[i]=new Array();
			for(j=0;j<game.BOARDSIZE;j++){
				game.boardArr[i][j]=new Box(document.getElementById('gId_'+gameId),'box_'+j+"|"+i,game.CELLSIZE,j,i);
//                console.log(game.boardArr[i][j]);
			}
		}
        
        var idCount = 0;
        
        for(c=0;c<game.BOARDSIZE+1;c++){
			game.dotArr[c]=new Array();
			for(r=0;r<game.BOARDSIZE+1;r++){
//                console.log(idCount);
				game.dotArr[c][r]=new Dot(document.getElementById('gId_'+gameId),'dot_'+r+"|"+c,game.CELLSIZE,r,c, idCount);
                idCount++;
//                console.log(game.dotArr[c][r]);
			}
		}
         for(f=0;f<game.BOARDSIZE+1;f++){
			game.horizLines[f] = new Array();
            game.vertLines[f] = new Array();
		}

//		//put the drop code on the document...
		document.getElementsByTagName('svg')[0].addEventListener('mouseup',drag.releaseDraw,false);
//		//put the go() method on the svg doc.
		document.getElementsByTagName('svg')[0].addEventListener('mousemove',drag.drawLine,false);
//		//put the player in the text
		document.getElementById('youPlayer').firstChild.data+=player;
		document.getElementById('opponentPlayer').firstChild.data+=player2;
        document.getElementById('score0').firstChild.data+=this.p0_score;
		document.getElementById('score1').firstChild.data+=this.p1_score;
//		
//		console.log(turn);
//        console.log(playerId);
		//set the colors of whose turn it is
		if(turn==playerId){
			document.getElementById('youPlayer').setAttributeNS(null,'fill',"green");
			document.getElementById('opponentPlayer').setAttributeNS(null,'fill',"black");
		}else{
			document.getElementById('youPlayer').setAttributeNS(null,'fill',"black");
			document.getElementById('opponentPlayer').setAttributeNS(null,'fill',"green");
		}
	
		///////////////////////////////////
		//Find out who's turn it is?
		ajax.checkTurnAjax("checkTurn", gameId);
        ajax.getMoveAjax("getMove", gameId);
	}
    
}
			
/////////////////////////Dragging code/////////////////////////
var drag={
//	//the problem of dragging....
	myX:'',						//hold my last pos.
	myY:'',					//hold my last pos.
	mover:'',					//hold the id of the thing I'm moving
//	////setMove/////
//	//	set the id of the thing I'm moving...
//	////////////////
    
    setDraw:function(which){		
		drag.mover = which;
        
        var dot = util.getDot(which);
        myX = dot.getCenterX();
        myY  = dot.getCenterY();
        
	},
    drawLine:function(evt){		
		if(drag.mover != ''){
            if(document.getElementById("lineDraw")) {
                drag.removeLine()
            }
//            game.pieceArr[]
            /*FIGURE OUT HOW TO DO IT THROUGH THE DOT PROTOTYPE*/
            var line = document.createElementNS(game.svgns,"line");
	        line.setAttributeNS(null,"x1",myX);
            line.setAttributeNS(null,"y1",myY);
	        line.setAttributeNS(null,"x2",evt.layerX);
	        line.setAttributeNS(null,"y2",evt.layerY);
            line.setAttributeNS(null, "style", "stroke:rgb(200,0,0);stroke-width:2");
            line.setAttributeNS(null, "id", "lineDraw");
            document.getElementsByTagName('svg')[0].appendChild(line);

		}
	},
    releaseDraw:function(evt){
		if(drag.mover != ''){
			//is it YOUR turn?
			if(turn == playerId){
				var hit=rules.checkHit(evt.layerX,evt.layerY,drag.mover);
			}else{
				var hit=false;
				util.nytwarning();
			}
			if(hit==false){
                drag.removeLine();
			}
			drag.mover = '';	
		}
	},
				
    removeLine: function() {
        if(document.getElementById("lineDraw")) {
            document.getElementsByTagName('svg')[0].removeChild(document.getElementById("lineDraw"));
        }
    },

}



//                              dot_0|1
//                              piece_0|v1
//dot_1|0      piece_0|h3       dot_1|1          piece_0|h4        dot_1|2
//                              piece_0|v5
//                              dot_2|1






//                          box_1|1
