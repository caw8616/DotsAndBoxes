//////////////////////////////////////////////////////
// Class: Piece										//
// Description: Using the javascript prototype, you //
// can make faux classes. This allows objects to be //
// made which act like classes and can be referenced//
// by the game.										//
//////////////////////////////////////////////////////
// Piece constructor
// creates and initializes each Piece object
function Piece(board,player, orientation, dot_1,dot_2,type){
	this.board = board;			// piece needs to know the svg board object so that it can be attached to it.
    this.orientation = orientation;
	this.player = 0;		// piece needs to know what player it belongs to.
	this.type = type;			// piece needs to know what type of piece it is. (put in so it could be something besides a checker!)
    this.dot_1 = dot_1;	// piece needs to know what its current cell/location is.
    this.dot_2 = dot_2;
    if(orientation == 'h') {
        this.row = Math.min(dot_1.getRow(), dot_2.getRow());
        this.col = dot_1.getCol();
    } else {
        this.row = dot_1.getRow();
        this.col = Math.min(dot_1.getCol(), dot_2.getCol());
    }

	this.isCaptured = 0;	// a boolean to know whether the piece has been captured yet or not.
//	this.id = "piece_"+dot_1.num+ "|" +dot_2.num;
    if(orientation == "h") {
        this.id = "pieceh_"+dot_1.row+ "|" +dot_1.col+ "|"+dot_2.col;
    } else {
        this.id = "piecev_"+dot_1.col+ "|" +dot_1.row+ "|"+dot_2.row;

    }
	this.x1=this.dot_1.getCenterX();		// the piece needs to know what its x location value is.
	this.y1=this.dot_1.getCenterY();		
    this.x2=this.dot_2.getCenterX();		// the piece needs to know what its x location value is.
	this.y2=this.dot_2.getCenterY();	// the piece needs to know what its y location value is as well.

	this.object=new window[type](this);			// based on the piece type, you need to create the more specific piece object 
	this.piece = this.object.piece;					// a shortcut to the actual svg piece object
	this.setAtt("id",this.id);						// make sure the SVG object has the correct id value (make sure it can be 
    document.getElementsByTagName('svg')[0].appendChild(this.piece);

	return this;
}
	
Piece.prototype={

	putOnTop:function(){
		document.getElementsByTagName('svg')[0].removeChild(this.piece);
		document.getElementsByTagName('svg')[0].appendChild(this.piece);
	},
	// function that allows a quick setting of an attribute of the specific piece object
	setAtt:function(att,val){
		this.piece.setAttributeNS(null,att,val);
	}, 
	isCaptured:function(pieceId){
		this.isCaptured=pieceId;
	},
	notCaptured:function(){
		this.isCaptured=0;
	}
}

function Connector(parent) {
    this.parent = parent;		//I can now inherit from Piece class												// each Checker should know its parents piece object
	this.piece = document.createElementNS(game.svgns,"g");	// each Checker should have an SVG group to store its svg checker in
    if(this.isCaptured == false){
		this.piece.setAttributeNS(null,"style","cursor: pointer;");						// change the cursor
	}

		
	// create the svg 'checker' piece.
    var line = document.createElementNS(game.svgns,"line");
	line.setAttributeNS(null,"x1",parent.x1);
    line.setAttributeNS(null,"y1",parent.y1);
	line.setAttributeNS(null,"x2",parent.x2);
	line.setAttributeNS(null,"y2",parent.y2);
    line.setAttributeNS(null, "style", "stroke:rgb(0,0,0);stroke-width:2")
	line.setAttributeNS(null,"class",'player' + this.parent.player);
    
	this.piece.appendChild(line);							
	return this;
}



























