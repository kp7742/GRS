<?php
	require_once("dbInfo.php");

	function addUsers($type, $enroll, $dept, $sem, $fName, $lName, $eMail, $password, $mobile, $birthDate, $gender, $admin) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `users`
				(
					`Type`,
					`Enroll`,
					`Dept`,
					`Sem`,
					`FName`,
					`LName`,
					`EMail`,
					`Password`,
					`Mobile`,
					`BirthDate`,
					`Gender`,
					`Admin`
				)
				VALUES
				(
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					STR_TO_DATE(?, '%d/%m/%Y'),
					?,
					?
				);";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'sisisssssssi', $type, $enroll, $dept, $sem, $fName, $lName, $eMail, $password, $mobile, $birthDate, $gender, $admin);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Get value of the auto increment column.
		$newId = mysqli_insert_id($conn);

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		// Return the id.
		return $newId;
	}

	function updateUsers($id, $type, $enroll, $dept, $sem, $fName, $lName, $eMail, $password, $mobile, $birthDate, $gender, $admin) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`users`
				SET		`Type` = ?,
						`Enroll` = ?,
						`Dept` = ?,
						`Sem` = ?,
						`FName` = ?,
						`LName` = ?,
						`EMail` = ?,
						`Password` = ?,
						`Mobile` = ?,
						`BirthDate` = STR_TO_DATE(?, '%d/%m/%Y'),
						`Gender` = ?,
						`Admin` = ?
				WHERE	`Id` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'sisisssssssii', $type, $enroll, $dept, $sem, $fName, $lName, $eMail, $password, $mobile, $birthDate, $gender, $admin, $id);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function isExisted($eMail) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`Id`
				FROM	`users`
				WHERE	`EMail` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 's', $eMail);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $id);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"Id" => $id);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getUser($eMail, $password) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`Id`,
						`Type`,
						`Enroll`,
						`Dept`,
						`Sem`,
						`FName`,
						`LName`,
						`EMail`,
						`Password`,
						`Mobile`,
						DATE_FORMAT(`BirthDate`, '%d/%m/%Y') AS BirthDate,
						`Gender`,
						`Admin`,
						DATE_FORMAT(`CreationDate`, '%m/%d/%Y %h:%i %p') AS CreationDate
				FROM	`users`
				WHERE	`EMail` = ?
				AND		`Password` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ss', $eMail, $password);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $id, $type, $enroll, $dept, $sem, $fName, $lName, $eMail, $password, $mobile, $birthDate, $gender, $admin, $creationDate);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"Id" => $id,
				"Type" => $type,
				"Enroll" => $enroll,
				"Dept" => $dept,
				"Sem" => $sem,
				"FName" => $fName,
				"LName" => $lName,
				"EMail" => $eMail,
				"Password" => $password,
				"Mobile" => $mobile,
				"BirthDate" => $birthDate,
				"Gender" => $gender,
				"Admin" => $admin,
				"CreationDate" => $creationDate);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function changePassword($password, $id, $eMail) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`users`
				SET		`Password` = ?
				WHERE	`Id` = ?
				AND		`EMail` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'sis', $password, $id, $eMail);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
?>