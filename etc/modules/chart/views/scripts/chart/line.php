<?
include 'charts.php';
require_once "../../portalconf.php";
require_once "libs/StockBUMNServices/StockBUMNPortalServices.php"; 



// $sCode=$_GET['sCode'];
// $iBUMN=$_GET['iBUMN'];

 $sCode="MYRX";
 $iBUMN="BNI";


 $Open = array();
 $Open[0]= "Stock Code : ".$sCode;
 //$Open[0]= "Open";
 $Date = array();
 $Date[0]= '';
 //$Close = array();
 //$Close[0]= "Close";
 $i=0;
 
 $void = getStockHistoryBUMN($sCode,$iBUMN);
 $outPutList=new StockBUMN();
 $i=0;	
 foreach ($void as $tt) { 	
 	$outPutList=unserialize($tt);
 	$Opening=$outPutList->getOpening();
        $Closing=$outPutList->getClosing();
        $Dat=$outPutList->getDateStock();
        $i++;
        $Open[$i]=$Opening;
        $Date[$i]=$Dat;
        $Close[$i]=$Closing;
     
        
}
/*
echo $i;
for ( $j=0; $j <= $i; $j++ ) {
echo "open".$Open[$j]."<br>";
echo "date".$Date[$j];
*/

$arrayline=array($Date,$Open );                           

$chart[ 'chart_data' ] = $arrayline;

$chart[ 'license' ] = "H1SS3CCOSIBZ21583VIS.G7SAVZ8-K";
$chart[ 'chart_type' ] = "Line";
$arrayClose=array($Close);
$chart [ 'link_data' ] = array (   'url'     =>  "javascript:display_info($arrayClose)", 
                                   'target'  =>  "javascript"
                               ); 
                              
$chart[ 'axis_category' ] = array (  'size'=>12, 'color'=>"000000", 'alpha'=>50, 'orientation'=>"horizontal" ); 
$chart[ 'axis_ticks' ] = array ( 'value_ticks'=>false, 'category_ticks'=>true, 'major_thickness'=>2, 'minor_thickness'=>1, 'minor_count'=>1, 'major_color'=>"000000", 'minor_color'=>"222222" ,'position'=>"inside" );
$chart[ 'axis_value' ] = array ('size'=>12, 'color'=>"000000", 'alpha'=>50,  'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'show_min'=>true );

$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1 );
$chart[ 'chart_pref' ] = array ( 'line_thickness'=>2, 'point_shape'=>"circle", 'fill_shape'=>false );
$chart[ 'chart_rect' ] = array ( 'x'=>50, 'y'=>100, 'width'=>600, 'height'=>200, 'positive_color'=>"ffffff", 'positive_alpha'=>100, 'negative_color'=>"000000", 'negative_alpha'=>10 );
$chart[ 'chart_transition' ] = array ( 'type'=>"slide_left", 'delay'=>.5, 'duration'=>.5, 'order'=>"series" );
$chart[ 'chart_type' ] = "Line";
$chart[ 'chart_value' ] = array ( 'position'=>"cursor", 'size'=>12, 'color'=>"000000", 'decimals'=>3, 'background_color'=>"aaff00", 'alpha'=>80 );

//$chart [ 'axis_value_text' ] = array ( "Opening", null, null, null, null,null );

$chart[ 'draw' ] = array ( array ( 'transition'=>"dissolve", 'delay'=>0, 'duration'=>.5, 'type'=>"text", 'color'=>"000000", 'alpha'=>50, 'font'=>"Arial", 'rotation'=>0, 'bold'=>true, 'size'=>26, 'x'=>18, 'y'=>7, 'width'=>600, 'height'=>50, 'text'=>"Stock History BUMN", 'h_align'=>"center", 'v_align'=>"bottom" ) );

$chart[ 'legend_label' ] = array ( 'layout'=>"horizontal", 'bullet'=>"line", 'font'=>"arial", 'bold'=>true, 'size'=>13, 'color'=>"000000", 'alpha'=>65 ); 
$chart[ 'legend_rect' ] = array ( 'x'=>50, 'y'=>75, 'width'=>600, 'height'=>5, 'margin'=>5, 'fill_color'=>"8844FF", 'fill_alpha'=>27, 'line_color'=>"000000", 'line_alpha'=>0, 'line_thickness'=>0 ); 
$chart[ 'legend_transition' ] = array ( 'type'=>"dissolve", 'delay'=>0, 'duration'=>.5 );

$chart[ 'series_color' ] = array ( "ff4444", "ffff00", "8844ff" ); 

$chart [ 'series_explode' ] = array ( 400 );

SendChartData ( $chart );

 ?>
