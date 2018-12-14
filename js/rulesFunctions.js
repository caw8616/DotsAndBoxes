var rules={
    dot_1:'',	
    row_1:'',
    col_1:'',
    dot_2:'',
    row_2:'',
    col_2:'',
    orientation:'',
    piece:'',
    box:'',
    hasSquare: false,
    hitSuccessful: false,
    playId:'',
  
    
    clearVars:function(){
        this.dot_1 = '';	
        this.row_1 = '';
        this.col_1 = '';
        this.dot_2 = '';
        this.row_2 = '';
        this.col_2 = '';
        this.orientation = '';
        this.piece = '';
        this.box ='';
        this.hasSquare = false;
        this.hitSuccessful = false;
        this.playId ="";
    },
	checkHit:function(x,y,id){
        
        rules.clearVars();
		x=x-game.BOARDX;
		y=y-game.BOARDY;	
        var firstDot = util.getDot(id);
        var firstRow  = firstDot.getRow();
        var firstCol  = firstDot.getCol();
        var secondDot;
        //Check left
        if(firstCol != 0) {
            var newDot = game.dotArr[firstCol-1][firstRow];
            var drop = newDot.myBBox;
            if(x>drop.x && x<(drop.x+drop.width) && y>drop.y && y<(drop.y+drop.height)) {
                secondDot = newDot;
                this.orientation = "h";
                this.hitSuccessful = true;
            }
        }
        //Check Top
        if(firstRow != 0) {
            var newDot = game.dotArr[firstCol][firstRow-1];
             var drop = newDot.myBBox;
            if(x>drop.x && x<(drop.x+drop.width) && y>drop.y && y<(drop.y+drop.height)) { 
                secondDot = newDot;
                this.orientation = "v";
                this.hitSuccessful = true;
            }
        }
        //Check Right
        if(firstCol != game.BOARDSIZE) {
            var newDot = game.dotArr[firstCol+1][firstRow];
            var drop = newDot.myBBox;
            if(x>drop.x && x<(drop.x+drop.width) && y>drop.y && y<(drop.y+drop.height)) { 
                secondDot = newDot;
                this.orientation = "h";
                this.hitSuccessful = true;
            }
        }
        //Check Bottom
        if(firstRow != game.BOARDSIZE) {
            var newDot = game.dotArr[firstCol][firstRow+1];
            var drop = newDot.myBBox;
            if(x>drop.x && x<(drop.x+drop.width) && y>drop.y && y<(drop.y+drop.height)) { 
                secondDot = newDot;
                this.orientation = "v";
                this.hitSuccessful = true;
            }
        }
        
        if(this.hitSuccessful == true) {
            var dots = util.orderDots(firstDot, secondDot);
            rules.setDots(dots[0], dots[1]);
            if (!rules.checkOccupied()) {
                this.playId = playerId;
                rules.successfulDrop();
                  if(rules.checkForSquare()) {
                        ajax.addToScoreAjax("addScore",gameId);
//                        if(this.playId == playerId) {
//                            ajax.changeBoardAjax(this.dot_1.id+","+this.dot_2.id,"changeBoard", gameId);
//                        }
                        if(rules.checkForWin()) {
                            var winner = rules.checkForWinner();
                            if(winner == playerId) {
                                document.getElementById("result").firstChild.data="GAME OVER! YOU WON! :)";

                                ajax.addWinnerAjax("addWinner", gameId, id_me);
                            } else if(winner == oppId) {
                                document.getElementById("result").firstChild.data="GAME OVER! YOU LOST! :(";

                                ajax.addWinnerAjax("addWinner", gameId, id_them);
                            } else {
                                document.getElementById("result").firstChild.data="GAME OVER! YOU TIED! :/";

                                ajax.addWinnerAjax("addWinner", gameId, 0);
                            }
                        } else {
                             if(this.playId == playerId) {
                            ajax.changeBoardAjax(this.dot_1.id+","+this.dot_2.id,"changeBoard", gameId);
                            }
                        }
                    } else {
                        if(this.playId == playerId) {
                            ajax.changeBoardAjax(this.dot_1.id+","+this.dot_2.id,"changeBoard", gameId);
                            util.changeTurn();
                        }
                    }
                return true;
            }
        }
        
		return false;
//        return this.hitSuccessful;
	},
    updateBoard:function(id_1,id_2){
        rules.clearVars();
        
        var firstDot = util.getDot(id_1);
        var newDot = util.getDot(id_2);
        
        if(firstDot.getRow() == newDot.getRow()) {
            this.orientation = "h";
        } else {
            this.orientation = "v";
        }
//            game.horizLines[this.row_1][this.col_1+"|"+this.col_2]
        var dots = util.orderDots(firstDot, newDot);
        rules.setDots(dots[0], dots[1]);
        if (!rules.checkOccupied()) {
            this.playId = oppId;
            rules.successfulDrop();
            if(rules.checkForSquare()) {
                if(rules.checkForWin()) {
                    var winner = rules.checkForWinner();
                    if(winner == playerId) {
                        document.getElementById("result").firstChild.data="GAME OVER! YOU WON! :)";
                    } else if(winner == oppId) {
                        document.getElementById("result").firstChild.data="GAME OVER! YOU LOST! :(";
                    } else {
                        document.getElementById("result").firstChild.data="GAME OVER! YOU TIED! :/";
                    }
                }
            } 
            return true;
        }
 
		return false;
	},

    setDots(first, second) {
        this.dot_1 = first;
        this.row_1 = first.getRow();
        this.col_1 = first.getCol();
        this.dot_2 = second;
        this.row_2 = second.getRow();
        this.col_2 = second.getCol();
    },
    
   
    checkOccupied: function() {        
        if(this.orientation == "h") {
            if(game.horizLines[this.row_1][this.col_1+"|"+this.col_2]) {
                    return true;
            }
        } else {
             if(game.vertLines[this.col_1][this.row_1+"|"+this.row_2]) {
                    return true;
            }
        }
        return false;
    },
    
     successfulDrop: function() {         
        if(this.orientation == "h") {
             game.horizLines[this.row_1][this.col_1+"|"+this.col_2]=new Piece('game_'+gameId,this.playId,this.orientation,this.dot_1,this.dot_2,'Connector');
        } else {
            game.vertLines[this.col_1][this.row_1+"|"+this.row_2]=new Piece('game_'+gameId,this.playId,this.orientation,this.dot_1,this.dot_2,'Connector');
        }
        drag.removeLine();

    },
    checkForSquare: function() {
//        rules.printVars();
        var hasSquare = false;
        if(this.orientation == "h") {
            if(this.row_1 == 0) {
                if(rules.checkBottomSquare()) {
                    hasSquare = true;
                    var topLeft = rules.getTopLeft("bottom");
                    rules.captureBox(topLeft);
                } 
            } else if(this.row_1 == game.BOARDSIZE) {
                if(rules.checkTopSquare()) {
                    hasSquare = true;
                    var topLeft = rules.getTopLeft("top");
                    rules.captureBox(topLeft);
                } 

            } else {
                if(rules.checkBottomSquare()) {
                    hasSquare = true;
                    var topLeft = rules.getTopLeft("bottom");
                    rules.captureBox(topLeft);
                } 
                if(rules.checkTopSquare()) {
                    hasSquare = true;
                    var topLeft = rules.getTopLeft( "top");
                    rules.captureBox(topLeft);
                }
            }
        } else {
            //is vertical
            if(this.col_1 == 0) {
               if(rules.checkRightSquare()) {
                   hasSquare = true;
                   var topLeft = rules.getTopLeft("right");
                   rules.captureBox(topLeft);
               } 
            
            } else if(this.col_1 == game.BOARDSIZE) {
                if(rules.checkLeftSquare()) {
                    hasSquare = true;
                    var topLeft = rules.getTopLeft("left");
                    rules.captureBox(topLeft);
                } 
                
            } else {
                if(rules.checkRightSquare()) {
                    hasSquare = true;
                   var topLeft = rules.getTopLeft("right");
                    rules.captureBox(topLeft);
               } 
                
                if(rules.checkLeftSquare()) {
                    hasSquare = true;
                    var topLeft = rules.getTopLeft("left");
                    rules.captureBox(topLeft);
                } 
            }    
        }

        this.hasSquare = hasSquare;
        return hasSquare;
    },
    
    
     checkTopSquare: function() {
        if(rules.squareChecker((this.row_1-1), (this.col_1), (this.row_2),(this.col_2-1))) {
            if(rules.squareChecker((this.row_1-1), (this.col_1), (this.row_2-1),(this.col_2))) {
                if(rules.squareChecker((this.row_1-1), (this.col_1+1), (this.row_2),(this.col_2))){
                    return true;
                }
            }
        }
        return false;
    },
    checkBottomSquare: function() {
        if(rules.squareChecker((this.row_1), (this.col_1), (this.row_2+1),(this.col_2-1))) {  
            if(rules.squareChecker((this.row_1+1), (this.col_1), (this.row_2+1),(this.col_2))) {
                if(rules.squareChecker((this.row_1), (this.col_1+1), (this.row_2+1),(this.col_2))){
                    return true;
                }
            }
        }
        return false;
    },
    checkLeftSquare: function() {
        if(rules.squareChecker((this.row_1), (this.col_1-1), (this.row_2-1),(this.col_2))) {   
            if(rules.squareChecker((this.row_1), (this.col_1-1), (this.row_2),(this.col_2-1))) {
                if(rules.squareChecker((this.row_1+1), (this.col_1-1), (this.row_2),(this.col_2))) {
                    return true;
                }
            }
        }
        return false;
    },
    checkRightSquare: function() {
        if(rules.squareChecker((this.row_1), (this.col_1), (this.row_2-1),(this.col_2+1))) {                
            if(rules.squareChecker((this.row_1), (this.col_1+1), (this.row_2),(this.col_2+1))) {
                if(rules.squareChecker((this.row_1+1), (this.col_1), (this.row_2),(this.col_2+1))) {
                    return true;
                }
            }
        }
        return false;
    },
    
     squareChecker(row_1, col_1, row_2, col_2) {

        if(row_1 == row_2) {
            if(game.horizLines[row_1][col_1+"|"+col_2]) {
                return true;
            }
        } else if(col_1 == col_2) {
            if(game.vertLines[col_1][row_1+"|"+row_2]) {
                return true;
            }
        }
            return false;
    },
    getTopLeft(squareType) {
        var coords = new Array();
        switch(squareType) {
            case "top":
                coords[0] = this.row_1-1;
                coords[1] = this.col_1;
                break;
            case "bottom":
                coords[0] = this.row_1;
                coords[1] = this.col_1;
                break;
            case "left":
                coords[0] = this.row_1;
                coords[1] = this.col_1-1;
                break;
            case "right":
                coords[0] = this.row_1;
                coords[1] = this.col_1;
                break;
            default:
                coords[0]=-1;
                coords[1]=-1;
                break;
        }
        return coords;
    },
    captureBox(coords) {
        var box = game.boardArr[coords[1]][coords[0]];
        box.isCaptured(this.playId);
        rules.addScore();
        box.colorMe();
        //add to score
    },
    addScore() {
        if(this.playId == 0) {
            game.p0_score +=1;
            document.getElementById('score0').firstChild.data=game.p0_score;
            
            
        } else {
            game.p1_score +=1;
            document.getElementById('score1').firstChild.data=game.p1_score;
        }
        if(this.playId == playerId) {
            ajax.changeBoardAjax(this.dot_1.id+","+this.dot_2.id,"changeBoard", gameId);
        }
    },
    checkForWin: function() { 
        if((game.p0_score+game.p1_score) == (game.BOARDSIZE*game.BOARDSIZE)) {
            return true;
        } 
        return false;
    },
    checkForWinner() {
        if(game.p0_score == game.p1_score) {
            return 2;
        } else if(game.p0_score > game.p1_score) {
            return 0;
        } else if(game.p0_score < game.p1_score) {
            return 1;
        }
    }
    
  

}
