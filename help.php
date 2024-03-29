<?php
    $title = "help";
    include ("header.php");
    include ("page_head.php");
    include ("page_header_element.php");
?>

<div class="container" style="width:95%;min-width:400;text-align:left;padding:3;">

<br><center><div class="container" style="background-image:url('images/HKUST-CWB.jpg');background-position: center center;"><h3>CWBSol: the <a href="http://www.ust.hk">Clear Water Bay</a> Solvation Package</h3></div></center><br>


<center><div class="container" style="background-color:#EEEEEE"><h4> IET options </h4></div></center>

<p><div id="iet_solvent"><b>Solvent file</b></div> (-p, -solvent): a text file containing the properties of solvent and major setups for for IET.

<p><div id="iet_solute"><b>Solute file</b></div> (-s, -solute): a text file containing solute atoms and force field parameters. Two formats are supported: solute format and prmtop format. Solute format can be translated from GROMACS top files with <a href="/gmxtop2solute.php">gmxtop2solute</a>, and prmtop can be generated by AMBER.

<p><div id="iet_traj"><b>Trajectory file</b></div> (-f, -traj): a PDB, GRO or XTC file of conformations (or trajectory) to be calculated. PDB and GRO formats are always supported, and XTC format will be supported if the XTC support is enabled during installation. Both PDB/GRO/XTC can contain multiple frames. With <b>-b</b>, <b>-e</b> and <b>-dt</b>, selected frames will be used to perform IET calculations.Note: -b -e -dt are followed by frame for PDB/GRO, and picosecond for XTC (For more details of -b, -e and -dt, see GROMACS).

<p><div id="iet_threads"><b>Threads</b></div> (-nt, -ntb): rismhi3d can use multiple cores to accelerate IET calculations. Enable multithread support in configurations to allow this feature. For most non-FFT calculations (e.g. grid-based potential generation, data collection closure calculation, etc), rismhi3d can make full use of all given cores; while the the Fast Fourier Transformation (FFT) is not fully parallelised. Each FFT is performed on a single thread, so solvent with N sites (e.g. N=2 for water) could only achieve N*N parallelisation in FFT.

<p>By default (or when -nt is missing), parallel version of rismhi3d will use all physical cores to do IET. Significant RAM will be used for highly parallelisation. Therefore, if you are running rismhi3d on a high performance computer cluster (which has many CPU cores) or you are calculating for a huge system, please manually set -nt to a small number.

<p>In <a href="#iet_batch">batch mode</a>, another option <b>-ntb</b> gives the number of processes to run for different solutes. E.g., -nt 4 -ntb 40 will perform up to 40 rismhi3d at the same time, each for a solute molecule and with 4 threads (this is only available in batch mode).

<p><div id="iet_grids"><b>Grid number</b></div> (-nr): can be 100x120x130 to specify a x=100, y=120 and z=130 grid, or a single number 200 go specify a cubic box of 200x200x200

<p><div id="iet_box"><b>Default box vector</b></div> (-box): format is similar to -nr. The default box vector is used when the current frame of PDB/GRO/XTC does not contain box information.

<p><div id="iet_rc"><b>Cutoff Distant</b></div> (-rc): rismhi3d adopts a cutoff scheme to compute Lenard-Jones potentials and a Particle-Ewald-Meshing method to compute Coulomb potentials. In LJ potential calculation, -rc is adopted as the cutoff distance. In PME calculation, -rc serves as a switching between short and long range Coulomb potentials, where the decay rate of the error function in PME is 2/rc.

<p><div id="iet_batch"><b>Batch mode</b></div>When both solute (-s) and trajectory (-f) are directories, a master rismhi3d will scan the -s directory for solute files (.solute and .prmtop) and -f folder for trajectories (either .pdb, .gro or .xtc), and then performed subsidiary IET calculations for each of solutes. For example, your -s directory has: acetone.solute, benzene.prmtop, chloroform.solute, ethanol_AC.prmtop; and your -f directory has: acetone.pdb, benzene.gro, dmso.pdb, ethanol.pdb; then rismhi3d will perform IET for acetone and benzene.

<p>If you are running rismhi3d for a lot of molecules in batch mode, please set -nt to 1 and -ntb as large as possible.

<p><div id="iet_output"><b>Output file</b></div> (-o, -ov, -o2, -ov2): Name of output files (extensions added later). When you choose to save RDF and when the <a href="#iet_rdf_grps">pairs of RDFs</a> are given, a text file with extension “rdf” will be generated. And for all other items you selected for “save”, three or four dimensional tensors would be saved in the TS4S format. A TS4S file can contain a lot of frames, and can be viewed and extracted with ts4sdump (which has been integrated to <a href="/analysis.php">the analysis page</a>). Compressions with ZLib can be performed if the ZLib support is enabled in configurations, where -o0, -o1 and -o2 stands for no compression, level 1 compression and level 2 compression respectively. By default, rismhi3d will choose a different name if the output file already exist (see screen output or log file of rismhi3d for the name of file), but you can force overwriting with -ov, -ov0, -ov1 and -ov2.

