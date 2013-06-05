<?php
    require_once 'config.php';
    
    try {
        $DBH= new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPW);
        $DBH->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);// ::TODO:: change it befor productive
        $STH = $DBH->query('SELECT p.title, r.rating, r.vote_count, DATE_FORMAT(FROM_UNIXTIME(r.tstamp), "%d.%m.%Y %H:%i") AS last_klick
                            FROM tx_ratings_data AS r
                            LEFT JOIN pages AS p ON (SUBSTR(r.reference,7) = p.uid)
                            ORDER BY r.tstamp DESC');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $STH->fetch()){
            $ratings[]= $row;
        }
        /*            
            [title] => Ausbildung & Studium finanzieren
            [rating] => 3
            [vote_count] => 1
            [last_klick] => 05.06.2013 15:04
         */
        
//        echo "<pre>";
//        print_r($app);
//        exit;
        
    }
    catch(PDOException $e) 
    {
        echo $e->getMessage();
    }
    
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>tt-ratings Auswertung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Matthias Hoffmann">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="brand" href="index.php">tt_rattings Auswertung</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="#">Seite 1</a></li>
              <li><a href="#">Seite 2</a></li>
              <li><a href="about.php">About</a></li>			  
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Oberste marketing Botschaft -->
      <div class="hero-unit">
        <h1>Auswertung <small> tt_rattings</small></h1>
<!--        <p></p>
        <p><a href="#" class="btn btn-primary btn-large">weiterlesen &raquo;</a></p>-->

      </div>

      <!-- Example row of columns -->
      <div class="row">
          <div class="span8">
              <table class="table">
                  <thead>
                      <th>Seitentitel</th>
                      <th>Durschnitt</th>
                      <th>Gesamtratings</th>
                      <th>Letztes rating</th>
                  </thead>
                  <tbody>
              <?php
              /*
               *             [title] => Ausbildung & Studium finanzieren
                            [rating] => 3
                            [vote_count] => 1
                            [last_klick] => 05.06.2013 15:04
               */
              foreach ($ratings as $value) {
                  echo '<tr>';
                  echo '<td>'.$value['title'].'</td>';
                  echo '<td>'.$value['rating']/$value['vote_count'].'</td>';
                  echo '<td>'.$value['vote_count'].'</td>';
                  echo '<td>'.$value['last_klick'].'</td>';
                  echo '<tr>';
              }
              ?>
                  </tbody>
              </table>
          </div>
          
<!--        <div class="span8">
          <h2>Heading</h2>
            <a href="#">
                <img src="http://placehold.it/120x120" style="float:left; padding-right: 10px" class="img-rounded"/>
            </a>  
		<p></p>
          <p><a class="btn" href="#">weiterlesen &raquo;</a></p>
        </div>

        <div class="span4">
          <h2>Heading</h2>
          <p></p>
          <p><a class="btn" href="#">weiterlesen &raquo;</a></p>
        </div>-->
      </div>

      <hr>

      <footer>
          <p><?php echo date("d.m.Y")?></p>
      </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-2.0.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
