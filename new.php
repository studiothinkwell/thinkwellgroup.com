<?php



session_start ;
set_time_limit(0); //removes php time limit

ini_set('max_upload_filesize', '999M'); 
ini_set('post_max_size','999M');

$host = "dbase.dev.thinkwellgroup.com" ;
$name = "twdevdbuser" ;
$pass = "itssosecret1" ;

mysql_connect($host,$name,$pass) ;
mysql_select_db("dev_thinkwellgroup_com") ;

//checks form submission
if(isset($_POST['button']))
{
    $ftp_config['server'] = 'dev.thinkwellgroup.com'; //ftp hostname
    $ftp_config['username'] = 'stwdrupal'; // ftp username
    $ftp_config['password'] = 'webonlock1'; // ftp password for ftp username
	$ftp_config['port'] = "21" ; // port number
    $ftp_config['web_root'] = 'upload'; //ftp folder

    $fileElementName = 'userFile'; //filefield name.

    $conn_id = ftp_connect($ftp_config['server'],$ftp_config['port']);
	
	$varid = serialize($conn_id) ;
	$sess = session_id() ;
	
	$sql = "INSERT INTO `session_detail` (`id` ,`object` ,`sess_id`) VALUES (NULL , '".$varid."', '".$sess."')" ;
	$res = mysql_query($sql) or die(mysql_error()) ;
	
    $ftp_login = ftp_login($conn_id,$ftp_config['username'],$ftp_config['password']);
    $file_upload_limit_size = (1024*1024*1025*500); //upload file size limit 100mb
	
	
	
    if(!ftp_put($conn_id,'dev.thinkwellgroup.com/upload/'.$_FILES[$fileElementName]['name'],$_FILES[$fileElementName]['tmp_name'],FTP_BINARY)){
        $result = " An error occurred while uploading... ";
    }else{
        $result = " File has been uploaded. ";
    }
    echo $result;
}
?>


<h2>file upload form</h2>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="userAction" value="send" >
<input name="userFile" id="userFile" type="file">
<input name="button" id="button" value="Upload file" type="submit">
</form>
<?php
	echo '<img src="index.jpeg" onclick="replace();"  >' ;
?>

<script type="text/javascript">
var http = false;

if(navigator.appName == "Microsoft Internet Explorer") {
  http = new ActiveXObject("Microsoft.XMLHTTP");
} else {
  http = new XMLHttpRequest();
}

function replace() 
{
  http.open("POST", "ajax.php?con_id=<?php echo $conn_id?>", true);
  http.onreadystatechange=function() {
    if(http.readyState == 4) {
      alert(http.responseText);
    }
  }
  http.send(null);
}
</script>

<?php
	echo phpinfo() ;
?>