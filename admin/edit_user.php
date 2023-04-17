<?php 
	require_once 'connection.php';

	$id = $_POST['id'];

	$q1 = $conn->query("SELECT * FROM users WHERE id = $id");
	$user = $q1->fetch_assoc();

	$q2 = $conn->query("SELECT * FROM role");
	$roles = $q2->fetch_all();

    $q3 = $conn->query("SELECT * FROM `evacuation_center` WHERE status = 1");

?>

<form method="POST" class="form" action="">
     <input type="hidden" id="eid" name="id" value="<?php echo $user['id'] ?>">
     <div class="form-group">
        <label>Fullname</label>
        <input type="text" value="<?php echo $user['fullname'] ?>" required="" name="fullname" class="form-control" placeholder="Center">
     </div>
     <div class="form-group col-md-12">
        <label>Roles</label>
        <select name="role" class="form-control" data-placeholder="Select a State" style="width: 100%;">
           <?php foreach ($roles as $role) { ?>
              <option value="<?php echo $role[0] ?>" <?php echo $role[0] === $user['role_id'] ? 'selected' : '' ?> ><?php echo $role[1] ?></option>
           <?php } ?>
        </select>
      </div>

      <div class="form-group col-md-12">
        <label>Assigned Evacuation Center</label>
        <select name="evacuation_center" class="form-control">
           <?php while($fetch = $q3->fetch_array()) { ?>
              <option value="<?php echo $fetch['id'] ?>" <?php echo $fetch['id'] === $user['evacuation_center_id'] ? 'selected' : '' ?> ><?php echo $fetch['center'] ?></option>
           <?php } ?>
        </select>
      </div>
      
            
     <div class="form-group">
        <label>Contact info</label>
        <input type="text" value="<?php echo $user['contactinfo'] ?>" required="" name="contactinfo" class="form-control">
     </div>
     
     <div class="form-group">
        <label>Designation</label>
        <input type="text" required="" value="<?php echo $user['designation'] ?>" name="designation" class="form-control">
     </div>

     <div class="form-group">
        <label>Email</label>
        <input type="text" required="" value="<?php echo $user['email'] ?>" name="email" class="form-control">
     </div>

</form>

<script type="text/javascript">
	$('.select2bs4').select2({
     theme: 'bootstrap4'
   })
</script>