<?php $icon = 'fa-clipboard'; ?>
<?php $headTitle = 'Reports by Age Bracket' ?>
<?php $mainTitle = "Reports by Age Bracket" ?>
<?php $breadcrumbs = " Reports by Age Bracket" ?>
<?php $menuParentSection = "evacuee-report" ?>
<?php $menuSection = "report-age" ?>

<?php
require_once 'logincheck.php';
require_once 'connection.php';

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");

$calamity_id = '';
$evac_center = 5;
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "" && $_GET['center'] != "") {
    $calamity_id = $_GET['calamity_id'];
    $evac_center = $_GET['center'];
    $calamitySql = " and calamity_id = $calamity_id  and calamity_id = $calamity_id";
}

if ($isAdmin) {
    $adminList = $conn->query("SELECT id, center FROM evacuation_center WHERE id = $evac_center");
} else {
    $age = $conn->query("SELECT count(bi.age) as cnt, bi.age 
      FROM evacuees e 
      LEFT JOIN barangay_info bi on e.person_id = bi.id 
      WHERE evacuation_center_id = $adminEvac and e.status = 1 $calamitySql group by bi.age
    ");

    $ageBracket = [];

    $ageData = $age->fetch_all();

    $ageList = [
      'five' => 0,
      'ten' => 0,
      'twenty' => 0,
      'thirty' => 0,
      'fourty' => 0,
      'fifty' => 0,
      'sixty' => 0,
      'others' => 0,
    ];

    foreach ($ageData as $each) {

      if ( in_array($each[1], range(0,5)) ) {
          $ageList['five'] = $each[0];
      }

      else if ( in_array($each[1], range(6,10)) ) {
          $ageList['ten'] = $each[0];

      } else if ( in_array($each[1], range(11,20)) ) {
          $ageList['twenty'] = $each[0];

      } else if ( in_array($each[1], range(21,30)) ) {
          $ageList['thirty'] = $each[0];

      } else if ( in_array($each[1], range(31,40)) ) {
          $ageList['fourty'] = $each[0];

      } else if ( in_array($each[1], range(41,50)) ) {
          $ageList['fifty'] = $each[0];

      } else if ( in_array($each[1], range(51,60)) ) {
          $ageList['sixty'] = $each[0];
      } else {
          $ageList['others'] = $each[0];
      }
    }

    $v = json_encode(array_values($ageList));

}
$evacCenter = $conn->query("SELECT * FROM evacuation_center WHERE status = 1");
?>

<?php include 'main.php' ?>
<script src="../asset/js/chart.js"></script>

<div class="content">
<div class="container-fluid">
    <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Select Disaster</label>
        <select id="select_calamity" class="form-control">
          <option value="">Select Disaster</option>
          <?php while($fetch = $sqlCalamity->fetch_assoc()) { ?>
            <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="col-md-6">
       <div class="form-group">
        <label>Select Evacuation Center</label>
           <select name="brgy" id="select_evac" class="form-control selectcntr" data-placeholder="Select a Barangays">
               <?php while($fetch = $evacCenter->fetch_assoc()) { ?>
                   <option <?php echo $evac_center === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['center'] ?></option>
               <?php } ?>
           </select>
      </div>
    </div>
        </div>


    <?php if ($isAdmin) {  ?>
    <?php $fetch = $adminList->fetch_array();
      $id = $fetch['id'];

        $age = $conn->query("SELECT count(bi.age) as cnt, bi.age 
        FROM evacuees e 
        LEFT JOIN barangay_info bi on e.person_id = bi.id 
        WHERE evacuation_center_id = $id and e.status = 1 $calamitySql group by bi.age
      ");

      $ageBracket = [];

      $ageData = $age->fetch_all();

      $ageList = [
        'five' => 0,
        'ten' => 0,
        'twenty' => 0,
        'thirty' => 0,
        'fourty' => 0,
        'fifty' => 0,
        'sixty' => 0,
        'others' => 0,
      ];

      foreach ($ageData as $each) {

        if ( in_array($each[1], range(0,5)) ) {
            $ageList['five'] = $each[0];
        }

        else if ( in_array($each[1], range(6,10)) ) {
            $ageList['ten'] = $each[0];

        } else if ( in_array($each[1], range(11,20)) ) {
            $ageList['twenty'] = $each[0];

        } else if ( in_array($each[1], range(21,30)) ) {
            $ageList['thirty'] = $each[0];

        } else if ( in_array($each[1], range(31,40)) ) {
            $ageList['fourty'] = $each[0];

        } else if ( in_array($each[1], range(41,50)) ) {
            $ageList['fifty'] = $each[0];

        } else if ( in_array($each[1], range(51,60)) ) {
            $ageList['sixty'] = $each[0];
        } else {
            $ageList['others'] = $each[0];
        }
      }

      $v = json_encode(array_values($ageList));
    ?>

      <hr>

      <div class="row">
          <div class="col-12 col-md-4 col-lg-4 col-xl-4">
              <div class="card">
                  <div class="card-header">
                      <h3> <?php echo $fetch['center'] ?> </h3>
                  </div>
                  <div class="card-body">
                      <table class="table table-bordered mytable">
                      <thead>
                         <tr>
                             <th>Age Bracket</th>
                             <th>Number</th>
                         </tr>
                      </thead>
                        <tbody>
                         <tr>
                             <td>0 to 5</td>
                             <td><?php echo $ageList['five'] ?></td>
                         </tr>
                         <tr>
                             <td>6 to 10</td>
                             <td><?php echo $ageList['ten'] ?></td>
                         </tr>
                         <tr>
                             <td>11 to 20</td>
                             <td><?php echo $ageList['twenty'] ?></td>
                         </tr>
                         <tr>
                             <td>21 to 30</td>
                             <td><?php echo $ageList['thirty'] ?></td>
                         </tr>
                         <tr>
                             <td>31 to 40</td>
                             <td><?php echo $ageList['fourty'] ?></td>
                         </tr>
                         <tr>
                             <td>41 to 50</td>
                             <td><?php echo $ageList['fifty'] ?></td>
                         </tr>
                         <tr>
                             <td>51 to 60</td>
                             <td><?php echo $ageList['sixty'] ?></td>
                         </tr>
                         <tr>
                             <td>60 up</td>
                             <td><?php echo $ageList['others'] ?></td>
                         </tr>
                      </tbody>
                    </table>
                  </div>
              </div>
          </div>
          <div class="col-12 col-md-8 col-lg-8 col-xl-8">
              <div class="card">
                  <div class="card-body">
                      <canvas id="bargraph_<?php echo $id ?>"></canvas>
                  </div>
              </div>
          </div>
      </div>

      <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Bar Chart
            var barChartData = {
                labels: ["0 to 5","6 to 10","11 to 20","21 to 30","31 to 40","41 to 50","51 to 60","60 up"],
                datasets: [{
                    label: 'Evacuees',
                    backgroundColor: 'rgb(79,129,189)',
                    borderColor: 'rgba(0, 158, 251, 1)',
                    borderWidth: 1,
                    data: JSON.parse('<?php echo $v ?>')
                }]
            };

            var ctx = document.getElementById('bargraph_<?php echo $id ?>').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    }
                }
            });


        });
    </script>
      <?php } else { ?>

        <div class="row">
          <div class="col-12 col-md-4 col-lg-4 col-xl-4">
              <div class="card">
                  <div class="card-body">
                      <table class="table table-bordered mytable">
                      <thead>
                         <tr>
                             <th>Age Bracket</th>
                             <th>Number</th>
                         </tr>
                      </thead>
                        <tbody>
                         <tr>
                             <td>0 to 5</td>
                             <td><?php echo $ageList['five'] ?></td>
                         </tr>
                         <tr>
                             <td>6 to 10</td>
                             <td><?php echo $ageList['ten'] ?></td>
                         </tr>
                         <tr>
                             <td>11 to 20</td>
                             <td><?php echo $ageList['twenty'] ?></td>
                         </tr>
                         <tr>
                             <td>21 to 30</td>
                             <td><?php echo $ageList['thirty'] ?></td>
                         </tr>
                         <tr>
                             <td>31 to 40</td>
                             <td><?php echo $ageList['fourty'] ?></td>
                         </tr>
                         <tr>
                             <td>41 to 50</td>
                             <td><?php echo $ageList['fifty'] ?></td>
                         </tr>
                         <tr>
                             <td>51 to 60</td>
                             <td><?php echo $ageList['sixty'] ?></td>
                         </tr>
                         <tr>
                             <td>60 up</td>
                             <td><?php echo $ageList['others'] ?></td>
                         </tr>
                      </tbody>
                    </table>
                  </div>
              </div>
          </div>
          <div class="col-12 col-md-8 col-lg-8 col-xl-8">
              <div class="card">
                  <div class="card-body">
                      <canvas id="bargraph"></canvas>
                  </div>
              </div>
          </div>
      </div>
      <?php } ?>
   </div>
