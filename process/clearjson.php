<?php 
  require_once('controllers/config/dbconn.php');
  $queryheader = "DELETE FROM agreement_header";
                  if(mysqli_query($conn,$queryheader))
                  {

                  }
                  else
                  {
                    echo "error ".$queryheader."<br>".$conn->error;
                  }
  
?>