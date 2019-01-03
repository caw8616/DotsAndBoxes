
<?php
    session_name("playerSession");
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dots and Boxes</title>
	<style type="text/css">
		#background { fill: #DCDCDC; stroke: black; stroke-width: 2px; }
		.player0   {fill: blue; stroke: blue; stroke-width: 1px; }
		.player1 {fill: red; stroke: red; stroke-width: 1px; }
		.htmlBlock {position:absolute;top:200px;left:300px;width:200px;height:100px;background:#ffc;padding:10px;display:none;}
		body{padding:0px;margin:0px;}
		.box_white{fill:white;stroke-width:2px;stroke:white;}
        .box_0{fill:blue;stroke-width:2px;stroke:white;}
        .box_1{fill:red;stroke-width:2px;stroke:white;}

/*		.cell_black{fill:black;stroke-width:2px;stroke:red;}*/
		.cell_alert{fill:#336666;stroke-width:2px;stroke:red;}
		.name_black{fill:black;font-size:18px}
		.name_orange{fill:orange;font-size:24px;}
		text{pointer-events:none;user-select:none;}
         ul {
               list-style: none; 
              padding: 0;
          }

	</style>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
<!--        <script src="js/jquery-3.3.1.min.js"></script>-->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="js/utilities.js" type="text/javascript"></script>

    <script src="js/validate.js" type="text/javascript"></script>
    <script src="js/chat.js" type="application/javascript"></script>

	<script src="js/objects/Box.js" type="text/javascript"></script>
    <script src="js/objects/Dot.js" type="text/javascript"></script>
<!--    <script src="js/Objects/Line.js" type="text/javascript"></script>-->
	<script src="js/objects/Piece.js" type="text/javascript"></script>
	<script src="js/gameFunctions.js" type="text/javascript"></script>
    <script src="js/rulesFunctions.js" type="text/javascript"></script>
	<script src="js/utilFunctions.js" type="text/javascript"></script>
	<script src="js/ajaxFunctions.js" type="text/javascript"></script>
	<script type="text/javascript">
			var gameId=<?php echo $_GET['gameId'] ?>;
			var player="<?php echo $_SESSION['username']?>";
			//need line to start it all here....
			ajax.initGameAjax("start", gameId);
	       
        
	$(document).ready(function(){
        console.log(document.cookie);
        setRoom();
		getChat();
        document.getElementById("newChatForm").addEventListener('submit',function(event) {
            event.preventDefault();
            var message = document.getElementById('message').value;
            if(validateChat(message)) {
                sendChat(message);
            } 
	   },false);
     });
        
    function setRoom(){
//         console.log('getChat');
     	MyXHR('get',{method:'setRoom',a:'login',data:gameId}).done(function(json){
//            console.log(json);
            if(json.loggedOut) {
            window.location.replace("login.html");
            } 
               
     	});
     }   
     
     ////////////////
     // chat stuff
     ////////////////
     function getChat(){
     	MyXHR('get',{method:'getChatRoom',a:'chat', data:'<?php echo $_GET['gameId'] ?>'}).done(function(json){
//            console.log(json);
            if(json.loggedOut) {
            window.location.replace("login.html");
            } else {
                var h='';
                var m='';
                for(i=0;i<json.length;i++){
                    m+="<li><strong>"+json[i].username+':</strong> '+json[i].message+'</li>';
                }
//                for(i=0;i<json.length;i++){
//                    h+=json[i].username+': '+json[i].message+'<span style="color:gray"> at time '+json[i].timestamp+'</span><br/>';
//                }
//                $('#chats').html(h);
                
                $('#conversation').find("ul").html(m);
                setTimeout('getChat()',2000); 
            }
     	});
     }
    function sendChat(message){
        document.getElementById('message').value = "";

     	MyXHR('post',{method:'sendChatRoom',a:'chat',data:"<?php echo $_GET['gameId'] ?>|"+message}).done(function(json){
//            console.log(json);
            if(json.loggedOut) {
                window.location.replace("login.html");
            } else {
                if(json.success == 1) {
                    $('#conversation').find("ul").append("<li><strong>"+json[i].username+':</strong> '+json[i].message+'</li>');
                } else {
                   
                    $('#error').html(json.error);
                }
            }
     	});
     }
            
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8">
            <svg xmlns="http://www.w3.org/2000/svg" 
                version="1.1"
                 width="100%" height="600px">
                <!-- Make the background -> 800x600 -->
                <rect x="0px" y="0px" width="100%" height="100%" id="background"></rect>
                <rect x="55px" y="5px" width="240px" height="40px" id="youPlayRect" fill="black">
                    
                </rect>
                <text x="60px" y="20px" id="youPlayer">
                    You are:
                </text>
                <text x="60px" y="40px" id="youScore">
                    Score: 
                </text>
                <text x="110px" y="40px" id="score0">
                </text>
             
                <rect x="300px" y="5px" width="245px" height="40px" id="oppPlayRect" fill="black"></rect>
                <text x="305px" y="20px" id="opponentPlayer">
                    Opponent is:
                </text>
                
                <text x="305px" y="40px" id="oppScore">
                    Score: 
                </text>
                <text x="355px" y="40px" id="score1">
                </text>                    
                    
                <text x="210px" y="65px" id="result">
                </text>
                    
                <text x="270px" y="65px" id="nyt" fill="red" display="none">
                    NOT YOUR TURN!
                </text>

            </svg>
            </div>
            <div class="col-xs-6 col-md-4">
                  <div id="chatter">
                      <h3>Chats</h3>
            <!--          <div id="chats"></div>-->
                     <div class="pre-scrollable">

                    <div id="conversation"><ul></ul></div>
                      </div>
                      <br>
                     <br>
                      <div id="newChat">
                          <form name='newChat' id='newChatForm'>
                              <textarea name='message' id='message' rows="4" cols="50" maxlength="120"></textarea>
                              <br>
                              <br>
                              <input type='submit' value='Submit'/>
                          </form>
                            <div id="error"></div>
                      </div>
                  </div>
            </div>
        </div>
    </div>
        
</body>

</html>