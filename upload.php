<?php	
session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} 	
	
$upload_errors = array(
UPLOAD_ERR_OK	      => "No errors.",
UPLOAD_ERR_INI_SIZE	  => "Larger than upload_max_filesize.",
UPLOAD_ERR_FORM_SIZE  => "Larger than form MAX_FILE_SIZE.",
UPLOAD_ERR_PARTIAL	  => "Partial upload.",
UPLOAD_ERR_NO_FILE    => "No file.",
UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
UPLOAD_ERR_EXTENSION  => "File upload stopped by extension.",
);

function findexts ($filename) 
	{
		$filename = strtolower($filename) ; 
		$exts = split("[/\\.]", $filename) ; 
		$n = count($exts)-1; 
		$exts = $exts[$n]; 
		return $exts; 
	} 
	
if (isset($_POST['submit'])){
 
	 $tmp_file = $_FILES['upload_file']['tmp_name'];
	 @$target_file = basename($_FILES['upload_file']['name']);
	 $upload_dir = "uploads";
	 $imgsize = $_FILES['upload_file']['size'];	
	 $imgtype = $_FILES['upload_file']['type'];
	 $member_id = $_SESSION['member_id'];
 
	if ($imgtype == "image/jpeg" || $imgtype == "image/png") {
  
		$ext = findexts ($_FILES['upload_file']['name']); 
		$ran = rand();
		$ran2 = $ran.".";
		$target_file = $ran2.$ext;
 
	if (move_uploaded_file($tmp_file,$upload_dir."/".$target_file)){
			$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
			mysqli_query($conn,"UPDATE `photos` SET `pri`='no' WHERE   member_id ='{$userID}'");
			mysqli_query($conn,"INSERT INTO `photos`(`filename`, `type`, `size`, `member_id`,`pri`) 
				VALUES ('{$target_file}', '{$imgtype}', '{$imgsize}', '{$userID}','yes' )");
 
			if (mysqli_affected_rows($conn) == 1) {
 
				echo "<script type=\"text/javascript\">
							alert(\"Profile picture changed.\");
						</script>";
						
 
			} else{
				echo "<script type=\"text/javascript\">
							alert(\"Upload failed!\");
						</script>";
			}
 
			//echo "File uploaded Succesfully";
 
		} else {
			$error = $_FILES['upload_file']['error'];
			$message = $upload_errors[$error];
			echo "<script type=\"text/javascript\">
							alert(\".{$message}.\");
 
						</script>";
		}
 
		} else {
		echo "<script type=\"text/javascript\">
							alert(\"Invalid image format. JPG or PNG only, please.\");
						</script>";
 
 
		}
		
		header('Location: profile.php');
 
}
?>