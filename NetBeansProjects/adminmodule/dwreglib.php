<?php

/* Template Name:DwRegLib */

/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * dwreglib.php: Library functions for registration module
 *

 *  */
function GetImageExtension($imagetype) {
    if (empty($imagetype))
        return false;

    switch ($imagetype) {

        case 'image/bmp': return '.bmp';

        case 'image/gif': return '.gif';

        case 'image/jpeg': return '.jpg';
        case 'image/png': return '.png';

        default: return false;
    }
}

/* gendwSql() function contain the all domestic worker variable */

function gendwSql() {


    $sql = "";

    if (isset($_REQUEST["v_dwidtype"])) {
        $dwidtype = $_REQUEST["v_dwidtype"];
        $sql .= " `dwidtype` = '" . $dwidtype . "' ,";
    }
    if (isset($_REQUEST["v_name"])) {
        $name = $_REQUEST["v_name"];
        $sql .= " `name` = '" . $name . "' ,";

        // echo $name;
    }
    if (isset($_REQUEST["v_nametts"])) {
        $nametts = $_REQUEST["v_nametts"];
        $sql .= " `nametts` = '" . $nametts . "' ,";

        // echo $nametts;
    }
    if (isset($_REQUEST["v_regmobile"])) {
        $regmobile = $_REQUEST["v_regmobile"];
        $sql .= " `regmobile` = '" . $regmobile . "' ,";

        // echo $regmobile;
    }
    if (isset($_REQUEST["v_dob"])) {
        $dob = $_REQUEST["v_dob"];
        $sql .= " `dob` = '" . $dob . "' ,";

        //echo $dob;
    }
    if (isset($_REQUEST["v_address1"])) {
        $address1 = $_REQUEST["v_address1"];
        $sql .= " `address1` = '" . $address1 . "' ,";

        //echo $address1;
    }
    if (isset($_REQUEST["v_address2"])) {
        $address2 = $_REQUEST["v_address2"];
        $sql .= " `address2` = '" . $address2 . "' ,";

        // echo $address2;
    }
    if (isset($_REQUEST["v_gender"])) {
        $gender = $_REQUEST["v_gender"];
        $sql .= " `gender` = '" . $gender . "' ,";

        //echo $gender;
    }
    if (isset($_REQUEST["v_email"])) {
        $email = $_REQUEST["v_email"];
        $sql .= " `email` = '" . $email . "' ,";

        // echo $email;
    }
    if (isset($_REQUEST["v_empstatus"])) {
        $empstatus = $_REQUEST["v_empstatus"];
        $sql .= " `empstatus` = '" . $empstatus . "' ,";

        // echo $empstatus;
    }


    if (isset($_REQUEST["v_appliances"])) {
        $appliances = implode(', ', $_REQUEST["v_appliances"]);
        $sql .= " `appliances` = '" . $appliances . "' ,";

        // echo $appliances;
    }

    if (isset($_REQUEST["v_majorskills"])) {
        $majorskills = implode(', ', $_REQUEST["v_majorskills"]);
        $sql .= " `majorskills` = '" . $majorskills . "' ,";

        // echo $majorskills;
    }

    if (isset($_REQUEST["v_lang1"])) {
        $lang1 = $_REQUEST["v_lang1"];
        $sql .= " `lang1` = '" . $lang1 . "' ,";

        if (isset($_REQUEST["v_prof1"])) {

            $prof1 = implode(', ', $_REQUEST["v_prof1"]);
            $sql .= " `prof1` = '" . $prof1 . "' ,";
        }
    }

    if (isset($_REQUEST["v_lang2"])) {
        $lang2 = $_REQUEST["v_lang2"];
        $sql .= " `lang2` = '" . $lang2 . "' ,";
        if (isset($_REQUEST["v_prof2"])) {

            $prof2 = implode(', ', $_REQUEST["v_prof2"]);
            $sql .= " `prof2` = '" . $prof2 . "' ,";
        }
    }
    if (isset($_REQUEST["v_lang3"])) {
        $lang3 = $_REQUEST["v_lang3"];
        $sql .= " `lang3` = '" . $lang3 . "' ,";

        if (isset($_REQUEST["v_prof3"])) {

            $prof3 = implode(', ', $_REQUEST["v_prof3"]);
            $sql .= " `prof3` = '" . $prof3 . "' ,";
        }
        /* if (isset($_REQUEST["v_prof3"])) {

          $prof3 = implode(', ', $_REQUEST["v_prof3"]);
          $sql .= " `prof3` = '" . $prof3 . "' ,";


          } */
    }


    if (isset($_REQUEST["v_edu"])) {
        $edu = $_REQUEST["v_edu"];
        $sql .= " `edu` = '" . $edu . "' ,";

        // echo $edu;
    }

    if (isset($_REQUEST["v_mliteracy"])) {
        $mliteracy = $_REQUEST["v_mliteracy"];
        $sql .= " `mliteracy` = '" . $mliteracy . "' ,";

        // echo $mliteracy;
    }



    if (isset($_REQUEST["v_marital"])) {
        $marital = $_REQUEST["v_marital"];
        $sql .= " `marital` = '" . $marital . "' ,";

        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_city"])) {
        $city = $_REQUEST["v_city"];
        $sql .= " `city` = '" . $city . "' ,";

        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_area"])) {
        $area = $_REQUEST["v_area"];
        $sql .= " `area` = '" . $area . "' ,";

        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_dwsupportid"])) {
        $dwsupportid = $_REQUEST["v_dwsupportid"];
        $sql .= " `dwsupportid` = '" . $dwsupportid . "' ,";

        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_supportname"])) {
        $supportname = $_REQUEST["v_supportname"];
        $sql .= " `supportname` = '" . $supportname . "', ";
        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_supportmobile"])) {
        $supportmobile = $_REQUEST["v_supportmobile"];
        $sql .= " `supportmobile` = '" . $supportmobile . "' ,";

        // echo $mliteracy;
    }if (isset($_REQUEST["v_groupname"])) {
        $groupname = $_REQUEST["v_groupname"];
        $sql .= " `groupname` = '" . $groupname . "' ,";

        // echo $mliteracy;
    }

    if (isset($_REQUEST["v_pincode"])) {
        $pincode = $_REQUEST["v_pincode"];
        $sql .= " `pincode` = '" . $pincode . "' ,";

        // echo $mliteracy;
    }

    if (isset($_REQUEST["v_worksince"])) {
        $worksince = $_REQUEST["v_worksince"];
        $sql .= " `worksince` = '" . $worksince . "', ";
    }
    if (isset($_REQUEST["v_doj"])) {
        $doj = $_REQUEST["v_doj"];
        $sql .= " `doj` = '" . $doj . "' ";
    }



    return ($sql);
}

