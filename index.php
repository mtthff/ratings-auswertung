<?php
    require_once 'config.php';
    
    try {
        $DBH= new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPW);
        $DBH->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);// ::TODO:: change it befor productive
        $STH = $DBH->query('SELECT SUBSTR(r.reference,7) AS id, p.title, r.rating, r.vote_count, DATE_FORMAT(FROM_UNIXTIME(r.tstamp), "%d.%m.%Y %H:%i") AS last_klick
                            FROM tx_ratings_data AS r
                            LEFT JOIN pages AS p ON (SUBSTR(r.reference,7) = p.uid)
                            ORDER BY r.rating/r.vote_count DESC, r.vote_count DESC');

        $STH->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $STH->fetch()){
            $ratings[]= $row;
        }
        /*            
            [id] => 99
            [title] => Die Bewerbung
            [rating] => 6
            [vote_count] => 2
            [last_klick] => 05.06.2013 18:21
            [tstamp] => 1370449275
         */
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
              <li class="active"><a href="index.php">Qualitativ</a></li>
              <li><a href="quantit.php">Quantitativ</a></li>
              <li><a href="zeitlich.php">Zeitlich</a></li>
              <!--<li><a href="about.php">About</a></li>-->			  
            </ul>
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

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-2.0.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
