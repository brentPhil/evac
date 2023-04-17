<?php $icon = 'fa-chart-pie'; ?>
<?php $headTitle = 'Reports by Evacuation Center' ?>
<?php $mainTitle = "Reports by Evacuation Center" ?>
<?php $breadcrumbs = " Reports by Evacuation Center" ?>
<?php $menuParentSection = "evacuee-report" ?>
<?php $menuSection = "report-center" ?>

<?php
require_once 'logincheck.php';
require_once 'connection.php';

$calamity_id = '';
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "") {
    $calamity_id = $_GET['calamity_id'];
    $calamitySql = " and calamity_id = $calamity_id";
}

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");


$center = $conn->query("SELECT count(evacuation_center_id) as cnt, evacuation_center_id, c.center as center 
FROM evacuees e 
LEFT JOIN evacuation_center c on e.evacuation_center_id = c.id 
WHERE e.status = 1 $calamitySql group by e.evacuation_center_id
");

$centerData = $center->fetch_all();

$centerList = [];
foreach ($centerData as $each) {
    $centerList[$each[2]] = $each[0];
}

$b = json_encode(array_keys($centerList));
$v = json_encode(array_values($centerList));

?>

<?php include 'main.php' ?>

<section class="content">

    <div class="container-fluid">

        <div class="col-md-6">
            <div class="form-group">
                <label>Select Calamity</label>
                <select id="select_calamity" class="form-control">
                    <option value="">Select Calamity</option>
                    <?php while ($fetch = $sqlCalamity->fetch_assoc()) { ?>
                        <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered mytable">
                            <thead>
                                <tr>
                                    <th>Center</th>
                                    <th>Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($centerData as $each) { ?>
                                    <tr>
                                        <td><?php echo $each[2] ?></td>
                                        <td><?php echo $each[0] ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="col-12 col-md-8 col-lg-8 col-xl-8">
              <div class="card">
                  <div class="card-body">
                      <canvas id="bargraph"></canvas>
                  </div>
              </div>
          </div> -->
        </div>
    </div>
</section>





<?php include 'footer.php' ?>

<script src="../asset/js/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Bar Chart
        console.log(JSON.parse('<?php echo $v ?>'));

        var barChartData = {
            labels: JSON.parse('<?php echo $b ?>'),
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

<script type="text/javascript">
    $('#select_calamity').on('change', function(e) {
        let id = $(this).val()
        window.location = 'center-report.php?c=true&calamity_id=' + id
    })
</script>