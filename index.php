<?php
	function processFilePath($filePath) {
		//Get extension
		$extension = explode ('.', $filePath);
		$extension = $extension[count($extension) - 1];

		//TODO: Convert file to use template
		if($extension == "html" or $extension == "htm" or $extension == "php")
		{
			include $filePath;
		}
		//Serve the file
		else
		{
			header('Content-Type: ' . mime_content_type($filePath));
			
			//Check these
			header("Expires: Mon, 1 Jan 2099 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			
			$size= filesize($filePath);
			header("Content-Length: $size bytes");
			readfile($filePath);
		}
	}

	$url=strtok($_SERVER["REQUEST_URI"],'?');
	$urlArray = explode ('/', $url);
	
	//This is in case of a custom request that includes a "..", we don't want that
	foreach($urlArray as $piece)
	{
		if($piece == "..")
		{
			http_response_code(404);
			die();
		}
	}
	
	//Get filePath
	$filePath = urldecode('.' . $url);
	
	if(file_exists($filePath))
	{
		//If it's a directory check if there is an index file
		if(is_dir($filePath))
		{
			if(file_exists($filePath . "/index.html"))
			{
				$filePath = $filePath . "/index.html";
			}
			else if(file_exists($filePath . "/index.php"))
			{
				$filePath = $filePath . "/index.php";
			}
			else if(file_exists($filePath . "/index.htm"))
			{
				$filePath = $filePath . "/index.htm";
			}
			else
			{
				http_response_code(404);
				die();
			}
		}
		
		processFilePath($filePath);
	}
	//Produce a 404
	else
	{
		http_response_code(404);
		die();
	}
?>
