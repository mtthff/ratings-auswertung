<?php
/**
 * Projekt: tt-Ratings-Auswertung
 * 
 * @author Matthias Hoffmann <mtthff@gmail.com>
 * @version v 0.1 2013-06-10
 */
    session_start();
    if(!$_SESSION['uid']){
        header('Location: index.php');
        exit;
    }

    require_once 'config.php';
    
    try {
        $DBH= new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPW);
        $DBH->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);// ::TODO:: change it befor productive
        
        $STH = $DBH->prepare('SELECT SUBSTR(r.reference,7) AS id, p.title, r.rating, r.vote_count, DATE_FORMAT(FROM_UNIXTIME(r.tstamp), "%d.%m.%Y %H:%i") AS last_klick, r.tstamp
                            FROM tx_ratings_data AS r
                            LEFT JOIN pages AS p ON (SUBSTR(r.reference,7) = p.uid)
                            ORDER BY r.tstamp DESC, r.rating/r.vote_count DESC, r.vote_count DESC');
        $STH->execute();
        $ratings = $STH->fetchAll();
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
            
        <!-- responsiv Navigation -->
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="qualit.php">Qualitativ</a></li>
              <li><a href="quantit.php">Quantitativ</a></li>
              <li class="active"><a href="#">Zeitlich</a></li>
              <!--<li><a href="about.php">About</a></li>-->			  
            </ul>
            <a href="logout.php" class="btn btn-primary pull-right">Sign out</a>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Oberste marketing Botschaft -->
      <div class="page-header">
        <h3>Auswertung - zeitlich</h3>
        <p>Bisher wurden <?=count($ratings) ?> Artikel bewertet.</p>
      </div>

        <div class="row">
            <div class="span8">
              
                <div class="accordion" id="accordion2">
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4>
                                <a class="accordion-toggle text-error" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                Ratings in den letzten 24 Stunden</a>
                            </h4>
                        </div>
                    <div id="collapseOne" class="accordion-body collapse in">
                        <div class="accordion-inner">
                            <table class="table table-striped">
                              <thead>
                                  <th>Seitentitel</th>
                                  <th>Durschnitt</th>
                                  <th>Gesamtratings</th>
                                  <th>Letztes rating</th>
                              </thead>
                              <tbody>
                            <?php
                              $gestern = time()-(60*60*24);
                              foreach ($ratings as $value) {
                                  if ($value['tstamp'] > $gestern){
                                      echo '<tr>';
                                      echo '<td><a href="'.WEBSITE.'/index.php?id='.$value['id'].'" target="_blank">'.$value['title'].'</a></td>';
                                      echo '<td>'.sprintf("%01.1f", ($value['rating']/$value['vote_count'])).'</td>';
                                      echo '<td>'.$value['vote_count'].'</td>';
                                      echo '<td>'.$value['last_klick'].'</td>';
                                      echo '<tr>';
                                  }
                              }
                            ?>
                              </tbody>
                            </table>
                        </div>
                    </div>
                    </div><!-- Ende accrdion group 1 -->
         
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4>
                                <a class="accordion-toggle text-error" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                    Gestrige Ratings</a>
                            </h4>
                        </div>
                    <div id="collapseTwo" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <table class="table table-striped">
                              <thead>
                                  <th>Seitentitel</th>
                                  <th>Durschnitt</th>
                                  <th>Gesamtratings</th>
                                  <th>Letztes rating</th>
                              </thead>
                              <tbody>
                            <?php
                              $vorgestern = time()-(60*60*48);
                              foreach ($ratings as $value) {
                                  if ($value['tstamp'] > $vorgestern AND $value['tstamp'] < $gestern){
                                      echo '<tr>';
                                      echo '<td><a href="'.WEBSITE.'/index.php?id='.$value['id'].'" target="_blank">'.$value['title'].'</a></td>';
                                      echo '<td>'.sprintf("%01.1f", (sprintf("%01.1f", ($value['rating']/$value['vote_count'])))).'</td>';
                                      echo '<td>'.$value['vote_count'].'</td>';
                                      echo '<td>'.$value['last_klick'].'</td>';
                                      echo '<tr>';
                                  }
                              }
                            ?>
                              </tbody>
                            </table>
                        </div>
                    </div>
                    </div><!-- Ende accrdion group 2 -->
         
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4>
                            <a class="accordion-toggle text-error" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                                Ratings der letzten Woche</a>
                            </h4>
                        </div>
                    <div id="collapseThree" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <table class="table table-striped">
                              <thead>
                                  <th>Seitentitel</th>
                                  <th>Durschnitt</th>
                                  <th>Gesamtratings</th>
                                  <th>Letztes rating</th>
                              </thead>
                              <tbody>
                            <?php
                              $letzteWoche = time()-(60*60*24*7);
                              foreach ($ratings as $value) {
                                  if ($value['tstamp'] > $letzteWoche AND $value['tstamp'] < $vorgestern){
                                      echo '<tr>';
                                      echo '<td><a href="'.WEBSITE.'/index.php?id='.$value['id'].'" target="_blank">'.$value['title'].'</a></td>';
                                      echo '<td>'.sprintf("%01.1f", ($value['rating']/$value['vote_count'])).'</td>';
                                      echo '<td>'.$value['vote_count'].'</td>';
                                      echo '<td>'.$value['last_klick'].'</td>';
                                      echo '<tr>';
                                  }
                              }
                            ?>
                              </tbody>
                            </table>
                        </div>
                    </div>
                    </div><!-- Ende accrdion group 3 -->
         
         
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4>
                            <a class="accordion-toggle text-error" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                                Ratings vor der letzten Woche</a>
                            </h4>
                        </div>
                    <div id="collapseFour" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <table class="table table-striped">
                              <thead>
                                  <th>Seitentitel</th>
                                  <th>Durschnitt</th>
                                  <th>Gesamtratings</th>
                                  <th>Letztes rating</th>
                              </thead>
                              <tbody>
                            <?php
                              foreach ($ratings as $value) {
                                  if ($value['tstamp'] < $letzteWoche){
                                      echo '<tr>';
                                      echo '<td><a href="'.WEBSITE.'/index.php?id='.$value['id'].'" target="_blank">'.$value['title'].'</a></td>';
                                      echo '<td>'.sprintf("%01.1f", ($value['rating']/$value['vote_count'])).'</td>';
                                      echo '<td>'.$value['vote_count'].'</td>';
                                      echo '<td>'.$value['last_klick'].'</td>';
                                      echo '<tr>';
                                  }
                              }
                            ?>
                              </tbody>
                            </table>
                        </div>
                    </div>
                    </div><!-- Ende accrdion group 4 -->
         
                </div><!-- Ende accordion -->
            </div><!-- Ende span8 -->
            <div class="span1"></div>
            <div class="span3">
                <h3>Hilfe</h3>
                <p>
                    Die Daten werden nach dem Datum des letzten ratings gefiltert.<br />
                    Sehr aktuelle ratings werden gesondert dargestellt, äktere dafür eher gesammelt.<br />
                    <br />
                    Bisher nicht bewertete Artikel werden nicht dargestellt.
                </p>

            </div>
            
        </div><!-- Ende row -->

        <hr>

      <footer>
          <p><?php echo date("d.m.Y")?></p>

      </footer>

    </div> <!-- /container -->

    <script src="js/jquery-2.0.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
