<?php
    $title = "install";
    $configfile = "src/rismhi3d/code-rismhi3d/header.h";
    $configfilebackup = "src/rismhi3d/code-rismhi3d/header.txt";

    include ("header.php");
    include ("page_head.php");
    echo ("<script>\n");
    echo ("</script>\n");
    include ("page_header_element.php");

    $fftw = $_POST["fftw"]??"";
    $main = $_POST["main"]??"";
    $working = (empty($fftw) && empty($main)) ? "" : "busy";
    // echo ('<pre>['.$fftw."]\n[".$main.']</pre>');

    if (!empty($fftw)){
        shell_exec('cd src/fftw; bash run-build-fftw.sh');
    } else if (!empty($main)){
        shell_exec('cd src/rismhi3d; bash run-build-core.sh');
    }

    $ver_rismhi3d = ""; if (!empty($rismhi3d)) $ver_rismhi3d = shell_exec($rismhi3d);
    $ver_ts4sdump = ""; if (!empty($ts4sdump)) $ver_ts4sdump = shell_exec($ts4sdump);
    $ver_gmxtop2solute = ""; if (!empty($gmxtop2solute)) $ver_gmxtop2solute = shell_exec($gmxtop2solute);
    $ver_gensolvent = ""; if (!empty($gensolvent)) $ver_gensolvent = shell_exec($gensolvent);
    $ver_heatmap = ""; if (!empty($heatmap)) $ver_heatmap = shell_exec($heatmap);

    //$ver_fftw = ""; if (file_exists("src/fftw/fftw3/lib/libfftw3.a")) $ver_fftw = "3";

    $ver_fftw = ""; if (file_exists("src/fftw/fftw3/lib/libfftw3.a") && file_exists("src/fftw/fftw3/bin/fftw-wisdom-to-conf")) $ver_fftw = shell_exec('src/fftw/fftw3/bin/fftw-wisdom-to-conf -V | head -n1 | awk \'{print $NF}\'');

    echo ('<center>'."\n");

    echo ('<div class="container" style="margin:5 auto;">'."\n");
    
    if (!empty($ver_fftw)){
        echo ('<br><b>fftw '.$ver_fftw.' installed</b><br>'."\n");
    } else {
        if (empty($iet_bin)) echo ('<br><font color=red>Please install FFTW3, which is required by CWBSol</font></br>'."\n");
        else echo ('<br>FFTW3 not installed<br>'."\n");
    }

    if (!empty($iet_bin)){
        echo ('<br><b>'.$software_name.' installed:</b><table>');
        echo ('<tr><td> <li>'.$ver_rismhi3d.'</td></tr>');
        echo ('<tr><td> <li>'.$ver_ts4sdump.'</td></tr>');
        echo ('<tr><td> <li>'.$ver_gmxtop2solute.'</td></tr>');
        if (!empty($ver_gensolvent)) echo ('<tr><td> <li>'.$ver_gensolvent.'</tr></td>');
        if (!empty($ver_heatmap)) echo ('<tr><td> <li>'.$ver_heatmap.'</tr></td>');
        echo ('</table>'."\n");
    } else {
        echo ('<br><font color=red>Please install CWBSol</font></br>'."\n");
    }

    $ver_gnuplot = shell_exec("gnuplot --version|head -n1|awk '{print $2}'");
    if (!empty($ver_gnuplot)){
        echo ("<br>gnuplot ".$ver_gnuplot." installed<br>");
    } else {
        echo ('<br><font color=red>Please install gnuplot for analysing</font></br>'."\n");
    }

    $ver_convert = shell_exec("convert --version|head -n1|awk '{print $3}'");
    if (!empty($ver_convert)){
        echo ("<br>ImageMagick ".$ver_convert." installed<br>");
    } else {
        echo ('<br><font color=red>Please install ImageMagick for analysing</font></br>'."\n");
    }

    echo ('  <br>');
    echo ('  <a href="/config.php"><input type="submit" name="stop" value="configurations" /></a>'."\n");
    echo ('  <form action="/install.php" method="post" style="display:inline;">'."\n");
    echo ('    <input type="submit" '.(empty($fftw)?'':'style="color:red"').' name="fftw" value="'.(empty($ver_fftw)?'install':'reinstall').' fftw3" '.(empty($working)?'':'disabled="disabled"').' />'."\n");
    echo ('    <input type="submit" '.(empty($main)?'':'style="color:red"').' name="main" value="'.(empty($iet_bin)?'install':'reinstall').' CWBSol" '.(empty($working)?'':'disabled="disabled"').' />'."\n");
    echo ('    <input type="submit" name="stop" value="done"/>'."\n");
    echo ('  </form><br>'."\n");

    if (file_exists($configfilebackup) && !empty(shell_exec("diff ".$configfile." ".$configfilebackup))){
        echo ('<p><small><font color=#FF8000>Warning: settings have been changed since last compilation.</font></small></p>');
    }

    if (file_exists("src/stdout.txt")){
        echo ('<br><a href="/viewfile.php?scroll=bottom&u='.getcwd().'/src/stdout.txt" target="_blank">see installation information in a new screen</a>'."\n");
    }

    echo ('</div><br>');

    echo ('</center>');

    include ("page_footer.php");

?>
