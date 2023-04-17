<?php 
	require_once 'connection.php';

	$id = $_POST['id'];

	$q1 = $conn->query("SELECT * FROM evacuees WHERE id = $id");
	$evacuees = $q1->fetch_assoc();

	$q2 = $conn->query("SELECT * FROM barangay");
	$brgy = $q2->fetch_all();

   $q3 = $conn->query("SELECT * FROM calamity ");

   $evacCenter = $conn->query("SELECT * FROM evacuation_center"); 
   
   $brgyQuery = $conn->query("
   SELECT bi.*, b.name as brgy FROM barangay_info bi
   LEFT JOIN barangay b on bi.barangay_id = b.id
   WHERE bi.status = 1");
   $persons = $brgyQuery->fetch_all();
?>

<form method="POST" class="form" action="">
     <input type="hidden" id="eid" name="id" value="<?php echo $evacuees['id'] ?>">
     <div class="row">
                                 
      <div class="col-md-12">
         <div class="form-group">
            <label>Calamity</label>
            <select class="form-control" name="calamity" required="">
               <?php while($fetch = $q3->fetch_array()) { ?>
                  <option <?php echo $evacuees['calamity_id'] ==  $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
               <?php } ?>
            </select>
         </div>
      </div>

      <div class="col-md-12">
         <div class="form-group">
            <label>Evacuation Center</label>
            <select class="form-control" name="evacuation_center_id" required="">
               
               <?php while($center = $evacCenter->fetch_array()) { ?>
                  <option <?php echo $evacuees['evacuation_center_id'] ==  $center['id'] ? 'selected' : '' ?> value="<?php echo $center['id'] ?>"><?php echo $center['center'] ?></option>
               <?php } ?>
            </select>
         </div>
      </div>

      <div class="form-group col-md-12">
         <label>Evacuee Name</label>
         <select name="person_id" class="form-control select2bs4" data-placeholder="Select Evacuee" style="width: 100%;">
            <?php foreach ($persons as $brg) { ?>
               <option <?php echo $evacuees['person_id'] ==  $brg[0] ? 'selected' : '' ?> value="<?php echo $brg[0] ?>"><?php echo $brg[1] . ', ' .$brg[2] . ' from ' . $brg[10]  ?></option>
            <?php } ?>
         </select>
      </div>

      
      <div class="col-md-12">
         <div class="form-group">
            <label>Head of Family</label>
            <select name="head_person_id" class="form-control select2bs4" data-placeholder="Select Evacuee" style="width: 100%;">
            <?php foreach ($persons as $brg) { ?>
               <option  <?php echo $evacuees['head_person_id'] ==  $brg[0] ? 'selected' : '' ?> value="<?php echo $brg[0] ?>"><?php echo $brg[1] . ', ' .$brg[2] . ' from ' . $brg[10]  ?></option>
            <?php } ?>
         </select>
         </div>
      </div>

      

   </div>

</form>

<script type="text/javascript">
	$('.select2bs4').select2({
     theme: 'bootstrap4'
   })
</script>