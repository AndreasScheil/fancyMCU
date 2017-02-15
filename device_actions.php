<?php 
// to write/create files 
//chown -R www-data:www-data filebin
//chmod -R g+w filebin

	include "inc_header.php";	// Header
	include "inc_db.php";	// DB

if(isset($_GET['DEL'])) {
	$id=$_GET['DEL'];
	
	// SAVE SQL DATA
$sql =<<<EOF
	delete from devices where id=$id		
EOF;

   $ret = $db->query($sql);
   //Folders will still remain
	header("location:index.php");
	exit();
}
elseif(isset($_GET['EDIT'])){
	$id=$_GET['EDIT'];
	header("location:index.php");
	exit();
}
elseif(isset($_GET['ADD'])){ //ADD MENU

echo  <<<EOT
<a href="index.php">back</a>
<div class="row">
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<h3>Remote Management - add new devices</h3>
		<form method=post action="device_actions.php" name="form1">
			<input type="hidden" name="ADD" value="ADD">
			<table class="table table-hover">
				<tr>
					<td>Description:</td>
					<td><input type="text" name="desc" class="form-control" /></td>
				</tr>
				<tr>
					<td>IP:</td>
					<td><input type="text" name="ip" class="form-control" /></td>
				</tr>
			</table>
			<input type="submit" name=sub value="save" class="form-control">
		</form>
	</div>
	<div class="col-md-4"> </div>
</div>
   
EOT;
  
}
elseif(isset($_POST['ADD'])){ //ADD MENU
echo  <<<EOT
Creating new device ($_POST[desc]:$_POST[ip])	
EOT;

// SAVE SQL DATA
$sql =<<<EOF
	INSERT INTO devices (ip,desc) VALUES ('$_POST[ip]','$_POST[desc]')		
EOF;

   $ret = $db->query($sql);
   
 // get current sql data
$sql =<<<EOF
		SELECT seq from SQLITE_SEQUENCE;
EOF;


$sqlid="";
   $ret = $db->query($sql);
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
		$sqlid=$row['seq'];		
   }
   
// adding ID folder
	if (!file_exists("./filebin/$sqlid")) {
		mkdir("./filebin/$sqlid", 0777, true);
	}
   
   header("location:index.php");
exit();
   
  
   

}
else{	
	header("location:index.php");
	exit();
}
