    function alphaCheck(text){ 
        var letters = /^[A-Za-z' ]+$/;
        if(letters.test(text)) {
           return true;
        } else {
            return false;
        }
    } 
    function alphanumeric(text){ 
        var letters = /^[0-9a-zA-Z]+$/;
    if(letters.test(text)) {
           return true;
        } else {
            return false;
        }
    } 
    function alphanumericPunct(text){ 
        var letters = /^[A-Za-z0-9 _.,!?\&\-\"']+$/;
        if(letters.test(text)) {
           return true;
        } else {
            return false;
        }
    } 
    function inRange(text,min, max){ 
        if(text.length < min || text.length > max) {
            return false;
       } else {
            return true;
        }
   }
