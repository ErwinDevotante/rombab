<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="http://localhost:3000/assets/rombab-logo.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="http://localhost:3000/rb-admin/admin-index.php" class="d-block">ROMANTIC BABOY</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/admin-index.php" class="nav-link <?php if($a==1){ echo 'active'; }?>">
              <i class="ion ion-speedometer nav-icon"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
		
		    <li class="nav-item has-treeview menu-open m-1">
            <a href="#" class="nav-link <?php if($a==2){ echo 'active'; }?>">
               <i class="ion ion-ios-filing-outline nav-icon"></i>
              <p>
               Inventory
              </p>
            </a>
        </li> 
        <li class="nav-item has-treeview menu-open m-1">
            <a href="#" class="nav-link <?php if($a==3){ echo 'active'; }?>">
               <i class="ion ion-document-text nav-icon"></i>
              <p>
                Inventory History
              </p>
            </a>
        </li> 
        <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/add-menu.php" class="nav-link <?php if($a==11){ echo 'active'; }?>">
               <i class="ion ion-android-restaurant nav-icon"></i>
              <p>
                Add Menu
              </p>
            </a>
        </li> 
		  
		
        <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/manage-appointment.php" class="nav-link <?php if($a==4){ echo 'active'; }?>">
               <i class="ion ion-clipboard nav-icon"></i>
              <p>
                Appointments
              </p>
            </a>
          </li>
		   <li class="nav-item has-treeview menu-open m-1">
            <a href="#" class="nav-link <?php if($a==5){ echo 'active'; }?>">
               <i class="ion ion-ios-paper-outline nav-icon"></i>
              <p>
                Online
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/create-walkin-appointment.php" class="nav-link <?php if($a==6){ echo 'active'; }?>">
               <i class="ion ion-android-walk nav-icon"></i>
              <p>
                Walk-Ins
              </p>
            </a>
          </li> 
		  <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/appointment-history.php" class="nav-link <?php if($a==7){ echo 'active'; }?>">
               <i class="ion ion-mouse nav-icon"></i>
              <p>
                Appointment History
              </p>
            </a>
          </li> 

      <?php if ($row['user_role'] == 1) { ?>
		   <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/super-admin/add-table.php" class="nav-link <?php if($a==8){ echo 'active'; }?>">
               <i class="ion ion-plus-circled nav-icon"></i>
              <p>
                Add Table
              </p>
            </a>
          </li> 
		  <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/super-admin/add-admin.php" class="nav-link <?php if($a==9){ echo 'active'; }?>">
              <i class="ion ion-plus-circled nav-icon"></i>
              <p>
                Add Admin
              </p>
            </a>
          </li>
      <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-admin/super-admin/add-kitchen.php" class="nav-link <?php if($a==10){ echo 'active'; }?>">
              <i class="ion ion-plus-circled nav-icon"></i>
              <p>
                Add Kitchen
              </p>
            </a>
          </li> 
        <?php } ?>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>