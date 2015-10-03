<?php
//Setup SQL connection
require_once('database.php');

//Checks
if (empty($_GET['userid'])){
	die("You need to provide an userID!");
}

//Init variables
$usernameid = mysql_escape_string($_GET['userid']); //The user we're looking for
$userFirstName;
$userLastName;
$userEmail;
$userFacebook;
$userYouTube;
$userTwitter;
$userAbout;
$userImage;

//First query, get user information
$result = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$usernameid'") or die(mysql_error());
$row = mysql_fetch_array($result);

//Put information in variables
$userFirstName = $row['FName'];
$userLastName = $row['LName'];
$userEmail = $row['Email'];
$userTwitter = $row['Twitter'];
$userFacebook = $row['Facebook'];
$userYouTube = $row['Youtube'];
$userAbout = $row['About'];
$userImage = $row['ThumbUrl'];
$userCampaign;

//Echo Information
if (!empty($userImage)){
echo "<img src='$userImage' /><br/>";
}
echo "<b>ID: $usernameid<br/>$userFirstName $userLastName</b><br/>Email: $userEmail<br/>";
if (!empty($userFacebook)){
	echo "Facebook: $userFacebook<br/>";
}
if (!empty($userYouTube)){
	echo "Youtube: $userYouTube<br/>";
}
if (!empty($userTwitter)){
	echo "Twitter: $userTwitter<br/>";
}
if (!empty($userAbout)){
	echo "<br/>About:<br/>$userAbout";
}

//Get if this user has an compaign!
$result = mysql_query("SELECT campaign_id FROM campaigns_users WHERE `user_id`='$usernameid'") or die(mysql_error());
$row = mysql_fetch_array($result);

if (mysql_num_rows($result) > 0) {
	$userCampaign = $row['campaign_id'];
}

echo "<br/><br/>----------[Portal]----------<br/>";
echo "<a href='GetSupporting.php?userid=$usernameid'>Get what $userFirstName supports</a><br/>";
echo "<a href='GetPM.php?userid=$usernameid'>Get $userFirstName PM's</a><br/>";
if (!empty($userCampaign)){ echo "<a href='GetCampaign.php?campaignid=$userCampaign'>Get $userFirstName's campaign</a><br/>"; }
echo "----------[Portal]----------<br/>";