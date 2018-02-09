<?php

/*Template Name:AdminHomePage */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * adminhomepage: The Home Page Of Admin dashboard
 *

 *  */


require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');

if(!check_dwaccess('administrator')) {
    auth_redirect();
    
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>AideExpert.com</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
      
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>
 
</head>
<?php 
            

get_header(); ?>
 

<body background="admin.jpg">

<nav class="navbar navbar-inverse visible-xs">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Admin</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav" style="list-style-type: none;">
          <li><a href="#">Dashboard</a></li>
       <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Domestic Worker">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseDw1" data-parent="#exampleAccordion">
             
              <span class="nav-link-text">
                Domestic Worker</span>
            </a>
            <ul  class="sidenav-second-level collapse" class="list-unstyled"  style="list-style-type: none;" id="collapseDw1">
              <li>
                  <a href="<?php echo site_url() . '/registerdw'?>"><span class="glyphicon glyphicon-plus">  Register</span></a>
              </li>
              <li>
                  <a href="<?php echo site_url() . '/searchdw'?>"><span class="glyphicon glyphicon-search">  Browse </span></a>
              </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employee">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseEmp1" data-parent="#exampleAccordion">
              
              <span class="nav-link-text">
                Employer</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseEmp1" style="list-style-type: none;">
              <li>
                  <a href="<?php echo site_url() . '/dwemp'?>"><span class="glyphicon glyphicon-plus">  Register</span></a>
              </li>
              <li>
                  <a href="<?php echo site_url() . '/searchemployee'?>"><span class="glyphicon glyphicon-search">  Browse </span></a>
              </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="contract">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseContracts1" data-parent="#exampleAccordion">
              
              <span class="nav-link-text">
                contracts</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseContracts1" style="list-style-type: none;">
              <li>
                  <a href="<?php echo site_url() . '/addcontract'?>"><span class="glyphicon glyphicon-plus"> Add Contract</span></a>
              </li>
              <li>
                  <a href="<?php echo site_url() . '/searchcontract'?>"><span class="glyphicon glyphicon-search">  Search Contract </span></a>
              </li>
            </ul>
          </li>
              
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="AddEmployerticket">
            <a href="<?php echo site_url() . '/requesthelperadmin'?>">
              
              <span class="nav-link-text">
                Add Employer Ticket</span>
            </a>
            </li>
                          
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <h2>AideExpert Administration</h2>
      <ul class="nav nav-pills nav-stacked"  style="list-style-type: none;">
        <li class=""><a href="">Dashboard</a></li>
      
             <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Domestic Worker">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseDw" data-parent="#exampleAccordion">
             
              <span class="nav-link-text">
                  <span class="glyphicon glyphicon-user">  Domestic Worker</span></span>
            </a>
                 
            <ul class="sidenav-second-level collapse"   id="collapseDw" style="list-style-type: none;">
              <li>
                  <a href="<?php echo site_url() . '/registerdw'?>"><span class="glyphicon glyphicon-plus">  Register</span></a>
              </li>
              <li>
                  <a href="<?php echo site_url() . '/searchdw'?>"><span class="glyphicon glyphicon-search">  Browse </span></a>
              </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Employer">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseEmp" data-parent="#exampleAccordion">
              
              <span class="nav-link-text">
                  <span class="glyphicon glyphicon-user">  Employer</span></span>
            </a>
            <ul class="sidenav-second-level collapse"  style="list-style-type: none;" id="collapseEmp">
              <li>
                  <a href="<?php echo site_url() . '/dwemp'?>"><span class="glyphicon glyphicon-plus">  Register</span></a>
              </li>
              <li>
                  <a href="<?php echo site_url() . '/searchemployee'?>"><span class="glyphicon glyphicon-search">  Browse </span></a>
              </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="contract">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseContracts" data-parent="#exampleAccordion">
             
              <span class="nav-link-text" >
                  <span class="glyphicon glyphicon-edit">  Contracts </span></span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseContracts" style="list-style-type: none;">
              <li>
                  <a href="<?php echo site_url() . '/addcontract'?>"><span class="glyphicon glyphicon-plus">  Add Contact</span></a>
              </li>
              <li>
                  <a href="<?php echo site_url() . '/searchcontract'?>"><span class="glyphicon glyphicon-search">  Search Contract </span></a>
              </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="addticketforemployer">
            <a href="<?php echo site_url() . '/requesthelperadmin'?>">
             
              <span class="nav-link-text" >
                  <span class="glyphicon glyphicon-edit">  Add Employer Ticket </span></span>
            </a>
           
          </li>
         </ul>    
    </div>
    <br>
    
    <div class="col-sm-9">
      <div >
        <h4>Dashboard</h4>
        <p>Some text..</p>
      </div>
      
    </div>
  </div>
</div>
    <?php get_footer(); ?>
</body>
</html>
<?php
// Wrap up script
closeAll();
?>