/* genbenefitsql() function gives tha all benefits details  */

function genbenefitsql() {
    $sql = "";
    if (isset($_REQUEST["v_acno"])) {
        $acno = $_REQUEST["v_acno"];
        $sql .= " `acno` = '" . $acno . "',";
    }
    if (isset($_REQUEST["v_bname"])) {
        $bname = $_REQUEST["v_bname"];
        $sql .= " `bname` = '" . $bname . "',";
    }
    if (isset($_REQUEST["v_branch"])) {
        $branch = $_REQUEST["v_branch"];
        $sql .= " `branch` = '" . $branch . "',";
    }
    if (isset($_REQUEST["v_ifsc"])) {
        $ifsc = $_REQUEST["v_ifsc"];
        $sql .= " `ifsc` = '" . $ifsc . "',";
    }
    if (isset($_REQUEST["v_b1name"])) {
        $b1name = $_REQUEST["v_b1name"];
        $sql .= " `b1name` = '" . $b1name . "',";
    }
    if (isset($_REQUEST["v_b1dob"])) {
        $b1dob = $_REQUEST["v_b1dob"];
        $sql .= " `b1dob` = '" . $b1dob . "',";
    }
    if (isset($_REQUEST["v_b1gender"])) {
        $b1gender = $_REQUEST["v_b1gender"];
        $sql .= " `b1gender` = '" . $b1gender . "',";
    }

    if (isset($_REQUEST["v_b1relation"])) {
        $b1relation = $_REQUEST["v_b1relation"];
        $sql .= " `b1relation` = '" . $b1relation . "',";
    }
    if (isset($_REQUEST["v_b1income"])) {
        $b1income = $_REQUEST["v_b1income"];
        $sql .= " `b1income` = '" . $b1income . "',";
    }

    if (isset($_REQUEST["v_b2name"])) {
        $b2name = $_REQUEST["v_b2name"];
        $sql .= " `b2name` = '" . $b2name . "',";
    }
    if (isset($_REQUEST["v_b2dob"])) {
        $b2dob = $_REQUEST["v_b2dob"];
        $sql .= " `b2dob` = '" . $b2dob . "',";
    }
    if (isset($_REQUEST["v_b2gender"])) {
        $b2gender = $_REQUEST["v_b2gender"];
        $sql .= " `b2gender` = '" . $b2gender . "',";
    }

    if (isset($_REQUEST["v_b2relation"])) {
        $b2relation = $_REQUEST["v_b2relation"];
        $sql .= " `b2relation` = '" . $b2relation . "',";
    }
    if (isset($_REQUEST["v_b2income"])) {
        $b2income = $_REQUEST["v_b2income"];
        $sql .= " `b2income` = '" . $b2income . "',";
    }

    if (isset($_REQUEST["v_b3name"])) {
        $b3name = $_REQUEST["v_b3name"];
        $sql .= " `b3name` = '" . $b3name . "',";
    }
    if (isset($_REQUEST["v_b3dob"])) {
        $b3dob = $_REQUEST["v_b3dob"];
        $sql .= " `b3dob` = '" . $b3dob . "',";
    }
    if (isset($_REQUEST["v_b3gender"])) {
        $b3gender = $_REQUEST["v_b3gender"];
        $sql .= " `b3gender` = '" . $b3gender . "',";
    }

    if (isset($_REQUEST["v_b3relation"])) {
        $b3relation = $_REQUEST["v_b3relation"];
        $sql .= " `b3relation` = '" . $b3relation . "',";
    }
    if (isset($_REQUEST["v_b3income"])) {
        $b3income = $_REQUEST["v_b3income"];
        $sql .= " `b3income` = '" . $b3income . "',";
    }

    if (isset($_REQUEST["v_b4name"])) {
        $b4name = $_REQUEST["v_b4name"];
        $sql .= " `b4name` = '" . $b4name . "',";
    }
    if (isset($_REQUEST["v_b4dob"])) {
        $b4dob = $_REQUEST["v_b4dob"];
        $sql .= " `b4dob` = '" . $b4dob . "',";
    }
    if (isset($_REQUEST["v_b4gender"])) {
        $b4gender = $_REQUEST["v_b4gender"];
        $sql .= " `b4gender` = '" . $b4gender . "',";
    }

    if (isset($_REQUEST["v_b4relation"])) {
        $b4relation = $_REQUEST["v_b4relation"];
        $sql .= " `b4relation` = '" . $b4relation . "',";
    }
    if (isset($_REQUEST["v_b4income"])) {
        $b4income = $_REQUEST["v_b4income"];
        $sql .= " `b4income` = '" . $b4income . "',";
    }

    if (isset($_REQUEST["v_b5name"])) {
        $b4name = $_REQUEST["v_b5name"];
        $sql .= " `b5name` = '" . $b5name . "',";
    }
    if (isset($_REQUEST["v_b5dob"])) {
        $b5dob = $_REQUEST["v_b5dob"];
        $sql .= " `b5dob` = '" . $b5dob . "',";
    }
    if (isset($_REQUEST["v_b5gender"])) {
        $b5gender = $_REQUEST["v_b5gender"];
        $sql .= " `b5gender` = '" . $b5gender . "',";
    }

    if (isset($_REQUEST["v_b5relation"])) {
        $b5relation = $_REQUEST["v_b5relation"];
        $sql .= " `b5relation` = '" . $b5relation . "',";
    }
    if (isset($_REQUEST["v_b5income"])) {
        $b5income = $_REQUEST["v_b5income"];
        $sql .= " `b5income` = '" . $b5income . "' ";
    }
    return ($sql);
}

