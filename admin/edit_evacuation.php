<?php

require_once 'connection.php';

$id = $_POST['id'];

$q1 = $conn->query("SELECT * FROM barangay WHERE status = 1");
$brgys = $q1->fetch_all();

$evacuationsQuery = $conn->query("SELECT * FROM evacuation_center WHERE id = $id");
$evac = $evacuationsQuery->fetch_assoc();

$q2 = $conn->query("SELECT * FROM evacuation_barangay WHERE evac_id = $id");
$brgyLists = $q2->fetch_all();

$selectedBrgy = [];

foreach ($brgyLists as $each) {
   array_push($selectedBrgy, $each[1]);
}

function formatFilePath($file_path = null)
{
   return str_replace("uploads/{$_POST['id']}/", "", $file_path);
}

?>

<form method="POST" class="form" action=""  enctype="multipart/form-data">
   <input type="hidden" id="eid" name="id" value="<?php echo $evac['id'] ?>">
   <div class="form-group">
      <label>Center</label>
      <input type="text" value="<?php echo $evac['center'] ?>" required="" id="ecenter" name="center" class="form-control" placeholder="Center">
   </div>

   <div class="form-group">
      <label>Camp Manager</label>
      <input type="text" value="<?php echo $evac['camp_manager'] ?>" required="" id="manager" name="manager" class="form-control" placeholder="">
   </div>

   <div class="form-group">
      <label>Guard</label>
      <input type="text" value="<?php echo $evac['guard'] ?>" required="" id="guard" name="guard" class="form-control" placeholder="">
   </div>


   <div class="form-group col-md-12">
      <label>Barangay's</label>
      <select name="brgy[]" class="select2bs4" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
         <?php foreach ($brgys as $brg) { ?>
            <option value="<?php echo $brg[0] ?>" <?php if (in_array($brg[0], $selectedBrgy)) {
                                                      echo 'selected';
                                                   } ?>><?php echo $brg[1] ?></option>
         <?php } ?>
      </select>
   </div>

   <div class="form-group hide">
      <label>Address</label>
      <input type="text" value="<?php echo $evac['address'] ?>" required="" id="eaddress" name="address" class="form-control" placeholder="Address">
   </div>

   <div class="form-group">
      <label>Contact</label>
      <input type="text" required="" value="<?php echo $evac['contact'] ?>" id="econtact" name="econtact" class="form-control" placeholder="Contact">
   </div>

   <div class="row">
      <div class="col-sm-6">
         <!-- text input -->
         <div class="form-group">
            <label>Lat</label>
            <input type="text" name="elat" value="<?php echo $evac['lat'] ?>" class="form-control" placeholder="Lat">
         </div>
      </div>
      <div class="col-sm-6">
         <div class="form-group">
            <label>Long</label>
            <input type="text" name="elong" value="<?php echo $evac['lng'] ?>" class="form-control" placeholder="Long">
         </div>
      </div>
   </div>

   <div class="form-group">
      <label>Capacity</label>
      <input type="text" required="" value="<?php echo $evac['capacity'] ?>" id="capacity" name="capacity" class="form-control">
   </div>

   <div class="form-group">
      <label>Evacuation Center Photo</label>
      <small><?php echo formatFilePath($evac['image_path']) ?></small>
      <input type="file" required="" id="image_path" name="image_path" class="form-control" accept="image/png, image/jpeg">
   </div>

   <div class="form-group">
      <label>Evacuation Type</label>
      <select class="form-control" name="type" id="type" required="">
         <option <?php echo $evac['type'] == 1 ? 'selected' : '' ?> value="1">Primary</option>
         <option <?php echo $evac['type'] == 2 ? 'selected' : '' ?> value="2">Secondary</option>
         <option <?php echo $evac['type'] == 3 ? 'selected' : '' ?> value="3">Alternative</option>
      </select>
   </div>

</form>

<script type="text/javascript">
   $('.select2bs4').select2({
      theme: 'bootstrap4'
   })
</script>