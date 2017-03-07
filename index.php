<?php
	/**
	*	@author Luis Otero, Jorge Aguiniga
	*	
	*	This application is a very simple text editor. It allows you to create new text files,
	*	edit existing text files, read existing text files, and delete them. There are four different
	*	screens one can fall into: landing (home page), edit (file edit page), read (file read page), or 
	*	confirm (file deletion confirmation page).
	*/
	
	/**
	*	This function displays the home page that lets you:
	*		a. create new text files
	*		b. choose to edit/delete/read existing text files
	*	In order to read existing text files, click on the name of the file.
	*/
	function landingView()
	{
		//holds the names of all text files in the current directory
		$filearray = glob("*.txt");
		
		//start of html
		?>
		<!doctype html>
		<html>
		<head>
		<title>Landing Page</title>
		<style>
			.table { display: table; }
			.tr { display: table-row; }
			.heading { display: table-header-group; }
			.tb { display: table-row-group; }
			.tf { display: table-footer-group; }
			.td { display: table-cell; padding: 0in 0.1in 0in 0.1in;}
			.th { display: table-cell; padding: 0in 0.1in 0in 0.1in; font-weight:bold;}
		</style>
		</head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<form action= "index.php" method="GET">
			<input type="text" name="arg1" placeholder="Text File Name" />
            <input type="hidden" name="a" value="edit">
			<input type="submit" value="Create" />
		</form>
		<h2>My Files</h2>
		<div class="table">
		<div class="tr"><div class="th">Filename</div><div class="th">Actions</div></div>
		<?php
			//used for listing all the file names as links and edit, delete buttons next to the file names
			foreach($filearray as $file)
			{
				?>
				<div class="tr">
					<div class="td">
						<a href="index.php?a=read&arg1=<?php echo str_replace('.txt', '', $file);?>">
							<?php echo $file; ?>
						</a>
					</div>			
					<div class="td">
						<button type="button" onClick="location.href='index.php?a=edit&arg1=<?php echo str_replace('.txt', '', $file);?>'">
							Edit
						</button>
					</div>
					<div class="td">
						<button type="button" onClick="location.href='index.php?a=confirm&arg1=<?php echo str_replace('.txt', '', $file);?>'">
							Delete
						</button>
					</div>
				</div>
				<?php
			}
		?>
		</div>
		</body>
		</html>
		<?php
		//end of html
	}
	
	/**
	*	This function displays the file edit page that lets you:
	*		a. edit existing text files
	*		b. save any change in the text file
	*		c. return to the home page
	*/
	function editView()
	{
		//acquires the file to be edited and adds .txt to it
        $filename = $_REQUEST['arg1'].".txt";

		//checks if the file exists in the current directory
        if(file_exists($filename)) {
            $file_handle = fopen($filename, "r");
			//checks if file content is empty. If not, stores the contents into a string
			if (filesize($filename) > 0) {
				$contents = fread($file_handle, filesize($filename));
			}
			//else it stores an empty string
			else{
				$contents = "";
			}
            fclose($file_handle);
        }

		//start of html
		?>
		<!doctype html>
		<html>
		<head><title>Edit Page</title></head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<h2>Edit: <?php echo $_REQUEST['arg1']; ?></h2>
		<form action="index.php?a=edit&arg1=<?php echo $_REQUEST['arg1']; ?>" method="post">
			<input type="submit" name="Save" value="Save" />
            <input type="hidden" name="arg1" value="<?php echo $_REQUEST['arg1']; ?>">
            <button formaction="index.php?a=landing">Return</button>
			<p><textarea name="content" rows="9" cols="50"><?php echo (!empty($contents))?$contents:"";?></textarea></p>
		</form>
		</body>
		</html>
		<?php
		//end of html
	}
	
	/**
	*	This function displays the file read page that lets you:
	*		a. read the contents of an existing text file
	*/
	function readView()
	{
		//acquires the name of the file to be read and adds .txt to it
        $filename = $_REQUEST['arg1'].".txt";
		
		//attempts to open the file for read purposes
        $file_handle = fopen($filename, "r");
		
		//stores the contents of the file
        $contents = fread($file_handle, filesize($filename));
		
        fclose($file_handle);
		
		//start of html
		?>
		<!doctype html>
		<html>
		<head><title>Read Page</title></head>
		<body>
		<h1><a href="index.php">Simple Text Editor</a></h1>
		<h2>Read: <?php echo $_REQUEST['arg1']; ?></h2>
		<div>
			<?php 
				//checks if the contents of the text file are empty
				if(!empty($contents))
				{ 
					//replaces all the possible html tags with "<" or ">" signs
					$contents = str_replace("<", "&lt;", $contents); 
					$contents = str_replace(">", "&gt;", $contents); 
					
					//prints contents of the text file
					echo $contents; 
				}
			?>
		</div>
		</body>
		</html>
		<?php
		//end of html
	}
	
	/**
	*	This function displays the delete file confirmation page that lets you:
	*		a. confirm that you want to delete a specific text file
	*		b. return to the home page
	*	
	*	@param $title (the name of the file to be deleted)
	*/
	function confirmView($title)
	{
		//start of html
		?>
		<!doctype html>
		<html>
		<head>
		<title>Confirm Page</title>
		<style>
			.p {padding-top:0.1in;}
		</style>
		</head>
		<body>
		<h1><a href="index.php?a=landing">Simple Text Editor</a></h1>
		<div>Are you sure you want to delete the file:</div>
		<div><b><?php echo str_replace(".txt", "", $title); ?></b>?</div>
		<div class="p">
		<form action="index.php" method="post">
			<input type="submit" name="Delete" value="Confirm" />
			<input type="submit" name="Delete" value="Cancel" />
			<input type="hidden" name="Delete_File" value = "<?php echo $title; ?>" />
		</form>
		</div>
		</body>
		</html>
		<?php
		//end of html
	}
	
	//redirects to landing page if the activity variable is not set
	if (empty($_REQUEST['a']))
	{
		$_SET['a'] = "landing";
		header("Location: index.php?a=landing");
	}
	//checks which page to redirect user to
	else
	{
		//checks activity variable for next activity
		switch($_REQUEST['a'])
		{
			//landing page
			case "landing":
				landingView();
			break;
			//edit page
			case "edit":
				//if new file created, checks that it does not have non-alphanumeric and/or non-space characters
				//redirects to landing page if any of the above characters found in file name
				if (empty($_REQUEST['arg1']) || empty(trim($_REQUEST['arg1'])) || !preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['arg1'])) {
					$_SET['a'] = "landing";
					header("Location: index.php?a=landing");
				}
				//otherwise, go to the edit page
				else {
					editView();
				}
			break;
			//read page
			case "read":
				readView();
			break;
			//confirm delete page
			case "confirm":
				confirmView($_REQUEST['arg1']);
			break;		
		}
	}

	//checks if there is content to save to a text file
    if (isset($_POST['Save'])) {
		//if there is something to save, access the file and write the contents to the specific text file
        if ($_POST['Save'] === 'Save') {
			//acquire name of file to write to
            $file = $_POST['arg1'].".txt";
			//open file with write permission
            $handler = fopen($file, "w");
			//if opening file was successful, begin writing
            if ($handler) {
                fwrite($handler, $_POST['content']);
                fclose($handler);
				$_SET['arg1'] = $_POST['arg1'];
				//refresh edit page when writing is finished
				header("Refresh:0");
            }
        }
    }
	
	//checks if there is a file confirmed for deletion
	if (isset($_POST['Delete']))
	{
		//if there is a file to delete, unset it 
		if($_POST['Delete'] === "Confirm")
		{
			//acquire the name of the file to be deleted
			$file = $_POST['Delete_File'].".txt";
			
			//if the file does exist, delete it
			if (file_exists($file))
			{
				unlink($file);
				//redirect user back to the landing page
				header("Location: index.php?a=landing");
			}
		}
	}