/*
 * this function give the all details of employer 

 */

function genempSql() {

    $sql = "";




    if (isset($_REQUEST["v_nametts"])) {
        $nametts = $_REQUEST["v_nametts"];
        $sql .= " `nametts` = '" . $nametts . "' ,";
    }
    if (isset($_REQUEST["v_address1"])) {
        $address1 = $_REQUEST["v_address1"];
        $sql .= " `address1` = '" . $address1 . "' ,";
    }
    if (isset($_REQUEST["v_address2"])) {
        $address2 = $_REQUEST["v_address2"];
        $sql .= " `address2` = '" . $address2 . "' ,";
    }
    if (isset($_REQUEST["v_email"])) {
        $email = $_REQUEST["v_email"];
        $sql .= " `email` = '" . $email . "' ,";
    }


    if (isset($_REQUEST["v_pincode"])) {
        $pincode = $_REQUEST["v_pincode"];
        $sql .= " `pincode` = '" . $pincode . "', ";
    }
    if (isset($_REQUEST["v_city"])) {
        $city = $_REQUEST["v_city"];
        $sql .= " `city` = '" . $city . "', ";
    }
    if (isset($_REQUEST["v_regmobile"])) {
        $regmobile = $_REQUEST["v_regmobile"];
        $sql .= " `regmobile` = '" . $regmobile . "', ";
    }
    if (isset($_REQUEST["v_area"])) {
        $area = $_REQUEST["v_area"];
        $sql .= " `area` = '" . $area . "',";
    }
    if (isset($_REQUEST["v_doj"])) {
        $doj = $_REQUEST["v_doj"];
        $sql .= " `doj` = '" . $doj . "' ";
    }
    return ($sql);
}

