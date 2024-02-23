<?php 
function prevedNaCeskeNazvyMesicu($param) {
   switch ($param) {
           case "January":
               $monthName="Leden";
               break;
           case "February":
               $monthName="Únor";
               break;
           case "March":
               $monthName="Březen";
               break;
           case "April":
               $monthName="Duben";
               break;
           case "May":
               $monthName="Květen";
               break;
           case "June":
               $monthName="Červen";
               break;
           case "July":
               $monthName="Červenec";
               break;
           case "August":
               $monthName="Srpen";
               break;
           case "September":
               $monthName="Září";
               break;
           case "October":
               $monthName="Říjen";
               break;
           case "November":
               $monthName="Listopad";
               break;
           case "December":
               $monthName="Prosinec";
               break;
       }
 return $monthName;
}


?>