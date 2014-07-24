
<?php 
 // Connects to your Database 

 
 //This checks to see if there is a page number. If not, it will set it to page 1 

if (!(isset($_GET['pagenum']))) { 

	$pagenum = 1; 
} else {
	$pagenum = $_GET['pagenum'];
 }

 $rows = mysqli_num_rows($fa_result); 
 $page_rows = 25; 

 //This tells us the page number of our last page 
 $last = ceil($rows/$page_rows); 


 //this makes sure the page number isn't below one, or more than our maximum pages 

 if ($pagenum < 1) { 
	$pagenum = 1; 
} 

 elseif ($pagenum > $last) { 

 $pagenum = $last; 

 } 


 //This sets the range to display in our query 

 $max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
  //This is your query again, the same one... the only difference is we add $max into it

 $fa_result_p = mysqli_query($conn, "SELECT * FROM player $max"); 


 //This is where you display your query results

 while($info = mysqli_fetch_array( $fa_result_p ))  { 
	
	echo $info['firstname']; 
	echo "<br>";
	
 } 

 echo "<p>";
 
 // This shows the user what page they are on, and the total number of pages

 echo " --Page $pagenum of $last-- <p>";

 // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.

 if ($pagenum == 1) {

 } 

 else {

 echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=1'> <<-First</a> ";
 echo " ";
 $previous = $pagenum-1;
 echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous'> <-Previous</a> ";
 
 } 




 //This does the same as above, only checking if we are on the last page, and then generating the Next and Last links

 if ($pagenum == $last) {

 } 

 else {

 $next = $pagenum+1;

 echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next'>Next -></a> ";

 echo " ";

 echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$last'>Last ->></a> ";

 } 

 ?> 