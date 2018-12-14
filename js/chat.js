     function validateChat(message) {
            var errors = "";
            if(message == ""){
                errors = errors+"<p>You must fill out the message Field!</p>";
            } else {
                if(!inRange(message, 1, 120)) {
                    errors = errors+"<p>Your message must be between 1 and 120 characters!</p>";
                }
                if(!alphanumericPunct(message)) {
                    errors = errors+"<p>Messages can only consist of letters, numbers and certain symbols!</p>";
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