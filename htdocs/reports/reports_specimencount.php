<?php
#
# Shows count for specimens handled for a site/location and date interval
#
include("redirect.php");
include("includes/header.php");
include("includes/stats_lib.php");
include("includes/scripts.php");
LangUtil::setPageId("reports");

$uiinfo = "from=".$date_from."%to=".$date_to;
?>

 <!-- BEGIN PAGE TITLE & BREADCRUMB-->       
                        <h3>
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="index.html">Home</a> 
                                <span class="icon-angle-right"></span>
                            </li>
                            <li><a href="#">Reports</a>
                            <span class="icon-angle-right"></span></li>
                            <li><a href="#"></a></li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
          <div class="row-fluid">
                <div class="span12 sortable">

                    <div class="portlet box green" id="prevalence_div">
                        <div class="portlet-title" >
                            <h4><i class="icon-reorder"></i> <?php echo LangUtil::$pageTerms['COUNT_SPECIMEN']; ?></h4>           
                        </div>
                        
                          <div class="portlet-body" >
                                <br>
                                <b><?php echo LangUtil::$pageTerms['COUNT_SPECIMEN']; ?></b>
                                 <?php /*| <a href="javascript:toggle_stat_table();" id='showtablelink'># echo LangUtil::$pageTerms['MSG_SHOWGRAPH']; </a> */ ?>
                                 | <a href='reports.php?show_c'>&laquo; <?php echo LangUtil::$pageTerms['MSG_BACKTOREPORTS']; ?></a>
                                <br><br>
                                <?php
                                $lab_config_id = $_REQUEST['location'];
                                $date_from = $_REQUEST['from-report-date'];
                                $date_to = $_REQUEST['to-report-date'];
                                $uiinfo = "from=".$date_from."&to=".$date_to;
                                putUILog('reports_specimen_count_ungrouped', $uiinfo, basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
                                
                                $lab_config = get_lab_config_by_id($lab_config_id);
                                if($lab_config == null)
                                {
                                    ?>
                                    <div class='sidetip_nopos'>
                                        <?php echo LangUtil::$generalTerms['MSG_NOTFOUND']; ?> <a href='javascript:history.go(-1);'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a>
                                    </div>
                                    <?php
                                    return;
                                }
                                DbUtil::switchToLabConfig($lab_config_id);
                                 $site_list = get_site_list($_SESSION['user_id']);
                                            if(count($site_list) != 1)
                                            { echo LangUtil::$generalTerms['FACILITY'] ?>: <?php echo $lab_config->getSiteName(); ?> | 
                                <?php
                                }
                                
                                if($date_from == $date_to)
                                {
                                    echo LangUtil::$generalTerms['DATE'].": ".DateLib::mysqlToString($date_from);
                                }
                                else
                                {
                                    echo LangUtil::$generalTerms['FROM_DATE'].": ".DateLib::mysqlToString($date_from);
                                    echo " | ";
                                    echo LangUtil::$generalTerms['TO_DATE'].": ".DateLib::mysqlToString($date_to);
                                }
                                ?>
                                <br><br>
                                
                                <?php $stat_list = StatsLib::getSpecimenCountStats($lab_config, $date_from, $date_to); ?>
                                <?php
                                /*
                                <div id='stat_graph'>
                                <?php
                                # To avoid cluttered graphs, divide stat_list to chunka
                                $chunk_size = 999;
                                $stat_chunks = array_chunk($stat_list, $chunk_size, true);
                                $i = 1;
                                foreach($stat_chunks as $stat_chunk)
                                {
                                    $div_id = "placeholder_".$i;
                                    $ylabel_id = "ylabel_".$i;
                                    $legend_id = "legend_".$i;
                                    $width_px = count($stat_chunk)*150;
                                    ?>
                                    <table>
                                    <tbody>
                                    <tr valign='top'>
                                    <td>
                                        <span id="<?php echo $ylabel_id; ?>" class='flipv_up' style="width:30px;height:30px;"><?php echo LangUtil::$pageTerms['COUNT_SPECIMEN']; ?></span>
                                    </td>
                                    <td>
                                        <div style='width:900px;height:340px;overflow:auto'>
                                            <div id="<?php echo $div_id; ?>" style="width:<?php echo $width_px; ?>px;height:300px;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="<?php echo $legend_id; ?>" style="width:200px;height:300px;"></div>
                                    </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <script id="source" language="javascript" type="text/javascript"> 
                                    $(function () {
                                        <?php
                                        $x_val = 0;
                                        $count = 1;
                                        foreach($stat_list as $key=>$value)
                                        {
                                            $test_type_id = $key;
                                            $tests_done_count = $value;
                                            echo "var d$count = [];";
                                            echo "d$count.push([$x_val, $tests_done_count]);";
                                            $count++;
                                            $x_val += 2;
                                            #ticks: [[0, "zero"], [1.2, "one mark"], [2.4, "two marks"]]
                                
                                        }
                                        ?>
                                        $.plot($("#<?php echo $div_id; ?>"), [
                                            <?php
                                            $count = 1;
                                            $index_count = 0;
                                            $tick_array = "[";
                                            foreach($stat_chunk as $key=>$value)
                                            {
                                                $specimen_name = get_specimen_name_by_id($key);
                                                $tick_array .= "[$index_count+0.4, '$specimen_name']";
                                                ?>
                                                {
                                                    data: d<?php echo $count; ?>,
                                                    bars: { show: true }
                                                    //,
                                                    //label: "<?php #echo get_specimen_name_by_id($key); ?>"
                                                }
                                                <?php
                                                $count++;
                                                $index_count += 2;
                                                if($count < count($stat_chunk) + 1)
                                                {
                                                    echo ",";
                                                    $tick_array .= ",";
                                                }
                                            }
                                            $tick_array .= "]";
                                            ?>
                                        ], { xaxis: {ticks: <?php echo $tick_array; ?>}, legend: {container: "#<?php echo $legend_id; ?>"}   });
                                        $('#<?php echo $ylabel_id; ?>').flipv_up();
                                    }); 
                                    </script>
                                    <?php
                                    # End of loop
                                    $i++;
                                }
                                ?>
                                </div>
                                <?php
                                */
                                ?>
                                <div id='stat_table'>
                                    <?php $page_elems->getSpecimenCountStatsTable($stat_list); ?>
                                </div>
                          </div>         
                    </div> 
                </div>         
         </div>              
<?php

$script_elems = new ScriptElems();
$script_elems->enableDatePicker();
$script_elems->enableFlotBasic();
$script_elems->enableFlipV();
$script_elems->enableTableSorter();
$script_elems->enableLatencyRecord();
?>
<script type='text/javascript'>
$(window).load(function(){
    $('#stat_graph').hide();
});
function toggle_stat_table()
{
    $('#stat_graph').toggle();
    var linktext = $('#showtablelink').text();
    if(linktext.indexOf("<?php echo LangUtil::$pageTerms['MSG_SHOWGRAPH']; ?>") != -1)
        $('#showtablelink').text("<?php echo LangUtil::$pageTerms['MSG_HIDEGRAPH']; ?>");
    else
        $('#showtablelink').text("<?php echo LangUtil::$pageTerms['MSG_SHOWGRAPH']; ?>");
}
</script>
<?php include("includes/footer.php"); ?>