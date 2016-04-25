<?php

/**********************************************************
* Global variables that will hold the extracted portions
* of the HTML file
**********************************************************/
$headContents = "";
$pageTitle = "";
$bodyContents = "";

/**********************************************************
* Extracts the head and title sections, as well as the
* body portion. Void return.
**********************************************************/
function extractContents($page)
{
    extractHeadAndTitle($page);
    extractBody($page);
}

/**********************************************************
* Extracts the head and title sections of the HTML file,
* if either or both of them exist. Extracted portions are
* stored in global variables for files that include this
* one. Void return.
**********************************************************/
function extractHeadAndTitle($page)
{
    global $headContents;
    global $pageTitle;

    $pattern = "#<head.*?>(.*?(<title.*?>(.*?)</title.*?>).*?|.*)</head.*?>#is";
    $matches = array();

    if(preg_match($pattern, $page, $matches))
    {
        $headContents = trim($matches[1], "\r\n");
        
        if(isset($matches[3]))
        {
            $pageTitle = trim($matches[3]);

            $headContents = str_replace($matches[2], '', $headContents);
        }
        else
        {
            $pageTitle = false;
        }
    }
}

/**********************************************************
* Extracts the body section of the HTML file, assuming one
* is present. Extracted portion is stored in a global
* variables for files that include this one. Void return.
**********************************************************/
function extractBody($page)
{
    global $bodyContents;

    $pattern = "#<body.*?>(.*?)</body.*?>#is";
    $matches = array();

    if(preg_match($pattern, $page, $matches))
    {
        $bodyContents = trim($matches[1], "\r\n");
    }
}



function headTitle()
{
    global $pageTitle;
    return $pageTitle;
}

function headContents()
{
    global $headContents;
    return $headContents;
}

function pageContents()
{
    global $bodyContents;
    return $bodyContents;
}

function mainNavigation()
{
    global $CONFIG;
    
    ob_start();
?>
<ul>
<?php foreach ($CONFIG[CONFIG_MAIN_NAV_KEY] as $item) : ?>
  <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
<?php endforeach; ?>
</ul>
    <?php
    return ob_get_clean();
}

function footerNavigation()
{
    global $CONFIG;
    
    ob_start();
?>
<ul>
<?php foreach ($CONFIG[CONFIG_MAIN_NAV_KEY] as $item) : ?>
  <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
<?php endforeach; ?>
<?php foreach ($CONFIG[CONFIG_FOOTER_NAV_KEY] as $item) : ?>
  <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
<?php endforeach; ?>
</ul>
    <?php
    return ob_get_clean();
}

function templateUrl()
{
    global $CONFIG;
    return '/templates/'.$CONFIG[CONFIG_TEMPLATE_KEY];
}

?>
