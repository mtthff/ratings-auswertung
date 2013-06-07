<?php
    require_once 'config.php';
    
    try {
        $DBH= new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPW);
        $DBH->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);// ::TODO:: change it befor productive
        
        $STH = $DBH->prepare('SELECT SUBSTR(r.reference,7) AS id, p.title, r.rating, r.vote_count, DATE_FORMAT(FROM_UNIXTIME(r.tstamp), "%d.%m.%Y %H:%i") AS last_klick
                            FROM tx_ratings_data AS r
                            LEFT JOIN pages AS p ON (SUBSTR(r.reference,7) = p.uid)
                            ORDER BY r.vote_count DESC');
        $STH->execute();
        $ratings = $STH->fetchAll();
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
              <li><a href="index.php">Qualitativ</a></li>
              <li class="active"><a href="#">Quantitativ</a></li>
              <li><a href="zeitlich.php">Zeitlich</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Oberste marketing Botschaft -->
      <div class="page-header">
        <h3>Auswertung - quantitativ</h3>
        <p>Bisher wurden <?=count($ratings) ?> Artikel bewertet.</p>
      </div>

      <!-- Example row of columns -->
       <div class="row">
          <div class="span8">
                <div class="accordion" id="accordion2">
                    <?php
                    foreach ($ratings as $value):
                        if ($rating_quantitaet != $value['vote_count']):
                            $rating_quantitaet = $value['vote_count'];
                            $accordionID = str_replace(".", "_", $rating_quantitaet);
                    ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <h4>
                                        <a class="accordion-toggle text-error" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?=$accordionID; ?>">
                                        Ratings mit <?=$rating_quantitaet ?> Bewertungen</a>
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
                                            if ($rating_quantitaet == $value['vote_count']):
                                                echo '<tr>';
                                                echo '<td><a href="'.WEBSITE.'/index.php?id='.$value['id'].'" target="_blank">'.$value['title'].'</a></td>';
                                                echo '<td>'.sprintf("%01.1f", ($value['rating']/$value['vote_count'])).'</td>';
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
                    Die Daten werden nach ihrer Häufigkeit der Votings gefiltert. Die Artikel mit den meisten Bewertungen stehen oben.<br />
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
