<?php
require_once 'includes/config.php';
require_once 'includes/mcs-user.php';
require_once 'includes/template.php';

User::setSubclass('MCSUser');

session_start();

$redirections = array(
	'./index.php' => './pages/home/index.php'
);

function processFilePath($filePath)
{
  global $CONFIG;
  
	//Get extension
	$extension = explode ('.', $filePath);
	$extension = $extension[count($extension) - 1];

	if ($extension == "php" || $extension == "html" || $extension == "htm")
	{
	  $pageContents = '';
	  
	  if ($extension == "php")
	  {
		  // Execute PHP
		  ob_start();                     // capture anything sent to the output buffer
		  include $filePath;
		  $pageContents = ob_get_clean(); // end output buffering
		}
	  else if ($extension == "html" or $extension == "htm")
	  {
		  // Print out the HTML contents
		  $pageContents = file_get_contents($filePath);
		}
		
		// Now that we have page contents, time to extract key HTML elements
		extractContents($pageContents);
		
		// Finally, execute the templating script
		include 'templates/'.$CONFIG[CONFIG_TEMPLATE_KEY].'/index.php';
	}
	else
	{
		//Serve the file
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
		echo "404: Not found";
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
		$defaultFiles = array(
			'index.php',
			'index.html',
			'index.htm'
		);

		$foundDefaultFile = false;

		foreach ($defaultFiles as $defaultFile)
		{
			if (file_exists($filePath . $defaultFile))
			{
				$filePath = $filePath . $defaultFile;
				$foundDefaultFile = true;
				break;
			}
		}

		if (!$foundDefaultFile)
		{
			//Produce a 404
			http_response_code(404);
			echo "404: Not found";
			die();
		}
	}
	
	// Apply any redirections
	if (isset($redirections[$filePath]))
	{
		$filePath = $redirections[$filePath];
		
		if (!file_exists($filePath))
		{
			http_response_code(404);
			echo "404: Not found";
			die();
		}
	}
	
	processFilePath($filePath);
}
else
{
	//Produce a 404
	http_response_code(404);
	echo "404: Not found";
	die();
}
