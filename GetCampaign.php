<?php
//Setup SQL connection
require_once('database.php');
require_once('functions.php');

//Checks
if (empty($_GET['campaignid'])){
	die("You need to provide an campaignID!");
}

//Init variables
$campaignid = mysql_escape_string($_GET['campaignid']); //The campaign we're looking for
$image;
$description;
$name;
$oneLiner;
$created;
$published;

//First query, get user information
$result = mysql_query("SELECT * FROM campaigns WHERE `campaign_id`='$campaignid'") or die(mysql_error());
$row = mysql_fetch_array($result);

//Set variables
$image = $row['image_small_url'];
$description = $row['summary'];
$oneLiner = $row['one_liner'];
$name = $row['creation_name'];
$created = $row['created_at'];
$published = $row['published_at'];
$ownerID;
$owner;
$total = 0;

//Second query, get campaign owner
$result = mysql_query("SELECT user_id FROM campaigns_users WHERE `campaign_id`='$campaignid'") or die(mysql_error());
$row = mysql_fetch_array($result);

$ownerID = $row['user_id'];

//Third query, get username

$result = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$ownerID'") or die(mysql_error());
$row = mysql_fetch_array($result);

$owner = $row['FName'] . " " . $row['LName'];

//Echo information
echo "<img src='$image' /><br/>";
echo "<b>\"$oneLiner\"</b><br/></br>";
echo "<b>$name</b> by <b>$owner</b> (<a href='UserPortal.php?userid=$ownerID'>$ownerID</a>) - Created: $created - Published: $published<br/><br/>";
echo "-------------------<br/><b>This campaign is supported by:</b><br/>-------------------<br/>";

$result = mysql_query("SELECT * FROM pledges WHERE `campaign_id` = '$campaignid' AND `deleted_at` IS NULL") or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
	$pledgeUser = $row['user_id'];
	$userFirstName;
	$userLastName;
	$amount = $row['amount_cents'];
	$since = $row['created_at'];
	
	$total += $amount;	
	$amount = getInFullDollar($amount);
	
	$result2 = mysql_query("SELECT * FROM tblUsers WHERE `UID`='$pledgeUser'") or die(mysql_error());
	$row2 = mysql_fetch_array($result2);
	
	$userFirstName = $row2['FName'];
	$userLastName = $row2['LName'];
	
	echo "<b>$userFirstName $userLastName</b> (<a href='UserPortal.php?userid=$pledgeUser'>$pledgeUser</a>)<br/>With <b>$$amount</b> - Since: <b>$since</b><br/><br/>";
}
$total = getInFullDollar($total);
echo "-------------------<br/><b>Total: $$total</b><br/>-------------------<br/>";