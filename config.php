<?php

    $title = "config";
    $phpurl = "/config.php";
    $configfile = "src/rismhi3d/code-rismhi3d/header.h";

    include ("header.php");
    include ("page_head.php");
    echo ("<script>\n");
    echo ("</script>\n");
    include ("page_header_element.php");

    echo ('<div class="contentcontainer">'."\n");
    echo ('<div class="container">'."\n");

    if (!file_exists('src/rismhi3d/code-rismhi3d')){
        shell_exec("cd src/rismhi3d; tar -zxf code-rismhi3d.tar.gz");
    }

    $maxnt  = $_POST["maxnt"]?? "100"; if ($maxnt<1) $maxnt = 1;
    $pfft   = $_POST["pfft"]??""; if ($maxnt<=1) $pfft = "";
    $libz   = $_POST["libz"]??"";
    $gmx5   = $_POST["gmx5"]??"";
    //$ogmx5 = $_POST["ogmx5"]??"";
    $intr   = $_POST["intr"]??"";
    $ttyc   = $_POST["ttyc"]??"";
    $maxsol = $_POST["maxsol"]?? "100";
    $maxdiis= $_POST["maxdiis"]?? "100";
    $save   = $_POST["save"]??"";

    //if (empty($pfft) && empty($libz) && empty($gmx5) && empty($intr) && empty($ttyc)){

    if (empty($save)){
        $maxnt = "";
        if (empty(shell_exec("cat ".$configfile." | grep _LOCALPARALLEL_"))){
            $maxnt = 1;
        } else if (!empty(shell_exec("cat ".$configfile." | grep MAX_THREADS"))){
            $sline = explode(" ", shell_exec("cat ".$configfile." | grep MAX_THREADS | tr '\n' ' ' | tr '\r' ' '"));
            $n_sline = count($sline);
            for ($i=0; $i<$n_sline; $i++){
                if (strcasecmp($sline[$i], "#define")==0) if ($i+1<$n_sline&&strcasecmp($sline[$i+1], "MAX_THREADS")==0) if ($i+2<$n_sline){
                    $maxnt = $sline[$i+2]; $i += 2;
                }
            }
        }; if (empty($maxnt)) $maxnt = 100;
        $pfft = empty(shell_exec("cat ".$configfile." | grep _LOCALPARALLEL_FFTW_"))? "" : "on";
        $libz = empty(shell_exec("cat ".$configfile." | grep _LIBZ_"))? "" : "on";
        $gmx5 = empty(shell_exec("cat ".$configfile." | grep _GROMACS_"))? "" : "on";
        $intr = empty(shell_exec("cat ".$configfile." | grep _INTERACTIVE_"))? "" : "on";
        $ttyc = empty(shell_exec("cat ".$configfile." | grep _TTYPROMPTCOLOR_"))? "" : "on";
        $maxsol = ""; if (!empty(shell_exec("cat ".$configfile." | grep MAX_SOL"))){
            $sline = explode(" ", shell_exec("cat ".$configfile." | grep MAX_SOL | tr '\n' ' ' | tr '\r' ' '"));
            $n_sline = count($sline);
            for ($i=0; $i<$n_sline; $i++){
                if (strcasecmp($sline[$i], "#define")==0) if ($i+1<$n_sline&&strcasecmp($sline[$i+1], "MAX_SOL")==0) if ($i+2<$n_sline){
                    $maxsol = $sline[$i+2]; $i += 2;
                }
            }
        }; if (empty($maxsol)) $maxsol = 100;
        /*$maxdiis = ""; if (!empty(shell_exec("cat ".$configfile." | grep MAX_DIIS"))){
            $sline = explode(" ", shell_exec("cat ".$configfile." | grep MAX_DIIS | tr '\n' ' ' | tr '\r' ' '"));
            $n_sline = count($sline);
            for ($i=0; $i<$n_sline; $i++){
                if (strcasecmp($sline[$i], "#define")==0) if ($i+1<$n_sline&&strcasecmp($sline[$i+1], "MAX_DIIS")==0) if ($i+2<$n_sline){
                    $maxdiis = $sline[$i+2]; $i += 2;
                }
            }
        }; if (empty($maxdiis)) $maxdiis = 100;*/
    } else {
        $config_text = "";
        if ($maxnt>1){
            $config_text .= '#define _LOCALPARALLEL_'."\n";
            $config_text .= '#define _LOCALPARALLEL_PTHREAD_'."\n";
            $config_text .= '#define MAX_THREADS '.$maxnt."\n";
            if ($pfft=="on"){
                $config_text .= '#define _LOCALPARALLEL_FFTW_'."\n";
            }
        }
        if ($libz=="on") $config_text .= '#define _LIBZ_'."\n";
        if ($gmx5=="on"){
            $gromacs_version=(int)shell_exec("bash src/rismhi3d/check-gromacs-version.sh");
            $config_text .= '#define _GROMACS_'."\n";
            if (!empty($gromacs_version) && $gromacs_version!=0){
                $config_text .= '#define _GROMACS'.$gromacs_version.'_'."\n";
            }
        }
        if ($intr=="on") $config_text .= '#define _INTERACTIVE_'."\n";
        if (!empty($ttyc)) $config_text .= '#define _TTYPROMPTCOLOR_'."\n";
        if (!empty($maxsol)) $config_text .= '#define MAX_SOL '.$maxsol."\n".'#define MAX_CMD_PARAMS '.$maxsol."\n";
        //if (!empty($maxdiis)) $config_text .= '#define MAX_DIIS '.$maxdiis."\n";
        //echo ('<pre>'.$config_text.'</pre>');
        $fp = fopen($configfile, 'w'); fwrite($fp, $config_text); fclose($fp);
    }

    //echo ('<pre> gmx folder: ['.$ogmx5.'] </pre>');

    echo ('<style>th, td { border-bottom: 1px solid #AAAAAA; }</style>'."\n");

    echo ('<div><form action="/config.php" method="post">'."\n");
    echo ('<center><h4>Configurations</h4>'."\n");
    echo ('<table width=600 CELLSPACING=0;><tr>'."\n");
        echo ('<th>option</th><th>note</th>'."\n");
    echo ('</tr><tr>'."\n");
        echo ('  <td>Maximum <input type="text" name="maxnt" '.(empty($maxnt)?"":'value='.$maxnt).' style="width:50"/> threads </td>'."\n");
        echo ('  <td width=66%><small> Multithread support. 1 for a serial (single thread) version, and other number for the maximum number of threads allowed in rismhi3d. </small></td>'."\n");
    echo ('</tr><tr>'."\n");
        echo ('  <td><input type="checkbox" name="pfft" '.($pfft=="on"?"checked":"").'> <font color=#FF8080>Multithread FFTW </font></input></td>'."\n");
        echo ('  <td><small> Enable only if you want to use multithread FFTW. Not recommended as multithread FFTW does little improvement of efficiency. </small></td>'."\n");
    echo ('</tr><tr>'."\n");
        echo ('  <td><input type="checkbox" name="libz" '.($libz=="on"?"checked":"").'> ZLib support</input></td>'."\n");
        echo ('  <td><small> Enable only if you want to compress the binary outputs with ZLib (i.e. TS4S files). Disable this if you don\'t have ZLib on your computer. </small></td>'."\n");
    echo ('</tr><tr>'."\n");
        $gmxldlib = getenv('GMXLDLIB'); $gromacs_version=(int)shell_exec("bash src/rismhi3d/check-gromacs-version.sh"); if (!empty($gmxldlib)) $gmxroot = realpath($gmxldlib."/..");
        echo ('  <td><input type="checkbox" name="gmx5" '.($gmx5=="on"?"checked":"").' > <font color=#FF8080>XTC support</font> </input><br>'."\n");
        if (!empty($gromacs_version)&&$gromacs_version!=0) echo ("<small>   with GROMACS ".$gromacs_version."</small>");
        echo ('  <input type="text" name="lgmx5" placeholder="GROMACS not detected" '.(!empty($gmxroot)? 'value="'.$gmxroot.'"' : "").' style="width:90%" readonly/><br>');
        echo ('</td>'."\n");
        echo ('  <td><small>  Enable this option only if you want to treat XTC trajectories. Please source GMXRC before running CWBSol server (refer to <a href="/help.php#install_gromacs">here</a> for more information). <br> <b>GROMACS 4</b> recommended. Other GROMACS versions like <b>5</b>, <b>2016</b>, <b>2018</b>, and <b>2020</b> can also be used but need to have static libraries (i.e. enable option <b>-DBUILD_SHARED_LIBS=off</b> when installing GROMACS).
 For <b>GROMACS 2020</b>, please also copy all folders in $INSTALL/src/include/gromacs to CMAKE_INSTALL_PREFIX/include/gromacs.</small></td>'."\n");
    echo ('</tr><tr>'."\n");
        echo ('  <td><input type="checkbox" name="intr" '.($intr=="on"?"checked":"").'> <font color=#80AAFF>Allow interactive mode</font> </input></td>'."\n");
        echo ('  <td><small> Enable only if you want to interact with rismhi3d in terminal when running rismhi3d <b>in a terminal</b>. Useless in GUI. </small></td>'."\n");
    echo ('</tr><tr>'."\n");
        echo ('  <td><input type="checkbox" name="ttyc" '.($ttyc=="on"?"checked":"").'> <font color=#80AAFF>Colorful error/warning</font> </input></td>'."\n");
        echo ('  <td><small> Enable only if you want to see colored warning/error message when running rismhi3d in <b>in a terminal</b>. Useless in GUI. </small></td>'."\n");
    echo ('</tr><tr>'."\n");
        echo ('  <td> MAX_SOL <input type="text" name="maxsol" '.(empty($maxsol)?"":'value='.$maxsol).' style="width:100"/></td>'."\n");
        echo ('  <td><small> Maximum number of solvent sites. This number will slightly affect the memory usage of rismhi3d, and never try to use a huge number (e.g. 2 million) in case of stack overflow. </small></td>'."\n");
    //echo ('</tr><tr>'."\n");
    //    echo ('  <td> MAX_DIIS <input type="text" name="maxdiis" '.(empty($maxdiis)?"":'value='.$maxdiis).' style="width:100"/></td>'."\n");
    //    echo ('  <td><small> Maximum number of historical frames of DIIS. </small></td>'."\n");
    if (!empty($accessroot)){
        echo ('</tr><tr>'."\n");
            //echo ('  <td> Accessible root: <font color="#0000CC">'.($accessroot==getcwd()?'$PWD' : $accessroot==getenv('HOME')?'$HOME' : $accessroot).'</font></td>'."\n");
            echo ('  <td> Accessible root: <font color="#0000CC">'.($accessroot==getcwd()?'CWBSol_root' : ($accessroot==getenv('HOME')?'$HOME' : $accessroot)).'</font></td>'."\n");
            echo ('  <td><small> The root folder that CWBSol can see. CWBSol will not scan folders outside the accessible root.<br> By default, the $Home folder will be used as the accessible root. Change $accessroot in header.php if you want another accessible root, e.g. <font color=blue>$accessroot="/";</font> to see all files on your computer. <br>Please refer to <a href="/help.php#about_security" style="color:red">security concerns</a> for details. </small></td>'."\n");
    }
    echo ('</tr><tr>'."\n");

    echo ('</tr></table>'."\n");
    echo ('<br>'."\n");
    echo ('<input type="checkbox" name="save" checked hidden/></input>'."\n");
    echo ('<center><input type="submit" height=100 value="save configurations"></center>'."\n");

    echo ('</form></div>'."\n");

    echo ('<center>'."\n");
    echo ('  <a href="'.$phpurl.'"><input type="submit" name="stop" value="load saved settings" /></a>'."\n");
    echo ('  <a href="/install.php"><input type="submit" name="stop" value="continue to install" /></a>'."\n");
    echo ('</center><br>'."\n");

    echo ('</div>'."\n".'</div>'."\n");

    include ("page_footer.php");

?>
