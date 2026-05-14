<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Advanced Software Engineering</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/owl.carousel.css">
<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/templatemo-style.css">
</head>
<body>
<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
     <!-- MENU -->
     <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
          <div class="container">
               <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                    </button>

                    <!-- lOGO TEXT HERE -->
                    <a href="#" class="navbar-brand">Search Equipment Database</a>
               </div>
               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-first">
                         <li><a href="index.php" class="smoothScroll">Home</a></li>
                         <li><a href="search.php" class="smoothScroll">Search Equipment</a></li>
                         <li><a href="add.php" class="smoothScroll">Add Equipment</a></li>
                         <li><a href="add-device-type.php" class="smoothScroll">Add Device Type</a></li>
                         <li><a href="add-manufacturer.php" class="smoothScroll">Add Manufacturer</a></li>
                         <li><a href="modify-device-type.php" class="smoothScroll">Modify Device Type</a></li>
                         <li><a href="modify-manufacturer.php" class="smoothScroll">Modify Manufacturer</a></li>
                    </ul>
               </div>
          </div>
     </section>
 <!-- HOME -->
     <section id="home">
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
          <div class="row">
                  <?php
                     include_once("functions.php");
                     include_once("api_base_url.php");
                     global $API_BASE_URL;

                     $deviceTypes=array();
                     $manufacturers=array();
                     $statuses=array();
                     $deviceTypes[0]='any';
                     $manufacturers[0]='any';
                     $statuses[0]='any';
                     
                     $res = callApi($API_BASE_URL . "/get_device_types", [], 'GET');
                        foreach($res['data'] as $data) {
                           $deviceTypes[$data['device_type_id']]=$data['device_type_name'];
                        }

                     $res = callApi($API_BASE_URL . "/get_manufacturers", [], 'GET');
                     foreach ($res['data'] as $data) {
                        $manufacturers[$data['manufacturer_id']]=$data['manufacturer_name'];
                     }

                     $res = callApi($API_BASE_URL . "/get_statuses", [], 'GET');
                     foreach ($res['data'] as $data) {
                        $statuses[$data['status_id']]=$data['status_name'];
                     }

                     if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="DeviceExists")
                     {
                         echo '<div class="alert alert-danger" role="alert">Serial Number already exists in database!</div>';
                     }
                  ?>
                 <form method="post" action="">
                    <div class="form-group">
                        <label for="exampleDevice">Device:</label>
                        <select class="form-control" name="deviceType">
                            <?php
                                foreach($deviceTypes as $key=>$value)
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleManufacturer">Manufacturer:</label>
                        <select class="form-control" name="manufacturer">
                            <?php
                                   foreach($manufacturers as $key=>$value)
                                       echo '<option value="'.$key.'">'.$value.'</option>';
                               ?>
                       </select>
                   </div>
                   <div class="form-group">
                        <label for="exampleStatus">Status:</label>
                        <select class="form-control" name="status">
                            <?php
                                   foreach($statuses as $key=>$value)
                                       echo '<option value="'.$key.'">'.$value.'</option>';
                               ?>
                       </select>
                   </div>
                   <div class="form-group">
                        <label for="exampleSerial">Serial Number:</label>
                        <input type="text" class="form-control" id="serialInput" name="serialnumber">
                   </div>
                        <button type="submit" class="btn btn-primary" name="search" value="Search">Search</button>
               </form>
               <?php
                   include_once("functions.php");
                   include_once("api_base_url.php");
                   global $API_BASE_URL;

                   if(isset($_POST['item_id'])) {
                   redirect("view.php?item_id=" . $_POST['item_id'] . "&edit_mode=false");
                   }
                   if (isset($_POST['search']))
                   {
                       $deviceType=$_POST['deviceType'];
                       $manufacturer=$_POST['manufacturer'];
                       $serialNumber=trim($_POST['serialnumber']);
                       $status=$_POST['status'];
                       $prefix = "";
                       $body = "";
                       if($serialNumber) {
                           validateSerialNumber($prefix, $body, $serialNumber);
                       }

                       $searchPayload = [
                           "device_type_id" => $deviceType,
                           "manufacturer_id" => $manufacturer,
                           "serial_number" => $serialNumber,
                           "status_id" => $status
                       ];

                       $res = callApi($API_BASE_URL . "/search_equipment", $searchPayload, 'GET');
                        echo '<br><table class="table table-bordered">
                        <tr>
                           <td>Manufacturer</td>
                           <td>Device Type</td>
                           <td>Serial Number</td>
                           <td>Status</td>
                           <td>View</td>
                        </tr>';

                        foreach ($res['data'] as $data) {
                           echo ' <tr>
                              <td>' . $data['manufacturer_name'] . '</td>
                              <td>' . $data['device_type_name'] . '</td>
                              <td>' . $data['serial_number_prefix'] . '-' . $data['serial_number_body'] . '</td>
                              <td>' . $data['status_name'] . '</td>
                              <td> 
                                 <form method="post" action="">
                                    <button type="submit" class="btn btn-primary" name="item_id" value="' . $data['device_id'] . '">View Equipment</button>
                                 </form> 
                              </td>
                           </tr>';    
                        }

                     echo '</table>';
                }
               ?>
      </section>
</body>
</html>

