<?php
$icon = 'fa-folder-open';
$headTitle = 'Reports';
$mainTitle = "Reports";
$breadcrumbs = " Reports";
$menuParentSection = "evacuee-report";
$menuSection = "report";
require_once 'connection.php';
require_once 'logincheck.php';
require_once 'connection.php';

$result = $conn->query("SELECT * FROM `evacuation_center`");
$calamity = $conn->query("SELECT * FROM `calamity`");
$info = $conn->query("SELECT * FROM `barangay_info`");
$brgy = $conn->query("SELECT * FROM `barangay`");
$sqlCalamity = $conn->query("SELECT id, name FROM calamity");
$sql = " ";
if (!$isAdmin) {
    $sql = "AND evacuation_center_id = $evacuation_center_id";
}
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
   WHERE evacuees.status = 1
   ORDER BY lastname ASC");
?>

<?php include 'main.php' ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

<section class="content">

    <div class="container-fluid">

        <div>
            <div class="card">
                <div class="card-header pt-4">
                    <div class="d-flex gap-3">
                        <div class="form-group m-0">
                            <label>
                                <select id="age-filter" class="form-control">
                                    <option value="">Age</option>
                                    <option value="18-24">18-24</option>
                                    <option value="25-34">25-34</option>
                                    <option value="35-44">35-44</option>
                                    <option value="45-54">45-54</option>
                                    <option value="55-64">55-64</option>
                                    <option value="65+">65+</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group m-0 ml-3" style="max-width: 180px;">
                            <label>
                                <select id="brgy" class="form-control">
                                    <option value="">Select Barangay</option>
                                    <?php if ($brgy->num_rows > 0) {
                                        // output data of each row
                                        while($row = $brgy->fetch_assoc()) {?>
                                            // do something with the data
                                            <option value="<?php echo $row['name']?>" ><?php echo $row['name']?></option>
                                        <?php }
                                    } else {
                                        echo "0 results";
                                    } ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group m-0 ml-3">
                            <label>
                                <select id="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group m-0 ml-3" style="max-width: 250px;">
                            <label>
                                <select id="center" class="form-control">
                                    <option value="">Select Evacuation Center</option>
                                    <?php if ($result->num_rows > 0) {
                                        // output data of each row
                                        while($row = $result->fetch_assoc()) {?>
                                            // do something with the data
                                            <option value="<?php echo $row['center'] ?>" ><?php echo $row['center'] ?></option>
                                        <?php }
                                    } else {
                                        echo "0 results";
                                    } ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group m-0 ml-3">
                            <label>
                                <select id="calamity" class="form-control">
                                    <option value="">Select Disaster</option>
                                    <?php if ($calamity->num_rows > 0) {
                                        // output data of each row
                                        while($row = $calamity->fetch_assoc()) {?>
                                            // do something with the data
                                            <option value="<?php echo $row['name'] ?>"><?php echo $row['name'] ?></option>
                                        <?php }
                                    } else {
                                        echo "0 results";
                                    } ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group m-0 ml-3">
                            <select class="form-control" name="remarks" id="remarks">
                                <option value="">Remarks</option>
                                <option value="PWD">PWD's</option>
                                <option value="Pregnant">Pregnant</option>
                                <option value="Senior Citizen">Senior Citizen</option>
                                <option value="Special needs">With Special Needs</option>
                            </select>
                        </div>
                        <div class="form-group m-0 ml-3">
                            <button id="generate-pdf" class="btn btn-outline-dark"><i class="far fa-file-pdf"></i> Generate PDF</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Brgy</th>
                            <th>Gender</th>
                            <th>Remark</th>
                            <th>Evacuation Center</th>
                            <th>Disaster</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while($fetch = $q1->fetch_array()) {
                            $evac = $fetch['evacuation_center_id'];
                            $q4 = $conn->query("SELECT * FROM evacuation_center WHERE id = '$evac'");
                            $evacuationCenter = $q4->fetch_assoc();?>
                            <tr>
                                <td><?php echo $fetch['fullname']?></td>
                                <td><?php echo $fetch['evacAge']?></td>
                                <td><?php echo $fetch['evacBrgy']?></td>
                                <td><?php echo $fetch['evacGender'] === '1' ? 'Male' : 'Female' ?></td>
                                <td><?php echo $fetch['remarks']?></td>
                                <td><?php echo $evacuationCenter['center']?></td>
                                <td><?php echo $fetch['calamity']?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#myTable').DataTable();
        const generatePDF = () => {
            const data = table.rows({ 'search': 'applied', 'page': 'all', 'order': 'applied' }).data().toArray();

            const tableHeader = ['Name', 'Age', 'Brgy', 'Gender', 'Remark', 'Evacuation Center', 'Disaster'];

            const docDefinition = {
                content: [
                    { text: 'Evacuation List', style: 'header' },
                    { text: '\n' },
                    {
                        style: 'table',
                        table: {
                            headerRows: 1, // add this property to include the header row
                            widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
                            body: data
                        }
                    }
                ],
                header: {
                    columns: tableHeader.map(header => ({ text: header, style: 'tableHeader' })),
                    style: 'tableHeader'
                },
                styles: {
                    header: { fontSize: 18, bold: true, alignment: 'center', margin: [0, 0, 0, 20] },
                    table: { margin: [0, 5, 0, 15], fontSize: 12, alignment: 'center' },
                    tableHeader: {
                        bold: true,
                        fontSize: 13,
                        color: 'white',
                        fillColor: '#2d4154',
                        alignment: 'center',
                    },
                },
            };

            pdfMake.createPdf(docDefinition).download('evacuation_list.pdf');
        };

        $('#generate-pdf').on('click', generatePDF);

        $('#age-filter').change(function() {
            const ageRange = $(this).val();
            if (ageRange === '') {
                table.columns(1).search('').draw();
            } else {
                const ages = ageRange.split('-');
                const minAge = parseInt(ages[0]);
                const maxAge = parseInt(ages[1]);
                table.columns(1).search('^(' + minAge + '|' + (minAge+1) + '|' + (minAge+2) + '|...|' + (maxAge-2) + '|' + (maxAge-1) + '|' + maxAge + ')$', true, false).draw();
            }
        });
        $('#gender').on('change', function() {
            var selectedGender = $(this).val(); // get selected gender value
            var regex = '\\b' + selectedGender + '\\b'; // add word boundaries to search string
            $('#myTable').DataTable().column(3).search(regex, true, false).draw(); // filter data based on selected gender
        });
        // Add event listeners to the dropdown filters
        $('#brgy, #center, #calamity').change(function() {
            const brgy = $('#brgy').val();
            const center = $('#center').val();
            const calamity = $('#calamity').val();

            table.columns(2).search(brgy).draw();
            table.columns(5).search(center).draw();
            table.columns(6).search(calamity).draw();
        });

        $(function() {
            const table = $('#myTable').DataTable();

            $('#remarks').on('change', function() {
                const selectedValue = $(this).val();
                table.column(4).search(selectedValue).draw();
            });
        });
    });
</script>