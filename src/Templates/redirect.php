<!DOCTYPE html>
<html>
<head>
	<title>User Login</title>
</head>
<body>
	<form name="frmUser" method="post" action="../Auth/authenticate.php">
		<table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
			<tr class="tableheader">
				<td align="center" colspan="2">Enter Login Details</td>
			</tr>
			<tr class="tablerow">
				<td align="right">Username</td>
				<td><input type="text" name="userName"></td>
			</tr>
			<tr class="tablerow">
				<td align="right">Password</td>
				<td><input type="password" name="password"></td>
			</tr>
			<tr class="tableheader">
				<td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
	</form>
</body>
</html>