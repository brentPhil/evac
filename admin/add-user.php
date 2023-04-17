<?php $icon = 'fa-user-plus'; ?>
<?php $headTitle = 'Add Users' ?>
<?php $mainTitle = "Add Users" ?>
<?php $breadcrumbs = "Add Users" ?>
<?php $menuParentSection = "user" ?>
<?php $menuSection = "add-user" ?>

<?php

require_once 'connection.php';


$isSave = false;
$isDuplicate = false;

if (isset($_POST['save'])) {

  $fullname = $_POST['fullname'];
  $role = $_POST['role'];
  $email = $_POST['email'];
  $contactinfo = $_POST['contactinfo'];
  $designation = $_POST['designation'];

  $username = $_POST['username'];
  $password = $_POST['password'];
  $evacuation_center_id = $_POST['evacuation_center_id'];
  
  $q2 = $conn->query("SELECT * FROM users WHERE email = '$email'");  
  $emails = $q2->fetch_all();
  
  if (count($emails) > 1) {
      $isDuplicate = true;
  } else {

     $conn->query("
      INSERT INTO `users` (id, email, fullname, role_id, contactinfo, designation, username, password, status, evacuation_center_id)
      VALUES(null, '$email', '$fullname', $role, '$contactinfo', '$designation', '$username', '$password', 1, $evacuation_center_id)");

     $isSave = true;
       
  }

}

  $q3 = $conn->query("SELECT * FROM evacuation_center");  
  $q1 = $conn->query("SELECT * FROM role");

?>

<?php include 'main.php' ?>

<section class="content">

   <div class="container-fluid">

      <?php if ($isSave) { ?>
         <div class="alert alert-success"> <i class="fas fa-check"> </i> Successfully Saved </div>
      <?php } ?>

      <?php if ($isDuplicate) { ?>
         <div class="alert alert-danger"> <i class="fas fa-check"> </i> Email already exist </div>
      <?php } ?>

      <div class="card card-info">
                  <div class="card-header">
                     <h3 class="card-title">Users Information</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Full Name</label>
                                       <input type="text" required="" name="fullname" class="form-control" placeholder="Full Name">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Email</label>
                                       <input type="email" required="" name="email" class="form-control" placeholder="Email">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Account Category</label>
                                       <select name="role" class="form-control">
                                        <?php while($fetch = $q1->fetch_array()) { ?>
                                          <option value="<?php echo $fetch['id'] ?>"><?php echo $fetch['role'] ?></option>
                                        <?php } ?>
                                         
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Assigned Evacuation Center</label>
                                       <select name="evacuation_center_id" class="form-control">
                                        <?php while($f = $q3->fetch_array()) { ?>
                                          <option value="<?php echo $f['id'] ?>"><?php echo $f['center'] ?></option>
                                        <?php } ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Contact Info</label>
                                       <input type="text" required="" class="form-control" name="contactinfo" placeholder="contact info">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Designation</label>
                                       <input type="text" required="" class="form-control" name="designation" placeholder="Designation">
                                    </div>
                                 </div>
                                 
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Username</label>
                                       <input type="text" required="" class="form-control" name="username" placeholder="Username">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Password</label>
                                       <input type="password" required="" class="form-control" name="password" placeholder="**********">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                     </div>
                     <!-- /.card-body -->

                     <div class="card-footer">
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                     </div>
                  </form>
               </div>
   </div>
</section>


<?php include 'footer.php' ?>         

