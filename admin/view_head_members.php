<?php 
	require_once 'connection.php';

	$family_head = $_POST['family_head'];

	$q1 = $conn->query("
	SELECT evacuees.*, c.name as calamity, 
	CONCAT(bi.lastname, ', ', bi.firstname) as fullname,
	bi.address as evacAddress,
	bi.age as evacAge,
	bi.gender as evacGender,
	bi.contact_number as evacContact,
	bi.gender as evacGender,
	b.name as evacBrgy,
	CONCAT(bi2.lastname, ', ', bi2.firstname) as head
    FROM evacuees 
   LEFT JOIN calamity c on evacuees.calamity_id = c.id 
   LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
   LEFT JOIN barangay b on bi.barangay_id = b.id
   LEFT JOIN barangay_info bi2 on evacuees.head_person_id = bi2.id 

	WHERE head_person_id = '$family_head'");


?>

<table class="table table-bordered table-hover">
    <thead>
       <tr>
          <th>Name</th>
          <th>Age</th>	
          <th>Gender</th>
       </tr>
    </thead>
    <tbody>
	   <?php while($fetch = $q1->fetch_array()) {
	      $calamity_id = $fetch['calamity_id'];
	      
	      $q5 = $conn->query("SELECT * FROM calamity WHERE id = $calamity_id");
	      $calamityR = $q5->fetch_assoc();
	    ?>
    	<tr>
        	<td><?php echo $fetch['fullname'] ?></td>
        	<td><?php echo $fetch['evacAge'] ?></td>
        	<td><?php echo $fetch['evacGender'] == 1 ? 'Male' : 'Female' ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

