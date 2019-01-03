//////////////////////////////////////////////////////
// Class: Dot										//
// Description:  This will create a cell object		// 
// (board square) that you can reference from the 	//
// game. 											//
// Arguments:										//
//		size - tell the object it's width & height	//
//		??
//		??
//		??
//		??
//////////////////////////////////////////////////////
//Dot constructor()
function Dot(parent,id,size,row,col, num){
	this.parent=parent;
	this.id=id;
	this.size=size;
	this.row=row;
	this.col=col;
    this.cx = this.size*this.col;
    this.cy = this.size*this.row;
    this.num = num;
//    this.cx = this.size*this.row;
//    this.cy = this.size*this.col;
	this.object=this.create();
	this.parent.appendChild(this.object);
    this.myBBox = this.getMyBBox();
}


//////////////////////////////////////////////////////
// Dot : Methods									//
// Description:  All of the methods for the			// 
// Dot Class (remember WHY we want these to be		//
// seperate from the object constructor!)			//
//////////////////////////////////////////////////////
Dot.prototype={
	create:function(){
//        <circle cx="25px" cy="25px" r="8px" fill="black" stroke="black"/>
        var dot = document.createElementNS(game.svgns, 'circle');
        dot.setAttributeNS(null,'cx',this.cx+'px');
		dot.setAttributeNS(null,'cy',this.cy+'px');
        dot.setAttributeNS(null,'r','10px');
        dot.setAttributeNS(null,'fill','black');
		dot.setAttributeNS(null,'id',this.id);
//		dot.onclick=function(){alert(this.id);};
        dot.onmousedown=function(){ drag.setDraw(this.id);};
        
		return dot;
	},
	//get CenterX
	getCenterX:function(){
		return (game.BOARDX+this.cx);
	},
	//get CenterY
	getCenterY:function(){
		return (game.BOARDY+this.cy);
	},
    getRow: function(){
        return this.row;
    },
    getCol: function() {
        return this.col;
    },
    getMyNum: function() {
		return this.num;
    },
    getMyBBox: function() {
		return this.object.getBBox();
    },
	PI:3.1415697
}