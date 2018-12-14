function MyXHR(getPost,d,id){
     	//ajax shortcut
     	return $.ajax({
     		type: getPost,
     		async: true,
     		cache: false,
     		url:'mid.php',
     		data:d,
     		dataType:'json',
     		beforeSend:function(){
     			//turn on spinner if I have one
     			if(id){
     				$(id).append('<img src="path/spinner.gif" class="awesome"/>');
     			}
     		}
     	}).always(function(){
     		//kill spinner
     		if(id){
				$(id).find('.awesome').fadeOut(4000,function(){
					$(this).remove();
				});
			}
     	}).fail(function(err){
     		console.log(err);
     	});
     }

 function logout() {
        MyXHR('post',{method:'logMeOut',a:'login'}).done(function(json){
            if(json.success == 1) {                 window.location.replace("login.html");
            } else {
                $('h5').html(json.error);
            }
        });
    }