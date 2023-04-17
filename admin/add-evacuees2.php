<?php $icon = 'fa-user-plus'; ?>
<?php $headTitle = 'Add Evacuees' ?>
<?php $mainTitle = "Add Evacuees" ?>
<?php $breadcrumbs = "Add Evacuees" ?>
<?php $menuParentSection = "evacuee" ?>
<?php $menuSection = "add-evacuess" ?>

<?php

require_once 'connection.php';


$brgyQuery = $conn->query("
   SELECT bi.*, b.name as brgy FROM barangay_info bi
   LEFT JOIN barangay b on bi.barangay_id = b.id
   WHERE bi.status = 1");
$persons = $brgyQuery->fetch_all();

$q2 = $conn->query("SELECT * FROM barangay ");

$q3 = $conn->query("SELECT * FROM calamity ");

$evacCenter = $conn->query("SELECT * FROM evacuation_center");

?>

<?php include 'main.php' ?>

<section class="content">

   <div class="container-fluid">

       <?php
       if (isset($_SESSION['isSave'])) {
           echo '<div class="alert alert-success"> <i class="fas fa-check"></i> Successfully Saved</div>';
           unset($_SESSION['isSave']);
       }
       ?>
       <div class="card shadow-sm">
           <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
               <h3 class="card-title">Evacuees Information</h3>
           </div>
           <form method="POST" action="./add_brgy_db.php">
               <div class="card-body">
                   <div class="row">
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Date:</label>
                               <input type="date" required class="form-control" name="date_added">
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Calamity:</label>
                               <select class="form-control" name="calamity" required="">
                                   <?php while ($fetch = $q3->fetch_array()) { ?>
                                       <option value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                                   <?php } ?>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Evacuation Center:</label>
                               <select class="form-control" name="evacuation_center_id" required="">
                                   <?php while ($center = $evacCenter->fetch_array()) { ?>
                                       <option value="<?php echo $center['id'] ?>"><?php echo $center['center'] ?></option>
                                   <?php } ?>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Evacuee Name:</label>
                               <select required name="person_id[]" class="ajax-people" multiple="multiple" data-placeholder="Select Evacuee" style="width: 100%;">
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Remarks:</label>
                               <select class="form-control" name="remarks" id="remarks" required>
                                   <option value="Normal">Normal</option>
                                   <option value="PWD">PWD</option>
                                   <option value="Pregnant">Pregnant</option>
                                   <option value="Sick">Sick</option>
                                   <option value="Special needs">w/ special needs</option>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Status:</label>
                               <select class="form-control" name="is_present">
                                   <option value="1">Present</option>
                                   <option value="2">Went Home</option>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="form-group">
                               <label>Head of Family:</label>
                               <select name="head_person_id" class="ajax-people" data-placeholder="Select Evacuee" style="width: 100%;">
                               </select>
                           </div>
                       </div>
                   </div>
               </div>
               <!-- /.card-body -->
               <div class="card-footer">
                   <button type="submit" name="evacuee" class="btn btn-primary">Save</button>
               </div>
           </form>
       </div>
   </div>
</section>



<?php include 'footer.php' ?>

<script>
   $(function() {

      $('.ajax-people').select2({
         theme: 'bootstrap4',
         minimumInputLength: 3,
         ajax: {
            url: 'search_evacuees.php',
            dataType: 'json',
            processResults: function(data) {
               return {
                  results: $.map(data.items, function(item) {
                     return {
                        text: item.name,
                        id: item.id
                     }
                  })
               };
            },
            data: function(term) {
               return {
                  term: term
               };
            },
         }
      })
   })
</script>