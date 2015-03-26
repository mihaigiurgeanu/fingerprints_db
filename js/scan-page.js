/**
Script loaded by the page that communicates with local server
*/

$(function () {

    console.log("scan page start");
    window.addEventListener("message", function(e) {
        console.log("Received message: " + JSON.stringify(e.data));
        
        var post = $.post("http://localhost:3000/api/" + e.data.command, {tokenid: e.data.tokenid});
        post.done(function(data) {
            console.log("post request success");
            window.opener.postMessage({success: true, command: e.data.command, tokenid: e.data.tokenid, data: data}, "*");
        })
            .fail(function(xhr) {
            console.log("post request fail");
            window.opener.postMessage({success: false, command: e.data.command, tokenid: e.data.tokenid, data: xhr.responseText}, "*");
        })
            .always(function() {
            console.log("post returned - always");
            window.close();
        });
    }, false);


    console.log("sending message to window opener");
    window.opener.postMessage("ready", "*");

});