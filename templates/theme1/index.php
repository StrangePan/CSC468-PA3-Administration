<!DOCTYPE html>
<html>
  <head>
    <!-- Site title -->
    <title><?php if (headTitle()) echo headTitle().' - '; ?>Math and Computer Science - SDSM&amp;T</title>
    
    <!-- Template stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?php echo templateUrl(); ?>/css/styles.css" />
    
    <!-- Template scripts -->
    
    <!-- Custom header contents -->
    <?php echo headContents(); ?>
  </head>
  <body>

    <!-- Main page header -->
    <header>

      <!-- School logo -->
      <img src="<?php echo templateUrl(); ?>/images/sdsmt-logo.png" class="logo" alt="South Dakota School of Mines and Technology" />
      
      <!-- Website title -->
      <h1>Math and Computer Science Department</h1>

      <!-- Website navigation menu -->
      <?php echo mainNavigation(); ?>
    </header>

    <!-- Main page content -->
    <article class="contents">

      <!-- Custom page contents -->
      <?php echo pageContents(); ?>

    </article>

    <!-- Main page footer -->
    <footer>

      <!-- Website footer navigation menu -->
      <?php echo footerNavigation(); ?>

      <!-- Website copyright notice -->
      <p class="copyright">Copyright &copy; 2016 South Dakota School of Mines and Technology.</p>
    </footer>

  </body>
</html>
