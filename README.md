# Website Organization and Administration

**School:** SDSM&T  
**Course:** CSC 468: GUI Programming  
**Semester:** Spring 2016  
**Professor:** Dr. John Weiss  
**Assignment:** Programming Assignment 3  
**Team:** Daniel Andrus, Austin Rotert, Christian Sieh

## Contents

1. [Installation and Configuration](#ch1)
2. [Authentication and Permissions](#ch2)
3. [Development Guidelines and Requirements](#ch3)

## <a name="ch1">Installation and Configuration</a>

To use this code during development, you will need to do some initial setup.

1. [Enable Apache URL rewriting module](#ch1s1)
2. [Enable .htaccess files](#ch1s2)

### <a name="ch1s1">1. Enable Apache URL rewriting module</a>

Apache must be configured to allow URL rewriting in order for this code to work
correctly. To enable URl rewriting, follow the below steps:

1. Open your Apache configuration file in a plain text editor. (Google it if you
don't know where it is.)
2. Find the line containing the text "LoadModule rewrite_module".
3. If the line has a "`#`" at the beginning, remove the "`#`" to uncomment the
line.
4. Save the file and restart Apache.

### <a name="ch1s2">2. Enable .htaccess Files</a>

Apache must be configured to allow the contents of .htaccess files to override
the default Apache configuration. This is necessary for our code to execute
properly.

1. Open your Apache configuration file in a plain text editor. (Google it if you
don't know where it is.)
2. Find the line containing the text "AllowOverride None". If this line does not
exist, then .htaccess files may already be enabled. If this line is found, then
it should exist between opening and closing "`<Directory>`" tags.
3. Change the line to say "AllowOverride All"
4. Save the file and restart Apache.

## <a name="ch2">Authentication and Permissions</a>

With our backend we are providing a convenient interface for checking for logged
in users and getting their permissions. We are providing this API as a PHP
abstract class that will be accessible from all pages accessed using the code in
this repository. With this API, you will be able to:

1. [Check if a user is logged in](#ch2s1)
2. [Get the currently logged in user](#ch2s2)
3. [Check if a user has certain permissions](#ch2s3)
4. [Declare custom permissions](#ch2s4)

Explainations on these subjects will be followed by an [example](#ch2s5)

Below is an outline of the PHP classes you will be using to perform the above
functions. The functionality of these classes will be developed over the course
of this project and will be uploaded to this repository.

    abstract class User
    {
        static boolean isAuthenticated();
        static User    getCurrentUser();
        static void    declarePermission(string $permission);
        string         getUsername();
        string         getDisplayName();
        boolean        hasPermission(string $permission);
    }

### <a name="ch2s1">1. Check if a User is Logged In</a>

To check if a user is currently logged in to the site, simply call the
`isAuthenticated()` static function, which will return `true` if a user is
logged in or `false` if no user is logged in. An example of this is provided
below.

### <a name="ch2s2">2. Get the Currently Logged in User</a>

After checking if a user is logged in to the site, you can get the `User` object
representing that user by calling the `getCurrentUser()` static function. This
function will return the `User` object of the currently logged in user, or it
will return `null` if no user is logged in. An example of this is provided
below.

### <a name="ch2s3">3. Check if a User has Certain Permissions</a>

Permissions will be represented using strings. Your own team can design what
permissions your pages will need to use, then you can check if a user if has
a permission by calling the `hasPermission(string $permission)` function. An
example of this is provided below.

### <a name="ch2s4">4. Declare Custom Permissions</a>

Before checking if a user has a certain permission, you will need to declare a
permission at the top of a file. This can be done by calling the
`declarePermission(string $permission)` static function. An example of this is
provided below.

### <a name="ch2s5">Example</a>

    <?php
    // Declare any permissions to be used at the top. These permission strings
    // can be built dynamically based on the page you're on and can have
    // as much granularity as you see fit.
    User::declarePermission('student-org.game-dev.edit-details');
    User::declarePermission('student-org.game-dev.edit-member-list');
    User::declarePermission('student-org.game-dev.edit-officer-list');
    
    // Check if a user is logged in
    if (User::isAuthenticated())
    {
        // User is logged in, get current user
        $user = User::getCurrentUser();
        
        echo '<form action="update-group.php" method="post">';
        echo '<input type="hidden" name="org-id" value="game-dev" />';
        
        // Display form fields based on user permissions
        if ($user->hasPermission('student-org.game-dev.edit-details')
        {
            // Insert whatever logic necessary here
            // ...
            // ...
        }
        
        if ($user->hasPermission('student-org.game-dev.edit-member-list')
        {
            // Insert whatever logic necessary here
            // ...
            // ...
        }
        
        if ($user->hasPermission('student-org.game-dev.edit-officer-list')
        {
            // Insert whatever logic necessary here
            // ...
            // ...
        }
        
        echo '<input type="submit" value="Update" />';
        echo '<button onclick="window.history.back()">Cancel</button>';
        echo '</form>';
    }
    else
    {
        // User is not logged in, display error message
        echo '<p class="error">You must be logged in to view this page!</p>';
    }
    ?>

## <a name="ch3">Development Guidelines and Requirements</a>

The goal of this project is to build a website framework upon which the other
teams in this assignment can develop their parts of this project. This document
contains guidelines and specifications when generating HTML content for pages
and for organizing your page files.

1. [All page files and content placed in that page's assigned subdirectory](#ch3s1)
2. [Only use relative paths](#ch3s2)
3. [Limit use of style tags and attributes](#ch3s3)
4. [Use standard HTML structure for navigation](#ch3s4)
5. [Do not use tables for layouts](#ch3s5)
6. [Avoid size attributes](#ch3s6)
7. [Name files using common convention](#ch3s7)

### <a name="ch3s1">1. All Page Files and Content Placed in That Page's Assigned Subdirectory (Required)</a>

Each distinct page for the site will have its own unique directory under the
`pages` directory. All files relevant for the page should be included in that
page's directory.

### <a name="ch3s2">2. Only Use Relative Paths (Required)</a>

When referencing links or images on a page, always use relative links, never
absolute links. This will make incorporating your team's code into the rest of
the site far easier.

*Example of relative links (RECOMMENDED):*

    <img src="images/upload.png" />
    <a href="../submit/">Submit</a>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <script type="text/javascript" src="../shared-scripts/myscript.js"></script>

*Example of absolute links (DISCOURAGED):*

    <img src="/pages/my-page/images/upload.png" />
    <a href="http://dev.mcs.sdsmt.edu/~1234567/pages/submit/index.php">Submit</a>
    <link rel="stylesheet" type="text/css" href="/~1234567/pages/my-page/css/styles.css" />

### <a name="ch3s3">3. Limit Use of Style Tags and Attributes</a>

When styling your elements, refrain from using the `style=""` attribute or
from putting `<style></style>` tags in your HTML. Move these rules to a
separate CSS file and apply them to elements using CSS classes.

*Example of external CSS (ENCOURAGED):*

`index.php` contents:

    <link type="text/css" rel="stylesheet" href="styles.css" />
    <p class="favorite"> This is my most favorite paragraph! </p>
    <a href="../submit/" class="submit-button"> This is a link that looks like a red button! </a>
    
`styles.css` contents:
    
    p.favorite {
      color: red;
      font-size: 1.25em;
    }
    a.submit-button {
      display: inline-block;
      margin: auto 1em;
      padding: 6px 10px;
      background-color: red;
      box-shadow: 0px 2px 6px 0px rgba(0,0,0,0.5);
    }

*Example of inline CSS (DISCOURAGED):*

    <p style="color: red; font-size: 1.25em;"> This is my most favorite paragraph! </p>
    <a href="../submit/" class="red-button"> This is a link that looks like a red button! </a>
    
    <style>
    a.red-button {
      display: inline-block;
      margin: auto 1em;
      padding: 6px 10px;
      background-color: red;
      box-shadow: 0px 2px 6px 0px rgba(0,0,0,0.5);
    }
    </style>

### <a name="ch3s4">4. Use Standard HTML Structure for Navigation (Recommended)</a>

If you need to include navigation between multiple pages in your section of the
site, please structure the HTML of your navigation menu as follows:

    <nav class="section-nav">
      <ul>
        <li><a href="link-to-page">Link 1</a></li>
        <li><a href="link to page">Link 2</a></li>
        <!-- repeat as necessary -->
      </ul>
    </nav>

Not all teams will need this, but if your pages does, please place it at the
very top of your HTML content.

If you would like this menu to navigate to different sections on the same page,
use the class `page-nav` instead of `section-nav`.

### <a name="ch3s5">5. Do Not Use Tables for Layouts (Required)</a>

Please avoid using `<table>...</table>` tags for laying out your page. Use
`<div>` elements for grouping elements in a related section and `<ul>` tags for
displaying lists of elements. These are far easier to style than tables.

If you need to display tabular data, such as numbers and calculations, using a
table to organize the data is okay.

### <a name="ch3s6">6. Avoid Size Attributes (Recommended)</a>

When adding images and other elements to your page, avoid including sizing
attributes, such as `width=""` and `height=""`; this is something that should be
placed in the CSS stylesheets.

### <a name="ch3s7">7. Follow File Naming Conventions (Required)</a>

When naming files, please use all lowercase names with words separated by dashes
(`-`). **Do not include spaces in your file names!**

- Encouraged names:
  - *index.php*
  - *my-javascript-file.js*
  - *images/logo.png*
  - *images/secondary-logo.png*
- Discouraged names:
  - *index.HTML*
  - *MyJavascriptFile.js*
  - *Images/Logo.png*
  - *Images/Secondary Logo.png*
