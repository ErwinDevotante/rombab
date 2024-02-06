<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: #1C1C1C;">
    <!-- Brand Logo -->
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../assets/rombab-logo.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="admin-index.php" class="d-block text-white">ROMANTIC BABOY</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item has-treeview menu-open m-1">
              <a href="admin-index.php" class="nav-link <?php if ($a == 1) { echo 'active'; }?>" style="<?php if ($a == 1) { echo 'background: #8b0000;'; } ?>">
                  <i class="ion ion-speedometer nav-icon"></i>
                  <p>
                      Dashboard
                  </p>
              </a>
          </li>

          
        <?php if ($row['user_role'] == '1' || $row['user_role'] == '2') { ?>
		    <li class="nav-item has-treeview menu-open m-1">
            <a href="inventory.php" class="nav-link <?php if($a==2){ echo 'active'; }?>" style="<?php if ($a == 2) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-ios-filing-outline nav-icon"></i>
              <p>
               Inventory
              </p>
            </a>
        </li> 
        <li class="nav-item has-treeview menu-open m-1">
            <a href="inventory-history.php" class="nav-link <?php if($a==3){ echo 'active'; }?>" style="<?php if ($a == 3) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-document-text nav-icon"></i>
              <p>
                Inventory Reports
              </p>
            </a>
        </li> 
        <li class="nav-item has-treeview menu-open m-1">
            <a href="add-menu.php" class="nav-link <?php if($a==11){ echo 'active'; }?>" style="<?php if ($a == 11) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-android-restaurant nav-icon"></i>
              <p>
                Menu
              </p>
            </a>
        </li> 
		
        <?php } if ($row['user_role'] == '1' || $row['user_role'] == '2' || $row['user_role'] == '5') { ?>
        <li class="nav-item has-treeview menu-open m-1">
            <a href="manage-appointment.php" class="nav-link <?php if($a==4){ echo 'active'; }?>" style="<?php if ($a == 4) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-clipboard nav-icon"></i>
              <p>
                Appointments
              </p>
            </a>
          </li>
		   <li class="nav-item has-treeview menu-open m-1">
            <a href="online-appointment.php" class="nav-link <?php if($a==5){ echo 'active'; }?>" style="<?php if ($a == 5) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-ios-paper-outline nav-icon"></i>
              <p>
                Online
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview menu-open m-1">
            <a href="create-walkin-appointment.php" class="nav-link <?php if($a==6){ echo 'active'; }?>" style="<?php if ($a == 6) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-android-walk nav-icon"></i>
              <p>
                Walk-Ins
              </p>
            </a>
          </li> 
		  <li class="nav-item has-treeview menu-open m-1">
            <a href="appointment-history.php" class="nav-link <?php if($a==7){ echo 'active'; }?>" style="<?php if ($a == 7) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-mouse nav-icon"></i>
              <p class="text-nowrap">
                Appointment History
              </p>
            </a>
          </li>
      <?php } ?>

      <?php if ($row['user_role'] == 1) { ?>
		   <li class="nav-item has-treeview menu-open m-1">
            <a href="add-account.php" class="nav-link <?php if($a==8){ echo 'active'; }?>" style="<?php if ($a == 8) { echo 'background: #8b0000;'; } ?>">
               <i class="ion ion-person-add nav-icon"></i>
              <p>
                Add Account
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