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
                    <a href="#" class="navbar-brand">Add New Manufacturer</a>
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
          </div>
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
               <div class="row">
                   <?php 
                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="ManufacturerNameInvalid")
                        {
                            echo '<div class="alert alert-danger" role="alert">Manufacturer name is invalid.</div>';
                        }

                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="ManufacturerExists")
                        {
                            echo '<div class="alert alert-danger" role="alert">Manufacturer already exists in database!</div>';
                        }
                     ?>
                    <form method="post" action="">
                        <div class="form-group">
                           <label for="exampleSerial">New Manufacturer:</label>
                           <input type="text" class="form-control" id="serialInput" name="manufacturer">
                       </div>
                           <button type="submit" class="btn btn-primary" name="submit" value="submit">Add Manufacturer</button>
                    </form>
               </div>
          </div>
      </section>
</body>
</html>
<?php
    include_once('functions.php');
    include_once('api_base_url.php');
    if (isset($_POST['submit']))
    {
       $manufacturerName=$_POST['manufacturer'];
       
       if(!preg_match('/^[A-Z][a-z\s]+$/', $manufacturerName)) 
       {
          redirect("add-manufacturer.php?msg=ManufacturerNameInvalid");
       }

       $newManufacturerInfo = [
          "device_type_name" => $manufacturerName,
          "status_id" => 1
       ];
       global $API_BASE_URL;
       $res = callApi($API_BASE_URL . '/add_manufacturer', $newManufacturerInfo ,"POST");
       if ($res['status'] == "Success") {
            redirect("index.php?msg=DeviceTypeAdded");
       }
        else {
           redirect("add-device-type.php?msg=DeviceTypeExists");
        }

       if ($res['status'] === "Success") {
            redirect("index.php?msg=ManufacturerAdded");
       }
       else
       {
           redirect("add-manufacturer.php?msg=ManufacturerExists");
       }
    }
?>
