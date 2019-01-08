
<?php 
include "includes.php";

$sql = "select id,  commenttext, resolutiontext from tblcomments";
$stmt = $dbconn->prepare($sql);
$stmt->execute();	

while ($row = $stmt->fetch()) {
	
	$text = $row["commenttext"];
	
	
	//$text = strip_tags($text, "<div>, <p>, <br>, <br />");
	
	$text = iconv("UTF-8","UTF-8//IGNORE",$text);
	
	echo $text . "<br /><br />";
	
	$sql1 = "update tblcomments set commenttype = ?, resolutiontext = ? where id = ?";
	$stmt1 = $dbconn->prepare($sql1);
	//$stmt1->execute(array($row1["commenttype"], $row1["resolutiontext"], $row1["id"]));	


	
};
	

?>