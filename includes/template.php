<?php

$headContents = "";
$pageTitle = "";
$bodyContents = "";

function extractContents($page)
{
    extractHeadAndTitle($page);
    extractBody($page);
}

function extractHeadAndTitle($page)
{
    global $headContents;
    global $pageTitle;

    $pattern = "#<head.*?>(.*?(<title.*?>(.*?)</title.*?>).*?|.*)</head.*?>#is";
    $matches = array();

    preg_match($pattern, $page, $matches);

    $headContents = trim($matches[1], "\r\n");
    
    if(isset($matches[3]))
    {
        $pageTitle = trim($matches[3]);
        $titlePattern = "#$matches[2]#";

        $headContents = preg_replace($titlePattern, '', $headContents);
    }
}

function extractBody($page)
{
    global $bodyContents;

    $pattern = "#<body.*?>(.*?)</body.*?>#is";
    $matches = array();

    preg_match($pattern, $page, $matches);

    $bodyContents = trim($matches[1], "\r\n");
}

?>
