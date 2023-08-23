<?php
    include './fetchdata.php'; // Get the JSON-encoded notification data

    // If there's anything to notify, prepare the data for push notification
    if (!empty($notificationData)) {
        $webNotificationPayload = [
            'title' => $notificationData[0]['title'], // Use the first notification from the array
            'body' => $notificationData[0]['body'],
            'icon' => $notificationData[0]['icon'],
            'url' => $notificationData[0]['payload']
        ];

        echo json_encode($webNotificationPayload);
    } else {
        // No notification data available
        exit();
    }
?>