<?php 

function connectDB(){
 
         // $connection=mysqli_connect("sql5.webzdarma.cz","obecnidumuna2249","08._h.#9A,^D&ele%196","obecnidumuna2249");
           $connection=mysqli_connect("localhost","root","","obecnidumuna2249");
     

  
 
    
    return $connection;
}
 

function connectDBSend2Queries($query,$query2){
    
    $connection=connectDB();
    if($connection){
        //echo "Jsme propojeni s db";
    }else{
        die ("Nespojeno s db");
    }

    $result=mysqli_query($connection, $query);
    if(!$result){
        die("Dotaz do databaze selhal".mysqli_error($connection));
    }
  
    $result2=mysqli_query($connection, $query2);
    if(!$result2){
        die("Query selhalo". mysqli_error($connection));
    }
    //mysql_free_result($result);
    mysqli_close($connection);
    
    
}
function connectDBSendQuery($query){   
    $connection=connectDB();
    if($connection){
        //echo "Jsme propojeni s db";
    }else{
        die ("Nespojeno s db");
    }   
    $result=mysqli_query($connection, $query);
    if(!$result){
        die("Dotaz do databaze selhal".mysqli_error($connection));
    }
    //mysql_free_result($result);
    mysqli_close($connection);
}
?>