/* this function generate the  contract  */

function gencontractSql() {

    $sql = "";



    if (isset($_REQUEST["v_dwempid"])) {
        $dwempid = $_REQUEST["v_dwempid"];
        $sql .= " `dwempid` = '" . $dwempid . "', ";
    }
    if (isset($_REQUEST["v_startdate"])) {
        $startdate = $_REQUEST["v_startdate"];
        $sql .= " `startdate` = '" . $startdate . "', ";
    }
    if (isset($_REQUEST["v_enddate"])) {
        $enddate = $_REQUEST["v_enddate"];
        $sql .= " `enddate` = '" . $enddate . "', ";
    }
    if (isset($_REQUEST["v_dwempnotes"])) {
        $dwempnotes = $_REQUEST["v_dwempnotes"];
        $sql .= " `dwempnotes` = '" . $dwempnotes . "', ";
    }




    if (isset($_REQUEST["v_dailystarttime"])) {
        $dailystarttime = $_REQUEST["v_dailystarttime"];
        $sql .= " `dailystarttime` = '" . $dailystarttime . "' ,";
    }






    if (isset($_REQUEST["v_dailyendtime"])) {
        $dailyendtime = $_REQUEST["v_dailyendtime"];
        $sql .= " `dailyendtime` = '" . $dailyendtime . "' ,";
    }




    if (isset($_REQUEST["v_sallary"])) {
        $sallary = $_REQUEST["v_sallary"];
        $sql .= " `sallary` = '" . $sallary . "', ";
    }
    if (isset($_REQUEST["v_majorskills"])) {
        $majorskills = implode(', ', $_REQUEST["v_majorskills"]);
        $sql .= " `majorskills` = '" . $majorskills . "' ";
    }


    return ($sql);
}


