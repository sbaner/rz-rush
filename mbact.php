<?php
	session_start();
	ob_start();
	date_default_timezone_set('America/New_York');
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	if (isset($_POST['delthread'])) {
		$threadid = $_POST['delthread'];
		$thread_result = mysqli_query($conn,"SELECT * FROM mb_posts WHERE threadid=$threadid AND post_type='thread'");
		$threadData = mysqli_fetch_array($thread_result);
		if ($threadData['author']==$userID) {
			mysqli_query($conn,"UPDATE mb_posts SET status='delete' WHERE threadid=$threadid");
		}
		header('Location: mboard.php?leagueid='.$threadData['boardid']);
	} else if (isset($_POST['delpost'])) {
		$postid = $_POST['delpost'];
		$post_result = mysqli_query($conn,"SELECT * FROM mb_posts WHERE postid=$postid");
		$postData = mysqli_fetch_array($post_result);
		if ($postData['author']==$userID) {
			mysqli_query($conn,"UPDATE mb_posts SET status='delete' WHERE postid=$postid");
		}
		header('Location: mbthread.php?threadid='.$postData['threadid']);
	} else if (isset($_POST['newthread'])) {
		$subject = mysqli_real_escape_string($conn,$_POST['subject']);
		$text = mysqli_real_escape_string($conn,$_POST['threadtext']);
		$boardid = mysqli_real_escape_string($conn,$_POST['boardid']);
		$thread_result = mysqli_query($conn,"SELECT threadid FROM mb_posts ORDER BY threadid DESC LIMIT 1");
		if (mysqli_num_rows($thread_result)==0) {
			$threadid=1;
		} else {
			$threadData = mysqli_fetch_array($thread_result);
			$threadid = $threadData['threadid']+1;
		}
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		$newthread_query = "INSERT INTO mb_posts (boardid,threadid,subject,text,post_type,timestamp,author,status,last_mod) VALUES ('$boardid',$threadid,'$subject','$text','thread','$timestamp',$userID,'publish','$timestamp')";
		mysqli_query($conn,$newthread_query);
		header('Location: mbthread.php?threadid='.$threadid);
	} else if (isset($_POST['newreply'])) {
		$text = mysqli_real_escape_string($conn,$_POST['replytext']);
		$threadid = mysqli_real_escape_string($conn,$_POST['threadid']);
		$board_result = mysqli_query($conn,"SELECT boardid FROM mb_posts WHERE threadid=$threadid LIMIT 1");
		$boardData = mysqli_fetch_array($board_result);
		$boardid = $boardData['boardid'];
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		$newreply_query = "INSERT INTO mb_posts (boardid,threadid,text,post_type,timestamp,author,status) VALUES ('$boardid',$threadid,'$text','subpost','$timestamp',$userID,'publish')";
		mysqli_query($conn,$newreply_query);
		mysqli_query($conn,"UPDATE mb_posts SET last_mod='$timestamp' WHERE threadid=$threadid AND post_type='thread'");
		header('Location: mbthread.php?threadid='.$threadid);
	} else {
		header('Location: 404.php');
	}
ob_flush();
?>