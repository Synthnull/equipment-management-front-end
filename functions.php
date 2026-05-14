<?php
function redirect($url)
{?>
  <script type="text/javascript">
    document.location.href="<?php echo $url;?>";
  </script>
<?php
  die;
  }
  
function partSerialNumber(string $fullSerialNumber, &$prefix, &$delimeter, &$body) : void {
   if($fullSerialNumber == null) {
      return;
   }

   $prefix = substr($fullSerialNumber, 0,2);
   $delimeter = $fullSerialNumber[2];
   $body = substr($fullSerialNumber, 3);
}

function validateSerialNumber(&$prefix, &$body, $serialNumber) : bool {
      $delimeter = "";
      partSerialNumber($serialNumber, $prefix, $delimeter, $body);

      //incorect size
      if(strlen($body) != 64) {
         return true;
      }

      //incorect delimiter
      if($delimeter != '-') {
         return true;
      }

      return false;
}

function callApi($apiUrl, $payload, $method) {
   $payload = json_encode($payload);
   $ch = curl_init($apiUrl);

   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFHOST, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

   if($payload) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
   }
   
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-type: application/json',
      'Content-length: ' . strlen($payload)));

   $res = curl_exec($ch);
   curl_close($ch);

   $data = json_decode($res, true);

   return $data;
}
?>
