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
$userFirstName;
$userLastName;
$userImage;

//First query, get user information
$result = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$usernameid'") or die(mysql_error());
$row = mysql_fetch_array($result);

//Put information in variables
$userFirstName = $row['FName'];
$userLastName = $row['LName'];
$userImage = $row['ThumbUrl'];

//Echo Information
if (!empty($userImage)){
echo "<img src='$userImage' /><br/>";
}
echo "<b>ID: <a href='UserPortal.php?userid=$usernameid'>$usernameid</a><br/>$userFirstName $userLastName</b><br/><br/>";

echo "$userFirstName is supporting:<br/>";
$total = 0;
$result = mysql_query("SELECT * FROM pledges WHERE `user_id`='$usernameid' AND `deleted_at` IS NULL") or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
	$campaignID = $row['campaign_id'];
	$amount = $row['amount_cents'];
	$since = $row['created_at'];
	
	$total += $amount;
	$amount = getInFullDollar($amount);
	
	$resultCamp = mysql_query("SELECT * FROM campaigns WHERE `campaign_id`='$campaignID'");
	$rowCamp = mysql_fetch_array($resultCamp);
	
	$campaign = $rowCamp['creation_name'];
	
	echo "<b>$campaign</b> (<a href='GetCampaign.php?campaignid=$campaignID'>$campaignID</a>)<br/>with <b>$$amount</b> - Since: <b>$since</b><br/><br/>";
}

$total = getInFullDollar($total);
echo "-------------------<br/><b>Total: $$total</b><br/>-------------------<br/>";

$result = mysql_query("SELECT * FROM pledges WHERE `user_id`='$usernameid' AND `deleted_at` IS NOT NULL") or die(mysql_error());
if (mysql_num_rows($result) > 0) { echo "<br/>$userFirstName was supporting:<br/>"; }
while($row = mysql_fetch_assoc($result)) {
	$campaignID = $row['campaign_id'];
	$campaign;
	$amount = $row['amount_cents'];
	$since = $row['created_at'];
	$stopped = $row['deleted_at'];
	
	$amount = getInFullDollar($amount);
	
	$resultCamp = mysql_query("SELECT * FROM campaigns WHERE `campaign_id`='$campaignID'");
	$rowCamp = mysql_fetch_array($resultCamp);
	
	$campaign = $rowCamp['creation_name'];
	
	echo "<b>$campaign</b> (<a href='GetCampaign.php?campaignid=$campaignID'>$campaignID</a>)<br/>With <b>$$amount</b> - Since: <b>$since</b> - Stopped: <b>$stopped</b><br/><br/>";
}