<p><div id="iet_debug_level"><b>Debug level</b></div> (-debug): level of showing debug information. Level 0 will not show any debug information, level 1 will only report limited debug information, while level 2 will print a list of all major function calls. Level 3 will print even more infromation, tracing memory allocations (-debug-crc), forcefield calculation, and data correctness under multithread. Thus debug level 3 requires additional computing time. CRC debug (-debug-crc) will display CRC32 checksums for major memory blocks. XVV debug (-debug-xvv) will display the leading terms of solvent-solvent correlations (i.e. wvv(k) and hvv(k) at k=0, dk, 2dk, 3dk and 4dk).

<p><div id="iet_rdf_grps"><b>Pairs of RDFs</b></div> (-rdf-pairs): define the pairs bins to compute RDF. Format pair1,pair2,..., and each_pair=solute_atom_index-solvent_site_index format (both begin with 1). For example: 58441-2,66276-1,74111-2,81946-1 will generate five column RDF data: the first column is the radius (in nm), and the following four columns are the RDF of solvent site 2 around solute atom 58441, site 1 around 66276, site 2 around 74111 and site 1 around 81946 respectively. For multiframe trajectories, RDF will be averaged over all frames.

<p><div id="iet_rdf_bins"><b>Number of bins for RDFs</b></div> (-rdf-bins): define the number of bins to compute RDF. RDF is calculated up to -rc, so -rc 1.2 and -rdf-bins 60 will generate RDFs with bin width of 0.02nm.

<p><div id="iet_lsa"><b>A of LES</b></div> (-lsa): A constant in the liquid equation of state that used in the HI equation. This is the constant C in <a style="color:blue" href="https://doi.org/10.1007/BF01133262">Tait's equation</a>. At room temperature, the A (or C) value of pure water is aroud 0.3, and for most organic solvents A (or C) is around 0.1 to 0.3.

<p><div id="iet_Coulomb"><b>Electric interaction</b></div> (-Coulomb): three electric interactions are provided, including the Coulomb potential, the Coulomb potential with dielectric constant (calculated with a serial mixing rule of dielectric contants of all solvent components), and a Yukawa potential with a constant decay factor (which is 1/param).

<p><div id="iet_data_format"><b>Data format</b></div> (-%...): the format of data that shown in reports (seen in screen output or log file). See <a href="https://en.wikipedia.org/wiki/Printf_format_string#Type_field" style="color:blue">printf format strings</a> for details

<p><div id="iet_extend_xvv"><b>Extension of solvent-solvent correlations</b></div> (-xvv-extend): extend the solvent-solvent RDFs by filling 1 to the extended distances. E.g. -xvv-extend 2.5 will extend the radius of original solvent-solvent RDF (saying, 3 nm) to 3.5 times (i.e., 10.5 nm), and extended memory will be filled with 1. This option can reduce the data error of the input RDFs.

<p><div id="iet_ndiis"><b>DIIS depth</b></div> (-ndiis): the maximum copies of history to perform DIIS. -ndiis 1 will disable DIIS.

<p><div id="iet_delvv"><b>SCF step scaling factor</b></div> (-delvv): A scaling factor for SCF stepin: F(n+1) = F(n) + factor*(F_calc(n+1) - F(n)).

<p><div id="iet_dynamic_delvv"><b>Dynamic SCF stepin scaling</b></div> (-dynamic-delvv): A parameter to scale down the change of SCF by |1+h*h|^(level/2). -dynamic-delvv 0 will turn off this feature. A number between 0 and 1 is recommended.

<p><div id="iet_errtol"><b>Error tolerance</b></div> (-errtolrism): A threshould, at which RISM would be stopped.

<p><div id="iet_sigdig"><b>Significant digits</b></div> (-sd): number of digits to be kept when compressing TS4S data. -sd 7 or -sd float to choose float precision, and -sd 15 or -sd double to choose double precision. Higher precision will require larger disk space.

<p><div id="iet_igram"><b>Ignore physical RAM size</b></div> (-ignore-ram): enable this then rismhi3d will not check physical memory when applying for memory. This option is to prevent you exhausting the physical RAM of your computer, and rismhi3d will quite immediately when this options is off and the required memory exceeds the total size of physical memory. Highly suggest not to enble this option.

</p>
<br><br>

<center><div class="container" style="background-color:#EEEEEE"><h4>Trouble Shooting</h4></div></center>

<p><div id="about_security"><b>Security Concerns</b></div> It is highly suggested to start the CWBSol server on a local address (e.g. localhost, 127.0.0.1, 192.168.*.*, 172.16.*.*, 10.*.*.*) or a secret port. If you start your server on a public computer, then all your files on your computer may be seen by external computers. There is no user/password control in CWBSol.

<p>CWBSol will block all access to folders outside the accessible root, and it is highly suggested to use the CWBSol root as the accessible root. You can change this, but it's  on your own risk.