function genReqHelper()
{
    
    
    $sql = "";
    
     if (isset($_REQUEST["v_worktype"])) {
        $worktype = implode(', ', $_REQUEST["v_worktype"]);
        $sql .= " `worktype` = '" . $worktype . "' ,";

        // echo $majorskills;
    }
    
    if (isset($_REQUEST["v_address"])) {
        $address = $_REQUEST["v_address"];
        $sql .= " `address` = '" . $address . "' ,";

        //echo $address1;
    }
    
    
    if (isset($_REQUEST["v_dailystarttime"])) {
        $dailystarttime = $_REQUEST["v_dailystarttime"];
        $sql .= " `dailystarttime` = '" . $dailystarttime . "' ,";
    }

    if (isset($_REQUEST["v_dailyendtime"])) {
        $dailyendtime = $_REQUEST["v_dailyendtime"];
        $sql .= " `dailyendtime` = '" . $dailyendtime . "' ,";
    }
    
    if (isset($_REQUEST["v_pincode"])) {
        $pincode = $_REQUEST["v_pincode"];
        $sql .= " `pincode` = '" . $pincode . "' ,";

        // echo $mliteracy;
    }
    
    if (isset($_REQUEST["v_area"])) {
        $area = $_REQUEST["v_area"];
        $sql .= " `area` = '" . $area . "' ,";

        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_landmark"])) {
        $landmark = $_REQUEST["v_landmark"];
        $sql .= " `landmark` = '" . $landmark . "',";
    }
    if (isset($_REQUEST["v_weekoff"])) {
        $weekoff = $_REQUEST["v_weekoff"];
        $sql .= " `weekoff` = '" . $weekoff . "',";
    }if (isset($_REQUEST["v_minbudget"])) {
        $minbudget = $_REQUEST["v_minbudget"];
        $sql .= " `minbudget` = '" . $minbudget . "',";
    }if (isset($_REQUEST["v_maxbudget"])) {
        $maxbudget = $_REQUEST["v_maxbudget"];
        $sql .= " `maxbudget` = '" . $maxbudget . "' ";
    }
    return ($sql);
}


function genReqHelperAdmin()
{
    
    
    $sql = "";
    
     if (isset($_REQUEST["v_worktype"])) {
        $worktype = implode(', ', $_REQUEST["v_worktype"]);
        $sql .= " `worktype` = '" . $worktype . "' ,";

        // echo $majorskills;
    }
    
    if (isset($_REQUEST["v_address"])) {
        $address = $_REQUEST["v_address"];
        $sql .= " `address` = '" . $address . "' ,";

        //echo $address1;
    }
    
    
    if (isset($_REQUEST["v_dailystarttime"])) {
        $dailystarttime = $_REQUEST["v_dailystarttime"];
        $sql .= " `dailystarttime` = '" . $dailystarttime . "' ,";
    }

    if (isset($_REQUEST["v_dailyendtime"])) {
        $dailyendtime = $_REQUEST["v_dailyendtime"];
        $sql .= " `dailyendtime` = '" . $dailyendtime . "' ,";
    }
    
    if (isset($_REQUEST["v_pincode"])) {
        $pincode = $_REQUEST["v_pincode"];
        $sql .= " `pincode` = '" . $pincode . "' ,";

        // echo $mliteracy;
    }
    
    if (isset($_REQUEST["v_area"])) {
        $area = $_REQUEST["v_area"];
        $sql .= " `area` = '" . $area . "' ,";

        // echo $mliteracy;
    }
    if (isset($_REQUEST["v_landmark"])) {
        $landmark = $_REQUEST["v_landmark"];
        $sql .= " `landmark` = '" . $landmark . "',";
    }
    if (isset($_REQUEST["v_weekoff"])) {
        $weekoff = $_REQUEST["v_weekoff"];
        $sql .= " `weekoff` = '" . $weekoff . "',";
    }if (isset($_REQUEST["v_minbudget"])) {
        $minbudget = $_REQUEST["v_minbudget"];
        $sql .= " `minbudget` = '" . $minbudget . "',";
    }if (isset($_REQUEST["v_maxbudget"])) {
        $maxbudget = $_REQUEST["v_maxbudget"];
        $sql .= " `maxbudget` = '" . $maxbudget . "' ";
    }
    return ($sql);
}
























/* this function check the access */

function check_dwaccess($role) {
    $current_user = wp_get_current_user();
    if (!user_can($current_user, $role)) {
        echo "<script>alert('Sorry! Access Denied.'); document.location='" . site_url() . "';</script>";
        return FALSE;
    } else {
        return TRUE;
    }
}
