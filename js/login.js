    
     function validateLogin(user, pass) {
            var errors = "";
            if((user == "") || (pass == "")){
                if(user == "") {
                    errors = errors+"<p>You must fill out the Username Field!</p>";
                }
                if (pass == "") {
                    errors = errors+"<p>You must fill out the Password Field!</p>";
                }  
            } else {
                if(!inRange(user, 5, 15)) {
                    errors = errors+"<p>Your Username must be between 5 and 15 characters!</p>";
                }
                 if(!inRange(pass, 8, 30)) {
                    errors = errors+"<p>Your Password must be between 8 and 30 characters!</p>";
                }
                if(!alphanumericPunct(user)) {
                    errors = errors+"<p>Username can only consist of letters, numbers and certain symbols!</p>";
                }
                if(!alphanumericPunct(pass)) {
                    errors = errors+"<p>Password can only consist of letters, numbers and certain symbols!</p>";
                }
            }
            if(errors == "") {
                return true;

            } else {
                $('#error').html(errors);
                console.log(errors);
                return false;
            }
       }
    function validateSignUp(user, pass, first, last) {
            var errors = "";
            if((user == "") || (pass == "") || (first == "") || (last == "")){
                if(user == "") {
                    errors = errors+"<p>You must fill out the Username Field!</p>";
                }
                if (pass == "") {
                    errors = errors+"<p>You must fill out the Password Field!</p>";
                }  
                if(first == "") {
                    errors = errors+"<p>You must fill out the First Name Field!</p>";
                }
                if (last == "") {
                    errors = errors+"<p>You must fill out the Last Name Field!</p>";
                } 
            } else {
                if(!inRange(user, 5, 15)) {
                    errors = errors+"<p>Your Username must be between 5 and 15 characters!</p>";
                }
                 if(!inRange(pass, 8, 30)) {
                    errors = errors+"<p>Your Password must be between 8 and 30 characters!</p>";
                }
                if(!inRange(first, 3, 15)) {
                    errors = errors+"<p>Your First Name must be between 3 and 15 characters!</p>";
                }
                if(!inRange(last, 3, 25)) {
                    errors = errors+"<p>Your Last Name must be between 3 and 25 characters!</p>";
                }
                if(!alphanumericPunct(user)) {
                    errors = errors+"<p>Username can only consist of letters, numbers and certain symbols!</p>";
                }
                if(!alphanumericPunct(pass)) {
                    errors = errors+"<p>Password can only consist of letters, numbers and certain symbols!</p>";
                }
                if(!alphaCheck(first)) {
                    errors = errors+"<p>First name can only consist of letters!</p>";
                }
                if(!alphaCheck(last)) {
                    errors = errors+"<p>Last name can only consist of letters!</p>";
                }
            }
            if(errors == "") {
                return true;

            } else {
                $('#error').html(errors);
                console.log(errors);
                return false;
            }
       }
 function logoutPlayer($myData) {
     include('BizDataLayer/loginData.php');

        return logoutData($myData);
    }