<p><div id="install_gromacs"><b>Installation: CWBSol with XTC support</b></div>
CWBSol can use the XTC libaries of GROMACS to read XTC trajectories. In order to do this, please setup the GROMACS environments (e.g. source GMXRC files) in the terminal before you start the CWBSol server. For example, suppose you have a GROMACS 2018 installed in /opt/gromacs-2018.1:
<br><font color=blue>source /opt/gromacs-2018.1/bin/GMXRC</font>
<br><font color=blue>php -S localhost:8080 -t .</font>
<br>Then you'll see the path of GROMACS in <a href="/config.php">configurations</a> and <a href="/gmxtop2solute.php">gmxtop2solute</a>.

<p><div id="install_gromacs_dlyb"><b>Installation: Shared librariy issue of GROMACS</b></div>
GROMACS has switched to shared libaries since GROMACS 5. Therefore, any software compiled with GROMACS need to have the dynamic libraries of GROMACS in /usr/lib or $LD_LIBRARY_PATH. However, this is not possible in a PHP based software. Maybe CWBSol can be correctly installed with GROMACS support, but it will not correctly run. In order to solve his issue, please reinstall gromacs with -DBUILD_SHARED_LIBS=off in cmake.
<br>GROMACS 2020 users also need to copy (or link) all folders under src/include/gromacs of the make directory to CMAKE_INSTALL_PREFIX/include/gromacs (e.g. you are doing cmake in $HOME/install/gromacs-2020.1/build, and you have enabled -DCMAKE_INSTALL_PREFIX=$HOME/gromacs-2020.1 in cmake, then you need to do <font color=blue>cp -r $HOME/install/gromacs-2020.1/src/gromacs/* $HOME/gromacs-2020.1/include/gromacs</font>)
<br>It's highly recommended to use GROMACS 4, the last version that only uses static libraries. As the XTC format is compatible across all version of GROMACS, CWBSol compiled with GROMACS 4 can also read the XTC the generated with GROMACS 5, 2016 or 2018.
<br>It's totally fine to disable the XTC support, as multiframe PDB/GRO trajectories can be correctly handled with CWBSol.

</p>
<br><br>

<center><div class="container" style="background-color:#EEEEEE"><h4>Most Frequently Occuring Error Messages</h4></div></center>

<p><div id="run_solvent_undefined"><b>Error: forcefield undefined</b></div>
Error message: <font color=magenta>rismhi3d : error : -ff : force field type not defined</font>
<br>The solvent file (-p) is incorrect. Please check you solvent file, make sure it's a text file but not a directory. Press the <input type="submit" value="view"/> button beside the <b>Solvent</b> label in <a href="/iet.php">IET</a>, and check whether the solvent file contains [solvent], [atom] and [bond] sections.

<p><div id="run_box_missing"><b>Error: Box information missing</b></div>
Error message: <font color=magenta>rismhi3d : error : unable to init box. Please check your trajectory... </font>
<br>The given frame of trajectory has no box informtaion. This often happens for a PDB file. Please set the default box vector which will be used when -f file doesn't contain box information.

<p><div id="run_solute_traj_mismatch"><b>Error: Solute and trajectory mismatch</b></div>
Error message: <font color=magenta>rismhi3d : error : numbers of atoms mismatch: ... </font>
<br>The solute file and the trajectory file are two different molecules, so they have different number of atoms.

<p><div id="run_non_np_ver"><b>Error: Not a multi-thread version</b></div>
Error message: <font color=magenta>rismhi3d : args[9] : unknown option "-nt" ... </font>
<br>CWBSol is compiled with single thread, while you try to run it with multiple thread. To solve this, either reinstall with multithread support enabled (i.e. Maximum >1 threads), or leave both the two text boxes of “threads” empty.

<p><div id="analysis_convert_eps"><b>Error: cannot convert RDF or EPS to PNG</b></div>
In <a href="/analysis.php">the analysis page</a>, if you selected a RDF or EPS file and press <div class="navbc" style="background-color:#EEEEEE;float:none"><small>view</small></div> button, a PNG file with the same filename should be generated. But this may not happen on some machines. If you already have ImageMagick installed, you may still have difficulty to do this due to the default policy settings of ImageMagick. On a Linux server, you can check the ImageMagick policy file, e.g. <font color=blue>/etc/ImageMagick*/policy.xml</font>, where you may see:
<br><font color=blue>&lt;policy domain="coder" rights="none" pattern="PS" /&gt;</font>
<br><font color=blue>&lt;policy domain="coder" rights="none" pattern="EPS" /&gt;</font>
<br><font color=blue>&lt;policy domain="coder" rights="none" pattern="PDF" /&gt;</font>
<br>Please change them to:
<br><font color=green>&lt;policy domain="coder" rights="read|write" pattern="PS" /&gt;</font>
<br><font color=green>&lt;policy domain="coder" rights="read|write" pattern="EPS" /&gt;</font>
<br><font color=green>&lt;policy domain="coder" rights="read|write" pattern="PDF" /&gt;</font>

</div></div>
<br>

<?php
    echo ('<center><div class="container">'."\n");
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
    echo ('</div></center><br>'."\n");

    include ("page_footer.php");
?>
