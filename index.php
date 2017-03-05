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
		<!doctype html>
		<html>
		<head><title>Landing Page</title></head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<form action= "index.php" method="GET">
			<input type="text" name="arg1" placeholder="Text File Name" />
            <input type="hidden" name="a" value="edit">
			<input type="submit" value="Create" />
		</form>
		<h2>My Files</h2>
		<table>
		<tr><th>Filename</th><th colspan="2">Actions</th></tr>
		<?php
			foreach($filearray as $file)
			{
				?>
				<form action="index.php" method="GET">
				<tr>
				<td>
					<a href="index.php?a=read&arg1=<?php echo str_replace('.txt', '', $file);?>"><?php echo $file; ?></a>
				</td>
				<td>			
				    <button type="submit" name="a" value="edit">Edit</button>
                    <button type="submit" name="a" value="confirm">Delete</button>
					<input type="hidden" name="arg1" value="<?php echo str_replace('.txt', '', $file);?>">
				</td>
				</tr>
				</form>
				<?php
			}
		?>
		</table>
		</body>
		</html>
		<?php
	}
	function editView()
	{
        $filename = "text/".$_REQUEST['arg1'].".txt";
        if(file_exists($filename)) {
            $file_handle = fopen($filename, "r");
			if (filesize($filename) > 0) {
				$contents = fread($file_handle, filesize($filename));
			}
			else{
				$contents = "";
			}
            fclose($file_handle);
        }

		?>
		<!doctype html>
		<html>
		<head><title>Edit Page</title></head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<h2>Read:<?php echo $_REQUEST['arg1']; ?></h2>
		<form action="" method="post">
			<input type="submit" name="Save" value="Save" />
            <input type="hidden" name="arg1" value="<?php echo $_REQUEST['arg1']; ?>">
            <button formaction="index.php?a=landing">Return</button>
			<p><textarea name="content" rows="9" cols="50"><?php echo (!empty($contents))?$contents:"";?></textarea></p>

		</form>

		</body>
		</html>
		<?php
	}
	function readView()
	{
        $filename = "text/".$_REQUEST['arg1'].".txt";
        $file_handle = fopen($filename, "r");
        $contents = fread($file_handle, filesize($filename));
        fclose($file_handle);
		?>
		<!doctype html>
		<html>
		<head><title>Read Page</title></head>
		<body>
		<h1><a href="index.php">Simple Text Editor</a></h1>
		<h2>Read:<?php echo $_REQUEST['arg1']; ?></h2>
		<p><?php echo (!empty($contents))?$contents:"a";?></p>
		</body>
		</html>
		<?php
	}
	function confirmView($title)
	{
		?>
		<!doctype html>
		<html>
		<head><title>Confirm Page</title></head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<div>Are you sure you want to delete the file:</div>
		<div><b><?php echo str_replace(".txt", "", $title); ?></b>?</div>
		<p>
		<form action="index.php" method="post">
			<input type="submit" name="Delete" value="Confirm" />
			<input type="submit" name="Delete" value="Cancel" />
			<input type="hidden" name="Delete_File" value = "<?php echo $title; ?>" />
		</form>
		</p>
		</body>
		</html>
		<?php
	}
	
	if (empty($_REQUEST['a']))
	{
		$_SET['a'] = "landing";
		header("Location: index.php?a=landing");
	}
	else
	{
		switch($_REQUEST['a'])
		{
			case "landing":
				landingView();
			break;
			case "edit":
				if (empty($_REQUEST['arg1']) || empty(trim($_REQUEST['arg1'])) || !preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['arg1'])) {
					$_SET['a'] = "landing";
					header("Location: index.php?a=landing");
				}
				/*
				else if (!preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['arg1'])) {
					$_SET['a'] = "landing";
					header("Location: index.php?a=landing");
				}
				*/
				else {
					editView();
				}
			break;
			case "read":
				readView();
			break;
			case "confirm":
				confirmView($_REQUEST['arg1']);
			break;		
		}
	}

    if (isset($_POST['Save'])) {
        if ($_POST['Save'] === 'Save') {
            $file = "text/".$_POST['arg1'].".txt";
            $handler = fopen($file, "w");
            if ($handler) {
                fwrite($handler, $_POST['content']);
                fclose($handler);
				$_SET['arg1'] = $_POST['arg1'];
				header("Refresh:0");
            }
        }
    }
	else if (isset($_POST['Delete']))
	{
		if($_POST['Delete'] === "Confirm")
		{
			$file = "text/".$_POST['Delete_File'].".txt";
			
			if (file_exists($file))
			{
				unlink($file);
				header("Location: index.php?a=landing");
			}
		}
	}
