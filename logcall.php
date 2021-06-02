<!doctype HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title> Police Emergency Service System </title>
			<link href="header_style.css" rel="stylesheet" type="text/css">
			<link href="content_style.css" rel="stylesheet" type="text/css">
				<script type="text/javascript">
					function validateForm()
					{
						var x=document.forms["frmLogCall"] ["callerName"].value;
						if (x==null || x=="")
						{
							alert("Caller Name is required!");
							return false;
						}
						
							var x=document.forms["frmLogCall"] ["contactNo"].value;
							if (x==null || x=="")
							{
								alert("Contact Number is required!");
								return false;
							}
						
						var x=document.forms["frmLogCall"] ["location"].value;
						if (x==null || x=="")
						{
							alert("Location is required!");
							return false;
						}
						
							var x=document.forms["frmLogCall"] ["incidentDesc"].value;
							if (x==null || x=="")
							{
								alert("Incident Description is required!");
								return false;
							}
					}
				</script>

	<style>
		.button {
		  display: inline-block;
		  padding: 5px 15px;
		  font-size: 20px;
		  cursor: pointer;
		  text-align: center;
		  text-decoration: none;
		  outline: none;
		  color: #fff;
		  background-color: #4CAF50;
		  border: none;
		  border-radius: 15px;
		  box-shadow: 0 9px #999;
		}

		.button:hover {background-color: #3e8e41}

		.button:active {
		  background-color: #3e8e41;
		  box-shadow: 0 5px #666;
		  transform: translateY(4px);
		}
	</style>
				
	</head>
	
<body class="ContentStyle">
	
	<?php require_once 'nav.php'; ?>
	<?php require_once 'db.php'; 
	
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
			if ($mysqli->connect_error)	{
				die("Connection failed: " . $mysqli->connect_error); 
			}			
	
			$sql = "SELECT * FROM incident_type";
				if(!($stmt = $mysqli->prepare($sql))) {
					die("Prepare FAILED! : " . $mysqli->errno);
				}
					if(!($stmt->execute())) {
						die("Execute failed! : " . $stmt->errno);
					}
				if(!($resultset = $stmt->get_result())) {
					die("Getting result set failed! : " . $stmt->errno);
				}
		
			$incidentType;
			while ($row = $resultset->fetch_assoc())	{
				$incidentType[$row['incident_type_id']] = $row['incident_type_desc'];	
			}
			
	$stmt->close();
	$resultset->close();
	$mysqli->close();
	?>
	<br><br>
	
	<form name="frmLogCall" method="post"
	onSubmit="return validateForm()" action="dispatch.php">
			<table class="Contentstyle">
				<tr>
					<td colspan="2">Log Call Panel</td>
				</tr>
				
				<tr>
					<td>Caller's Name : </td>
					<td><input type="text" name="callerName" id="callerName"></td>
				</tr>
				
					<tr>
						<td>Contact No. :</td>
						<td><input type="text" name="contactNo" id="contactNo"></td>
					</tr>
				
				<tr>
					<td>Location :</td>
					<td><input type="text" name="location" id="location" /></td>
				</tr>
				
					<tr>
						<td>Incident Type :</td>
						<td>
							<select name="incidentType" id="incidentType">
								<?php foreach( $incidentType as $key => $value) { ?>
									<option value="<?php echo $key ?>">
										<?php echo $value ?>
									</option>
								<?php
									}
								?>
								</select>
						</td>
					</tr>
				
				<tr>
					<td>Description :</td>
					<td>
						<textarea name="incidentDesc" id="incidentDesc"
						cols="45" rows="5"></textarea>
					</td>
				</tr>
				<tr>
					<td><input type="Reset" name="btnCancel" id="btnCancel"
						value="Reset">
					</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"
					name="btnProcessCall" id="btnProcessCall" value="Process Call...">
					</td>
				</tr>	
		</table>
	</form>
	</div>
</body>
</html>