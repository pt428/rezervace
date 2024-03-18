<?php  
$msg="";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if(isset($_POST)){
    require "assets/db.php";
    $mysqli=connectDB();                
                
    $stmt=$mysqli->prepare("select * from admin_login where admin_id=1");
    $bookings=array();
    if($stmt->execute()){
        $result=$stmt->get_result();
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $user[]=$row["admin_name"];
                $pass[] = $row["admin_password"];
                
            }
            
            $stmt->close();
        }
    }

 
    if($_POST["user"]===$user[0] and $_POST["pass"]===$pass[0]){
        session_start();
        $_SESSION["admin"]=1;
        header("Location:./admin.php ");
        // header("Location:https://obecnidum.unas.cz//admin.php");


    }else{
        $msg=  "<div class='alert alert-danger'>Špatné přihlašovací údaje</div>";
    }

}  
}


?>
<!doctype html>
<html lang="cs">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rezervace</title>
	<link rel="icon" type="image/x-icon" href="/img/favicon.ico">
  
 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
 
    <link rel="stylesheet" href="css/style.css">
  </head>
 
  <body>    
    <div class="container">
    <div class="col-md-6 offset-md-3">
    			<form action="" method="post" >
        				<h1 class="text-centre">Přihlášení do administratorského účtu</h1>
                        <?php echo (isset($msg)?$msg:"") ;?> 

                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Login</span>
        					      <input type="text"  
                            class="form-control" 
                            name="user"
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"
                            required  >
        				        </div>    

                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Heslo</span>
        					      <input type="password" 
                            class="form-control" 
                            name="pass"
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg"
                            required  >
        				      </div>
                      <div class="d-grid gap-2">
        				      <button class="btn btn-primary btn-lg btn-block" 
                            type="submit" 
                            name="submit">---- Odeslat ----
                      </button>        				
                      <a class="btn btn-info btn-lg btn-block" href="index.php">Zpět do kalendáře</a>
                      </div>
    			</form>    			
    			
 


  
          </div>
    </div>
  </body>
</html>