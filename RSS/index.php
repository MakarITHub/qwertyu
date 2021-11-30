<!DOCTYPE html>
<html lang="en" dir="ltr">
          <head>
                    <meta charset="utf-8">
                    <title>RSS</title>
                    <link rel="stylesheet" href="style.css">
                    <?php
                              ini_set('display_errors', 'off'); error_reporting(0);
                              include("write_rss.php");
                              $myRSS = new rssReader();
                    ?>
          </head>
          <body>
                    <div class="RSS">
                              <?php
                                        $myRSS->createHtmlFromFeed(0, 5);
                              ?>
                    </div>
          </body>
</html>
