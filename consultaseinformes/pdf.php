<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../connect.php");
require_once('../FPDI/src/autoload.php');
require_once('../fpdf/fpdf.php');

$desde          = date("Y/m/d", strtotime($_GET['desde']))." 00:00:00";
if($_GET['hasta'] == "3019/01/01"){
  $hasta = "3019/01/01 23:59:59";
}else{
  $hasta = date("Y/m/d", strtotime($_GET['hasta']))." 23:59:59";
}
//$hasta          = date("Y/m/d", strtotime($_GET['hasta']))." 23:59:59";
$ing_env        = $_GET['ing_env'];

$query = "SELECT f03.nro_mesaent Nro,f01.fecha Fecha,f01.asunto Asunto,fent.razon Remitente,fseg.usuario 'Recibido Por',if(fpar.pardesc is null,' ',fpar.pardesc)Tipo
  FROM `fil01mail` f01
  inner join fil03mail f03 on f01.id_mail = f03.id_mail
  inner join fil01ent fent on fent.codigo = f01.cod_entidad
  inner join fil01seg fseg on fseg.nrousuario = f03.quienderi
  left join fil00par fpar on fpar.parcod = 7 and fpar.parvalor = f03.tipo_docum
  WHERE f01.tipo_ingreso = 'P' AND 
     (f01.fecha >= '$desde' and f01.fecha <= '$hasta') AND
     f03.ing_env = '$ing_env'
     ORDER BY Nro desc";

$sql = mysqli_query($con, $query);

class PDF_HTML extends FPDF
{
    var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';

    function WriteHTML($html)
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
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                elseif($this->ALIGN=='right')
                    $this->Cell(435,5,$e,0,1,'C');
                else
                    $this->Write(5,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }

    function OpenTag($tag,$prop)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
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

    function Header()
    {
      $desde = $_GET['desde'];
      
      $this->SetFont('Arial','B',12);
      //Cabezera
      $this->Image('../images/logo.png',10,8,33);
      $this->WriteHTML('<p align="center">Colegio de Ingenieros Especialistas de Santa Fe - Distrito II</p>');
      $this->SetFont('Arial','',9);
      $this->WriteHTML('<p align="center">Listado de Correspondencia Recibida.</p>');
      $formato_desde = date("d/m/Y", strtotime($desde));
      if($_GET['hasta'] == "3019/01/01"){
        $formato_hasta = "01/01/3019";
      }else{
        $formato_hasta = date("d/m/Y", strtotime($_GET['hasta']));
      }
      $this->WriteHTML('<p align="center">'.$formato_desde.' al '.$formato_hasta.'</p><br><br>');
      $this->SetFont('Arial','B',10);
      $this->SetFillColor(192, 192, 192);

      //Nro
      $this->Cell(15,4,"Nro",0,0, '', True);
      $this->Cell(2,2,"",0,0, '', FALSE);

      //Fecha
      $this->Cell(21,4,"Fecha",0,0, '', True);
      $this->Cell(2,2,"",0,0, '', FALSE);

      //Asunto
      $this->Cell(90,4,"Asunto",0,0, '', True);
      $this->Cell(2,2,"",0,0, '', FALSE);

      //Remitente
      $this->Cell(90,4,"Remitente",0,0, '', True);
      $this->Cell(2,2,"",0,0, '', FALSE);

      //Recibido Por
      $this->Cell(25,4,"Recibido Por",0,0, '', True);
      $this->Cell(2,2,"",0,0, '', FALSE);

      //Tipo
      $this->Cell(25,4,"Tipo",0,0, '', True);
      $this->Cell(2,2,"",0,0, '', FALSE);

      //Salto de ringlon
      $this->WriteHTML('<br>');
    }

    function Footer()
    {
      $this->SetY(-13);
      $this->WriteHTML('<hr />');
      $this->WriteHTML('Fecha: '.date('d/m/Y H:i:s A').' <p align="right">Hoja Nro: '.$this->PageNo().'</p>');
     
    }
}


$pdf=new PDF_HTML('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
//$re2 = mysqli_fetch_array($sql);
//error_log("$desde,$hasta,$ing_env");
//error_log("".$query."");
while ($re = mysqli_fetch_array($sql)) {

  //Nro
  $pdf->Cell(15,4,''.$re['Nro'].'',0,0, '', FALSE);
  $pdf->Cell(2,2,"",0,0, '', FALSE);

  //Fecha
  $fecha = substr($re['Fecha'],0,-9);
  $formato = date("d/m/Y", strtotime($fecha));
  $pdf->Cell(21,4,''.$formato.'',0,0, '', FALSE);
  $pdf->Cell(2,2,"",0,0, '', FALSE);

  //Asunto
  $pdf->Cell(90,4,''.$re['Asunto'].'',0,0, '', FALSE);
  $pdf->Cell(2,2,"",0,0, '', FALSE);

  //Remitente
  $pdf->Cell(90,4,''.$re['Remitente'].'',0,0, '', FALSE);
  $pdf->Cell(2,2,"",0,0, '', FALSE);

  //Recibido Por
  $pdf->Cell(25,4,''.$re['Recibido Por'].'',0,0, '', FALSE);
  $pdf->Cell(2,2,"",0,0, '', FALSE);

  //Tipo
  $pdf->Cell(25,4,''.$re['Tipo'].'',0,0, '', FALSE);
  $pdf->Cell(2,2,"",0,0, '', FALSE);

  //salto de ringlon
  $pdf->WriteHTML('<br>');
}

$pdf->Output();
?>