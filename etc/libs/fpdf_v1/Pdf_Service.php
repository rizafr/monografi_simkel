<?php
    require('fpdf.php');
    //require_once('fpdf.php');
    require_once('color.inc.php');
    require_once('htmlparser.inc.php');
    require_once('fpdi.php');
    class Pdf_Service extends FPDI {

        var $angle=0;    
        var $left;			
        var $right;			
        var $top;			
        var $bottom;		
        var $width;			
        var $height;		
        var $defaultFontFamily ;
        var $defaultFontStyle;
        var $defaultFontSize;
        var $HREF;
        var $I;
        var $U;
        var $B;



        function Pdf_Service($orientation='P',$unit='mm',$format='A4',$param_backgroud = null){
            parent::FPDF($orientation,$unit,$format);
            $this->SetMargins(20,20,20);
            $this->SetAuthor('UJANG JAPRA');
            $this->_makePageSize();
            $this->Header($param_backgroud);
        }

        function SetMargins($left,$top,$right=-1){
            parent::SetMargins($left, $top, $right);

            $this->_makePageSize();
        }

        function SetLeftMargin($margin){
            parent::SetLeftMargin($margin);
            $this->_makePageSize();
        }

        function SetRightMargin($margin){
            parent::SetRightMargin($margin);
            $this->_makePageSize();
        }


        function _makePageSize(){
            if ($this->CurOrientation=='P'){
                $this->left		= $this->lMargin;
                $this->right	= $this->fw - $this->rMargin;
                $this->top		= $this->tMargin;
                $this->bottom	= $this->fh - $this->bMargin;
                $this->width	= $this->right - $this->left;
                $this->height	= $this->bottom - $this->tMargin;
            }else{
                $this->left		= $this->tMargin;
                $this->right	= $this->fh - $this->bMargin;
                $this->top		= $this->rMargin;
                $this->bottom	= $this->fw - $this->rMargin;
                $this->width	= $this->right - $this->left;
                $this->height	= $this->bottom - $this->lMargin;
            }
        }


        function getLineHeight($fontSize = 0){
            if ($fontSize == 0) $fontSize = $this->FontSizePt;
            return ($fontSize/$this->k)+1;
        }


        function countLine($w,$txt){
            //Computes the number of lines a MultiCell of width w will take
            $cw=&$this->CurrentFont['cw'];
            if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
            $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
            $s=str_replace("\r",'',$txt);
            $nb=strlen($s);
            if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
            $sep=-1;
            $i=$j=$l=0;
            $nl=1;
            while($i<$nb){
                $c=$s[$i];
                if($c=="\n"){
                    $i++;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                    continue;
                }
                if($c==' ')
                $sep=$i;
                $l+=$cw[$c];
                if($l>$wmax){
                    if($sep==-1){
                        if($i==$j)
                        $i++;
                    }
                    else
                    $i=$sep+1;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                }
                else
                $i++;
            }
            return $nl;
        }


        function _tableParser(&$html){
            $align = array('left'=>'L','center'=>'C','right'=>'R','top'=>'T','middle'=>'M','bottom'=>'B');
            $t = new TreeHTML(new HTMLParser($html), 0);
            $row	= $col	= -1;
            $table['nc'] = $table['nr'] = 0;
            $table['repeat'] = array();
            $cell   = array();
            foreach ($t->name as $i=>$element){
                if ($t->type[$i] != NODE_TYPE_ELEMENT && $t->type[$i] != NODE_TYPE_TEXT) continue;
                switch ($element){
                    case 'table':
                    $a	= &$t->attribute[$i];
                    if (isset($a['width']))		$table['w']	= intval($a['width']);
                    if (isset($a['height']))	$table['h']	= intval($a['height']);
                    if (isset($a['align']))		$table['a']	= $align[strtolower($a['align'])];
                    $table['border'] = (isset($a['border']))?	$a['border']: 0;
                    if (isset($a['bgcolor']))	$table['bgcolor'][-1]	= $a['bgcolor'];
                    break;
                    case 'tr':
                    $row++;
                    $table['nr']++;
                    $col = -1;
                    $a	= &$t->attribute[$i];
                    if (isset($a['bgcolor']))	$table['bgcolor'][$row]	= $a['bgcolor'];
                    if (isset($a['repeat']))	$table['repeat'][]		= $row;
                    break;
                    case 'td':
                    $col++;while (isset($cell[$row][$col])) $col++;
                    //Update number column
                    if ($table['nc'] < $col+1)		$table['nc']		= $col+1;
                    $cell[$row][$col] = array();
                    $c = &$cell[$row][$col];
                    $a	= &$t->attribute[$i];
                    $c['text'] = array();
                    $c['s']	= 2;
                    if (isset($a['width']))		$c['w']		= intval($a['width']);
                    if (isset($a['height']))	$c['h']		= intval($a['height']);
                    if (isset($a['align']))		$c['a']		= $align[strtolower($a['align'])];
                    if (isset($a['valign']))	$c['va']	= $align[strtolower($a['valign'])];
                    if (isset($a['border']))	$c['border']	= $a['border'];
                    else					$c['border']	= $table['border'];
                    if (isset($a['bgcolor']))	$c['bgcolor']	= $a['bgcolor'];
                    $cs = $rs = 1;
                    if (isset($a['colspan']) && $a['colspan']>1)	$cs = $c['colspan']	= intval($a['colspan']);
                    if (isset($a['rowspan']) && $a['rowspan']>1)	$rs = $c['rowspan']	= intval($a['rowspan']);
                    if (isset($a['size']))		$c['fontSize']   	= $a['size'];
                    if (isset($a['family']))	$c['fontFamily'] 	= $a['family'];
                    if (isset($a['style']))		$c['fontStyle']  	= $a['style'];
                    if (isset($a['color']))		$c['color'] 		= $a['color'];
                    //Chiem dung vi tri de danh cho cell span
                    for ($k=$row;$k<$row+$rs;$k++) for($l=$col;$l<$col+$cs;$l++){
                        if ($k-$row || $l-$col)
                        $cell[$k][$l] = 0;
                    }
                    if (isset($a['nowrap']))	$c['nowrap']= 1;
                    break;
                    case 'Text':
                    $this->_setTextAndSize($c,$this->_html2text($t->value[$i]));
                    break;
                    case 'br':
                    break;
                }
            }
            $table['cells'] = $cell;
            $table['wc']	= array_pad(array(),$table['nc'],array('miw'=>0,'maw'=>0));
            $table['hr']	= array_pad(array(),$table['nr'],0);
            return $table;
        }

        function _setTextAndSize(&$c, $text){
            $c['text'][] = $text;

            $this->_setFontText($c);
            $width =  $this->GetStringWidth($text)+3;
            if (!isset($c['s']) || $c['s'] < $width) $c['s'] = $width;
        }

        function _html2text($text){
            $text = str_replace('&nbsp;',' ',$text);
            $text = str_replace('&lt;','<',$text);
            return $text;
        }


        function _tableColumnWidth(&$table){
            $cs = &$table['cells'];
            $mw = $this->getStringWidth('W');
            $nc = $table['nc'];
            $nr = $table['nr'];
            $listspan = array();
        
            for ($j=0;$j<$nc;$j++){
                $wc = &$table['wc'][$j];
                for ($i=0;$i<$nr;$i++){
                    if (isset($cs[$i][$j]) && $cs[$i][$j]){
                        $c   = &$cs[$i][$j];
                        $miw = $mw;
                        $c['maw']	= $c['s'];
                        if (isset($c['nowrap']))			$miw = $c['maw'];
                        if (isset($c['w'])){
                            if ($miw<$c['w'])	$c['miw'] = $c['w'];
                            if ($miw>$c['w'])	$c['miw'] = $c['w']	  = $miw;
                            if (!isset($wc['w'])) $wc['w'] = 1;
                        }else{
                            $c['miw'] = $miw;
                        }
                        if ($c['maw']  < $c['miw'])			$c['maw']  = $c['miw'];
                        if (!isset($c['colspan'])){
                            if ($wc['miw'] < $c['miw'])		$wc['miw']	= $c['miw'];
                            if ($wc['maw'] < $c['maw'])		$wc['maw']	= $c['maw'];
                        }else $listspan[] = array($i,$j);
                    }
                }
            }
        
            $wc = &$table['wc'];
            foreach ($listspan as $_z=>$span){
                list($i,$j) = $span;
                $c = &$cs[$i][$j];
                $lc = $j + $c['colspan'];
                if ($lc > $nc) $lc = $nc;

                $wis = $wisa = 0;
                $was = $wasa = 0;
                $list = array();
                for($k=$j;$k<$lc;$k++){
                    $wis += $wc[$k]['miw'];
                    $was += $wc[$k]['maw'];
                    if (!isset($c['w'])){
                        $list[] = $k;
                        $wisa += $wc[$k]['miw'];
                        $wasa += $wc[$k]['maw'];
                    }
                }
                if ($c['miw'] > $wis){
                    if (!$wis){
                        for($k=$j;$k<$lc;$k++) $wc[$k]['miw'] = $c['miw']/$c['colspan'];
                    }elseif (!count($list)){
                        $wi = $c['miw'] - $wis;
                        for($k=$j;$k<$lc;$k++)
                        $wc[$k]['miw'] += ($wc[$k]['miw']/$wis)*$wi;
                    }else{
                        $wi = $c['miw'] - $wis;
                        foreach ($list as $_z2=>$k)
                        $wc[$k]['miw'] += ($wc[$k]['miw']/$wisa)*$wi;
                    }
                }
                if ($c['maw'] > $was){
                    if (!$wis){
                        for($k=$j;$k<$lc;$k++) $wc[$k]['maw'] = $c['maw']/$c['colspan'];
                    }elseif (!count($list)){
                        $wi = $c['maw'] - $was;
                        for($k=$j;$k<$lc;$k++)
                        $wc[$k]['maw'] += ($wc[$k]['maw']/$was)*$wi;
                    }else{
                        $wi = $c['maw'] - $was;
                        foreach ($list as $_z2=>$k)
                        $wc[$k]['maw'] += ($wc[$k]['maw']/$wasa)*$wi;
                    }
                }
            }
        }


        function _tableWidth(&$table){
            $wc = &$table['wc'];
            $nc = $table['nc'];
            $a = 0;
            for ($i=0;$i<$nc;$i++){
                $a += isset($wc[$i]['w']) ? $wc[$i]['miw'] : $wc[$i]['maw'];
            }
            if ($a > $this->width) $table['w'] = $this->width;

            if (isset($table['w'])){
                $wis = $wisa = 0;
                $list = array();
                for ($i=0;$i<$nc;$i++){
                    $wis += $wc[$i]['miw'];
                    if (!isset($wc[$i]['w'])){ $list[] = $i;$wisa += $wc[$i]['miw'];}
                }
                if ($table['w'] > $wis){
                    if (!count($list)){
                        //$wi = $table['w'] - $wis;
                        $wi = ($table['w'] - $wis)/$nc;
                        for($k=0;$k<$nc;$k++)
                        //$wc[$k]['miw'] += ($wc[$k]['miw']/$wis)*$wi;
                        $wc[$k]['miw'] += $wi;
                    }else{//Co mot so cot co kich thuoc auto => chia deu phan du cho cac cot auto
                        //$wi = $table['w'] - $wis;
                        $wi = ($table['w'] - $wis)/count($list);
                        foreach ($list as $_z2=>$k)
                        //$wc[$k]['miw'] += ($wc[$k]['miw']/$wisa)*$wi;
                        $wc[$k]['miw'] += $wi;
                    }
                }
                for ($i=0;$i<$nc;$i++){
                    $a = $wc[$i]['miw'];
                    unset($wc[$i]);
                    $wc[$i] = $a;
                }
            }else{
                $table['w'] = $a;
                for ($i=0;$i<$nc;$i++){
                    $a = isset($wc[$i]['w']) ? $wc[$i]['miw'] : $wc[$i]['maw'];
                    unset($wc[$i]);
                    $wc[$i] = $a;
                }
            }
            $table['w'] = array_sum($wc);
        }

        function _tableHeight(&$table){
            $cs = &$table['cells'];
            $nc = $table['nc'];
            $nr = $table['nr'];
            $listspan = array();
            for ($i=0;$i<$nr;$i++){
                $hr = &$table['hr'][$i];
                for ($j=0;$j<$nc;$j++){
                    if (isset($cs[$i][$j]) && $cs[$i][$j]){
                        $c = &$cs[$i][$j];
                        list($x,$cw) = $this->_tableGetWCell($table, $i,$j);

                        $fontSize = (isset($c['fontSize']) && ($c['fontSize'] >0))? $c['fontSize']: 0;

                        $ch = $this->countLine($cw, implode("\n", $c['text'])) * $this->getLineHeight($fontSize);
                        $c['ch'] = $ch;

                        if (isset($c['h']) && $c['h'] > $ch) $ch = $c['h'];

                        if (isset($c['rowspan'])) $listspan[] = array($i,$j);
                        elseif ($hr < $ch)				$hr         = $ch;
                        $c['mih'] = $ch;
                    }
                }
            }
            $hr = &$table['hr'];
            foreach ($listspan as $_z=>$span){
                list($i,$j) = $span;
                $c = &$cs[$i][$j];
                $lr = $i + $c['rowspan'];
                if ($lr > $nr) $lr = $nr;
                $hs = $hsa = 0;
                $list = array();
                for($k=$i;$k<$lr;$k++){
                    $hs += $hr[$k];
                    if (!isset($c['h'])){
                        $list[] = $k;
                        $hsa += $hr[$k];
                    }
                }
                if ($c['mih'] > $hs){
                    if (!$hs){//Cac dong chua co kich thuoc => chia deu
                        for($k=$i;$k<$lr;$k++) $hr[$k] = $c['mih']/$c['rowspan'];
                    }elseif (!count($list)){//Khong co dong nao co kich thuoc auto => chia deu phan du cho tat ca
                        $hi = $c['mih'] - $hs;
                        for($k=$i;$k<$lr;$k++)
                        $hr[$k] += ($hr[$k]/$hs)*$hi;
                    }else{//Co mot so dong co kich thuoc auto => chia deu phan du cho cac dong auto
                        $hi = $c['mih'] - $hsa;
                        foreach ($list as $_z2=>$k)
                        $hr[$k] += ($hr[$k]/$hsa)*$hi;
                    }
                }
            }
            $table['repeatH'] = 0;
            if (count($table['repeat'])){
                foreach ($table['repeat'] as $_z=>$i) $table['repeatH'] += $hr[$i];
            }else $table['repeat'] = 0;
        }

/**
 * @desc Xac dinh toa do va do rong cua mot cell
 */
        function _tableGetWCell(&$table, $i,$j){
            $c = &$table['cells'][$i][$j];
            if ($c){
                if (isset($c['x0'])) return array($c['x0'], $c['w0']);
                $x = 0;
                $wc = &$table['wc'];
                for ($k=0;$k<$j;$k++) $x += $wc[$k];
                $w = $wc[$j];
                if (isset($c['colspan'])){
                    for ($k=$j+$c['colspan']-1;$k>$j;$k--)
                    $w += $wc[$k];
                }
                $c['x0'] = $x;
                $c['w0'] = $w;
                return array($x, $w);
            }
            return array(0,0);
        }

        function _tableGetHCell(&$table, $i,$j){
            $c = &$table['cells'][$i][$j];
            if ($c){
                if (isset($c['h0'])) return $c['h0'];
                $hr = &$table['hr'];
                $h = $hr[$i];
                if (isset($c['rowspan'])){
                    for ($k=$i+$c['rowspan']-1;$k>$i;$k--)
                    $h += $hr[$k];
                }
                $c['h0'] = $h;
                return $h;
            }
            return 0;
        }

        function _tableRect($x, $y, $w, $h, $type=1){
            if (strlen($type)==4)
            {
                $x2 = $x + $w; $y2 = $y + $h;
                if (intval($type{0})) $this->Line($x , $y , $x2, $y );
                if (intval($type{1})) $this->Line($x2, $y , $x2, $y2);
                if (intval($type{2})) $this->Line($x , $y2, $x2, $y2);
                if (intval($type{3})) $this->Line($x , $y , $x , $y2);
            }
            elseif ((int)$type===1)
            $this->Rect($x, $y, $w, $h);
            elseif ((int)$type>1 && (int)$type<11)
            {
                $width = $this->LineWidth;
                $this->SetLineWidth($type * $this->LineWidth);
                $this->Rect($x, $y, $w, $h);
                $this->SetLineWidth($width);
            }
        }

        function _tableDrawBorder(&$table){
            //When fill a cell, it overwrite the border of prevous cell, then I have to draw border at the end
            foreach ($table['listborder'] as $_z=>$c){
                list($x,$y,$w,$h,$s) = $c;
                $this->_tableRect($x,$y,$w,$h,$s);
            }

            $table['listborder'] = array();
        }
        function _tableWriteRow(&$table,$i,$x0){
            $maxh = 0;
            for ($j=0;$j<$table['nc'];$j++){
                $h = $this->_tableGetHCell($table, $i, $j);
                if ($maxh < $h) $maxh = $h;
            }
            if ($table['lasty']+$maxh>$this->bottom && $table['multipage']){
                if ($maxh+$table['repeatH'] > $this->height){				    
                    $msg = 'Height of this row is greater than page height!';
                    //$msg = 'PANJANG TEUINGGGGG!';
                    $h = $this->countLine(0,$msg) * $this->getLineHeight();
                    $this->SetFillColor(255,0,0);
                    $this->Rect($this->x, $this->y=$table['lasty'], $table['w'], $h, 'F');
                    $this->MultiCell($table['w'],$this->getLineHeight(),$msg);
                    $table['lasty'] += $h;
                    return ;
                }
                $this->_tableDrawBorder($table);
                $this->AddPage($this->CurOrientation);

                $table['lasty'] = $this->y;
                if ($table['repeat']){
                    foreach ($table['repeat'] as $_z=>$r){
                        if ($r==$i) continue;
                        $this->_tableWriteRow($table,$r,$x0);
                    }
                }
            }
            $y = $table['lasty'];
            for ($j=0;$j<$table['nc'];$j++){
                if (isset($table['cells'][$i][$j]) && $table['cells'][$i][$j]){
                    $c = &$table['cells'][$i][$j];
                    list($x,$w) = $this->_tableGetWCell($table, $i, $j);
                    $h = $this->_tableGetHCell($table, $i, $j);
                    $x += $x0;
                    //Align
                    $this->x = $x; $this->y = $y;
                    $align = isset($c['a'])? $c['a'] : 'L';
                    //Vertical align
                    $verticalAlign = (!isset($c['va']))? 'T': $c['va'];
                    $verticalAlign = (strpos('TMB', $verticalAlign)=== false)? 'T': $verticalAlign;

                    if ($verticalAlign == 'M')	   $this->y += ($c['mih']>$c['ch'])? ($h-$c['ch'])/2: ($h-$c['mih'])/2;
                    elseif ($verticalAlign == 'B') $this->y += ($c['mih']>$c['ch'])? $h-$c['ch']: $h-$c['mih'];
                    //Fill
                    $fill = isset($c['bgcolor']) ? $c['bgcolor']
                    : (isset($table['bgcolor'][$i]) ? $table['bgcolor'][$i]
                        : (isset($table['bgcolor'][-1]) ? $table['bgcolor'][-1] : 0));
                    if ($fill){
                        $color = Color::HEX2RGB($fill);
                        $this->SetFillColor($color[0],$color[1],$color[2]);
                        $this->Rect($x, $y, $w, $h, 'F');
                    };
                    //Content
                    $f = $this->_setFontText($c);

                    if (isset($c['color']))
                    {
                        $color = Color::HEX2RGB($c['color']);
                        $this->SetTextColor($color[0],$color[1],$color[2]);
                    }else unset($color);

                    $this->MultiCell($w,$this->getLineHeight($f),implode("\n",$c['text']),0,$align);

                    if (isset($color))
                    $this->SetTextColor(0);

                    //Border
                    if (isset($c['border'])){
                        $table['listborder'][] = array($x,$y,$w,$h,$c['border']);
                    }elseif (isset($table['border']) && $table['border'])
                    $table['listborder'][] = array($x,$y,$w,$h,$table['border']);
                }
            }
            $table['lasty'] += $table['hr'][$i];
            $this->y = $table['lasty'];
        }
        function _setFontText(&$c){
            //$count = 0;

            if (isset($c['fontSize']) && ($c['fontSize'] >0)){
                $fontSize   = $c['fontSize'];
                //$count++;
            }else $fontSize   = $this->defaultFontSize;
            if (isset($c['fontFamily'])){
                $fontFamily = $c['fontFamily'];
                //$count++;
            }else $fontFamily = $this->defaultFontFamily;
            if (isset($c['fontStyle'])){
                $STYLE     = explode(",", $c['fontStyle']);
                $fontStyle = '';

                foreach($STYLE AS $si=>$style)  $fontStyle .= strtoupper(substr(trim($style), 0, 1));
                // $count++;
            }else $fontStyle  = $this->defaultFontStyle;

            $this->SetFont($fontFamily, $fontStyle, $fontSize);
            return $fontSize;
        }
        function _tableWrite(&$table){
            //if ($table['w']>$this->width+5)
            //debug($this->CurOrientation,$table['w'],$this->width);
            if ($this->CurOrientation == 'P' && $table['w']>$this->width+5) $this->AddPage('L');
            $x0 = $this->x;
            $y0 = $this->y;
            if (isset($table['a'])){
                if ($table['a']=='C'){
                    $x0 += (($this->right-$x0) - $table['w'])/2;
                }elseif ($table['a']=='R'){
                    $x0 = $this->right - $table['w'];
                }
            }
            $table['lasty'] = $y0;
            $table['listborder'] = array();
            for ($i=0;$i<$table['nr'];$i++) $this->_tableWriteRow($table, $i, $x0);
            $this->_tableDrawBorder($table);
            //echo "<pre>";print_r($table);
        }

        function htmltable(&$html,$multipage=1){
            $a = $this->AutoPageBreak;
			//echo $this->bMargin;
            $this->SetAutoPageBreak(0,$this->bMargin);
            $HTML = explode("<table", $html);
            foreach ($HTML as $i=>$table)
            {
                if (strlen($table)<6) continue;
                $table = '<table' . $table;
                $table = $this->_tableParser($table);
                $table['multipage'] = $multipage;
                $this->_tableColumnWidth($table);
                $this->_tableWidth($table);
                $this->_tableHeight($table);
                $this->_tableWrite($table);
            }
            $this->SetAutoPageBreak($a,$this->bMargin);
        }


        function Footer()
        {
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            //Arial italic 8
            $this->SetFont('Arial','I',8);
            //Text color in gray
            $this->SetTextColor(128);
            //Page number
            $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}' ,0,0,'C');
        }

        function ChapterTitle($num,$label)
        {
            //Arial 12
            $this->SetFont('Arial','',12);
            //Title
            $this->Cell(0,6,"Chapter $num : $label",0,1,'L',1);
            //Line break
            $this->Ln(4);
        }

        function ChapterBody($file)
        {
        
            $this->SetFont('Times','',10);	
            //Line break
            $this->Ln();	
            $this->SetFont('','I');
            $this->Cell(0,5,'(end of excerpt)');
        }


        function hex2dec($couleur = "#000000"){
            $R = substr($couleur, 1, 2);
            $rouge = hexdec($R);
            $V = substr($couleur, 3, 2);
            $vert = hexdec($V);
            $B = substr($couleur, 5, 2);
            $bleu = hexdec($B);
            $tbl_couleur = array();
            $tbl_couleur['R']=$rouge;
            $tbl_couleur['G']=$vert;
            $tbl_couleur['B']=$bleu;
            return $tbl_couleur;
        }

        //conversion pixel -> millimeter in 72 dpi
        function px2mm($px){
            return $px*25.4/72;
        }

        function txtentities($html){
            $trans = get_html_translation_table(HTML_ENTITIES);
            $trans = array_flip($trans);
            return strtr($html, $trans);
        }
        function WriteHTML2($html)
        {
            //HTML parser
            $html=str_replace("\n",' ',$html);
            $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
            foreach($a as $i=>$e)
            {
                if($i%2==0)
                {
                    //Text
                    if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                    else
                    $this->Write(5,$e);
                }
                else
                {
                    //Tag
                    if($e{0}=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                    else
                    {
                        //Extract attributes
                        $a2=explode(' ',$e);
                        $tag=strtoupper(array_shift($a2));
                        $attr=array();
                        foreach($a2 as $v)
                        if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                        $this->OpenTag($tag,$attr);
                    }
                }
            }
        }

        function OpenTag($tag,$attr)
        {
            //Opening tag
            switch($tag){
                case 'STRONG':
                $this->SetStyle('B',true);
                break;
                case 'EM':
                $this->SetStyle('I',true);
                break;
                case 'B':
                case 'I':
                case 'U':
                $this->SetStyle($tag,true);
                break;
                case 'A':
                $this->HREF=$attr['HREF'];
                break;
                case 'IMG':
                if(isset($attr['SRC']) and (isset($attr['WIDTH']) or isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;

                    //-----------add image position----------------
                    if(isset($attr['ALIGN'])){
                        if($attr['ALIGN'] != 'left' || $attr['ALIGN'] != 'LEFT'){
                            $posisi = $attr['ALIGN'];
                            $xpos = 0;
                            $lebar = $attr['WIDTH'];
                            switch(strtoupper($posisi{0}))
                            {
                                case "C":
                                $xpos = 205-$lebar ;
                                break;
                                case "R":
                                $xpos =270-$lebar ;
                                //echo "masuk R".$xpos."- ".$lebar;
                                break;
                                default: break;
                            }

                        }
                        $this->Image($attr['SRC'], ($this->GetX()+$xpos), $this->GetY(), $this->px2mm($attr['WIDTH']), $this->px2mm($attr['HEIGHT']));
                        $this->widthImage = $xpos;
                        $this->heightImage=$attr['HEIGHT'];
                    }else{
                        $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), $this->px2mm($attr['WIDTH']), $this->px2mm($attr['HEIGHT']));

                    }
                }
                break;

                case 'BLOCKQUOTE':
                case 'BR':
                $this->Ln();
                break;
                case 'P':
                $this->Ln(2);
                break;
                case 'FONT':
                if (isset($attr['COLOR']) and $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
            }
        }

        function CloseTag($tag)
        {
            //Closing tag

            if($tag=='STRONG')
            $tag='B';
            if($tag=='EM')
            $tag='I';
            if($tag=='B' or $tag=='I' or $tag=='U')
            $this->SetStyle($tag,false);
            if($tag=='A')
            $this->HREF='';
            if($tag=='TH') $this->SetStyle('B',false);
            if($tag=='TH' or $tag=='TD') $this->tdbegin = false;
            if($tag=='FONT'){
                if ($this->issetcolor==true) {
                    $this->SetTextColor(0);
                }
                if ($this->issetfont) {
                    $this->SetFont('arial');
                    $this->issetfont=false;
                }
            }
        }

        function SetStyle($tag,$enable)
        {
            //Modify style and select corresponding font
            $this->$tag+=($enable ? 1 : -1);
            $style='';
            foreach(array('B','I','U') as $s)
            if($this->$s>0)
            $style.=$s;
            $this->SetFont('',$style);
        }

        function PutLink($URL,$txt)
        {
            //Put a hyperlink
            $this->SetTextColor(0,0,255);
            $this->SetStyle('U',true);
            $this->Write(5,$txt,$URL);
            $this->SetStyle('U',false);
            $this->SetTextColor(0);
        }

        function WriteTable($data,$w)
        {
            $this->SetLineWidth(.3);
            $this->SetFillColor(255,255,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            foreach($data as $row)
            {
                $nb=0;
                for($i=0;$i<count($row);$i++)
                $nb=max($nb,$this->NbLines($w[$i],trim($row[$i])));
                $h=5*$nb;
                $this->CheckPageBreak($h);
                for($i=0;$i<count($row);$i++)
                {
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->Rect($x,$y,$w[$i],$h);
                    $this->MultiCell($w[$i],5,trim($row[$i]),0,'C');
                    //Put the position to the right of the cell
                    $this->SetXY($x+$w[$i],$y);					
                }
                $this->Ln($h);

            }
        }

        function NbLines($w,$txt)
        {
            //Computes the number of lines a MultiCell of width w will take
            $cw=&$this->CurrentFont['cw'];
            if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
            $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
            $s=str_replace("\r",'',$txt);
            $nb=strlen($s);
            if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
            $sep=-1;
            $i=0;
            $j=0;
            $l=0;
            $nl=1;
            while($i<$nb)
            {
                $c=$s[$i];
                if($c=="\n")
                {
                    $i++;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                    continue;
                }
                if($c==' ')
                $sep=$i;
                $l+=$cw[$c];
                if($l>$wmax)
                {
                    if($sep==-1)
                    {
                        if($i==$j)
                        $i++;
                    }
                    else
                    $i=$sep+1;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                }
                else
                $i++;
            }
            return $nl;
        }

        function CheckPageBreak($h)
        {
            //If the height h would cause an overflow, add a new page immediately
            if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
        }

        function ReplaceHTML($html)
        {
            $html = str_replace( '<li>', "\n<br> - " , $html );
            $html = str_replace( '<LI>', "\n - " , $html );
            $html = str_replace( '</ul>', "\n\n" , $html );
            $html = str_replace( '<strong>', "<b>" , $html );
            $html = str_replace( '</strong>', "</b>" , $html );
            $html = str_replace( '&#160;', "\n" , $html );
            $html = str_replace( '&nbsp;', " " , $html );
            $html = str_replace( '&quot;', "\"" , $html ); 
            $html = str_replace( '&#39;', "'" , $html );
            return $html;
        }

        function ParseTable($Table)
        {
            $_var='';
            $htmlText = $Table;
            $parser = new HtmlParser ($htmlText);
            while ($parser->parse()) {
                if(strtolower($parser->iNodeName)=='table')
                {
                    if($parser->iNodeType == NODE_TYPE_ENDELEMENT)
                    $_var .='/::';
                    else
                    $_var .='::';
                }

                if(strtolower($parser->iNodeName)=='tr')
                {
                    if($parser->iNodeType == NODE_TYPE_ENDELEMENT)
                    $_var .='!-:'; //opening row
                    else
                    $_var .=':-!'; //closing row
                }
                if(strtolower($parser->iNodeName)=='td' && $parser->iNodeType == NODE_TYPE_ENDELEMENT)
                {
                    $_var .='#,#';
                }
                if ($parser->iNodeName=='Text' && isset($parser->iNodeValue))
                {
                    $_var .= $parser->iNodeValue;
                }
            }
            $elems = split(':-!',str_replace('/','',str_replace('::','',str_replace('!-:','',$_var)))); //opening row
            foreach($elems as $key=>$value)
            {
                if(trim($value)!='')
                {
                    $elems2 = split('#,#',$value);
                    array_pop($elems2);
                    $data[] = $elems2;
                }
            }
            return $data;
        }

        function WriteHTML($html)
        {
            $html = $this->ReplaceHTML($html);
            //echo $html;
            $start = strpos(strtolower($html),'<table');			
            $end = strpos(strtolower($html),'</table');			
            if($start !== false && $end !== false)
            {//echo"TES1";
                $this->htmltable($html);
            }
            else
            {//echo"TES2";
                $this->WriteHTML2($html);
            }
        }

   

                
                
        function Header()
        {
            $this->_makePageSize();
                        
        }

                
  
    }



?>