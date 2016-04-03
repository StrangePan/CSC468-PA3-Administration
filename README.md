# Adminstration

**School:** SDSM&T  
**Course:** CSC 468: GUI Programming  
**Semester:** Spring 2016  
**Professor:** Dr. John Weiss  
**Assignment:** Programming Assignment 3  
**Team:** Website Organization and Administration  

## Development Guidelines and Requirements

The goal of this project is to build a website framework upon which the other
teams in this assignment can develop their parts of this project. This document
contains guidelines and specifications when generating HTML content for pages
and for organizing your page files.

### 1. All Page Files And Content Placed In Your Team's Assigned Subdirectory In `pages`

Each distinct page for the site will have its own unique directory under the
`pages` directory. All files relevant for the page should be included in that
page's directory.

### 2. Only Use Relative Links

When referencing links or images on a page, always use relative links, never
absolute links. This will make incorporating your team's code into the rest of
the site far easier.

*Example of relative links (DO THIS):*

    <img src="images/upload.png" />
    <a href="../submit/">Submit</a>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <script type="text/javascript" src="../shared-scripts/myscript.js"></script>

*Example of absolute links (DO NOT DO THIS):*

    <img src="/pages/my-page/images/upload.png" />
    <a href="http://dev.mcs.sdsmt.edu/~1234567/pages/submit/index.php">Submit</a>
    <link rel="stylesheet" type="text/css" href="/~1234567/pages/my-page/css/styles.css" />

### 3. Custom CSS and JavaScripts is OK

Any custom CSS or JavaScript files can be placed in the same directory as the
page in which it is used and referenced as normal in the HTML of the page. There
are no restrictions on this, but ther are some things to keep in mind.

1. You can include multiple CSS files. Use this to your advantage.
2. Try to keep CSS for overal page separate from CSS for specific elements.
3. Remember to always use relative paths.

### 4. Avoid Style Attributes/Tags

When styling your elements, refrain from using the `style=""` attribute or
from putting `<style></style>` tags in your HTML. Move these rules to a
separate CSS file and apply them to elements using CSS classes.

*Example of inline CSS (DO NOT DO THIS):*

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

*Example of better CSS practice (DO THIS):*

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

### 5. No Header/Footer

When displaying a page, please avoid adding any page headers or footers. Such
site-wide navigation elements will be included by a standard site template and
are thus redundant.

### 6. Navigation

If you need to include navigation between multiple pages in your secstion of the
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

### 7. Avoid Using Tables

Please avoid using `<table>...</table>` tags for laying out your page. Use
`<div>` elements for grouping elements in a related section and `<ul>` tags for
displaying lists of elements. These are far easier to style than tables.

If you need to display tabular data, such as numbers and calculations, using a
table to organize the data is okay.

### 8. Avoid Size Attributes

Wehn adding images and other elements to your page, avoid including sizing
attributes, such as `width=""` and `height=""`; this is something that should be
placed in the CSS stylesheets.

### 9. File Naming

When naming files, please use all lowercase names with words separated by dashes
(`-`).

- Valid names:
  - *index.php*
  - *my-javascript-file.js*
  - *images/logo.png*
  - *images/secondary-logo.png*
- Discouraged names:
  - *index.HTML*
  - *MyJavascriptFile.js*
  - *Images/Logo.png*
  - *Images/Secondary Logo.png*
