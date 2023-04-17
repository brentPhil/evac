<?php $icon = 'fa-user-cog'; ?>
<?php $headTitle = 'Account Setting' ?>
<?php $mainTitle = "Account Setting" ?>
<?php $breadcrumbs = "Account Setting" ?>
<?php $menuSection = "setting" ?>

<?php
require_once 'logincheck.php';
require_once 'connection.php';

  $query = $conn->query("
    SELECT a.*, r.role FROM `users` a LEFT JOIN role r on a.role_id = r.id
  ");


  if (isset($_POST['save'])) {

    $id = $admin['id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['email'];
    $password = $_POST['password'];
    
    $conn->query("
      UPDATE users SET email = '$username', password = '$password',
       fullname = '$fullname'
      WHERE id = $id;
    ");

    echo "<script>window.location = 'setting.php'</script>";
  }


?>

<?php include 'main.php' ?>


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" src="../asset/img/avatar.png" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">
                  <?php echo $admin['fullname'] ?>
                </h3>

                <p class="text-muted text-center"><?php echo $admin['role'] ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right"><?php echo $admin['email'] ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Password</b> <a class="float-right">
                      <?php echo $admin['password'] ?></a>
                  </li>
                </ul>

                <a href="logout.php" class="btn btn-primary btn-block"><b>Logout</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-6">
            <div class="card card-primary card-outline">
              <form method="POST" id="" action="" enctype = "multi-part/form-data">
              <div class="card-body">
                
                <div class="form-group">
                  <label for="zone">Fullname</label>
                  <input required type="text" name="fullname" value="<?php echo $admin['fullname'] ?>" class="form-control">
                </div>

                <div class="form-group">
                  <label for="zone">Email</label>
                  <input required type="text" name="email" value="<?php echo $admin['email'] ?>" class="form-control">
                </div>

                <div class="form-group">
                  <label for="zone">Password </label>
                  <input required type="password" name="password" value="<?php echo $admin['password'] ?>" class="form-control">
                </div>

                <button name="save" class="btn btn-primary btn-block col-md-6"><b>Update</b></button>
              </div>
            </form>
            </div>
          </div>

            
          </div>
        </div>
      </div>
    </div>


      
 <?php include 'footer.php' ?>
