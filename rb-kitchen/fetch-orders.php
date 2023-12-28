<?php
// fetch-orders.php

include '../conn.php';

$result_tb = mysqli_query($connection, "SELECT * FROM `orders`
                      LEFT JOIN `users` ON orders.user_table = users.user_id 
                      WHERE status = 0;");

if (mysqli_num_rows($result_tb) > 0) {
  while ($row = mysqli_fetch_array($result_tb)) {
    // Output HTML for each order, similar to your existing code
        if ($row['status'] == '0') {
        echo "<div class='card p-2 mb-2 card-red text-black'>";
        echo "<input type='hidden' name='update_id' value='" . $row["order_id"] . "'>";
        echo "<h2 class='card-title mb-3'>" . $row["name"] . "</h2>";
        echo "<h6 class='card-subtitle mb-2 text-muted'>" . date('Y-m-d H:i:s', strtotime($row["time_date"])) . "</h6>";

        $skillsArray = explode(",", $row['total_products']);
        foreach ($skillsArray as $skill) {
        echo "<p class='card-text m-0'>" . trim($skill) . "</p>";
        echo "<hr class='bg-dark m-1'>";
        }

        echo "<p class='card-text m-0'>Elapsed time: <span id='timer_" . $row['order_id'] . "'></span> seconds</p>";
        echo "<input type='hidden' name='elapsed_seconds' id='elapsed_seconds_" . $row["order_id"] . "' value='0'>";
        echo "<input type='submit' class='btn btn-md btn-outline-danger w-100 mt-1 serve-order-button' data-order-id='". $row["order_id"] ."' value='SERVE ORDER' name='serve_order'>";
        echo "</div>";
        }
  }
} else {
  echo "<div><p>No available orders!</p></div>";
}
?>
    <script>
          // JavaScript code to update the timers and elapsed time in real-time
          function updateTimers() {
            <?php 
            $result_tb = mysqli_query($connection, "SELECT * FROM `orders` ");
            if(mysqli_num_rows($result_tb) > 0){
            while ($row = mysqli_fetch_array($result_tb)) { ?>

                var timerElement_<?php echo $row['order_id']; ?> = document.getElementById('timer_<?php echo $row['order_id']; ?>');
                var elapsedSecondsElement_<?php echo $row['order_id']; ?> = document.getElementById('elapsed_seconds_<?php echo $row["order_id"]; ?>');
                var timeDate_<?php echo $row['order_id']; ?> = new Date('<?php echo $row['time_date']; ?>').getTime();

                var interval_<?php echo $row['order_id']; ?> = setInterval(function() {
                var currentTime = new Date().getTime();
                var diffMilliseconds = currentTime - timeDate_<?php echo $row['order_id']; ?>;
                var elapsedSeconds = Math.floor(diffMilliseconds / 1000);

                // Check if elapsed time exceeds 999 seconds
                if (elapsedSeconds >= 999) {
                    clearInterval(interval_<?php echo $row['order_id']; ?>); // Clear the interval
                    timerElement_<?php echo $row['order_id']; ?>.innerText = '999';
                    elapsedSecondsElement_<?php echo $row['order_id']; ?>.value = 999; // Update the hidden input field
                    timerElement_<?php echo $row['order_id']; ?>.style.color = 'red'; // Change text color to red
                } else {
                    timerElement_<?php echo $row['order_id']; ?>.innerText = elapsedSeconds;
                    elapsedSecondsElement_<?php echo $row['order_id']; ?>.value = elapsedSeconds; // Update the hidden input field
                }
                }, 1000);
            <?php 
            } 
            }
        ?>
        }

        // Call the function to update the timers in real-time
        updateTimers();

        // Attach the event handler for "Mark as Served" button using delegation
        $(document).ready(function() {
            $(document).on('click', '.serve-order-button', function(e) {
                e.stopPropagation();
                var orderID = $(this).data('order-id');

                // Use AJAX to update read_notif_session
                $.ajax({
                type: "POST",
                url: "order-update.php",
                data: { orderID: orderID, elapsedSeconds: $('#elapsed_seconds_' + orderID).val() },
                success: function(response) {
                    // If successful, update the button class and text
                    var button = $('[data-order-id="' + orderID + '"]');
                    button.text('SERVED');
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
            });
        });

    </script>
