<?php
//Setup SQL connection
require_once('database.php');
?>
<b>Patreon - Database Search</b> - <a href='https://github.com/smiba/Patreon-DB-Browser'>Github</a><br/>
<!-- Shitty html form from the internet because I suck at html -->
Please enter at least one field to start the search.<br/><br/>
<form name="htmlform" method="post" action="index.php">
<table width="450px">
</tr>
<tr>
 <td valign="top">
  <label for="first_name">First Name</label>
 </td>
 <td valign="top">
  <input  type="text" name="first_name" maxlength="50" size="30">
 </td>
</tr>
 
<tr>
 <td valign="top"">
  <label for="last_name">Last Name</label>
 </td>
 <td valign="top">
  <input  type="text" name="last_name" maxlength="50" size="30">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="email">Email Address</label>
 </td>
 <td valign="top">
  <input  type="text" name="email" maxlength="80" size="30">
 </td>
 
</tr>
<tr>
 <td valign="top">
  <label for="telephone">Username / 'Vanity'</label>
 </td>
 <td valign="top">
  <input  type="text" name="vanity" maxlength="30" size="30">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="telephone">Twitter</label>
 </td>
 <td valign="top">
  <input  type="text" name="twitter" maxlength="30" size="30">
 </td>
</tr>
<tr>
 <td colspan="2" style="text-align:center">
  <input type="submit" value="Search">
 </td>
</tr>
</table>
</form>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE); //Yes, I know that some of the $_POST variables are NULL php, no need to show me noticies

$userFirstName = mysql_escape_string($_POST['first_name']);
$userLastName = mysql_escape_string($_POST['last_name']);
$userEmail = mysql_escape_string($_POST['email']);
$userVanity = mysql_escape_string($_POST['vanity']);
$userTwitter = mysql_escape_string($_POST['twitter']);

if (!(!empty($userFirstName) || !empty($userLastName) || !empty($userEmail) || !empty($userVanity) || !empty($userTwitter))){
}else{
	$query = "SELECT * FROM tblUsers WHERE ";
	if (!empty($userFirstName)){
		$query = "$query`FName` = '$userFirstName' AND ";
	}
	if (!empty($userLastName)){
		$query = "$query`LName` = '$userLastName' AND ";
	}
	if (!empty($userEmail)){
		$query = "$query`Email` = '$userEmail' AND ";
	}
	if (!empty($userVanity)){
		$query = "$query`Vanity` = '$userVanity' AND ";
	}
	if (!empty($userTwitter)){
		$query = "$query`Twitter` = '$userTwitter' AND ";
	}
	$query = substr($query, 0, -4); //Remove last AND
	$query = "$query LIMIT 150";
	$result = mysql_query($query) or die(mysql_error());
	echo "----------[Results]----------<br/>";
	if (mysql_num_rows($result) == 0) { die("No results"); }
	while($row = mysql_fetch_assoc($result)) {
		$FirstName = $row['FName'];
		$LastName = $row['LName'];
		$Email = $row['Email'];
		$Vanity = $row['Vanity'];
		$Twitter = $row['Twitter'];
		$ID = $row['UID'];

		$echostring = "$FirstName $LastName (";
		if (!empty($Vanity)){
			$echostring = "$echostring$Vanity -";
		}
		$echostring = "$echostring<a href='UserPortal.php?userid=$ID'>$ID</a>) - Email: $Email";
		if (!empty($Twitter)){
			$echostring = "$echostring - Twitter: $Twitter";
		}
		echo "$echostring<br/>";
	}
	if (mysql_num_rows($result) == 150) { die("... There are more results, but the maximum of 150 results has been reached"); }
}
?>