</div>



<?php include 'footer.php' ?>

<?php if (!$isAdmin) { ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Bar Chart
            var barChartData = {
                labels: ["0 to 5","6 to 10","11 to 20","21 to 30","31 to 40","41 to 50","51 to 60","60 up"],
                datasets: [{
                    label: 'Evacuees',
                    backgroundColor: 'rgb(79,129,189)',
                    borderColor: 'rgba(0, 158, 251, 1)',
                    borderWidth: 1,
                    data: JSON.parse('<?php echo $v ?>')
                }]
            };

            var ctx = document.getElementById('bargraph').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    }
                }
            });


        });
    </script>

<?php } ?>

<script type="text/javascript">
    $('#select_calamity').on('change', function(e) {
        let calamity_id = $(this).val();
        let center_id = getParameterByName('center'); // get the value of the center ID from the URL
        window.location = `age-report.php?c=true&calamity_id=` +calamity_id + (center_id !== null ? `&center=` +center_id : '&center=5');
    });

    $('#select_evac').on('change', function(e) {
        let center_id = $(this).val();
        let calamity_id = getParameterByName('calamity_id'); // get the value of the calamity ID from the URL
        window.location = `age-report.php?c=true&center=` +center_id + (calamity_id !== null ? `&calamity_id=` +calamity_id : '&calamity_id=1');
    });

    // helper function to get a query parameter from the URL
    function getParameterByName(name) {
        let url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        let results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
</script>
