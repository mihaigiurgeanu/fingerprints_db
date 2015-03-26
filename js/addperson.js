"use strict";
var command = null;

window.addEventListener("message", function(e) {
    if(e.data == "ready") {
        console.log("sending command to wait window: " + JSON.stringify(command.data));
        e.source.postMessage(command.data, e.origin);
    } else {
        console.log("Received message: " + JSON.stringify(e.data));
        command.oncomplete(e.data);
    }
}, false);

$("#save-button").click(function (e) {
    e.preventDefault();
});

$("#scan-button").click(function(e) {
    e.preventDefault();
    var scan_request = $.post("/api/tokens", {type: "scan"});
    scan_request.done(function(token) {
        console.log("Received token: " + token.id);
        command = {
            data: {
                command: "fingerprintsscans", 
                tokenid: token.id?token.id:"none"
            },
            oncomplete: function(data) {
                if(data.command == this.data.command && data.tokenid == this.data.tokenid) {
                    if(data.success) {
                        $.notify({message: "Scan command completed"}, {type: "success"});
                        $("#fingerprint-scan").attr("src", "/api/fingerprintsscans/" + data.data.id)
                    } else {
                        $.notify({message: "Scan command failed"}, {type: "danger"});
                        console.log("Scaning failed with this message text: " + data.data);
                    }
                } else {
                    console.log("Received unknown message");
                }
            }
        };
        console.log("Opening fingerprints wait window");
        window.open("http://localhost:3000/static/pages/waitdevice", 'width = 500, height = 500');
    });
    scan_request.fail(function() {
        $.notify({message: "Failed to get token from server"}, {type: "danger"});
    });
});

$("#photo-button").click(function(e) {
    e.preventDefault();
    var scan_request = $.post("/api/tokens", {type: "scan"});
    scan_request.done(function(token) {
        console.log("Received token: " + token.id);
        command = {
            data: {
                command: "photos", 
                tokenid: token.id?token.id:"none"    
            },
            oncomplete: function(data) {
                if(data.command == this.data.command && data.tokenid == this.data.tokenid) {
                    if(data.success) {
                        $.notify({message: "Take photo command completed"}, {type: "success"});

                    } else {
                        $.notify({message: "Take photo command failed"}, {type: "danger"});
                        console.log("Taking photo failed with this message text: " + data.data);
                    }
                } else {
                    console.log("Received unknown message");
                }
            }
        };
        console.log("Opening photos wait window");
        window.open("http://localhost:3000/static/pages/waitdevice", 'width = 500, height = 500');        
    });
    scan_request.fail(function() {
        $.notify({message: "Failed to get token from server"}, {type: "danger"});
    });
});

$("#reset-button").click(function(e) {
   e.preventDefault(); 
});

