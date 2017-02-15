<?php 
// to write/create files 
//chown -R www-data:www-data filebin
//chmod -R g+w filebin

	include "inc_header.php";	// Header
	include "inc_db.php";	// DB
	
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]index.php";
//foreach($_POST as $key => $value)
//	echo "post: $key : $value";
 
if(isset($_GET['ID'])) {

echo  <<<EOT

<a href="index.php">back</a>

<div class="row">
	
	<div class="col-md-4">
		<h3>Remote Management - Files for ID $_GET[ID]</h3>
		<table class="table table-hover">
			<tr>
				<th>Filename</th>
			</tr>
EOT;

// get current sql data
$sql =<<<EOF
		SELECT id,ip,desc from devices where id=$_GET[ID];
EOF;
 $sqlip="";
 $sqldesc="";

   $ret = $db->query($sql);
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
		$sqlip=$row['ip'];
		$sqldesc=$row['desc'];
   }
   

//List alle files
	if ($handle = opendir("./filebin/$_GET[ID]")) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != ".." && $entry != "bak") {
echo  <<<EOT
            <tr><td><a href="index.php?ID=$_GET[ID]&file=$entry">$entry</a></td><td><a href="file_actions.php?DEL=$_GET[ID]&file=$entry">remove</td></tr>
EOT;
        }
    }

    closedir($handle);
	}
	
	


echo  <<<EOT


		
			
			</table>
			<a href="index.php?mcu=mcu&id=$_GET[ID]&ip=$sqlip">Upload-Manager<a>
			<a href="file_actions.php?ADD=$_GET[ID]">Add new file<a>
			</div>
<div class="col-md-8" style="height:100vh"> 
EOT;

if(isset($_GET['file'])){
	//get file
	$fileEditor="";
	$handle = fopen("./filebin/$_GET[ID]/$_GET[file]", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
	$fileEditor=$fileEditor.$line;
    }

    fclose($handle);
} else {
    // error opening the file.
} 

echo  <<<EOT

<pre id="editor" style="height:70%">$fileEditor</pre>

	<form name="save" method="post" id="save" action="index.php">
		<button type="button" class="btn btn-default btn-xs" name="bttn" id="bttn" value="$_GET[ID]">save</button>
		<input type="hidden" name="id" value="$_GET[ID]">
		<input type="hidden" name="file" value="$_GET[file]">
		<textarea id="area" name=txt style="display:none;"></textarea>
	</form>
	<script>
		var editor = ace.edit("editor");
		/*editor.setTheme("ace/theme/twilight");*/
		editor.session.setMode("ace/mode/lua");
		editor.setHighlightActiveLine(true);
		/*editor.setValue("$fileEditor");*/
		
		$('#save').click(function(){
			$('#area').val(editor.getValue());
			$( "#save" ).submit();
		});
	</script>	
EOT;

}



echo  <<<EOT
</div>

	</div>
	
EOT;

}
elseif(isset($_POST['txt'])){
	
//create backup folder
	if (!file_exists("./filebin/$_POST[id]/bak")) {
		mkdir("./filebin/$_POST[id]/bak", 0777, true);
	}

//backup lua file
	copy("./filebin/$_POST[id]/$_POST[file]", "./filebin/$_POST[id]/bak/$_POST[file]");

//write lua file
	$myfile = fopen("./filebin/$_POST[id]/$_POST[file]", "w") or die("Unable to open file!");
	fwrite($myfile, $_POST['txt']);
	fclose($myfile);

echo  <<<EOT
$_POST[txt]
EOT;

header("location:index.php?ID=$_POST[id]");
exit();

}
elseif(isset($_GET['mcu'])){
	//removing old lua files
	array_map('unlink', glob("./mcu/filebin/*"));
	
	//copy lua files
	
	//copyToDir("./filebin/$_GET[id]/*", './mcu/filebin/'); 
	if ($handle = opendir("./filebin/$_GET[id]")) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "bak") {
				copy("./filebin/$_GET[id]/$entry", "./mcu/filebin/$entry");
			}
		}
		closedir($handle);
	}
	
	
	// setting controller ip
	$myfile = fopen("./mcu/controllerIP.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $_GET['ip']);
	fclose($myfile);
	
	//redirecting to mcu site
	
	header("location:mcu/index.php");
	exit();
	




}
else{
   
?>


<div class="row">
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<h3>Remote Management</h3>
		<table class="table table-hover">
			<tr>
				<th>Name</th>
				<th>IP</th>
				<th>Actions</th>
			</tr>
			
<?php			
	$sql =<<<EOF
		SELECT id,ip,desc from devices;
EOF;

   $ret = $db->query($sql);
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
      echo  <<<EOT
		<tr>
			<td><a href="index.php?ID=$row[id]"> $row[desc]  </a></td>
			<td>$row[ip]</td>
			<td><a class="" href="#" >edit</a>|<a class="" href="device_actions.php?DEL=$row[id]" >remove</a></a></td>
		</tr>
EOT;
   }
   
?> 
  
			
		</table>
		<br>
		<br>
		<a class="btn btn-default btn-xs" href="device_actions.php?ADD=ADD" role="button">+ add new device</a>

	</div>
	<div class="col-md-4"> </div>

</div>



   
   

<?php 
}
	include "inc_footer.php";	// Header
?>

