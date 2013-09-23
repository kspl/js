<?php   

/* 33
$zip = '1323 rg';
$site = file_get_contents('http://geocoder.ca/?postal='.$zip, false, NULL, 1000, 1000);
$goods = strstr($site, 'GPoint('); // cut off the first part up until 
$end = strpos($goods, ')'); // the ending parenthesis of the coordinate
$cords = substr($goods, 7, $end - 7); // returns string with only the 
$array = explode(', ',$cords); // convert string into array
echo "<pre>";
print_r($array); // output the array to verify

exit;*/

$con = mysql_connect("localhost","root","");
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("my_db", $con);

 $code = '1354 EV';
$op = getLatLong($code);
 print_r($op);
//$main_sql = getSQL($op['Latitude'] , $op['Longitude'] );
 
    function getLatLong($code){
         $mapsApiKey = 'AIzaSyAZb31BXm80Zx9ppVTFNpN2ZTlxlsfV_TU';
         $query = "http://maps.google.com/maps/geo?q=".urlencode($code)."&output=json&key=".$mapsApiKey; 
         
         $data = file_get_contents($query);
         // if data returned
         if($data){
            // convert into readable format
            $data = json_decode($data);
            $long = $data->Placemark[0]->Point->coordinates[0];
            $lat = $data->Placemark[0]->Point->coordinates[1];
            return array('Latitude'=>$lat,'Longitude'=>$long);
         }else{
            return false;
         }
    } 
exit; 
 
    function getSQL($lat,$long , $where_clause =''){ 
        $radious =15;
        if($lat && $long ){
             echo $sql =" SELECT *, ( 6371 * acos( cos( radians($lat) ) * cos( radians( lattitude ) ) * cos( radians( longitude ) - radians($long) ) + sin( radians($lat) ) * sin( radians( lattitude ) ) ) ) AS distance FROM tbl_user $where_clause HAVING distance < $radious ORDER BY distance LIMIT 0 , 2;";
             
             $res = mysql_query($sql);
             if($res !=''){
                 while($array_result = mysql_fetch_assoc($res) ){
                    $op[] = $array_result;             
                 }
                 if(is_array($op) && count($op) > 0 ){
                    return $op;
                 }else{
                    return false;
                 }
             }
        }else{

        }
    }
function getPostcode($lat, $lng) {
  $returnValue = NULL;
  $ch = curl_init();
  $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&sensor=false";
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $result = curl_exec($ch);
  $json = json_decode($result, TRUE);

  if (isset($json['results'])) {
     foreach    ($json['results'] as $result) {
        foreach ($result['address_components'] as $address_component) {
          $types = $address_component['types'];
          if (in_array('postal_code', $types) && sizeof($types) == 1) {
             $returnValue = $address_component['short_name'];
          }
    }
     }
  }
  return $returnValue;
}
 
 


  
  


 

