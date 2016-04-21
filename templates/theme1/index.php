<!DOCTYPE html>
<html>
  <head>
    <!-- Site title -->
    <title><?php if (headTitle()) echo headTitle().' - '; ?>Math and Computer Science - SDSM&amp;T</title>
    
    <!-- Template stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?php echo templateUrl(); ?>/css/styles.css" />
    <link rel="icon" type="image/x-icon" href="<?php echo templateUrl(); ?>/images/favicon.ico" />
    
    <!-- Template scripts -->
    
    <!-- Custom header contents -->
    <?php echo headContents(); ?>
  </head>
  <body>

    <!-- Main page header -->
    <div class="header-wrapper">
      <header>

        <!-- School logo -->
        <img src="<?php echo templateUrl(); ?>/images/sdsmt-logo.png" class="logo" alt="South Dakota School of Mines and Technology" />
        
        <!-- Website title -->
        <h1>Math and Computer Science Department</h1>
        <h2>South Dakota School of Mines and Technology</h2>
      </header>
    </div>
    
    <!-- Website navigation menu -->
    <nav class="main-nav">
      <?php echo mainNavigation(); ?>
    </nav>

    <!-- Main page content -->
    <div class="content-wrapper">
      <article class="contents">

        <!-- Custom page contents -->
        <?php echo pageContents(); ?>

      </article>
    </div>

    <!-- Main page footer -->
    <div class="footer-wrapper">
      <footer>

        <!-- Website footer navigation menu -->
        <nav class="footer-nav">
          <?php echo footerNavigation(); ?>
        </nav>

        <!-- Website copyright notice -->
        <p class="copyright">Copyright &copy; 2016 South Dakota School of Mines and Technology.</p>
      </footer>
    </div>
  </body>
</html>
