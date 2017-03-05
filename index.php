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
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<form action= "" method="GET">
			<input type="text" name="file" placeholder="Text File Name" />
            <input type="hidden" name="a" value="edit">
			<input type="submit" value="Create" />
		</form>
		<h2>My Files</h2>
		<h3>Filename<span> Actions</span></h3>
		<?php
			foreach($filearray as $file)
			{
				?>
				<form action="" method="GET">
					<a href="index.php?a=read&file=<?php echo str_replace('.txt', '', $file);?>"><?php echo $file; ?></a>
                    <input type="submit" name="a" value="edit" />
                    <input type="submit" name="a" value="delete" /><input type="hidden" name="file" value="<?php echo str_replace('.txt', '', $file);?>">
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
        $filename = "text/".$_REQUEST['file'].".txt";
        if(file_exists($filename)) {
            $file_handle = fopen($filename, "r");
            $contents = fread($file_handle, filesize($filename));
            fclose($file_handle);
        }

		?>
		<!doctype>
		<head><title>Edit Page</title></head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<h2>Read:<?php echo $_REQUEST['file']; ?></h2>
		<form action="" method="post">
			<input type="submit" name="Save" value="Save" />
            <input type="hidden" name="file" value="<?php echo $_REQUEST['file']; ?>">
            <button formaction="index.php?a=landing">Return</button>
			<p><textarea name="content" rows="9" cols="50"><?php echo (!empty($contents))?$contents:"";?></textarea></p>

		</form>

		</body>
		</html>
		<?php
	}
	function readView()
	{
        $filename = "text/".$_REQUEST['file'].".txt";
        $file_handle = fopen($filename, "r");
        $contents = fread($file_handle, filesize($filename));
        fclose($file_handle);
		?>
		<!doctype>
		<head><title>Read Page</title></head>
		<body>
		<h1><a href="index.php">Simple Text Editor</a></h1>
		<h2>Read:<?php echo $_REQUEST['file']; ?></h2>
		<p><?php echo (!empty($contents))?$contents:"a";?></p>
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
			<input type="hidden" name="a" value="edit">
			<input type="submit" value="Create" />
		</form>
		<h2>My Files</h2>
		<h3>Filename<span> Actions</span></h3>
		</body>
		</html>
		<?php
	}

	if (empty($_REQUEST['a']))
	{
		$_SET['a'] = "landing";
		landingView();
	}
	else
	{
		if ($_REQUEST ['a'] === "landing")
		{
			landingView();
		}
		else if ($_REQUEST ['a'] === "edit")
		{
            if (empty($_REQUEST['file']) || empty(trim($_REQUEST['file']))) {
                $_SET['a'] = "landing";
                header("Location: index.php?a=landing");
            }
            else if (!preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['file'])) {
                $_SET['a'] = "landing";
                header("Location: index.php?a=landing");
            }
            else {
             editView();
            }

		}
		else if ($_REQUEST['a'] === "read")
		{
			readView();
		}
	}

    if (isset($_POST['Save'])) {
        if ($_POST['Save'] === 'Save') {
            $file = "text/".$_POST['file'].".txt";
            $handler = fopen($file, "w");
            if ($handler) {
                fwrite($handler, $_POST['content']);
                fclose($handler);
                //echo "<meta http-equiv='refresh' content='0'>";
            }
        }
    }
