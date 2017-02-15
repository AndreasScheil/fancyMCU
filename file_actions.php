<?php 
// to write/create files 
//chown -R www-data:www-data filebin
//chmod -R g+w filebin

	include "inc_header.php";	// Header
	include "inc_db.php";	// DB

if(isset($_GET['DEL'])) {
	$id=$_GET['DEL'];
	$file=$_GET['file'];
	array_map('unlink', glob("./filebin/$id/$file"));

header("location:index.php?ID=$id");
exit();

}
elseif(isset($_GET['ADD'])) {
	$id=$_GET['ADD'];
	
echo  <<<EOT
<a href="index.php?ID=$id">back</a>
<div class="row">
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<h3>Remote Management - add new file</h3>
		<form method=post action="file_actions.php" name="form1">
			<input type="hidden" name="ADD" value="$id">
			<table class="table table-hover">
				<tr>
					<td>Filename:</td>
					<td><input type="text" name="file" class="form-control" value=".lua"/></td>
				</tr>
			</table>
			<input type="submit" name=sub value="save" class="form-control">
		</form>
	</div>
	<div class="col-md-4"> </div>
</div>
   
EOT;
  
}
elseif(isset($_POST['ADD'])) {
	$file=$_POST['file'];
	$id=$_POST['ADD'];
	//write lua file
	if (!file_exists("./filebin/$id/$file")) {
		$myfile = fopen("./filebin/$id/$file", "w") or die("Unable to open file!");
		fwrite($myfile, "Hi!");
		fclose($myfile);
	}
	
	header("location:index.php?ID=$id");
	exit();

}
else{
header("location:index.php");
exit();
}