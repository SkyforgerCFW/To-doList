<?php 
	$errors = "";

	$db = mysqli_connect("localhost", "root", "", "todo");

	if (isset($_POST['submit'])) {
		if (empty($_POST['task'])) {
			$errors = "You must fill in the task";
		}
		else{
			$task = $_POST['task'];
			$sql = "INSERT INTO tasks (task, completed) VALUES ('$task', 0);";
			mysqli_query($db, $sql);
			header('location: index.php');
		}
	}

	if (isset($_GET['del_task'])) {
		$id = $_GET['del_task'];

		mysqli_query($db, "DELETE FROM tasks WHERE id=".$id);
		header('location: index.php');
	}

	if(isset($_GET['completed'])) {
		$id = $_GET['completed'];
		mysqli_query($db, "UPDATE tasks SET completed = !completed WHERE tasks.id = '$id'");
		header('location: index.php');
	}

	if (isset($_POST['accept'])) {
		$id = $_GET['editid'];
		$edit = html_entity_decode($_POST['edit'], ENT_QUOTES);
		mysqli_query($db, "UPDATE tasks SET task = '$edit' WHERE tasks.id = $id");
		header('location: index.php');
	}

	if (isset($_POST['cancel'])) {
		header('location: index.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>To-do List Application PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="heading">
		<h2 style="font-family: 'Helvetica';">To-do List Application - PHP and MySql database</h2>
	</div>
	<form method="post" action="index.php" class="input_form">
		<?php if (isset($errors)) { ?>
			<p><?php echo $errors; ?></p>
		<?php } ?>
		<input type="text" name="task" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
	</form>
	<!-- <?php if(mysqli_ping($db)) { ?>
	<p><?php echo "Connected"; ?></p>
	<?php } else { ?>
	<p><?php echo "Not connected"; ?></p>
	<?php } ?> -->
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Tasks</th>
				<th style="width: 20px; padding-right: 8px;">Edit</th>
				<th style="width: 20px;">Completed</th>
				<th style="width: 60px;">Action</th>
			</tr>
		</thead>

		<tbody>
			<?php 
			$tasks = mysqli_query($db, "SELECT * FROM tasks");

			$i = 1; while($row = mysqli_fetch_array($tasks)) { ?>
				<tr>
					<td> <?php echo $i; ?> </td>
					<td class="task"> 
						<?php  
						if(isset($_GET['editid'])) { ?>
						<form method="post" action="index.php?editid=<?php echo $_GET['editid']; ?>" style="margin: 5px auto; width: auto;" >
							<input type="text" name="edit" style="width: 80%; float: left;" value="<?php echo htmlentities($row['task'], ENT_QUOTES); ?>">
							<button type="submit" name="accept">Accept</button>
							<button type="submit" name="cancel">Cancel</button>
						</form>
						<?php } else echo $row['task']; ?>
					</td>
					<td class="edit">
						<a href="index.php?editid=<?php echo $row['id'] ?>">X</a>
					</td>
					<td>
						<input type="checkbox" name="complete<?php echo $i; ?>" onclick="return false;"
							<?php if($row['completed'] == "1") { 
								echo "checked value=1";
							}
							else{
								echo "value=0";
							}?> >
						<br>
						<a href="index.php?completed=<?php echo $row['id'] ?>">Change</a>
					</td>
					<td class="delete">
						<a href="index.php?del_task=<?php echo $row['id'] ?>">X</a>
					</td>
				</tr>
			<?php $i++; } ?>
		</tbody>
	</table>
</body>
</html>