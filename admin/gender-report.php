    <?php $icon = 'fa-clipboard'; ?>
    <?php $headTitle = 'Reports by Gender' ?>
    <?php $mainTitle = "Reports by Gender" ?>
    <?php $breadcrumbs = " Reports by Gender" ?>
    <?php $menuParentSection = "evacuee-report" ?>
    <?php $menuSection = "report-gender" ?>
    
    <?php
    require_once 'logincheck.php';
    require_once 'connection.php';
    
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

        $gender = $conn->query("SELECT count(*) as cnt, bi.gender 
      FROM evacuees 
      LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
      WHERE evacuation_center_id = $adminEvac and evacuees.status = 1 $calamitySql group by bi.gender");
        $genderData = $gender->fetch_all();
    }
    
    $sqlCalamity = $conn->query("SELECT id, name FROM calamity");
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
    
                $gender = $conn->query("SELECT count(*) as cnt, bi.gender 
          FROM evacuees 
          LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
          WHERE evacuation_center_id = $id and evacuees.status = 1 $calamitySql group by bi.gender");
                $genderData = $gender->fetch_all();
                ?>
            <hr>
                <div class="row">
    
                    <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="mb-3"> <?php echo $fetch['center'] ?> </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-hver custom-table mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>Male</th>
                                        <th>Female</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?php echo isset($genderData[0][0]) ? $genderData[0][0] : '0' ?></td>
                                        <td><?php echo isset($genderData[1][0]) ? $genderData[1][0] : '0' ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="chart chart-lg">
                                    <canvas id="chartjs-pie_<?php echo $id ?>"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <script type="text/javascript">
    
                    document.addEventListener("DOMContentLoaded", function () {
                        // Pie chart
                        new Chart(document.getElementById("chartjs-pie_<?php echo $id ?>"), {
                            type: "pie",
                            data: {
                                labels: ["Male", "Female"],
                                datasets: [{
                                    data: [<?php echo isset($genderData[0][0]) ? $genderData[0][0] : 0 ?>, <?php echo isset($genderData[1][0]) ? $genderData[1][0] : 0 ?>],
                                    backgroundColor: [
                                        window.theme.primary,
                                        window.theme.danger,
                                    ],
                                    borderColor: "transparent"
                                }]
                            },
                            options: {
                                maintainAspectRatio: true,
                                legend: {
                                    display: true,
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
                                <table class="table table-hver custom-table mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>Male</th>
                                        <th>Female</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?php echo isset($genderData[0][0]) ? $genderData[0][0] : '0' ?></td>
                                        <td><?php echo isset($genderData[1][0]) ? $genderData[1][0] : '0' ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="chart chart-lg">
                                 <canvas id="chartjs-pie"></canvas>
                             </div>
                            </div>
                        </div>
                    </div>
                </div>
    
            <?php } ?>
        </div>
    </div>
    
    
    
    
    
    <?php include 'footer.php' ?>
    
    
    <?php
    if (!$isAdmin) { ?>
    
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Pie chart
                new Chart(document.getElementById("chartjs-pie"), {
                    type: "pie",
                    data: {
                        labels: ["Male", "Female"],
                        datasets: [{
                            data: [<?php echo isset($genderData[0][0]) ? $genderData[0][0] : 0 ?>, <?php echo isset($genderData[1][0]) ? $genderData[1][0] : 0 ?>],
                            backgroundColor: [
                                window.theme.primary,
                                window.theme.danger,
                            ],
                            borderColor: "transparent"
                        }]
                    },
                    options: {
                        maintainAspectRatio: true,
                        legend: {
                            display: true,
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
            window.location = `gender-report.php?c=true&calamity_id=` +calamity_id + (center_id !== null ? `&center=` +center_id : '&center=5');
        });

        $('#select_evac').on('change', function(e) {
            let center_id = $(this).val();
            let calamity_id = getParameterByName('calamity_id'); // get the value of the calamity ID from the URL
            window.location = `gender-report.php?c=true&center=` +center_id + (calamity_id !== null ? `&calamity_id=` +calamity_id : '&calamity_id=1');
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

        $(function() {
            $('.selectcntr').select2({
                theme: 'bootstrap4'
            })
        })

    </script>
