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
                    <a href="#" class="navbar-brand">Add New Equipment</a>
               </div>
               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-first">
                         <li><a href="index.php" class="smoothScroll">Home</a></li>
                         <li><a href="search.php" class="smoothScroll">Search Equipment</a></li>
                    <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Add<span class="caret"></span></a> 
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                              <li><a href="add.php" class="smoothScroll">Add Equipment</a></li>
                              <li><a href="add-device-type.php" class="smoothScroll">Add Device Type</a></li>
                              <li><a href="add-manufacturer.php" class="smoothScroll">Add Manufacturer</a></li>     
                          </ul>
                     </li>
                         <li><a href="modify-device-type.php" class="smoothScroll">Modify Device Type</a></li>
                         <li><a href="modify-manufacturer.php" class="smoothScroll">Modify Manufacturer</a></li>
                    </ul>
               </div>
             </div>
     </section>
 <!-- HOME -->
     <section id="home">
          </div>
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
               <div class="row">
                   <?php 
                        include_once("functions.php");
                        include_once("api_base_url.php");
                        global $API_BASE_URL;

                        $deviceTypes = array();
                        $manufacturers = array();

                        $res = callApi($API_BASE_URL . "/get_device_types", [], 'GET');
                        foreach($res['data'] as $data) {
                           $deviceTypes[$data['device_type_id']]=$data['device_type_name'];
                        }

                        $res = callApi($API_BASE_URL . "/get_manufacturers", [], 'GET');
                        foreach ($res['data'] as $data) {
                           $manufacturers[$data['manufacturer_id']]=$data['manufacturer_name'];
                        }
                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="EquipmentExists")
                        {
                            echo '<div class="alert alert-danger" role="alert">Serial Number already exists in database!</div>';
                        }
                     ?>
                    <form method="post" action="">
                    <div class="form-group">
                        <label for="exampleDevice">Device:</label>
                        <select class="form-control" name="device">
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
                        <label for="exampleSerial">Serial Number:</label>
                        <input type="text" class="form-control" id="serialInput" name="serialnumber">
                    </div>
                        <button type="submit" class="btn btn-primary" name="submit" value="submit">Add Equipment</button>
                   </form>
               </div>
          </div>
      </section>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!--import needed Jquery dependdancy from dropdown from google CDN-->
   <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
<?php
    if (isset($_POST['submit']))
    {
        include_once("functions.php");
        include_once("api_base_url.php");
        global $API_BASE_URL;

        $device=$_POST['device'];
        $manufacturer=$_POST['manufacturer'];
        $serialNumber=trim($_POST['serialnumber']);
        $newEquipmentInfo = [
            "device_type_id" => $device,
            "manufacturer_id" => $manufacturer,
            "serial_number" => $serialNumber,
            "status_id" => 1
        ];
        validateSerialNumber($prefix, $body, $serialNumber);

        $res = callApi($API_BASE_URL . "/add_equipment", $newEquipmentInfo, 'POST');

        if ($res['status'] === "Success")
        {
            redirect("index.php?msg=EquipmentAdded");
        }
        else
            redirect("add.php?msg=EquipmentExists");
    }
?>
