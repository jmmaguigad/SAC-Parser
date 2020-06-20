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

  // var_dump(strlen("test tetsts tes et"))

  function firstCharacter($word){
    return substr(strtoupper($word), 0, 1);
  }

  // function replaceSpecialCharacter($replacement,$word){
  //   $word = preg_replace('/[^A-Za-z0-9-]/', '', $word);
  //   return str_replace("-",$replacement,$word);
  // }

  function findSector($birthday,$kasarian,$haystack){
    if (!empty($haystack)){
      if(stristr($haystack,"buntis") !== false || firstCharacter($haystack) == "B") {
        $sector = "B - Buntis";
      } else if(stristr($haystack,"tanda") !== false || firstCharacter($haystack) == "A") {
        $birthDate = explode("/", $birthday);
        $arrCount = count($birthDate);
        if ($arrCount >= 3){
          $birthDate = array_values(array_filter($birthDate));
          $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
          ? ((date("Y") - $birthDate[2]) - 1)
          : (date("Y") - $birthDate[2]));          
        } else if ($arrCount < 3){
          $age = date("Y") - $birthDate[1];
        }
        if ($age > 59){
          $sector = "$age - A - Nakakatanda";
        } else {
          $sector = "$age - W";
        }           
      } else if(stristr($haystack,"suso") !== false || firstCharacter($haystack) == "C") {
        if ($kasarian == "M"){
          $sector = "W";
        } else {
          $sector = "C - Nagpapasusong Ina";
        }     
      } else if(stristr($haystack,"pwd") !== false || firstCharacter($haystack) == "D") {
        $sector = "D - PWD";
      } else if(stristr($haystack,"solo") !== false || firstCharacter($haystack) == "E") {
        $sector = "E - Solo Parent";
      } else if(stristr($haystack,"homeless") !== false || stristr($haystack,"tirahan") !== false || firstCharacter($haystack) == "F") {
        $sector = "F - Walang Tirahan";
      } else {
        $sector = "W";
      }
    } else {
      $sector = "W";
    }
    return $sector;
  }

  function findRelHH($haystack){
    if (!empty($haystack)){
      if(stristr($haystack,"puno") !== false || stristr($haystack,"pamilya") !== false || firstCharacter($haystack) == "1") {
        $relHH = "1 - Puno ng Pamilya";
      } else if(stristr($haystack,"asawa") !== false || stristr($haystack,"mister") !== false || stristr($haystack,"misis") !== false ||  stristr($haystack,"live") !== false || firstCharacter($haystack) == "2" || firstCharacter($haystack) == "2") {
        $relHH = "2 - Asawa";
      } else if(stristr($haystack,"anak") !== false || firstCharacter($haystack) == "3") {
        $relHH = "3 - Anak";
      } else if (stristr($haystack,"kapatid") !== false || firstCharacter($haystack) == "4" || stristr($haystack,"brother") !== false || stristr($haystack,"sister") !== false) {
        if (stristr($haystack,"law") !== false) {
          $relHH = "8 - Other Relative";
        }else{
          $relHH = "4 - Kapatid";
        }
      } else if (stristr($haystack,"bayaw") !== false || stristr($haystack,"hipag") !== false || firstCharacter($haystack) == "5") {
        $relHH = "5 - Bayaw o Hipag";
      } else if (stristr($haystack,"apo") !== false || (stristr($haystack,"grand") !== false && (stristr($haystack,"son") !== false || stristr($haystack,"daugh") !== false))) {
        $relHH = "6 - Apo";
      } else if (stristr($haystack,"tatay") !== false || stristr($haystack,"nanay") !== false || (stristr($haystack,"law") !== false && (stristr($haystack,"mother") !== false || stristr($haystack,"father") !== false))) {
        $relHH = "7 - Tatay/Nanay";
      } else {
        $relHH = "8 - Other Relative";
      }
    } else {
      $relHH = "8 - Other Relative";
    }
    return $relHH;
  }

  echo findRelHH("");
  // echo findSector(replaceSpecialCharacter("/","05-04-1970"),"A");
?>