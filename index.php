<?php
	function landingView()
	{
		$filearray = glob("text/*.txt");
		for($i = 0; $i < sizeof($filearray); $i++)
		{
            $st = str_replace("text/", "", $filearray[$i]);
			$filearray[$i] = $st;

		}
		?>
		<!doctype>
		<head><title>Landing Page</title></head>
		<body>
		<h1><a href="index.php?page=landingView">Simple Text Editor</a></h1>
		<form action= "" method="GET">
			<input type="text" name="file" placeholder="Text File Name" />
            <input type="hidden" name="page" value="editView">
			<input type="submit" value="Create" />
		</form>
		<h2>My Files</h2>
		<h3>Filename<span> Actions</span></h3>
		<?php
			foreach($filearray as $file)
			{
				?>
				<form action="" method="GET">
					<a href="index.php?page=readView&file=<?php echo str_replace('.txt', '', $file);?>"><?php echo $file; ?></a>
					<input type="submit" name="page" value="edit" />
					<input type="submit" name="page" value="delete" />
				</form>
				<?php
			}
		?>
		</body>
		</html>
		<?php
	}
	function editView()
	{
		?>
		<!doctype>
		<head><title>Edit Page</title></head>
		<body>
		<h1><a href="index.php?page=landingView">Simple Text Editor</a></h1>
		<h2>Edit:<?php echo (!empty($_REQUEST['file']))?$_REQUEST['file']:'No Title'; ?></h2>
		<form action="index.php" method="post">
			<input type="submit" name="edit_submit" value="save" />
			<input type="submit" name="edit_submit" value="cancel" />
			<input type="hidden" name="page" value="landingView">
			<p><textarea name="content" rows="9" cols="50"><?php echo (!empty($contents))?$contents:"";?></textarea></p>
		</form>
		</body>
		</html>
		<?php
	}
	function readView()
	{
		?>
		<!doctype>
		<head><title>Read Page</title></head>
		<body>
		<h1><a href="index.php">Simple Text Editor</a></h1>
		<h2>Read:<?php echo (!empty($_REQUEST['file']))?$_REQUEST['file']:'No Title'; ?></h2>
		<p><?php echo (!empty($contents))?$contents:"";?></p>
		</body>
		</html>
		<?php
	}
	function confirmView()
	{
		?>
		<!doctype>
		<head><title>Landing Page</title></head>
		<body>
		<h1><a href="index.php">Simple Text Editor</a></h1>
		<form action= "" method="GET">
			<input type="text" name="file" placeholder="Text File Name" />
			<input type="hidden" name="page" value="editView">
			<input type="submit" value="Create" />
		</form>
		<h2>My Files</h2>
		<h3>Filename<span> Actions</span></h3>
		</body>
		</html>
		<?php
	}

	if (empty($_REQUEST['page']))
	{
		$_SET['page'] = "landingView";
		landingView();
	}
	else
	{
		if ($_REQUEST ['page'] === "landingView")
		{
			landingView();
		}
		else if ($_REQUEST ['page'] === "editView")
		{
            if (empty($_REQUEST['file']) || empty(trim($_REQUEST['file']))) {
                $_SET['page'] = "landingView";
                landingView();
            }
            else if (!preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['file'])) {
                $_SET['page'] = "landingView";
                landingView();
            }
            else {
             editView();
            }

		}
		else if ($_REQUEST['page'] === "readView")
		{
			readView();
		}
	}