//
////////////////////////////////////////////////////////
//// Class: Piece										//
//// Description: Using the javascript prototype, you //
//// can make faux classes. This allows objects to be //
//// made which act like classes and can be referenced//
//// by the game.										//
////////////////////////////////////////////////////////
//// Piece constructor
//// creates and initializes each Piece object
//function Piece(board,orientation,player,cellRow,cellCol,type,num){
//	this.board = board;			// piece needs to know the svg board object so that it can be attached to it.
//    this.orientation = orientation;
//	this.player = player;		// piece needs to know what player it belongs to.
//	this.type = type;			// piece needs to know what type of piece it is. (put in so it could be something besides a checker!)
//    this.dot_1 = game.dotArr[cellRow][cellCol];	// piece needs to know what its current cell/location is.
//    if(orientation == 'h') {
//        this.dot_2 = game.dotArr[cellRow][cellCol+1];	// piece needs to know what its current cell/location is.        
//    } else {
//        this.dot_2 = game.dotArr[cellRow+1][cellCol];
//    }
//
//	this.number = num;			// piece needs to know what number piece it is.
//	this.isCaptured = 0;	// a boolean to know whether the piece has been captured yet or not.
//	
//	this.id = "piece_" + this.player + "|" + this.number;	// the piece also needs to know what it's id is.
//	this.x1=this.dot_1.getCenterX();		// the piece needs to know what its x location value is.
//	this.y1=this.dot_1.getCenterY();		
//    this.x2=this.dot_2.getCenterX();		// the piece needs to know what its x location value is.
//	this.y2=this.dot_2.getCenterY();	// the piece needs to know what its y location value is as well.
//
//	this.object=new window[type](this);			// based on the piece type, you need to create the more specific piece object 
//	this.piece = this.object.piece;					// a shortcut to the actual svg piece object
//	this.setAtt("id",this.id);						// make sure the SVG object has the correct id value (make sure it can be 
//    document.getElementsByTagName('svg')[0].appendChild(this.piece);
//
//	return this;
//}
//	
//Piece.prototype={
//	//change cell (used to move the piece to a new cell and clear the old)
////	changeCell:function(newCell,row,col){
////		this.current_cell.notOccupied();
////        document.getElementById('output').firstChild.nodeValue='dropped cell: '+newCell;
////		this.current_cell = game.boardArr[row][col];
////		this.current_cell.isOccupied(this.id);
////	},
////	//when called, will remove the piece from the document and then re-append it (put it on top!)
//	putOnTop:function(){
//		document.getElementsByTagName('svg')[0].removeChild(this.piece);
//		document.getElementsByTagName('svg')[0].appendChild(this.piece);
//	},
//	// function that allows a quick setting of an attribute of the specific piece object
//	setAtt:function(att,val){
//		this.piece.setAttributeNS(null,att,val);
//	}, 
//	isCaptured:function(pieceId){
//		this.isCaptured=pieceId;
//	},
//	notCaptured:function(){
//		this.isCaptured=0;
//	},
//}
//
//function Connector(parent) {
//    this.parent = parent;		//I can now inherit from Piece class												// each Checker should know its parents piece object
//	this.piece = document.createElementNS(game.svgns,"g");	// each Checker should have an SVG group to store its svg checker in
//    if(this.isCaptured == false){
//		this.piece.setAttributeNS(null,"style","cursor: pointer;");						// change the cursor
//	}
////    this.piece.setAttributeNS(null,"transform","translate("+this.parent.x+","+this.parent.y+")");	
//		
//	// create the svg 'checker' piece.
//    var line = document.createElementNS(game.svgns,"line");
//	line.setAttributeNS(null,"x1",parent.x1);
//    line.setAttributeNS(null,"y1",parent.y1);
//	line.setAttributeNS(null,"x2",parent.x2);
//	line.setAttributeNS(null,"y2",parent.y2);
//    line.setAttributeNS(null, "style", "stroke:rgb(0,0,0);stroke-width:2")
//	line.setAttributeNS(null,"class",'player' + this.parent.player);
//    
//	this.piece.appendChild(line);											// add the svg 'checker' to svg group
//	//create more circles to prove I'm moving the group (and to make it purty)
//
//
//	// return this object to be stored in a variable
//	return this;
//}
//
