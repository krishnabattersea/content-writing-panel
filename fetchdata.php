<?php
   include 'dbconnect.php';

   $conn = OpenCon();
   // No need to echo here, as it might cause issues when passing data

   $getinfo = "SELECT 
                  id, 
                  title, 
                  body,
                  icon,
                  url
               FROM notification";

   $notificationData = []; // Initialize an array to hold notification data

   if ($result = $conn->query($getinfo)) {
      while ($row = $result->fetch_object()) {
         $notificationData[] = [
               'id' => $row->id,
               'title' => $row->title,
               'body' => $row->body,
               'icon' => $row->icon,
               'payload' => $row->url
         ];
      }
      $result->close();
   } else {
      echo 'Something went wrong.';
   }

   CloseCon($conn);

   // Return the notification data as JSON
   json_encode($notificationData);
?>