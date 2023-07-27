<?php 
    ob_start();
	require($_SERVER["DOCUMENT_ROOT"] . "/connect.php");
?>
<html>

<head>
    <title>Student Report</title>
    <link rel="stylesheet" type="text/css" href="../css/print.css">
</head>

<body>
    <?php
        require($_SERVER["DOCUMENT_ROOT"] . "/functions.php");
        if(!isSet($_SESSION['type'])){
        header('Location: /admin/unauthorized.php');
    }
        if($_SESSION['admin']){
            $curYear = date('Y');
            $curMonth = date('n');
            $curSem = monthToSemesterID($curMonth);
            //require($_SERVER["DOCUMENT_ROOT"] . "/adminheader.php");
            //echo "<br>";
        ?>
        <div class="print_sr">
            <?php
            //FIXME: Format printable report
            //make print button show the printable text on the same page (don't change user's location)
            $students = $_SESSION['class_array'];
    foreach($students as $student){
        echo '<table class="marks" border="1">';
        echo '<tr><td><strong>Somo</strong></td><td><strong>MAZOEZI</strong></td><td><strong>MITIHANI</strong></td><td><strong>JUMLA</strong></td><td><strong>WASTANI</strong></td><td><strong>DARAJA</strong></td><td><strong>Saini ya mwalimu ya somo</td></tr>';
        $array_position = $student["rank"] - 1;
        //echo $array_position;
        $students =  $_SESSION['class_array'];
        $subjectnames = $_SESSION['subjectnames'];
        $subjects = $_SESSION['subjects'];
        $subject_count = $_SESSION['subject_count'];
        $decimal = 1;

        $mz_total = 0;
        $majar_total = 0;
        $jumla_total = 0;
        $wastani_total = NULL;
		
        $student_id = $students[$array_position]['user_id'];?>			
                <h1 class="sr_nameheader" align="center"> <img src="s.jpg" align="left"> WIZARA YA ULINZI NA JESHI LA KUJENGA TAIFA</h1>
                <h1 align="center">SHULE YA SEKONDARI RUHUWIKO</h1>
                <h1 align="center">S.L.P 448,SONGEA</h1>
				<h1 align="center">SIMU 0755371937/0762818180/0754636049</h1>
                <br>
                <hr height="20">
                <?php
		echo'<br><br>';		
        echo '<h2 align="center"><b>TAARIFA YA MAENDELEO YA MWANAFUNZI</b></h2>';
		echo'<br><br>';
		echo '<h2>Jina la mwanafunzi: <strong><u>' . $students[$array_position]['fname'] . ' ' . $students[$array_position]['lname'] . '</u></strong></h2>';
        echo '<h2>Kidato:_________ Mhula:___________Mwaka: _________</h2>';
        echo '<strong><h2>A. Masomo:</h2></strong>';
    for($i=0;$i<$subject_count;$i++){
        $sid = $subjects[$i];
        echo '<tr>';
        //TODO: add subject name link if student is registered for class of that subject in this semester/year and for their cohort
        $classID = classStudent_getClassID($student_id, $sid, $curYear, $curSem, $pdo);
        if ($classID !== NULL){
            echo '<td>' . $subjectnames[$i] . '</td>';
        }
        else{
            echo '<td>' . $subjectnames[$i] . '</td>';
        }
        if ($students[$array_position][$sid]['mz_avg'] !== NULL){
            echo '<td>' . number_format($students[$array_position][$sid]['mz_avg'],$decimal) . '</td>';
        }
        else{
            echo '<td></td>';
        }
        if ($students[$array_position][$sid][4] !== NULL){
            echo '<td>' . number_format($students[$array_position][$sid][4],$decimal) . '</td>';
        }
        else{
            echo '<td></td>';
        }
        if ($students[$array_position][$sid]['total'] !== NULL){
            echo '<td>' . number_format($students[$array_position][$sid]['total'],$decimal) . '</td>';
        }
        else{
            echo '<td></td>';
        }
        if ($students[$array_position][$sid]['percentage_grade'] !== NULL){
            echo '<td>' . number_format($students[$array_position][$sid]['percentage_grade'],$decimal) . '</td>';
            $wastani_total = $wastani_total + $students[$array_position][$sid]['percentage_grade'];
        }
        else{
            echo '<td></td>';
        }

        echo '<td>' . $students[$array_position][$sid]['letter_grade'] . '</td>';
        echo '<td></td></tr>';
        //echo $subjects[$i];
        //echo $subjectnames[$i];
    }
        if ($wastani_total !== NULL){
            echo '<tr><td><strong>TOTAL</strong></td><td></td><td></td><td></td><td><strong>' . number_format($wastani_total, $decimal) . '</strong></td><td></td><td></td></tr></table>';
        }
        else{
            echo '<tr><td><strong>TOTAL</strong></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>';
        }
        echo '<table><tr>';
        if ($students[$array_position]['subject_avg'] !== NULL){
            echo '<td><strong>Wastani:</strong> ' . number_format($students[$array_position]['subject_avg'],$decimal) . ' ..... </td>';
            echo '<td><strong>Amekuwa wa:</strong> ' . ($array_position + 1) . ' ..... </td>';
        }
        else{
            echo '<td><strong>Wastani:</strong> ' . $students[$array_position]['subject_avg'] . '..... </td>';
            echo '<td><strong>Amekuwa wa:</strong>  ..... </td>';
        }
        echo '<td><strong>Kati ya wanafunzi:</strong> ' . $_SESSION['num_students'] . '</td></tr></table>';
        ?>
                    <strong><h3>B. Tabia/Nidhamu:</h3></strong>
                    <h3>901: Kufanyakazi kwa bidii, juhudi na maarifa ____________</h3>
                    <h3>902: Ubora wa kazi ____________</h3>
                    <h3>903: Kupenda kuthamini na kuheshimu kazi ___________</h3>
                    <h3>904: Uaminifu wa mali yake binafsi ____________</h3>
                    <h3>905: Uelewano na ushirikiano ____________</h3>
                    <h3>906: Heshima kwa walimu na wanafunzi wengine wa shule ____________</h3>
                    <h3>907: Wenzake hutafuta ushauri toka kwake ____________</h3>
                    <h3>908:Ana sifa za kufanya wenzake wavutiwe na uongozi wake ____________</h3>
                    <h3>909:Anajitolea kuongoza wenzake kumaliza kazi iliyotolewa ____________</h3>
                    <h3>910:Hutii kwa hiari na kufuata maagizo ____________</h3>
                    <h3>911: Usafi binafsi ____________</h3>
                    <h3>912: Uaminifu ____________</h3>
                    <h3>913: Kushiriki katika michezo ____________</h3>
                    <h2 class="endpage"><span class="big_bold_underline">Alama ya kupimika: </span> A = Vizuri sana; B = Vizuri; C = Wastani; D = Dhaifu; F = Mbaya sana</h2>
                    <h2><span class="big_bold_underline">Nyongeza: </span></h2>
                    <h2>1) Afya yake</h2>
                    <h2>2) Sifa nzuri / Mbaya</h2>
                    <h2>Mimi ___________________ Nimeona taarifa hii na ninaahidi kuongeza juhudi katika masomo pia katika tabia, kazi, na mwenendo.</h2>
                    <h2>Tarehe _____________________ Saini ya Mwanafunzi _________________________</h2>
                    <h2>Saini na maoni ya Mwalimu wa darasa ______________________________________</h2>
                    <h2>_______________________________ Tarehe_______________</h2>
                    <!--                    FIXME: how to format this line??-->
                        <br>
                    <h2>Maoni na Saini ya Mkuu wa Shule ___________________________________________________</h2>
                    <h2>_________________________________________________________________ Tarehe ________</h2>
                    <br>
                        <h2>MAONI YA MZAZI/MLEZI</h2>
                        <h2>_______________________________________________________________________________</h2>
                        <br>
                        <h2>Anuani ya Mzazi/Mlezi au namba ya simu tuandikie hapo juu. <span>Mwanafunzi asiyerejesha ripoti hii hapokelewi shuleni.</span></h2>
                    <br>
                    <ol type="1">
                        <li>Mwanafunzi haruhusiwi kumiliki simu awapo shuleni, au kutumia simu nje ya mamlaka rasmi. Kinyume na hapo hatua kali za kinidhamu zitachukuliwa ikiwa ni pamoja na kufukuzwa shule.</li>
                        <li>Mwanafunzi yeyote kidato cha I-III atakayepata wastani wa masomo yote chini ya alama 50 hataruhusiwa kuendelea na masomo katika shule hii.</li>
                        <li>Mwanafunzi asiyeripoti shuleni siku/tarehe ya kufungua shule atakuwa amejifukuzisha shule. Sababu yoyote haitasikilizwa. Mzazi/Mlezi tafadhali mwezeshe na kumruhusu mtoto arudi shuleni mapema iwezekanavyo.</li>
                        <li>Mwanafunzi aje na Ream A4 moja. Aje nayo mkononi.</li>
                        <li>Mwanafunzi yeyote hatoruhusiwa kuingia kwenye geti la shule bila kukamilisha ada na michango hivyo atarudishwa getini,barua za maelezo ya ada hazitapokelewa.</li>
                    </ol>
                    <?php
        }
    }
	else{
		header('Location: /admin/unauthorized.php');
	}
?>
                        <script type="text/javascript">
                            <!--
                            window.print();
                            //-->

                        </script>
        </div>
</body>

</html>
