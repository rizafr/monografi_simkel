<?


if (!($GLOBALS['FORM_POLL']))  {
   include '../portalconf.php';
   require_once "libs/StockBUMNServices/StockBUMNPortalServices.php";
}else {
  require_once "libs/StockBUMNServices/StockBUMNPortalServices.php";
}



 $sCode=$_GET['sCode'];
 $iBUMN=$_GET['iBUMN'];
// $sCode = "MYRX";
// $iBUMN = "BNI";

 $Open = array();
 $Open[0]= "Stock Code : ".$sCode;
 //$Open[0]= "Open";
 $Date = array();
 $Date[0]= '';
 //$Close = array();
 //$Close[0]= "Close";
 $i=0;
 
 $void = getStockHistoryBUMN($sCode,$iBUMN);
 $outPutList=new StockBUMN2();
 $i=0;	

 foreach ($void as $tt) { 	
 	$outPutList=unserialize($tt);
 	//$Opening=$outPutList->getOpening();
       // $Closing=$outPutList->getClosing();
       // $Dat=$outPutList->getDateStock();

 	$Opening=$outPutList->getLastValue();
       $Closing=$outPutList->getLastVolume();
       $Dat=$outPutList->getDateStock();

        $i++;
        $Open[$i]=$Opening;
        $Date[$i]=$Dat;
        $Close[$i]=$Closing;
     
        
}

include 'charts.php';

$arrayline=array($Date,$Open );                           

$chart[ 'chart_data' ] = $arrayline;
$chart[ 'license' ] = "H1SS3CCOSIBZ21583VIS.G7SAVZ8-K";
$chart[ 'chart_type' ] = "Line";


$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1 );
$chart[ 'chart_pref' ] = array ( 'line_thickness'=>1, 'point_shape'=>"circle", 'fill_shape'=>false );


$chart [ 'chart_rect' ] = array ( 'x'=>50,
                                  'y'=>100,
                                  'width'=>550,
                                  'height'=>250,
                                  'positive_color'  =>  "ffffff",
                                  'negative_color'  =>  "000000",
                                  'positive_alpha'  =>  100,
                                  'negative_alpha'  =>  1000
                                );

//$chart[ 'chart_rect' ] = array ( 'x'=>50, 'y'=>100, 'width'=>600, 'height'=>300, 'positive_color'=>"ffffff", 'positive_alpha'=>100, 'negative_color'=>"000000", 'negative_alpha'=>10 );
									 
$chart[ 'chart_transition' ] = array ( 'type'=>"zoom", 
                                       'delay'=>.5, 
					     'duration'=>.5, 
						  'order'=>"series" );

$chart[ 'chart_value' ] = array ( 'position'=>"cursor", 
								  'size'=>9, 
								  'color'=>"000000", 
								  'decimals'=>3, 
								  'background_color'=>"aaff00", 
								  'alpha'=>80 );

$chart [ 'axis_category' ] = array (   'font'         =>  "Arial", 
                                       'bold'         =>  true, 
                                       'size'         =>  9, 
                                       'color'        =>  "000000", 
                                       'alpha'        =>  80,
                                       'orientation'  =>  "diagonal_down",
					     'margin'        =>  true,
					     'steps'         =>  1,
                                   ); 


$chart [ 'axis_ticks' ] = array (   'value_ticks'      =>  true, 
                                    'category_ticks'   =>  true, 
                                    'position'         =>  "centered", 
                                    'major_thickness'  =>  2, 
                                    'major_color'      =>  "000000", 
                                    'minor_thickness'  =>  1, 
                                    'minor_color'      =>  "222222",
                                    'minor_count'      =>  3
                                ); 
								
$chart [ 'axis_value' ] = array (   'min'           =>  0,  
                                    'max'           =>  1000, 
                                    'steps'         =>  5,  
                                    'prefix'        =>  "", 
                                    'suffix'        =>  "", 
                                    'decimals'      =>  0,
                                    'decimal_char'  =>  ".",
                                    'separator'     =>  "", 
                                    'show_min'      =>  true, 
                                    'font'          =>  "Arial", 
                                    'bold'          =>  true, 
                                    'size'          =>  9, 
                                    'color'         =>  "000000", 
                                    'alpha'         =>  75
                                   );

$chart [ 'chart_border' ] = array (   'top_thickness'     =>  0,
                                      'bottom_thickness'  =>  3,
                                      'left_thickness'    =>  3,
                                      'right_thickness'   =>  0,
                                      'color'             =>  "222222"
                                   );



$chart[ 'legend_label' ] = array ( 'layout'=>"horizontal", 
								   'bullet'=>"circle", 
								   'font'=>"arial", 
								   'bold'=>true, 
								   'size'=>9, 
								   'color'=>"000000", 
								   'alpha'=>85 ); 
								   
$chart[ 'legend_rect' ] = array ( 'x'=>50, 
                                  'y'=>75, 
								  'width'=>600, 
								  'height'=>5, 
								  'margin'=>5, 
								  'fill_color'=>"8844FF", 
								  'fill_alpha'=>27, 
								  'line_color'=>"000000", 
								  'line_alpha'=>0, 
								  'line_thickness'=>0 ); 
								  
$chart[ 'legend_transition' ] = array ( 'type'=>"dissolve", 
										'delay'=>0, 
										'duration'=>.5 );
										
$chart[ 'draw' ] = array ( array ( 'transition'=>"dissolve",
									 'delay'=>0, 
									 'duration'=>.5, 
									 'type'=>"text", 
									 'color'=>"000000", 
									 'alpha'=>50, 
									 'font'=>"Arial", 
									 'rotation'=>0, 
									 'bold'=>true, 
									 'size'=>26, 
									 'x'=>18, 
									 'y'=>7, 
									 'width'=>550, 
									 'height'=>50, 
									 'text'=>"Stock History", 
									 'h_align'=>"center", 
									 'v_align'=>"bottom" ) );
$chart[ 'series_color' ] = array ( "ff4444", "ffff00", "8844ff" ); 
$chart [ 'series_explode' ] = array ( 400 );

SendChartData ( $chart );


?>
