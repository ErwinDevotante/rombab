<nav class="main-header navbar navbar-expand navbar-white navbar-light bg-red">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu"><i class="ion ion-navicon-round text-white"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <?php if ($row['user_role'] == 1) { ?>
          <a class="nav-link text-white">Welcome Super Admin <?php echo $row['name'] ?>!</a>
          <?php } else if ($row['user_role'] == 2) {?>
            <a class="nav-link text-white">Welcome Admin <?php echo $row['name'] ?>!</a>
          <?php }?>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link text-white" href="..\..\..\log-out.php">
         Logout
        </a>
      </li>
    </ul>
  </nav>