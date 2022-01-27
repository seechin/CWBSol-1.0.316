<?php
    $title = "Clear Water Bay Solvation";
    $configfile = "src/rismhi3d/code-rismhi3d/header.h";

    include ("ietsettings.php");

    include ("header.php");
    include ("page_head.php");
    echo ('<style> p { text-indent: 1em; } </style>'."\n");
    include ("page_header_element.php");

    $ver_rismhi3d = ""; if (!empty($rismhi3d)) $ver_rismhi3d = shell_exec($rismhi3d);
    $ver_ts4sdump = ""; if (!empty($ts4sdump)) $ver_ts4sdump = shell_exec($ts4sdump);
    $ver_gmxtop2solute = ""; if (!empty($gmxtop2solute)) $ver_gmxtop2solute = shell_exec($gmxtop2solute);
    $ver_gensolvent = ""; if (!empty($gensolvent)) $ver_gensolvent = shell_exec($gensolvent);

    echo ('<div class="container" style="margin:5 auto;">'."\n");

    echo ('<center>'."\n");


    $xtc_support = file_exists($configfile)? (empty(shell_exec("cat ".$configfile." | grep _GROMACS_"))? "" : "on") : "on";

    echo ('<div class="container" style="width:90%;min-width:400;text-align:left;padding:3;">'."\n");

    echo ('<br><center><div class="container" style="background-image:url(\'images/HKUST-CWB.jpg\');background-position: center center;"><h3>CWBSol: the <a href="http://www.ust.hk">Clear Water Bay</a> Solvation Package</h3></div></center>'."\n");

    echo ('<h4>&#9678 What is CWBSol?</h4>
  <p>CWBSol is a graphic interface of <a href="/iet.php">rismhi3d</a>. <a href="/iet.php">rismhi3d</a> does solvation calculations based on the Integration Equation Theory of Liquid (IET), which consists of the <a style="color:blue" href="http://doi.org/10.1021/cr5000283">3D Reference Interaction Site Model</a> (3DRISM) and <a style="color:blue" href="http://doi.org/10.1063/1.4928051">Hydrophobicity induced density Inhomogeneity theory</a> (HI) in the current implementation. CWBSol can produce site-based (i.e. atom-based) thermodynamical distributions of simple liquids around a given conformation of solute molecule; and HI can calculate molecule-based liquid density depletion of polar solvents or density segregation of solvent mixtures at solid-liquid interfaces.</p>
'."\n");

echo ('<h4>&#9678 A tutorial</h4>
<p><small>(Please click the bold text below to follow the steps)</small>
<p>1. Prepare solute file. If you want to use <a href="/viewfile.php?u='.getcwd().'/solute/tutorial/methane.prmtop"><b>methane.prmtop</b></a> then you don\'t need to do anything here; or if you want to use <a href="/viewfile.php?u='.getcwd().'/solute/tutorial/methane.solute"><b>methane.solute</b></a>, you need to use <a href="/gmxtop2solute.php?top='.getcwd().'/solute/tutorial/methane.top&sname=tutorial/methane"><b>gmxtop2solute</b></a> to generate it from <a href="/viewfile.php?u='.getcwd().'/solute/tutorial/methane.top"><b>methane.top</b></a> (press the <input type="submit" value="generate"/> button there please. GROMACS library not required for this tutorial).
<p>2. Run the IET calculations. You can either <a href="/iet.php?workspace=default&solute='.getcwd().'/solute/tutorial/methane.prmtop&solvent='.getcwd().'/solvent/tip3p-amber14.gaff&traj='.getcwd().'/solute/tutorial/methane.pdb&rc=1.2&nr=60x60x60&log=tutorial_methane&out=tutorial_methane&verbo=2&debug=0&debugxc=16&closure=KH&steprism=500&stephi=0&temperature=298&save=3587&report=111&display=table&rdfbins=60&rdfgrps=1-1,1-2&fmt=14.7g&lsa=0.3&xvvextend=0&ndiis=5&delvv=1&errtol=0.0000001&sd=5&enlv=1&coulomb=Coulomb&ignoreram=no"><b>run with methane.prmtop</b></a> or <a href="/iet.php?workspace=default&solute='.getcwd().'/solute/tutorial/methane.solute&solvent='.getcwd().'/solvent/tip3p-amber14.gaff&traj='.getcwd().'/solute/tutorial/methane.gro&rc=1.2&nr=60x60x60&log=tutorial_methane&out=tutorial_methane&verbo=2&debug=0&debugxc=16&closure=KH&steprism=500&stephi=0&temperature=298&save=3587&report=111&display=table&rdfbins=60&rdfgrps=1-1,1-2&fmt=14.7g&lsa=0.3&xvvextend=0&ndiis=5&delvv=1&errtol=0.0000001&sd=5&enlv=1&coulomb=Coulomb&ignoreram=no"><b>run with methane.solute</b></a>. Press the <input type="submit" value="perform calculation" /> button there to start calculations. After starting IET, please press the <input type="submit" value="view screen output" /> button to check <a href="/viewfile.php?scroll=bottom&u='.getcwd().'/run/default/tutorial_methane"><b>the screen output (or log)</b></a>.
<p>3. Analyse the outputs.
(a) From <a href="/analysis.php?filename='.getcwd().'/run/default/tutorial_methane"><b>the screen out put</b></a>, the hydration free energy can be calculated with the excessive chemical potential (columne “excess”) and partial molar volume (columne “volume”) following the Universal Correction scheme.
(b) Click <a href="/analysis.php?filename='.getcwd().'/run/default/tutorial_methane.rdf"><b>tutorial_methane.rdf</b></a> and you can see the raw data of RDF. Then press the <a href="/analysis.php?filename='.getcwd().'/run/default/tutorial_methane.rdf&rebuild=on"><span style="padding:2;background-color:#EEEEEE"><small>view</small></span></a> button to view image of this RDF file.
(c) Click <a href="/analysis.php?filename='.getcwd().'/run/default/tutorial_methane.ts4s"><b>tutorial_methane.ts4s</b></a> to view the TS4S file. This TS4S file should have two frames, where the second frame contain the spatial distribution of density. Choose the second frame <span style="background-color:#EEEEEE"><small><input type="radio" value="2" checked> Frame 2  guv@1, real8:60x60x60x2</input></small></span> and click the <input type="submit" value="render image" /> to generate cutviews in <a href="/analysis.php?filename='.getcwd().'/run/default/tutorial_methane.ts4s.frame_2"><b>methane.ts4s.frame_2</b></a> folder.
</p>
'."\n");

    echo ('<h4>&#9678 Licence</h4>
  <p>3DRISM-HI is free software for non-commercial use. You can use, modify or redistribute for non-commercial purposes under the terms of the <a href="GPL3LICENCE.txt">GNU General Public License version 3</a>.</p>
'."\n");

    echo ('</div>'."\n");

    echo ('<div class="container">'."\n");

    echo ('<table style="width:95%;min-width:480;max-width:750;table-layout:fixed">'."\n");
        echo ('<tr>'."\n");
        echo ('<td width=25% height=100><center><a href="/config.php"><img src="images/Install.png" width=100 height=80 /></a></center></td>'."\n");
        echo ('<td width=25% height=100><center>'.(empty($iet_bin)?"":'<a href="/iet.php">').'<img src="images/Solvate.png" width=100 height=80 />'.(empty($iet_bin)?"":'</a>').'</center></td>'."\n");
        echo ('<td width=25% height=100><center><a href="/analysis.php"><img src="images/Analysis.png" width=100 height=80 />'.(empty($iet_bin)?"":'</a>').'</center></td>'."\n");
        echo ('<td width=25% height=100><center>'.(empty($iet_bin)?"":'<a href="/help.php">').'<img src="images/TT.png" width=100 height=80 />'.(empty($iet_bin)?"":'</a>').'</center></td>'."\n");
        echo ('</tr>'."\n");
        echo ('<tr>'."\n");
        echo ('<td><center><a href="/config.php">'.(empty($iet_bin)?'Configurations and installation':'Reinstallation').'</a></center></td>'."\n");
        echo ('<td><center>'.(empty($iet_bin)?"":'<a href="/iet.php">').'IET calculations'.(empty($iet_bin)?"":'</a>').'</center></td>'."\n");
        echo ('<td><center><a href="/analyse.php">Data analysis</center></a></td>'."\n");
        echo ('<td><center><a href="/help.php">Help</a></center></td>'."\n");
        echo ('</tr>'."\n");
    echo ("</table>\n");

    echo ('</div>'."\n");

    echo ('<br>'."\n");

    echo ('</center>'."\n");

    echo ('</div>'."\n");

    include ("page_footer.php");

?>
