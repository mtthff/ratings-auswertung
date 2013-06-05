<?php
    require_once 'config.php';
    
    try {
        $DBH= new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPW);
        $DBH->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);// ::TODO:: change it befor productive
        $STH = $DBH->query('SELECT a.id,
                                status_id,
                                strftime("%d.%m.%Y", a.datetime) AS day,
                                strftime("%H:%M", a.datetime) AS time,
                                cu.organisation,
                                cu.contact,
                                cu.phone,
                                a.number,
                                a.comment,
                                a.type_id,
                                a.age,
                                at.label AS labeltarif,
                                a.juhe,
                                av.label AS labelversion,
                                a.fotocd,
                                co.name,
                                strftime("%d.%m.%Y", a.listed_date) AS listed_date
                            FROM appointment AS a
                            LEFT JOIN customer AS cu ON (a.customer_id = cu.id)
                            LEFT JOIN contributor AS co ON (a.contributor_id = co.id)
                            LEFT JOIN appointment_version AS av ON (a.version_id = av.id)
                            LEFT JOIN appointment_tarif AS at ON (a.tarif_id = at.id)
                            ORDER BY a.datetime ASC');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $STH->fetch()){
            $app[]= $row;
        }
    //    echo "<pre>";
    //    print_r($app);
    //    exit;
        
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
    <title>tt-ranking Auswertung</title>
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

          <a class="brand" href="index.php">tt_ranking Auswertung</a>
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
        <h1>Auswertung <small> Rankings</small></h1>
<!--        <p></p>
        <p><a href="#" class="btn btn-primary btn-large">weiterlesen &raquo;</a></p>-->

      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span8">
          <h2>Heading</h2>
            <a href="#">
                <img src="http://placehold.it/120x120" style="float:left; padding-right: 10px" class="img-rounded"/>
            </a>  
		<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.</p>
          <p><a class="btn" href="#">weiterlesen &raquo;</a></p>
        </div>

        <div class="span4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="#">weiterlesen &raquo;</a></p>
        </div>
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
