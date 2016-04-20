<?php

// Global dictionary of website configuration values
$CONFIG = array();

define('CONFIG_FILENAME', __DIR__.'/config.xml');
define('CONFIG_TEMPLATE_KEY', 'template');

// Load and parse config.xml
$xml = new SimpleXMLElement(file_get_contents(CONFIG_FILENAME));

if ($xml) {
    if (isset($xml->template)) {
        $CONFIG[CONFIG_TEMPLATE_KEY] = (string) $xml->template;
    }
}

// Clean up working variables, since all working variables are global
unset($xml);

