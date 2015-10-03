<?php
//Setup SQL connection
require_once('database.php');
require_once('functions.php');

//Checks
if (empty($_GET['userid'])){
	die("You need to provide an userID!");
}

//Init variables
$usernameid = mysql_escape_string($_GET['userid']); //The user we're looking for
$usernameid2;
$userFirstName;
$userFirstName2;
$userLastName;
$userLastName2;
$userImage;
$userImage2;

//First query, get first user information
$result = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$usernameid'") or die(mysql_error());
$row = mysql_fetch_array($result);

//Second query, get second user information (if user2 is speceified)
if (!empty($_GET['userid2'])){
	$usernameid2 = mysql_escape_string($_GET['userid2']); //Needed when we're doing some communications only query
	$result2 = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$usernameid2'") or die(mysql_error());
	$row2 = mysql_fetch_array($result2);
}

//Put information in variables
$userFirstName = $row['FName'];
$userLastName = $row['LName'];
$userImage = $row['ThumbUrl'];

//Check if userid2 is empty and if its not add information to the variables
if (!empty($_GET['userid2'])){
	$userFirstName2 = $row2['FName'];
	$userLastName2 = $row2['LName'];
	$userImage2 = $row2['ThumbUrl'];
}

//Main SQL, get the messages!

if (empty($usernameid2)){
	$result = mysql_query("SELECT * FROM messages WHERE `sender_id` = '$usernameid' OR `recipient_id` = '$usernameid' ORDER BY `sent_at` desc") or die(mysql_error());
	
	//Echo basic information
	if (!empty($userImage)){
		echo "<img src='$userImage'/><br/>";
	}
	echo "<b>ID: <a href='UserPortal.php?userid=$usernameid'>$usernameid</a><br/>$userFirstName $userLastName</b><br/><br/>";
	
}else{
	$result = mysql_query("SELECT * FROM messages WHERE `sender_id` = '$usernameid' AND `recipient_id` = '$usernameid2' OR `sender_id` = '$usernameid2' AND `recipient_id` = '$usernameid' ORDER BY `sent_at` desc") or die(mysql_error());
		
	//Echo basic information
	if (!empty($userImage)){
		echo "<img src='$userImage'/>";
	}else{
		echo "<b>$usernameid</b>";
	}
	
	echo " <-> ";
	
	if (!empty($userImage2)){
		echo "<img src='$userImage2'/>";
	}else{
		echo "<b>$usernameid2</b>";
	}
	
	echo "<br/><b>ID: <a href='UserPortal.php?userid=$usernameid'>$usernameid</a> - ID: <a href='UserPortal.php?userid=$usernameid2'>$usernameid2</a><br/>$userFirstName $userLastName - $userFirstName2 $userLastName2</b><br/><br/>";
}
while($row = mysql_fetch_assoc($result)) {
	//Set Variables
	$sender = $row['sender_id'];
	$sender_name = $sender;
	$recipient = $row['recipient_id'];
	$recipient_name = $recipient;
	$message = $row['content'];
	$messagedate = $row['sent_at'];
	
	//Get real names
	//Sender
	$resultname = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$sender'");
	$resultrow = mysql_fetch_array($resultname);
	$sender_name = $resultrow['FName'] . " " . $resultrow['LName'];
	
	//Recipient
	$resultname = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$recipient'");
	$resultrow = mysql_fetch_array($resultname);
	$recipient_name = $resultrow['FName'] . " " . $resultrow['LName'];
	
	//Echo messages
	echo "[$messagedate | $sender_name (<a href='GetPM.php?userid=$sender'>$sender</a>) to $recipient_name (<a href='GetPM.php?userid=$recipient'>$recipient</a>) - <a href='GetPM.php?userid=$sender&userid2=$recipient'>Conversation</a>]:</br>";
	echo "\/-----------------------\/<br/>";
	echo "$message<br/>";
	echo "/\-----------------------/\<br/><br/>";
}

