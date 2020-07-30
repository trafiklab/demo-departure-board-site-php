// https://goodies.pixabay.com/javascript/auto-complete/demo.html
var autocomplete = new autoComplete({
    selector: 'input[name="name"]',
    source: function(term, response){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/autocomplete/' + term);
        xhr.send(null);
        xhr.onreadystatechange = function () {
            var DONE = 4; // readyState 4 means the request is done.
            var OK = 200; // status 200 is a successful return.
            if (xhr.readyState === DONE) {
                if (xhr.status === OK) {
                   response(JSON.parse(xhr.responseText)); // 'This is the returned text.'
                } else {
                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                }
            }
        };
    }
});