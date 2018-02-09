<?php
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * editdw.php: Edit the Domestic Worker record
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once($libpath . '/../dwpivr/common.php');
require_once('dwreglib.php');
initconfig();
//connecton will be created
createConnection();
//Search by regid or dwid or regmobile


//print_r($_REQUEST);
if (isset($_REQUEST['search'])) {
     $dwid = $_REQUEST["v_dwid"];
    
        $sql = "SELECT * from `contracts` WHERE `dwid`='" . $dwid . "'";
   
        $result = dwqueryexec(__FUNCTION__, $sql);

    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
 
    
    //If Sql ERROR, give alert and go back 
 /* if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!'); document.location='searchdw.php'</script>";
    }
   //Else If No DW found, give alert and go back
    else if ($currentdw == NULL) {

        echo "<script>alert('data updated succeessfully.'); document.location='searchdw.php'</script>";
    }
*/
}
?>
<html>
    <head>
        <title>Aideexpert.com</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
 	<style type="text/css">
            .form-section {
                padding-left: 15px;

                display: none;
            }
            .form-section.current {
                display: inherit;
            }
            .btn-info, .btn-default {
                margin-top: 10px;
            }
            input.parsley-success,
            select.parsley-success,
            textarea.parsley-success {
                color: #468847;
                background-color: #DFF0D8;
                border: 1px solid #D6E9C6;
            }

            input.parsley-error,
            select.parsley-error,
            textarea.parsley-error {
                color: #B94A48;
                background-color: #F2DEDE;
                border: 1px solid #EED3D7;
            }

            .parsley-errors-list {
                margin: 2px 0 3px;
                padding: 0;
                list-style-type: none;
                font-size: 0.9em;
                line-height: 0.9em;
                opacity: 0;

                transition: all .3s ease-in;
                -o-transition: all .3s ease-in;
                -moz-transition: all .3s ease-in;
                -webkit-transition: all .3s ease-in;
            }

            .parsley-errors-list.filled {
                opacity: 1;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->
        <script type="text/javascript">
            $(function () {
                var $sections = $('.form-section');

                function navigateTo(index) {
                    // Mark the current section with the class 'current'
                    $sections
                            .removeClass('current')
                            .eq(index)
                            .addClass('current');
                    // Show only the navigation buttons that make sense for the current section:
                    $('.form-navigation .previous').toggle(index > 0);
                    var atTheEnd = index >= $sections.length - 1;
                    $('.form-navigation .next').toggle(!atTheEnd);
                    $('.form-navigation [type=submit]').toggle(atTheEnd);
                }

                function curIndex() {
                    // Return the current index by looking at which section has the class 'current'
                    return $sections.index($sections.filter('.current'));
                }

                // Previous button is easy, just go back
                $('.form-navigation .previous').click(function () {
                    navigateTo(curIndex() - 1);
                });

                // Next button goes forward iff current block validates
                $('.form-navigation .next').click(function () {
                    if ($('.demo-form').parsley().validate({group: 'block-' + curIndex()}))
                        navigateTo(curIndex() + 1);
                });

                // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
                $sections.each(function (index, section) {
                    $(section).find(':input').attr('data-parsley-group', 'block-' + index);
                });
                navigateTo(0); // Start at the beginning
            });
        </script>

        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load("elements", "1", {packages: "transliteration"});

            function OnLoad() {
                var options = {
                    sourceLanguage:
                            google.elements.transliteration.LanguageCode.ENGLISH,
                    destinationLanguage:
                            [google.elements.transliteration.LanguageCode.HINDI],
                    shortcutKey: 'ctrl+g',
                    transliterationEnabled: true
                };

                var control = new google.elements.transliteration.TransliterationControl(options);
                control.makeTransliteratable(["txtHindi"]);
                var keyVal = 32; // Space key
                $("#txtEnglish").on('keydown', function (event) {
                    if (event.keyCode === 32) {
                        var engText = $("#txtEnglish").val() + " ";
                        var engTextArray = engText.split(" ");
                        $("#txtHindi").val($("#txtHindi").val() + engTextArray[engTextArray.length - 2]);

                        document.getElementById("txtHindi").focus();
                        $("#txtHindi").trigger({
                            type: 'keypress', keyCode: keyVal, which: keyVal, charCode: keyVal
                        });
                    }
                });

                $("#txtHindi").bind("keyup", function (event) {
                    setTimeout(function () {
                        $("#txtEnglish").val($("#txtEnglish").val() + " ");
                        document.getElementById("txtEnglish").focus()
                    }, 0);
                });
            } //end onLoad function

            google.setOnLoadCallback(OnLoad);
        </script>


    </head>  
    <body>
        <div class="container-fluid">
            <h1 class="text-center"> Contract of Domestic Worker</h1>
            <br> <
            <div class="col-md-12">
                <form class="form-horizontal demo-form" name="editdw" data-parsley-validate="" method="post" action="dwupdate.php">
                    <!--action="action"-->
                    <div class="col-xs-12">
                    <div class="table-responsive">           
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SerialNo</th>
                                    <th>ContractId</th>
                                    <th>DwEmpId</th>
                                    <th>StartDate</th>
                                    <th>EndDate</th>
                                    <th>DailyStartTime</th>
                                    <th>DailyEndTime</th>
                                    <th>Sallary</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    for ($mydw = 1; $mydw <= count($currentdw); $mydw++) {
                                        ?>   
                                        <th scope="row"><?php echo $mydw; ?></th>
                                        <th ><?php echo $currentdw[$mydw - 1]['contractid'] ?>  </th>
                                        <td><?php echo $currentdw[$mydw - 1]['dwempid'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['startdate'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['enddate'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['dailystarttime'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['dailyendtime'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['sallary'] ?></td>

                            </tbody>                                     <?php } ?>
   
                        </table>
                    </div>
                        </div>
                </form>

                        </div>
                        </div>

           
               
        


    </body>
</html>

