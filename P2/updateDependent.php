<?php
	session_start();	
// Include config file
	require_once "config.php";
 
// Define variables and initialize with empty values
// Note: You can not update SSN 
$Dependent_name = $Relationship = $Sex = $Bdate = "";
$Dependent_name_err = $Relationship_err = $Sex_err = $Bdate_err = "" ;
// Form default values

if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
  $_SESSION["Dname"] = $_GET["Dname"];

  // Prepare a select statement
  $sql1 = "SELECT Dependent_name, Relationship, Sex, Bdate FROM DEPENDENT WHERE Dependent_name = ?";

  if($stmt1 = mysqli_prepare($link, $sql1)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt1, "s", $param_Dependent_name);      
      // Set parameters
     $param_Dependent_name = trim($_GET["Dname"]);
     
      // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt1)){
      $result1 = mysqli_stmt_get_result($stmt1);
          
			if(mysqli_num_rows($result1) > 0){

				$row = mysqli_fetch_array($result1);

				$Dependent_name = $row['Dependent_name'];
				$Relationship = $row['Relationship'];
				$Sex = $row['Sex'];
				$Bdate = $row['Bdate'];
			}
	  }
  }
}

// Post information about the dependent when the form is submitted
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $Dname = $_GET["Dname"];
    // the id is hidden and can not be changed
    $Dependent_name = $_SESSION["Dname"];
    // Validate form data this is similar to the create Employee file
    // Validate Dependent name
    $Dependent_name = trim($_POST["Dependent_name"]);
    if(empty($Dependent_name)){
        $Dependent_name_err = "Please enter a name.";
    } elseif(!filter_var($Dependent_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Dependent_name_err = "Please enter a valid name.";
    } 
    
    // Validate Relationship
    $Relationship = trim($_POST["Relationship"]);
    if(empty($Relationship)){
        $Relationship_err = "Please enter a relationship.";
    } elseif(!filter_var($Relationship, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Relationship_err = "Please enter a valid relationship.";
    }  

    // Validate Sex
    $Sex = trim($_POST["Sex"]);
    if(empty($Sex)){
        $Sex_err = "Please enter a sex.";
    } elseif(!filter_var($Sex, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Sex_err = "Please enter a valid sex.";
    }  

    // Check input errors before inserting into database
    if(empty($Dependent_name_err) && empty($Relationship_err) && empty($Sex_err)){
        // Prepare an update statement
        $sql = "UPDATE DEPENDENT SET Dependent_name=?, Relationship=?, Sex=?, Bdate=? WHERE Dependent_name=?";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_Dependent_name, $param_Relationship, $param_Sex, $param_Bdate, $param_Dname);
            
            // Set parameters
			      $param_Dependent_name = $Dependent_name;            
			      $param_Relationship = $Relationship;
            $param_Sex = $Sex;
            $param_Bdate = $Bdate;
            $param_Dname = $Dname;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: viewDependents.php");
                exit();
            } else{
                echo "<center><h2>Error when updating</center></h2>";
            }
        }        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {

    // Check existence of sID parameter before processing further
	// Form default values

	if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
    $_SESSION["Dame"] = $_GET["Dname"];

		// Prepare a select statement
		$sql1 = "SELECT Dependent_name, Relationship, Sex, Bdate FROM DEPENDENT WHERE Dependent_name = ?";
  
		if($stmt1 = mysqli_prepare($link, $sql1)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt1, "s", $param_Dependent_name);      
			// Set parameters
		$param_Dependent_name = trim($_GET["Dname"]);

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt1)){
				$result1 = mysqli_stmt_get_result($stmt1);
				if(mysqli_num_rows($result1) == 1){

					$row = mysqli_fetch_array($result1);

					$Dependent_name = $row['Dependent_name'];
					$Relationship = $row['Relationship'];
					$Sex = $row['Sex'];
					$Bdate = $row['Bdate'];
				} else{
					// URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}
                
			} else{
				echo "Error in SSN while updating";
			}
		
		}
			// Close statement
			mysqli_stmt_close($stmt);
        
			// Close connection
			mysqli_close($link);
	}  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
	}	
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College DB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h3>Update Record for SSN =  <?php echo $_GET["Ssn"]; ?> </H3>
                    </div>
                    <p>Please edit the input values and submit to update.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
						<div class="form-group <?php echo (!empty($Dependent_name_err)) ? 'has-error' : ''; ?>">
                            <label>Dependent Name</label>
                            <input type="text" name="Dependent_name" class="form-control" value="<?php echo $Dependent_name; ?>">
                            <span class="help-block"><?php echo $Dependent_name_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($Relationship_err)) ? 'has-error' : ''; ?>">
                            <label>Relationship</label>
                            <input type="text" name="Relationship" class="form-control" value="<?php echo $Relationship; ?>">
                            <span class="help-block"><?php echo $Relationship_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($Sex_err)) ? 'has-error' : ''; ?>">
                            <label>Sex</label>
                            <input type="text" name="Sex" class="form-control" value="<?php echo $Sex; ?>">
                            <span class="help-block"><?php echo $Sex_err;?></span>
                        </div>
  		    <div class="form-group <?php echo (!empty($Birth_err)) ? 'has-error' : ''; ?>">
                            <label>Birth date</label>
                            <input type="date" name="Bdate" class="form-control" value="<?php echo $Bdate; ?>">
                            <span class="help-block"><?php echo $Birth_err;?></span>
                        </div>
                        <input type="hidden" name="Ssn" value="<?php echo $Ssn; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="viewDependents.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>