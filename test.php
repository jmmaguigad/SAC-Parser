<?php
// echo substr("hasd", 0, 1);
$string = "02/11/92";
// $arr = explode("/", $string, 2);
// $first = $arr[0];
// echo $first;
// echo substr($string,1);

// getting 2 digit year
// $twodigits = substr($string,strlen($string)-(strlen($string)+2),2);

// getting remain date
// echo trim(str_replace($twodigits,"",$string));

// echo strlen($string);
// echo 8-10;
// echo strlen($string)-(strlen($string)+2);
// $date = 7/23/1999;
// echo date("m/d/Y",strtotime($date));

// function formatBarcodeNumber($barcode){
//     $length = strlen($barcode);
//     $zero = "";
//     for($l=0;$l < (8 - $length);$l++){
//       $zero .= "0"; 
//     }
//     $returnval = $zero . $barcode;
//     return 'PH-COVID--'.$returnval;
// }

// echo formatBarcodeNumber("1");
// $haystack,$needle

// function findID($haystack){
//   if (!empty($haystack)){
//     if(stristr($haystack,"driv") !== false) {
//       $id = "DRIVER'S LICENSE";
//     } else if (stristr($haystack,"barang") !== false || stristr($haystack,"brgy") !== false){
//       $id = "BARANGAY CERTIFICATION";
//     } else if (stristr($haystack,"company") !== false || stristr($haystack,"employee") !== false){
//       $id = "EMPLOYMENT ID";
//     } else if (stristr($haystack,"gsis") !== false) {
//       $id = "GSIS UMID";
//     } else if (stristr($haystack,"ncip") !== false || stristr($haystack,"i.p") !== false){
//       $id = "NCIP CERTIFICATION";
//     } else if (stristr($haystack,"ofw") !== false || stristr($haystack,"owwa") !== false){
//       $id = "OFW";
//     } else if (stristr($haystack,"passport") !== false){
//       $id = "PASSPORT";
//     } else if (stristr($haystack,"health") !== false || stristr($haystack,"phil") !== false){
//       $id = "PHILHEALTH";
//     } else if (stristr($haystack,"postal") !== false){
//       $id = "POSTAL";
//     } else if (stristr($haystack,"prc") !== false){
//       $id = "PRC";
//     } else if (stristr($haystack,"pwd") !== false){
//       $id = "PWD";
//     } else if (stristr($haystack,"osca") !== false || stristr($haystack,"senior") !== false || stristr($haystack,"citizen") !== false || 
//       stristr($haystack,"s.c") !== false || stristr($haystack,"sc") !== false){
//       $id = "SENIOR CITIZEN";
//     } else if (stristr($haystack,"sss") !== false){
//       $id = "SSS UMID";
//     } else if (stristr($haystack,"solo") !== false || stristr($haystack,"single") !== false){
//       $id = "SOLO PARENT";
//     } else if (stristr($haystack,"tin") !== false || stristr($haystack,"bir") !== false){
//       $id = "TIN";
//     } else if (stristr($haystack,"vote") !== false){
//       $id = "VOTER'S ID";
//     } else {
//       $id = "OTHERS";
//     }
//   } else {
//     $id = "NONE";
//   }
//   return $id;
// }

// echo findID("romy");

// Barcode
// $barcode = "--0001258";

// function formatBarcodeNumber($psgc,$barcode){
//   // check if there's any dash present
//   if (stristr($barcode,"-") !== false){
//     $barcode = substr($barcode, strrpos($barcode, '-') + 1);
//   }
//   $length = strlen($barcode);
//   $zero = "";
//   for($l=0;$l < (8 - $length);$l++){
//     $zero .= "0"; 
//   }
//   $returnval = $zero . $barcode;
//   return 'PH-COVID-'.$psgc.'-'.$returnval;
// }

// echo formatBarcodeNumber("021238436",$barcode);


// $filteredNumbers = array_filter(preg_split("/\D+/", $string));
// $firstOccurence = reset($filteredNumbers);
// echo $firstOccurence; // 3

// function my_ofset($text){
//   preg_match('/^\D*(?=\d)/', $text, $m);
//   return isset($m[0]) ? strlen($m[0]) : false;
// }

  //date in mm/dd/yyyy format; or it can be in other formats as well
  // $birthDate = "12/17/1983";
  // //explode the date to get month, day and year
  // $birthDate = explode("/", $birthDate);
  // //get age from date or birthdate
  // $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
  //   ? ((date("Y") - $birthDate[2]) - 1)
  //   : (date("Y") - $birthDate[2]));
  // echo "Age is:" . $age;

  var_dump(strlen("test tetsts tes et"))
?>