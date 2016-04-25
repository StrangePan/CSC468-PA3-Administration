<?php

// Global dictionary of website configuration values
$CONFIG = array();

define('CONFIG_FILENAME', __DIR__.'/config.xml');
define('CONFIG_TEMPLATE_KEY', 'template');
define('CONFIG_MAIN_NAV_KEY', 'mainNav');
define('CONFIG_ADMIN_PERMISSION_KEY', 'adminPermission');

// Load and parse config.xml
$xml = new SimpleXMLElement(file_get_contents(CONFIG_FILENAME));

// Parse the XML file if it loaded properly
if ($xml) {

    // extract set template
    if (isset($xml->template)) {
        $CONFIG[CONFIG_TEMPLATE_KEY] = (string) $xml->template;
    }
    
    // extract main navigation
    if (isset($xml->mainNav)) {
        $CONFIG[CONFIG_MAIN_NAV_KEY] = array();
        
        foreach ($xml->mainNav->item as $item) {
            $CONFIG[CONFIG_MAIN_NAV_KEY][] = array(
                'label' => $item->label,
                'href'  => $item->href
            );
        }
    }
    
    if (isset($xml->adminPermission)) {
        $CONFIG[CONFIG_ADMIN_PERMISSION_KEY] = (string) $xml->adminPermission;
    }
}

// Define the admin permission as a global constant
if (isset($CONFIG[CONFIG_ADMIN_PERMISSION_KEY])) {
    define('ADMIN_PERMISSION', $CONFIG[CONFIG_ADMIN_PERMISSION_KEY]);
}

// Clean up working variables, since all working variables are global
unset($xml);

