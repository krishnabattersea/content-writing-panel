    <!DOCTYPE html>
<html>
<head>
<title>GetAssist Push Notifications</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
    crossorigin="anonymous"></script>
</head>
<body>
    
<?php
	include './dbconnect.php';
	$conn = OpenCon();
	
	CloseCon($conn);
?> 

<script>
    function pushNotify() {

		if (!("Notification" in window)) {
			alert("Web browser does not support desktop notification");
        }
        
        if (Notification.permission !== "granted") {
			Notification.requestPermission();
        } else {
            $.ajax({
                url: "push-notify.php",
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    if ($.trim(data)) {
                        var dataObj = JSON.parse(data);
                        console.log(dataObj);
                        var notification = createNotification(dataObj.title, dataObj.icon, dataObj.body, dataObj.url);
                       

                        setTimeout(function() {
                            notification.close();
                        }, 10000);
                        
                        setTimeout(function() {
                            window.location.replace("http://localhost:80/content-writing/article_management.php")
                        }, 500);
                        
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) { }
            });
        }
    }

    function createNotification(title, icon, body, url) {
        var notification = new Notification(title, {
            icon: icon,
            body: body,
        });

        notification.onclick = function() {
            window.open(url);
        };

        return notification;
    }

    // Enable this line if you want to make only one call and not repeated calls automatically
    // pushNotify();

</script>
</body>
</html>