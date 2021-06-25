<?php
	session_start();
	ob_start();
	$Ssn = $_SESSION["Ssn"];
	// Include config file
	require_once "config.php";
?>

<?php 
	// Define variables and initialize with empty values
	$Dependent_name = $Relationship = $Sex = $Bdate = "";
	$Dependent_name_err = $Relationship_err = $Sex_err = $Bdate_err = $Ssn_err = "" ;

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    
    // Validate Bdate
    $Bdate = trim($_POST["Bdate"]);
    if(empty($Bdate)){
        $Bdate_err = "Please enter birthdate.";     
    }	
    
		// Validate the SSN
		if(empty($Ssn)){
			$Ssn_err = "No SSN.";     
		}   
    
    // Check input errors before inserting in database
		if(empty($Ssn_err) && empty($Dependent_name_err) && empty($Relationship_err) && empty($Sex_err)){
        // Prepare an insert statement
			$sql = "INSERT INTO DEPENDENT (Essn, Dependent_name, Sex, Bdate, Relationship) VALUES (?, ?, ?, ?, ?)";


      if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'sssss', $param_Ssn, $param_Dependent_name, $param_Sex, $param_Bdate, $param_Relationship);
            
				// Set parameters
				$param_Ssn = $Ssn;
				$param_Dependent_name = $Dependent_name;
				$param_Sex = $Sex;
        $param_Bdate = $Bdate;
        $param_Relationship = $Relationship;
        
            // Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
               // Records created successfully. Redirect to landing page
				    header("location: viewDependents.php");
				} else{
					// Error
					
					$SQL_err = mysqli_error($link);
				}

			}
         
        // Close statement
        mysqli_stmt_close($stmt);
		
	}   
		// Close connection
		mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company DB</title>
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
                        <h3>Create a Dependent</h3>
                    </div>
		
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}

  echo"<h4>Employee SSN = ".$Ssn."</h4><p>";
?>
	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($Dependent_name)) ? 'has-error' : ''; ?>">
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
      <input type="date" name="Bdate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
      <span class="help-block"><?php echo $Birth_err;?></span>
    </div>
    
		<div>
			<input type="submit" class="btn btn-primary" value="Submit">
      &nbsp;
			<a href="viewDependents.php" class="btn btn-default">Cancel</a>

		</div>
	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>