<!doctype HTML>
<html>
<head>
	<link href="header_style.css" rel="stylesheet" type="text/css">
	<link href="content_style.css" rel="stylesheet" type="text/css">
		<meta charset="utf-8">
</head>
<body>
	<?php require_once 'nav.php'; ?>
		<br><br>
	<?php if (!(isset($_POST["btnSearch"])))	{ ?>
		
		<form name="form1" method="post"
			action="<?php echo htmlentities($_SERVER['PHP_SELF']);	?>	">
			<table class="ContentStyle">
				<tr></tr>
					<tr>
						<td>Patrol Car ID :</td>
						<td><input type="text" name="patrolCarId" id="patrolCarId"></td>
						<td><input type="submit" name="btnSearch" id="btnSearch" value="Search"></td>
					</tr>
			</table>
	
	<?php	}	
		else	{
			require_once 'db.php';
			
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
				if ($conn->connect_error)	{
					die("Connection failed! : " . $conn->connect_error);
				}
				
					$sql = "SELECT * FROM patrolcar WHERE patrolcar_id = '".$_POST['patrolCarId']."'";
					$result = $conn->query($sql);
					
					if ($result->num_rows == 0)	{
						?>
							<script type="text/javascript">window.location="./update.php";</script>
					<?php	}	
					
						while ($row = $result->fetch_assoc())	{
							$patrolCarId = $row['patrolcar_id'];
							$patrolCarStatusId = $row['patrolcar_status_id'];
						}
						
							$sql = "SELECT * FROM patrolcar_status";
							$result = $conn->query($sql);
								
								if ($result->num_rows == 0)	{
									while ($row = $result->fetch_assoc())	{
										$patrolCarStatusArray[$row['patrolcar_status_id']] =
										$row['patrolcar_status_desc'];
									}
								}
								
								while ($row = $result->fetch_assoc())	{
									$patrolCarStatusArray[$row['patrolcar_status_id']] =
									$row['patrolcar_status_desc'];
								}
					$conn->close()
					?>
								
					
		<form name="form2" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);	?>	">
				<table class="ContentStyle">
					<tr></tr>
					<tr>
						<td>ID :</td>
						<td><?php echo $patrolCarId	?>
							<input type="hidden"　name="patrolCarId" id="patrolCarId"
							value="<?php echo $patrolCarId	?>">
						</td>
					</tr>
					<tr>
						<td>Status :</td>
						<td><select name="patrolCarStatus" id="patrolCarStatus">
						 <?php foreach($patrolCarStatusArray as $key => $value) { ?>
						  <option value="<?php echo $key ?>"
						 <?php if ($key == $patrolCarStatusId)	{?> selected="selected"
						 <?php	}	?>
					>
						 <?php echo $value ?>
						  </option>
						 <?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><input type="reset" name="btnCancel" id="btnCancel" value="Reset"></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"
						name="btnUpdate" id="btnUpdate" value="Update"> </td>
					</tr>
				</table>
			</form>
			
		<?php
		if (isset($_POST["btnUpdate"]))	{
			require_once 'db.php';
			
				$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
					if ($conn->connect_error)	{
						die("Connection failed! : " . $conn->connect_error);
					}
					
				$sql = "UPDATE patrolcar SET patrolcar_status_id = '".$_POST['patrolCarStatus']."' WHERE patrolcar_id
				= '".$_POST['patrolCarId']."'";
					if ($conn->query($sql) === FALSE)	{
						echo "Error! : " . $sql . "<br>" . $conn->error;
					}
					
					if ($_POST["patrolCarStatus"] == '4')	{
						$sql = "UPDATE dispatch SET time_arrived = NOW() WHERE time_arrived is NULL AND patrolcar_id
						= '".$_POST['patrolCarId']."'";
						
						if ($conn->query($sql) === FALSE)	{
							echo "Error! : " . $sql . "<br>" . $conn->error;
						}
					}
						else if ($_POST["patrolCarStatus"] == '3')	{
							$sql = "SELECT incident_id FROM dispatch WHERE time_completed IS NULL AND patrolcar_id =
							'".$_POST['patrolCarId']."'";
							$result = $conn->query($sql);
							if ($result->num_rows > 0)	{
								while ($row = $result->fetch_assoc())	{
									$incidentId = $row['incident_id'];
								}
							}
							
							$sql = "UPDATE dispatch SET time_arrived = NOW() WHERE time_arrived is NULL AND 
							patrolcar_id = '".$_POST['patrolCarId']."'";
								if ($conn->query($sql)===FALSE)	{
									echo "Error! : " . $sql . "<br>" . $conn->error;
								}
								
								$sql = "UPDATE incident SET incident_status_id = '3' WHERE incident_id = '$incidentId'
								AND NOT EXISTS (SELECT * FROM dispatch WHERE time_completed IS NULL AND 
								incident_id = '$incidentId')";
								
								if ($conn->query($sql)===FALSE)	{
									echo "Error! : " . $sql . "<br>" . $conn->error;
								}
						}
					$conn->close();
					?>
				<script type="text/javascript">window.location="./logcall.php";</script>
		<?php	}
				}		?>
</body>
</html>