<?php

    $gmxtop2solute = file_exists("src/rismhi3d/gmxtop2solute")? "src/rismhi3d/gmxtop2solute" : "";
    $rismhi3d = file_exists("src/rismhi3d/rismhi3d")? "src/rismhi3d/rismhi3d" : "";
    $ts4sdump = file_exists("src/rismhi3d/ts4sdump")? "src/rismhi3d/ts4sdump" : "";
    $gensolvent = file_exists("src/rismhi3d/gensolvent")? "src/rismhi3d/gensolvent" : "";
    $heatmap = file_exists("src/rismhi3d/heatmap")? "src/rismhi3d/heatmap" : "";
    $iet_bin = (!empty($rismhi3d) && !empty($gmxtop2solute) && !empty($ts4sdump))? "src/rismhi3d" : "";

  echo("<html><head>\n");
    echo ('<meta charset="utf-8" />'."\n");
    echo ('<link rel="apple-touch-icon" href="/images/favicon.png">'."\n");
    $title_folder_name = explode('/',$url);
    echo("<title>".(!empty($title)?($title." - "):"").$servername."</title>\n");
    echo('<meta name="viewport" content="width='.(empty($page_width)?"device-width":$page_width).', initial-scale=1.0">'."\n");
    if (!empty($auto_reload)) echo('<meta http-equiv="refresh" content="'.$auto_reload.'" />'."\n");
    echo ('<link href="/'.$css.'" rel="stylesheet" type="text/css">'."\n");
  echo("</head>"."\n");

?>
