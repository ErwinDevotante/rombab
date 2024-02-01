<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../assets/rombab-logo.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="admin-index.php" class="d-block">ROMANTIC BABOY</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open m-1">
            <a href="dashboard.php" class="nav-link <?php if($a==1){ echo 'active'; }?>">
              <i class="ion ion-speedometer nav-icon"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
        <li class="nav-item has-treeview menu-open m-1">
            <a href="table-availability.php" class="nav-link <?php if($a==2){ echo 'active'; }?>">
               <i class="ion ion-clipboard nav-icon"></i>
              <p>
                Table Availability
              </p>
            </a>
          </li>
		   <li class="nav-item has-treeview menu-open m-1">
            <a href="create-online-appointment.php" class="nav-link <?php if($a==3){ echo 'active'; }?>">
               <i class="ion ion-ios-paper-outline nav-icon"></i>
              <p>
                Create Appointment
              </p>
            </a>
          </li>
		  <li class="nav-item has-treeview menu-open m-1">
            <a href="appointment-history.php" class="nav-link <?php if($a==4){ echo 'active'; }?>">
               <i class="ion ion-mouse nav-icon"></i>
              <p class="text-nowrap">
                Appointment History
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview menu-open m-1">
            <a href="../log-out.php" class="nav-link">
               <i class="ion ion-log-out nav-icon"></i>
              <p class="text-nowrap">
                Log-out
              </p>
            </a>
          </li>

          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>