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
        
        $STH = $DBH->prepare('SELECT SUBSTR(r.reference,7) AS id, p.title, r.rating, r.vote_count, DATE_FORMAT(FROM_UNIXTIME(r.tstamp), "%d.%m.%Y %H:%i") AS last_klick
                            FROM tx_ratings_data AS r
                            LEFT JOIN pages AS p ON (SUBSTR(r.reference,7) = p.uid)
                            ORDER BY r.rating/r.vote_count DESC, r.vote_count DESC');
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
              <li class="active"><a href="qualit.php">Qualitativ</a></li>
              <li><a href="quantit.php">Quantitativ</a></li>
              <li><a href="zeitlich.php">Zeitlich</a></li>
            </ul>
              <a href="logout.php" class="btn btn-primary pull-right">Sign out</a>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Oberste marketing Botschaft -->
      <div class="page-header">
        <h3>Auswertung - qualitativ</h3>
        <p>Bisher wurden <?=count($ratings) ?> Artikel bewertet.</p>
      </div>

      <div class="row">
          <div class="span8">
                <div class="accordion" id="accordion2">
                    <?php
                    foreach ($ratings as $value):
                        if ($rating_qualitaet != round($value['rating']/$value['vote_count'],1)):
                            $rating_qualitaet = round($value['rating']/$value['vote_count'],1);
                            $accordionID = str_replace(".", "_", $rating_qualitaet);
                    ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <h4>
                                        <a class="accordion-toggle text-error" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?=$accordionID; ?>">
                                        Ratings mit <?= sprintf("%01.1f", $rating_qualitaet) ?> Punkten</a>
                                    </h4>
                                </div>
                                <div id="collapse<?=$accordionID; ?>" class="accordion-body collapse">
                                    <div class="accordion-inner">              

                                        <table class="table">
                                          <thead>
                                              <th>Seitentitel</th>
                                              <th>Durschnitt</th>
                                              <th>Gesamtratings</th>
                                              <th>Letztes rating</th>
                                          </thead>
                                          <tbody>
                                        <?php
                                        foreach ($ratings as $value):
                                            if ($rating_qualitaet == round($value['rating']/$value['vote_count'],1)):
                                                echo '<tr>';
                                                echo '<td><a href="'.WEBSITE.'/index.php?id='.$value['id'].'" target="_blank">'.$value['title'].'</a></td>';
//                                                echo '<td>'.sprintf("%01.1f", ($value['rating']/$value['vote_count'])).'</td>';
                                                echo '<td>'.sprintf("%01.1f", round($value['rating']/$value['vote_count'],1)).'</td>';
                                                echo '<td>'.$value['vote_count'].'</td>';
                                                echo '<td>'.$value['last_klick'].'</td>';
                                                echo '<tr>';
                                            endif;
                                        endforeach;;

                                        ?>
                                          </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- Ende accrdion group  -->
                        <?php
                        endif;
                        endforeach;
                    ?>
                </div><!-- Ende accordion -->
            </div><!-- Ende span8 -->                            
          
            <div class="span1"></div>
            <div class="span3">
                <h3>Hilfe</h3>
                <p>
                    Die Daten werden nach ihrer Häufigkeit der Votings gefiltert.<br />
                    Nachkommastellen werden wegen der beseren Lesbarkeit auf eine Stelle hiner dem Komma gekürtzt und ggf. zusammengefasst. Gibt es mehrere Artikel mit gleicher Punktzahl , so werden die Artikel, die mehr Bewertungen haben weiter oben angezeigt.<br />
                    <br />
                    Bisher nicht bewertete Artikel werden nicht dargestellt.
                </p>
            </div>

      </div>

      <hr>

      <footer>
          <p><?php echo date("d.m.Y")?></p>
      </footer>

    </div> <!-- /container -->

    <script src="js/jquery-2.0.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
