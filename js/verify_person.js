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

$("a.verify-person").click(function(e) {
    e.preventDefault();
    console.log("a.verify-person clicke for element: " + this.nodeName);
    console.log("Creating a new verify token for person_id: " + this.dataset.personId);
    console.log(JSON.stringify(this.dataset));
    var verify_request = $.post("/api/tokens", {type: "verify", person_id: this.dataset.personId});
    verify_request.done(function(token) {
        console.log("Received token: " + token.id);
        if(token.id) {
            command = {
                data: {
                    command: "fingerprintsverifications", 
                    tokenid: token.id?token.id:"none"
                },
                oncomplete: function(data) {
                    if(data.command == this.data.command && data.tokenid == this.data.tokenid) {
                        if(data.success) {
                            if(data.data.match) {
                                $.notify({message: "Match!"}, {tipe: "danger"});
                            } else {
                                $.notify({message: "Scanned fingerprint does NOT match with database fingerprint!"}, {type: "danger"});
                            }
                        } else {
                            $.notify({message: "Verify command failed"}, {type: "danger"});
                            console.log("Scaning failed with this message text: " + data.data);
                        }
                    } else {
                        console.log("Received unknown message");
                    }
                }
            };
            console.log("Opening fingerprints wait window");
            window.open("http://localhost:3000/static/pages/waitdevice", 'width = 500, height = 500');    
        } else {
            $.notify({message: "Server did not allocate a token for verify request"}, {type: "danger"});
        }
        
    });
    verify_request.fail(function(xhr, status, error) {
        console.log("verify_request failed: " 
                    + status + "/"
                    + error + "/" 
                    + xhr.status + " " + xhr.statusText);
        $.notify({message: "Failed to get token from server"}, {type: "danger"});
    });
});


