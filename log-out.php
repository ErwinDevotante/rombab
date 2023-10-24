<?php
session_start();

$_SESSION['success'] = true;
unset($_SESSION["user_id"]);
session_destroy();

//echo '<script type="text/javascript">'; 
        //echo 'alert("Logged-out Sucessfully!");'; echo 'window.location.href = "index.php";';
        //echo '</script>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/rombab-logo.png">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="node_modules/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="node_modules/ionicons/css/ionicons.min.css">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex align-items-center justify-content-center bg-grill">
<!-- Success alert modal -->
<div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="successModalLabel">Log-out</h5>
          </div>
          <div class="modal-body">
            <p>Logged-out Successfully!</p>
          </div>
          <div class="modal-footer">
              <a href="index.php" class="btn btn-primary">Okay</a>   
          </div>
        </div>
      </div>
</div>
</body>
</html>

<?php if (isset($_SESSION['success'])) { ?>
<script>
  $(document).ready(function() {
  $("#successModal").modal("show");
})
</script>
<?php
  unset($_SESSION['success']);
  exit();
  }
  ?>