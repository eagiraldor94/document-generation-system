<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use ludcis;

use Carbon\Carbon;

use Mpdf\Mpdf;

use Mail;

use QrCode;

class ControladorDocumentos extends Controller
{
    public function pdfPagare(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        // if ($document->document_state == 1) {
        //   return redirect('/');        
        // }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
    		date_default_timezone_set('America/Bogota');
        $valorLetras = ludcis\NumeroALetras::convertir($datos['newAmount'], 'pesos colombianos', 'centavos');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $fechaFin = Carbon::createFromFormat('d/m/Y',$datos['newPaymentDate']);
        // $meses = floor($hoy->diffInMonths($fechaFin));
        // $interes = $datos['newAmount']*$meses*$datos['newInterest']/100;
        // $total = $interes + $datos['newAmount'];
        // $valorLetrasTotal = ludcis\NumeroALetras::convertir($total, 'pesos colombianos', 'centavos');
        // $valorLetrasTotal .=' (COP).';
        switch ($datos['newFeesType']) {
          case "Semanal":
            $fecha = $hoy->copy()->addWeek();
            $i = 1;
            while ($fecha <= $fechaFin) {
                $datos['newFeesNumber']=$i;
                $key = 'newPaymentDate'.$i;
                $datos[$key]=$fecha->copy()->format('d/m/Y');
                $i++;
                $fecha->addWeek();
            }
            break;
          case "Mensual":
            $fecha = $hoy->copy()->addMonth();
            $i = 1;
            while ($fecha <= $fechaFin) {
                $datos['newFeesNumber']=$i;
                $key = 'newPaymentDate'.$i;
                $datos[$key]=$fecha->copy()->format('d/m/Y');
                $i++;
                $fecha->addMonth();
            }
            break;
          case "Cada 2 semanas":
            $fecha = $hoy->copy()->addWeeks(2);
            $i = 1;
            while ($fecha <= $fechaFin) {
                $datos['newFeesNumber']=$i;
                $key = 'newPaymentDate'.$i;
                $datos[$key]=$fecha->copy()->format('d/m/Y');
                $i++;
                $fecha->addWeeks(2);
            }
            break;
          case "Cada 15 días":
            $fecha = $hoy->copy()->addDays(15);
            $i = 1;
            while ($fecha <= $fechaFin) {
                $datos['newFeesNumber']=$i;
                $key = 'newPaymentDate'.$i;
                $datos[$key]=$fecha->copy()->format('d/m/Y');
                $i++;
                $fecha->addDays(15);
            }
            break;
          case "Cada 30 días":
            $fecha = $hoy->copy()->addDays(30);
            $i = 1;
            while ($fecha <= $fechaFin) {
                $datos['newFeesNumber']=$i;
                $key = 'newPaymentDate'.$i;
                $datos[$key]=$fecha->copy()->format('d/m/Y');
                $i++;
                $fecha->addDays(30);
            }
            break;
          
          default:
            // code...
            break;
        }
        if (!isset($datos['newFeesMix'])) {
            $datos['newFeesMix']='intereses';
        }
    	$qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
      $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row{
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:20px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:18px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 20px;
    margin-top: 35px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-top: 45px;
}
.m4{
    margin-bottom: 20px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body>

<div class="row m1" style="text-align:center;">
  <span class="font-size"><b>CARTA DE INSTRUCCIONES</b></span>
</div>
<div class="row m4" style="text-align:left;">
  <span class="font-size2">Señor(a):<br><b>'.mb_strtoupper($datos['newCreditor']).'</b><br>Ciudad '.ucfirst($datos['newCreditCity']).'</span>
</div>
<div class="row m4" style="text-align:left;">
  <span class="font-size3">REFERENCIA: <b>AUTORIZACIÓN EXPRESA PARA EL LLENO DE LOS ESPACIOS EN BLANCO DEL PAGARÉ No. '.$document->id.'</b></span>
</div><div class="row m2" style="text-align:justify;">
  <span class="font-size2">Yo, <b>'.mb_strtoupper($datos['newDebtor']).'</b>, mayor de edad y residente de la ciudad de '.ucfirst($datos['newDebtorCity']).', identificado (a) con <b>'.$datos['newDebtorIdType'].'</b> No. <b>'.$datos['newDebtorId'].'</b>';
  if (isset($datos['newDebtorExpedition']) && $datos['newDebtorExpedition'] != '' && $datos['newDebtorExpedition'] != null) {
    $html.=' expedida en el municipio de <b>'.mb_strtoupper($datos['newDebtorExpedition']).'</b>';
  }
  $html.=', actuando en nombre propio, por medio del presente escrito manifiesto que le faculto a usted, de manera permanente e irrevocable, para que, en caso de incumplimiento del compromiso de pago oportuno de alguna de las obligaciones que he adquirido con usted, mismas derivadas de los negocios ya sea comerciales y contractuales, bien o a bien se hayan dado de manera verbal o escrita; sin previo aviso, proceda a llenar los espacios en blanco del pagaré No. '.$document->id.', que he suscrito en la fecha '.$hoy->format('d').' de '.$mes.' del año '.$hoy->format('Y').' a su favor y que se anexa, esto con el fin, de convertir el pagare, en un documento que presta merito ejecutivo y que está sujeto a los parámetros legales da la luz del Artículo 622 del Código de Comercio.</span>
</div>
<div class="row m2" style="text-align:justify;"><span class="font-size2">
    <ol>
      <li>El espacio correspondiente a “la suma cierta de” se rellenará por una suma igual a la que resulte pendiente de pago de todas las obligaciones contraídas con el acreedor, por concepto de capital, intereses, seguros, cobranza extrajudicial, según la contabilidad del acreedor a la fecha en que sea llenado el pagare.</li>
      <li>El espacio correspondiente a “la fecha” en que se debe hacer el pago, se llenara con la fecha correspondiente al día en que sea llenado el pagaré, fecha que se entiende que es la de su vencimiento.</li>
      </ol>
  </span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En constancia y a raíz de lo anterior, se firma la autorización expresa para el lleno de los espacios en blanco del pagare <b>No. '.$document->id.'</b> a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<div class="m3">
  <table>
    <tr>
      <td>
      <div class="row">
        <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
        <span class="font-size2 m4"><b>EL DEUDOR,</b></span><br>
        <span class="font-size3"><b>'.mb_strtoupper($datos['newDebtor']).'</b><br>
        <b>'.$datos['newDebtorIdType'].'</b> No.<b>'.$datos['newDebtorId'].'</b>';
        if (isset($datos['newDebtorExpedition']) && $datos['newDebtorExpedition'] != '' && $datos['newDebtorExpedition'] != null) {
           $html.=' de <b>'.mb_strtoupper($datos['newDebtorExpedition']).'</b>';
         } 
         $html.='</span>
      </td>
    </tr>
  </table>
</div>
<pagebreak>

<div class="row m1" style="text-align:center;">
	<span class="font-size"><b>PAGARÉ</b></span>
</div>
<div class="row m4" style="text-align:right;">
  <span class="font-size3"><b>PAGARÉ No. '.$document->id.'</b></span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2">Yo, <b>'.mb_strtoupper($datos['newDebtor']).'</b>, mayor de edad y residente de la ciudad de '.ucfirst($datos['newDebtorCity']).', identificado (a) con <b>'.$datos['newDebtorIdType'].'</b> No. <b>'.$datos['newDebtorId'].'</b>';
  if (isset($datos['newDebtorExpedition']) && $datos['newDebtorExpedition'] != '' && $datos['newDebtorExpedition'] != null) {
     $html.=' expedida en el municipio de <b>'.mb_strtoupper($datos['newDebtorExpedition']).'</b>';
   } 
   $html.=', actuando en nombre propio, por medio del presente escrito manifiesto, lo siguiente: </span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>PRIMERO:</u></b> Que debo y pagare, de manera incondicional y solidariamente, a la orden de (el) (la) señor (a) <b>'.mb_strtoupper($datos['newCreditor']).'</b>, identificado (a) con '.$datos['newCreditorIdType'].' No. <b>'.$datos['newCreditorId'].'</b>';
  if (isset($datos['newCreditorExpedition']) && $datos['newCreditorExpedition'] != '' && $datos['newCreditorExpedition'] != null) {
     $html.=' expedida en el municipio de <b>'.mb_strtoupper($datos['newCreditorExpedition']).'</b>';
   } 
   $html.=', o en su defecto, a la persona ya sea natural o jurídica, a quien (el) (la) mencionado (a) acreedor (a) (el (la) señor (a) <b>'.mb_strtoupper($datos['newCreditor']).'</b>), ceda o endose sus derechos sobre este pagaré, la suma real y cierta de: <span style="font-family:sans-serif">$____________________________</span> <b>M/L</b>. mismos que derivan de préstamo a título personal de $ '.number_format($datos['newAmount'],2).' COP ('.$valorLetras.'), con fines de libre inversión. En dicho importe, se encuentran sumados los intereses del préstamo, en favor del (la) señor (a) <b>'.mb_strtoupper($datos['newCreditor']).'</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>SEGUNDO:</u></b> Que el pago total de la citada obligación se efectuara en ';
    	if (isset($datos['newFeesNumber']) && $datos['newFeesNumber']>1) {
    		$html .='un total de '.$datos['newFeesNumber'].' cuotas de la siguiente manera: </span>';
    	}else{
    		$html .='una sola cuota o contado de la siguiente manera: </span><br>';
    	}
$html .= '
	<span class="font-size2">
		<ul>
			<li>';
    	if (isset($datos['newFeesNumber']) && $datos['newFeesNumber']>1) {
    		$html .='El '.substr($fechaFin,0,10).', habré pagado en favor del acreedor o a quién este último, ceda o endose sus derechos sobre este pagaré, la suma cierta de: <span style="font-family:sans-serif">$____________________________</span> mismos que derivan del préstamo para libre inversión, en la ciudad de <b>'.ucfirst($datos['newCreditCity']).'</b>, ';
    		if($datos['newPaymentType']=='Deposito'){
    			$html .='en la '.$datos['newPaymentAccount'].' <b>No.</b> '.$datos['newPaymentNumber'].' del banco '.$datos['newPaymentBank'].'en favor y a nombre del suscrito acreedor.';
    		}else{
    			$html .='en efectivo a favor del suscrito acreedor';
    		}
    	}else{
    		$html .='El '.substr($fechaFin,0,10).', pagaré en favor del acreedor o a quién este último, ceda o endose sus derechos sobre este pagaré, la suma cierta de: <span style="font-family:sans-serif">$____________________________</span> mismos que derivan del préstamo para libre inversión, en la ciudad de <b>'.ucfirst($datos['newCreditCity']).'</b>, ';
    		if($datos['newPaymentType']=='Deposito'){
    			$html .='en la '.$datos['newPaymentAccount'].' <b>No.</b> '.$datos['newPaymentNumber'].' del banco '.$datos['newPaymentBank'].'en favor y a nombre del suscrito acreedor.';
    		}else{
    			$html .='en efectivo a favor del suscrito acreedor';
    		}
    	}
$html .= '</li>';
    	if (isset($datos['newFeesNumber']) && $datos['newFeesNumber']>1) {
	    	for ($i = 1; $i <= $datos['newFeesNumber'] ; $i++) {
	    		$key = 'newPaymentDate'.$i;
	    		$html .= '<li>
	    			El pago número '.$i.' se realizará la fecha '.'<b>'.$datos[$key].'</b>'.'
	    		</li>';
	    	}
    	}
    	$html .= '<li>Los abonos correspondientes a '.$datos['newFeesMix'].' serán causados mensualmente, estos, se tendrán un valor del '.$datos['newInterest'].'% de interés efectivo mensual, sobre el saldo de la deuda y deberán ';
		if($datos['newPaymentType']=='Deposito'){
			$html .='consignarse en la '.$datos['newPaymentAccount'].' <b>No.</b> '.$datos['newPaymentNumber'].' del banco '.$datos['newPaymentBank'].'en favor y a nombre del suscrito acreedor, ';
		}else{
			$html .='pagarse en efectivo a favor del suscrito acreedor, ';
		}
    	$html .= 'o en su defecto, a la persona ya sea natural o jurídica, a quien el (la) mencionado (a) acreedor (a) el (la) señor (a) <b>'.mb_strtoupper($datos['newCreditor']).'</b>, ceda o endose sus derechos sobre este pagaré</li>';
$html .= '</ul>
	</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>TERCERO:</u></b> La fecha de pago de este título valor será el dia: <span style="font-family:sans-serif">______</span> de <span style="font-family:sans-serif">_____________</span> del año <span style="font-family:sans-serif">______</span>.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>CUARTO:</u></b> Que, en caso de mora, yo, pagaré a el (la) señor (a) <b>'.mb_strtoupper($datos['newCreditor']).'</b>, identificada con '.$datos['newCreditorIdType'].' No. <b>'.$datos['newCreditorId'].'</b>';
  if (isset($datos['newCreditorExpedition']) && $datos['newCreditorExpedition'] != '' && $datos['newCreditorExpedition'] != null) {
     $html.=' expedida en el municipio de <b>'.mb_strtoupper($datos['newCreditorExpedition']).'</b>';
   } 
   $html.=', o a la persona natural o jurídica, a quien el (la) mencionado (a) acreedor (a) ceda o endose sus derechos sobre este pagaré; intereses de mora, a la más alta tasa permitida por la Ley, desde el día siguiente a la fecha de exigibilidad del presente pagaré, y hasta cuando su pago total se efectúe.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>QUINTO:</u></b> Expresa, precisa y claramente, declaro excusado, el protesto del presente pagaré; además, de todos y cada uno de los requerimientos judiciales o extrajudiciales para la constitución en mora.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>SEXTO:</u></b> En caso de que haya lugar al recaudo judicial o extrajudicial de la obligación, contenida en el presente título valor, esta, será a mi cargo en su totalidad, junto con las costas jurídicas y/o los honorarios que se causaren por razón del proceso contencioso que devenga del no pago de esta obligación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SÉPTIMO:</u></b> A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el documento y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2">En constancia y por consecuencia de lo anterior, se firma y suscribe este título valor, en la ciudad de <b>'.ucfirst($datos['newCreditCity']).'</b>, a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div></body>
</html>';
$footer='<table>
  <tr>
    <td>
    <div class="row">
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
      <span class="font-size2 m4"><b>EL DEUDOR,</b></span><br>
      <span class="font-size3"><b>'.mb_strtoupper($datos['newDebtor']).'</b><br>
      <b>'.$datos['newDebtorIdType'].'</b> No.<b>'.$datos['newDebtorId'].'</b>';
      if (isset($datos['newDebtorExpedition']) && $datos['newDebtorExpedition'] != '' && $datos['newDebtorExpedition'] != null) {
         $footer.=' de <b>'.mb_strtoupper($datos['newDebtorExpedition']).'</b>';
       }
       $footer.='</span>
    </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->defaultfooterline = 0;
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/pagare_'.$codigo.'.pdf';
        $mpdf->Output('Views/documents/'.$document->product->code.'/pagare_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newCreditor']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();          
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfTransito(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
      $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
      $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row{
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-top: 20px;
}
.m4{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body>
<div class="row m1">
  <span class="font-size2">'.ucfirst($datos['newDocumentCity']).', '.$hoy->format('d').' de '.$mes.' de '.$hoy->format('Y').'.</span>
</div>
<div class="row m2">
  <span class="font-size2">SEÑORES:<br>
  SECRETARÍA DE MOVILIDAD (TRÁNSITO) DE <b>'.mb_strtoupper($datos['newTransitSecretary']).'</b><br>
  INSPECTOR DE FOTODETECCIONES<br> 
  E.S.D</span>
</div>
<div class="row m2">
  <span class="font-size2">ASUNTO: DERECHO DE PETICIÓN ARTICULO 23 CONSTITUCIÓN POLÍTICA DE COLOMBIA </span>
</div>
<div class="row m2">
  <span class="font-size2">Cordial saludo:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>'.mb_strtoupper($datos['newPetitioner']).'</b>, identificado (a) con <b>'.$datos['newPetitionerIdType'].'</b> No. <b>'.$datos['newPetitionerId'].'</b>';
  if (isset($datos['newPetitionerExpedition']) && $datos['newPetitionerExpedition'] != '' && $datos['newPetitionerExpedition'] != null) {
     $html.=' de <b>'.mb_strtoupper($datos['newPetitionerExpedition']).'</b>';
   }
   $html.=' en ejercicio del derecho de petición consagrado en el artículo 23 de la Constitución Política de Colombia y con el lleno de los requisitos del artículo 5, 15 y 16 del Código de lo Contencioso Administrativo, ley 1437 de 2011 modificado por el artículo 1º de la ley 1755 de 2015, respetuosamente me dirijo a su despacho con el fin de solicitarle: </span>
</div>
<div class="row m2" style="text-align:justify;">
  <ol>
    <li><span class="font-size2"> Les solicito por favor la guía o prueba de envío ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='de los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
          $html .='.';
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='del comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= '</span></li>
      <li><span class="font-size2"> <b>Les solicito por favor me informen con qué dirección aparezco registrado(a) en el RUNT.</b> En caso de que la dirección del <b>RUNT</b> no sea la misma que aparece en la guía de entrega la cual se supone que es a donde deben enviar el Formulario Único Nacional de Comparendo y la foto de la infracción como lo establece el inciso segundo del artículo 137 del Código Nacional de Tránsito, solicito por favor se aplique la nulidad del mismo y se retire de todas las bases de datos incluido el SIMIT pues se estaría presentando violación al derecho fundamental al debido proceso, legalidad y defensa del artículo 29 de la Constitución Política de Colombia y en concordancia con la sentencia T-247 de 1997 que establece que el no seguir el debido proceso por parte de la administración genera nulidad de lo actuado.</span></li>
      <li><span class="font-size2"> Les solicito muy comedidamente, se me expida copia de la Orden de Comparendo Único Nacional que debe ir junto con la <b>FOTODETECCIÓN</b>, tal como lo ordenan los artículos 4,5 y 6 de la resolución 3027 del año 2010, los artículos 135 y 137 del Código Nacional de Tránsito y el artículo 8 de la ley 1843 de 2017, para cada comparendo señanalado en el númeral 1.</span></li>
      <li><span class="font-size2"> Solicito por favor para ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='el comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ' prueba de que en el sitio había señalización de Detección Electrónica tal como lo ordena el artículo 10 de la ley 1843 de 2017 y el artículo 10 de la resolución 718 de 2018.</span></li>
      <li><span class="font-size2"> Les solicito por favor copia de los permisos solicitados ante la Dirección de Tránsito y Transporte del Ministerio de Transporte para instalar cámaras de FOTODETECCIÓN en dicho(s) sector(es) tal como lo ordenan el artículo 2 de la ley 1843 de 2017 y el artículo 5 de la resolución 718 de 2018.</span></li>
    <li><span class="font-size2"> Les solicito muy comedidamente, copia de la debida calibración de las cámaras de <b>FOTODETECCIÓN</b> con la cual ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='se realizaron los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
          $html .='.';
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='se realizó el comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ', certificado expedido por <b>Instituto Nacional de Metrología INM</b> tal como lo establecen la ley 1843 del año 2017 y la Resolución 718 del año 2018 y lo preceptuado en el capítulo III “Condiciones de operación” de la Resolución 0000718 de marzo del 2018, el su artículo 8º.</span></li>
      <li><span class="font-size2"> Les solicito por favor copia de la resolución sancionatoria ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='de los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='del comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ' en caso de que exista.</span></li>
      <li><span class="font-size2"> Solicito por favor copia del aviso de llegada 1 y aviso de llegada 2 (en caso de que el motivo de devolución fuera otros/cerrado) para ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='el comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ' tal como lo establece el artículo 10 de la resolución 3095 del año 2011 de la Comisión de Regulación de Comunicaciones y en concordancia con el artículo 74 de la Constitución Política de Colombia.  </span></li>
      <li><span class="font-size2"> Les solicito por favor la prueba o guía de envío de la notificación por aviso tal como lo establece el artículo 69 de la ley 1437 de 2011 que establece que la notificación por aviso se debe enviar y no solo publicar.</span></li>
      <li><span class="font-size2"> Les solicito por favor retirar del SIMIT ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='el comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ' en caso de que no hayan enviado la notificación por aviso tal como lo ordena el artículo 69 de la ley 1437 de 2011.</span></li>
      <li><span class="font-size2"> Solicito por favor copia de la guía de envío notificación del mandamiento de pago ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='de los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='del comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ' de acuerdo con lo establecido en el artículo 826 del estatuto tributario.</span></li>
    <li><span class="font-size2"> Solicito muy comedidamente la exoneración ';
       if ($datos['newPenaltyNumber'] > 1) {
          $html .='de los comparendos con número: ';
          for ($i = 1; $i <= $datos['newPenaltyNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newPenalty'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
          $html .='.';
       }elseif ($datos['newPenaltyNumber'] == 1) {
           $html .='del comparendo con número: <b>'.$datos['newPenalty1'].'</b>.';
       }
      $html .= ', en caso de que no tengan prueba que permita identificar plenamente al infractor de la FOTODETECCIÓN, o en caso contrario dicha evidencia, tal como lo ordena la Sentencia C – 038 de 2020.</span></li>
  </ol>
</div>
<div class="row m2" style="text-align:center">
  <span class="font-size2"><b>RAZONES QUE SUSTENTAN ESTA PETICIÓN</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Las nuevas normas sobre las <b>FOTODETECCIONES</b> como la ley 1843 de 2017 y la resolución 718 de 2018 del Ministerio de Transporte establecieron que los organismos de tránsito en adelante deberán pedir permisos ante el Ministerio para poder instalar cámaras de <b>FOTODETECCIÓN</b>, estas deberán estar señalizadas con un letrero que diga “Detección Electrónica”, que la Superintendencia de Puertos y Transporte velará por el cumplimiento de estas normas, que se prohibirá su uso en colinas, viviendas ni vehículos en movimiento (parágrafo 1, articulo 6 de la resolución 718 de 2018), que los privados no podrán llevarse más del 10% de la utilidad, etc.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Los organismos de transito argumentan haber notificado por aviso. Pero la ley 1437 de 2011 en su artículo 69 establece que dicho tipo de notificación debe acompañarse de una copia íntegra del acto administrativo y de los recursos que legalmente proceden. Y en ninguno de los casos los organismos de tránsito adjuntan la copia del acto administrativo ni tampoco indican los recursos que legalmente proceden.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 69. Notificación por aviso.</b> Si no pudiere hacerse la notificación personal al cabo de los cinco (5) días del envío de la citación, esta se hará por medio de aviso que se remitirá a la dirección, al número de fax o al correo electrónico que figuren en el expediente o puedan obtenerse del registro mercantil, <u>acompañado de copia íntegra del acto administrativo</u>. El aviso deberá indicar la fecha y la del acto que se notifica, la autoridad que lo expidió, <u>los recursos que legalmente proceden</u>, las autoridades ante quienes deben interponerse, los plazos respectivos y la advertencia de que la notificación se considerará surtida al finalizar el día siguiente al de la entrega del aviso en el lugar de destino.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Cuando se desconozca la información sobre el destinatario, el aviso, con <u>copia íntegra del acto administrativo</u>, se publicará en la página electrónica y en todo caso en un lugar de acceso al público de la respectiva entidad por el término de cinco (5) días, con la advertencia de que la notificación se considerará surtida al finalizar el día siguiente al retiro del aviso.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En el expediente se dejará constancia de la remisión o publicación del aviso y de la fecha en que por este medio quedará surtida la notificación personal.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En este mismo artículo también se deja muy claro que el aviso debe ENVIARSE a la dirección que aparece en el registro (en este caso sería a la dirección del RUNT) y en mi caso no existe prueba de que dicho aviso lo hayan enviado. Porque si bien existe la posibilidad de publicar dicho aviso en un lugar de acceso público o en su sitio web, esto solo procede cuando se desconozca la dirección del destinatario cosa que no sucede en mi caso y por tanto el aviso no debieron <u>publicarlo</u> sino <u>enviarlo</u>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Ahora el artículo 72 ibidem que si la notificación no cumple con dichos requisitos no tendrá efectos jurídicos y por tanto se tendrá como no hecha. Y sin notificación no puede haber lugar a sanción.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 72. Falta o irregularidad de las notificaciones y notificación por conducta concluyente.</b> <u>Sin el lleno de los anteriores requisitos no se tendrá por hecha la notificación, ni producirá efectos legales la decisión</u>, a menos que la parte interesada revele que conoce el acto, consienta la decisión o interponga los recursos legales.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">El artículo 10 de la resolución 3095 del año 2011 de la Comisión de Regulación de Comunicaciones que establece lo siguiente en cuanto a los intentos de entrega:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 10. Intentos de entrega:</b> <u>En el evento en que el operador del Servicio de Mensajería Expresa proceda a efectuar la entrega del objeto postal en el domicilio del usuario destinatario consignado en la guía y éste no encuentra a nadie, deberá expedir un documento por medios físicos o electrónicos, en el que informe que tuvo lugar un intento de entrega de dicho objeto</u>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Dicho documento deberá contener, por lo menos, la siguiente información:</span>
</div>
<div class="row m2">
  <ul>
    <li><span class="font-size2"> Nombre del operador postal que está a cargo de la prestación del servicio.</span></li>
    <li><span class="font-size2"> Nombre del usuario remitente.</span></li>
    <li><span class="font-size2"> Número de la guía.</span></li>
    <li><span class="font-size2"> Fecha y hora del intento de entrega</span></li>
    <li><span class="font-size2"> Fecha y hora del próximo intento de entrega (de ser posible).</span></li>
    <li><span class="font-size2"> Dirección, número de teléfono y horario de atención de la oficina donde se encuentra a disposición del usuario destinatario el objeto postal.</span></li>
    <li><span class="font-size2"> Fecha hasta la cual se conservará el objeto postal en la oficina indicada.</span></li>
  </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">El documento al que se refiere el presente artículo relativo a los intentos de entrega de los objetos postales no tendrá que expedirse y diligenciarse en los eventos en que al primer intento se configure alguno de los motivos de devolución establecidos en los numerales 9.1, 9.2, 9.3 o 9.5 del Artículo 9 de la presente Resolución.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><u>Los operadores de servicios postales de Mensajería Expresa deben efectuar al menos dos (2) intentos de entrega, entre los cuales no debe transcurrir un tiempo superior a un (1) día hábil</u>. Si después de dos (2) intentos no se logra llevar a cabo la entrega del objeto postal, <u>se debe dejar un segundo aviso informando al usuario destinatario que puede recoger el objeto en una determinada oficina de atención al usuario, indicando además la fecha límite de retiro</u>, la cual será de treinta (30) días calendario, a partir de la fecha del último intento de entrega.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Si el objeto postal no es reclamado por el usuario destinatario en dicho plazo, este se considerará como no distribuible, caso en lo cual se debe dar aplicación a lo dispuesto en el Artículo 22 de la presente Resolución. Sin perjuicio de lo anterior, los operadores de Mensajería Expresa podrán efectuar los intentos de entrega que consideren necesario, hasta lograr la entrega del objeto postal al usuario destinatario registrado en la guía, para lo cual deberá expedir un documento por medios físicos o electrónicos en el que informe que tuvo lugar un intento de entrega.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Los intentos de entrega deben quedar registrados en la información materia de rastreo que debe estar disponible en la página web del operador y en la prueba de entrega, de que tratan los Artículo 11 y 8 de la presente Resolución, respectivamente.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Parágrafo 1.</b> Las disposiciones del presente Artículo no serán aplicables a los envíos postales correspondientes al ámbito internacional saliente.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Parágrafo 2.</b> Los operadores postales que presten el servicio de Mensajería Expresa en el ámbito internacional entrante, una vez finalizada las actividades aduaneras a cargo de la autoridad correspondiente, deben cumplir las disposiciones del presente Artículo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Parágrafo 3.</b> Cuando el servicio de Mensajería Expresa tenga como fin la distribución de objetos postales masivos, el operador postal deberá efectuar un (1) intento de entrega. En todo caso el operador deberá dejar, en el domicilio del usuario destinatario, el documento en el cual se informa que tuvo lugar dicho intento de entrega.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Del aparte subrayado podemos observar claramente que en caso de que al primer intento de entrega se encuentre cerrado, se debe dejar un primer aviso de llegada y hacer un siguiente intento de entrega al siguiente día hábil. En caso de no ser posible la entrega en el segundo intento se deberá dejar un segundo aviso de llegada informando donde podrá ser reclamado el objeto postal. La ley es clara en este sentido y no da lugar para interpretaciones.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En cuanto a la <b>FOTODETECCIÓN</b> como tal y la orden de comparendo, la <b>Resolución 3027 de 2010</b> del Ministerio de Transporte nos dice:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 4°. Nuevas tecnologías.</b> Las autoridades competentes podrán implementar el servicio de medios técnicos y tecnológicos que permitan la captura, lectura y almacenamiento de la información contenida en el <u>formulario Orden de Comparendo Único Nacional</u>, e igualmente deberán implementar medios técnicos y tecnológicos que permitan evidenciar la comisión de infracciones o contravenciones, el vehículo, la fecha, el lugar y la hora y <u>demás datos establecidos en el formulario de Comparendo Único Nacional</u>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En este artículo 4 de la resolución 3027 del año 2010 es importante hacer la precisión de que si bien la ley permite que los organismos de tránsito a través de nuevas tecnologías capturen y almacenen la información contenida en el formulario Orden de Comparendo Único Nacional, se deja claro que deberá contener los mismos datos y/o campos que este.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Sin embargo, en el presente caso si bien existe una <b>FOTODETECCIÓN</b> en donde debajo de la foto del vehículo cometiendo la infracción (prueba) están algunos datos contenidos en el formulario Orden de Comparendo Único Nacional, no aparecen otros campos como son: Observaciones (campo número 17), Testigo (campo número 18), entre otros. Esto es importante porque en el inciso 4to del artículo 22 de la ley 1383 dice: “La <u>orden de comparendo</u> deberá estar firmada por el conductor, siempre y cuando ello sea posible. Si el conductor se negara a firmar o a presentar la licencia, <u>firmará por él un testigo</u>, el cual deberá identificarse plenamente con el número de su cédula de ciudadanía o pasaporte, dirección de domicilio y teléfono, si lo tuviere.”.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 5°. Formato y elaboración del formulario de comparendo.</b><u>Adóptese el formulario de Comparendo Único Nacional</u> anexo a la presente resolución y que hace parte integral de la misma, el cual deberá ser utilizado una vez se agoten las existencias en cada Organismo de Tránsito. Los organismos de tránsito ordenarán la impresión y reparto del formulario –Orden de Comparendo Único Nacional– el cual deberá contener la codificación de las infracciones y demás datos y características descritas en la presente resolución.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 6°. Copias del comparendo.</b> De conformidad con lo dispuesto en el artículo 22 de la Ley 1383 de 2010, el Organismo de Tránsito competente deberá <u>enviar dentro de los tres (3) días hábiles siguientes</u> a la imposición de un <u>comparendo</u> por infracción a las normas de tránsito, <u>copia</u> de este al propietario <u>y a la empresa donde se encuentra vinculado el vehículo</u>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">El artículo 8 de la ley 1843 de 2017, que modificó el artículo 22 de la ley 1383 de 2010 que a su vez modificaba el artículo 135 del Código Nacional de Tránsito, establece que la notificación debe enviarse a los 3 días hábiles siguientes a través de una empresa de mensajería. Y según el auto aclaratorio 123 de 2016 de la sentencia T-051 de 2016 se establece es que el organismo de tránsito tiene 3 días es para enviar la notificación a la empresa de mensajería. Ya luego la empresa de mensajería a través de un contrato privado de prestación de servicios establece en cuanto tiempo entregará la notificación al destinatario final que en la mayoría de las ciudades es de 5 días hábiles. O sea que en total la notificación no puede enviarse al destinatario final más allá de los 8 días hábiles en promedio (aunque dependiendo de la ciudad el tiempo puede variar un poco). Sin embargo, ese tiempo se tiene que cumplir o si no se genera nulidad de lo actuado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">El Artículo 14º de la misma ley 1843 de 2017 señala que “los laboratorios. que se acrediten para prestar el servicio deberán demostrar la trazabilidad de sus equipos medidores de velocidad conforme a los patrones de referencia nacional, definidos por el Instituto Nacional de Metrología.”</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">También aduce que “El servicio de trazabilidad de los equipos medidores de velocidad, se prestará con sujeción a las tarifas establecidas por dicho instituto,”</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Deja claro que “Hasta tanto existan laboratorios acreditados en el territorio nacional, la calibración de los equipos, medidores de velocidad, estará a cargo del Instituto Nacional de Metrología.”</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">El capítulo III “Condiciones de operación” de la Resolución 0000718 de marzo del 2018, en su artículo 8º señala que “todos los sistemas o equipos usados para la detección de infracciones de tránsito deberán tener “desde el inicio de su operación” mecanismos de calibración”.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">La <b>sentencia T – 247 de 1997 dice lo siguiente:</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Al respecto la jurisprudencia de esta Corporación ha destacado que si no se ha procurado el acceso del demandante o de los interesados a la actuación procesal, para los fines de su defensa, se produce una evidente vulneración del debido proceso que <b><u>genera la nulidad de lo que se haya adelantado</u></b> sobre la base de ese erróneo proceder; empero, con apoyo en las normas del procedimiento civil, aplicables en lo no regulado al procedimiento de  tutela, la Corte ha distinguido entre la falta de notificación de la iniciación del trámite y la falta de notificación de la sentencia, así:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">“En el presente caso, al tenor del artículo 140 del Código de Procedimiento Civil (modificado por el decreto 2282 de 1989, artículo 1º, numeral 8º), se presentan dos causales de nulidad: la del numeral 8º, cuando no se practica en legal forma, o <b>eficaz</b> en este caso, la notificación del auto que admite la acción al ‘demandado’ (…) y la del numeral 3º, por haberse pretermitido íntegramente una instancia, al no haber tenido la parte oportunidad de impugnar la sentencia, por no haber sido notificado en forma <b>eficaz</b> de ella.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Es preciso tener en cuenta que la debida notificación en los términos y tiempo establecidos en la ley pretende garantizar el derecho a la defensa, evitar que se impongan sanciones a persona distinta a quien cometió la infracción y responsabilidades objetivas las cuales están proscritas en Colombia. <b>LA SENTENCIA C-530 DE 2003</b> dice lo siguiente:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Del texto del artículo 129 de la ley acusada no se sigue directamente la responsabilidad del propietario, pues éste será notificado de la infracción de tránsito sólo si no es posible identificar o notificar al conductor. <b><u>La notificación tiene como fin asegurar su derecho a la defensa en el proceso</u></b>, pues así tendrá la oportunidad de rendir sus descargos. Así, la notificación prevista en este artículo no viola el derecho al debido proceso de conductores o propietarios. Por el contrario, esa regulación busca que el propietario del vehículo se defienda en el proceso y pueda tomar las medidas pertinentes para aclarar la situación. Además, el parágrafo 1º del artículo 129 establece que las <b><u>multas no serán impuestas a persona distinta de quien cometió la infracción</u></b>. Esta regla general debe ser la guía en el entendimiento del aparte acusado, pues el legislador previó distintas formas de hacer comparecer al conductor y de avisar al propietario del vehículo sobre la infracción, para que pueda desvirtuar los hechos. <b><u>Lo anterior proscribe cualquier forma de responsabilidad objetiva</u></b> que pudiera predicarse del propietario como pasará a demostrarse.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Aunque del texto del artículo 129 de la ley acusada no se sigue directamente la responsabilidad del propietario, pues éste será notificado de la infracción de tránsito sólo si no es posible identificar o notificar al conductor, podría pensarse que dicha notificación hace responsable automáticamente al dueño del vehículo. Pero cabe anotar que <b><u>la notificación busca que el propietario del vehículo se defienda en el proceso y pueda tomar las medidas pertinentes para aclarar la situación</u></b>. Con todo, esta situación no podrá presentarse a menos que <b><u>las autoridades hayan intentado, por todos los medios posibles, identificar y notificar al conductor, pues lo contrario implicaría no sólo permitir que las autoridades evadan su obligación de identificar al real infractor, sino que haría responsable al propietario, a pesar de que no haya tenido ninguna participación en la infracción. Ello implicaría la aplicación de una forma de responsabilidad objetiva que, en el derecho sancionatorio está proscrita por nuestra Constitución (CP art. 29).</u></b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">La <b>sentencia C-980 de 2010</b> establece que el debido proceso no solo lo deben aplicar las autoridades judiciales sino también administrativas, que su fin es garantizar el derecho a la defensa e incluye la notificación en los términos legales (3 días hábiles) y bajo las formas propias establecidas por la ley (adjuntando el formulario único nacional de comparendo y enviando obviamente a la dirección registrada en el RUNT y no a otra):</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Como ya se anotó, la Constitución extiende la garantía del debido proceso no solo a los juicios y procedimientos judiciales, sino también a todas las actuaciones administrativas.<br>…<br>En el propósito de asegurar la defensa de los administrados, la jurisprudencia ha señalado que hacen parte de las garantías del debido proceso administrativo, entre otros, los derechos a: (i)ser oído durante toda la actuación, (ii) <b>a la notificación oportuna y de conformidad con la ley</b>, (iii) a que la actuación se surta sin dilaciones injustificadas, (iv) a que se permita la participación en la actuación desde su inicio hasta su culminación, (v) a que la actuación se adelante por autoridad competente y con el <b>pleno respeto de las formas propias previstas en el ordenamiento jurídico</b>, (vi) <b>a gozar de la presunción de inocencia</b>, (vii) <b>al ejercicio del derecho de defensa y contradicción</b>, (viii) a solicitar, aportar y controvertir pruebas, y (ix) a impugnar las decisiones y a <b>promover la nulidad de aquellas obtenidas con violación del debido proceso</b>.<br>…<br>De acuerdo con su contenido esencial, este Tribunal ha expresado que el <b><u>debido proceso administrativo se entiende vulnerado, cuando las autoridades no siguen los actos y procedimientos establecidos en la ley y los reglamentos</u></b>, y, por esa vía, desconocen las garantías reconocidas a los administrados.<br>…<br>En consecuencia, por tratarse de un derecho fundamental, <u>el derecho al debido proceso administrativo “exige a la administración pública <b>sumisión plena</b> a la Constitución y a la ley en el ejercicio de sus funciones</u>, tal como lo disponen los artículos 6°, 29 y 209 de la Carta Política”</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">La notificación en los términos de ley (3 días hábiles y enviando el formulario único nacional de comparendo a la dirección registrada en el RUNT y no a otra) pretende que se cumplan los fines esenciales del estado, así como que se materialice el principio de publicidad de los actos administrativos y no se vulnere el principio de seguridad jurídica. La <b>sentencia C- 957 de 1.999</b> dice:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">"El Estado de derecho se funda, entre otros principios, en el de la <b>publicidad</b>, el cual supone el conocimiento de los actos de los órganos y autoridades estatales, en consecuencia, implica para ellos desplegar una actividad efectiva para alcanzar dicho propósito; dado que, la certeza y seguridad jurídica exigen que las <u>personas puedan conocer</u>, no sólo la existencia y vigilancia de los mandatos dictados por dichos órganos y autoridades estatales, sino, en especial, del <u>contenido de las decisiones</u> por ellos adoptadas, para lo cual, la publicación se instituye en presupuesto básico de su vigencia y oponibilidad, mediante los instrumentos creados con tal fin...”</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En la misma línea, la <b>sentencia del Consejo de Estado 25234200020130432901</b> del 26 de septiembre de 2013 establece que los comparendos realizados por medios electrónicos se notificarán en los 3 días hábiles siguientes enviando los soportes (formulario único nacional de comparendo y prueba de la comisión de la infracción a la dirección registrada en el RUNT y no a otra) lo cual no tiene excepciones:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En efecto, la Ley 1383 de 2010 que reforma el Código Nacional de Tránsito estipula que los <b><u>comparendos</u></b> realizados por medios técnicos y tecnológicos <b><u>se notificaran</u></b> por correo dentro de los <b><u>tres días hábiles</u></b> siguientes la infracción y <b><u>sus soportes</u></b>, <b><u>disposición que no tiene excepciones legales</u></b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">La <b>Sentencia T – 051 de 2016</b> refuerza lo dicho al respecto del envío de la notificación en los 3 días hábiles y de hecho menciona específicamente que se debe adjuntar el comparendo:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <ol>
    <li><span class="font-size2"> A través de medios técnicos y tecnológicos es admisible registrar una infracción de tránsito, individualizando el vehículo, la fecha, el lugar y la hora, lo cual, constituye prueba suficiente para imponer un comparendo, así como la respectiva multa, de ser ello procedente (Artículo 129).</span></li>
    <li><span class="font-size2"> <b>Dentro de los <u>tres días hábiles</u> siguientes se debe <u>notificar</u></b> al último propietario registrado del vehículo o, de ser posible, al conductor que incurrió en la infracción (Artículo 135, Inciso 5).</span></li>
    <li><span class="font-size2"> La notificación debe realizarse por correo certificado, de no ser posible se deben agotar todos los medios de notificación regulados en la legislación vigente (Artículo 135, inciso 5 y Sentencia C-980 de 2010).</span></li>
    <li><span class="font-size2"> <b><u>A la notificación se debe adjuntar el comparendo</u></b> y los soportes de este (Artículo 135, inciso 5 y Ley 1437 de 2011, Artículo 72).</span></li>
  </ol>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">La <b>sentencia T-558 de 2011</b> que habla sobre el derecho al debido proceso administrativo:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>DERECHO AL DEBIDO PROCESO ADMINISTRATIVO</b>-Importancia de la notificación de los actos administrativos de carácter particular</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Las actuaciones que adelante el Estado para resolver una solicitud de reconocimiento de un derecho o prestación, deben adelantarse respetando, entre otras, las garantías del peticionario al derecho de defensa y de impugnación y <b><u>publicidad de los actos administrativos</u></b>. Una de las formas de respetar dichas garantías, es a través de la <b><u>notificación de las actuaciones administrativas</u></b>. En efecto, desde sus primeros fallos, la Corte Constitucional ha reconocido la importancia de la notificación de las actuaciones administrativas, pues de esta forma se garantiza que las personas hagan valer sus derechos impugnando las decisiones de la autoridad que los afecten. Ahora bien, la notificación de las actuaciones administrativas son actos plenamente regulados en el ordenamiento jurídico colombiano, específicamente en los <b><u>artículos 44 al 48 del Código Contencioso Administrativo</u></b>, en los cuales se indica que las decisiones que pongan término a una actuación administrativa deberán notificarse personalmente, enviando una <b><u></u></b>citación por correo certificado</u></b> al peticionario para que se notifique personalmente y se le entregue una copia íntegra, auténtica y gratuita de la decisión, y en caso de no poder surtirse la notificación personal, se deberá notificar la decisión por edicto.  Por lo anterior, <b><u>cuando la Administración no adelante la notificación con el lleno de los anteriores requisitos se entenderá que esta no se surtió y la decisión no producirá efectos legales</u></b>. Esto es así, porque en aquellos eventos en los que una entidad pública notifica indebidamente una decisión, <b><u>le impide al interesado ejercer su derecho de defensa y vulnera su derecho fundamental al debido proceso</u></b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">La <b>sentencia T – 677 de 2004</b> que dice:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">DEBIDO PROCESO-Implica proscripción de responsabilidad objetiva.<br>El debido proceso implica la proscripción de la responsabilidad objetiva, toda vez que aquella es "incompatible con el principio de la dignidad humana" y con el principio de culpabilidad acogido por la Carta en su artículo 29.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Vemos pues como ya hay varias sentencias de las altas cortes en el mismo sentido sobre todo enfatizando que los organismos de tránsito deben apegarse estrictamente a lo que dice la ley respecto a la notificación y por tanto se vuelve de obligatorio cumplimiento lo expuesto en las mismas pues de lo contrario podría haber consecuencias tanto penales como disciplinarias tal como lo establece el numeral 19, artículo 35 del Código Único Disciplinario:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 35. Prohibiciones.</b> A todo servidor público le está prohibido:<br>19. Reproducir actos administrativos suspendidos o anulados por la jurisdicción contencioso-administrativa, o proceder contra resolución o <b><u>providencia ejecutoriadas del superior</u></b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Y así como lo establece el artículo 454 del Código Penal:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Artículo 454. Fraude a resolución judicial.</b> <u>Modificado por el art. 12, Ley 890 de 2004,  Modificado por el art. 47, Ley 1453 de 2011</u>. El que por cualquier medio se sustraiga al <b><u>cumplimiento de obligación</u></b> impuesta en <b><u>resolución judicial</u></b>, incurrirá en <b><u>prisión</u></b> de uno (1) a cuatro (4) años y <b><u>multa</u></b> de cinco (5) a cincuenta (50) salarios mínimos legales mensuales vigentes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>La sentencia C - 038 de 2020</b> declaró inexequible el parágrafo 1 del artículo 8 de la ley 1843 de 2017 que trataba sobre la solidaridad entre el conductor y el propietario del vehículo por las infracciones captadas con cámaras de <b>FOTODETECCIÓN</b>. Ello implica que automáticamente <b>TODAS</b> las <b>FOTODETECCIONES</b> realizadas desde el 14 de julio de 2017 (fecha en la cual se sanciona la ley 1843 de 2017) hasta la fecha son ilegales y deben ser exoneradas con base en el principio general del derecho <b>ACCESORIUM SEQUITUR PRINCIPALE</b> o también <b>ACCESORIUM NON DUCIT</b>, <b>SED SEQUITUR SUUM PRINCIPALE</b> (lo accesorio sigue la suerte de lo principal).</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Y para todas aquellas <b>FOTODETECCIONES</b> anteriores al 2017, por analogía y según el artículo 162 del Código Nacional de Tránsito, también deben exonerarse todas aquellas <b>FOTODETECCIONES</b> en donde no se hubiera podido establecer plenamente la identidad del infractor ya que la sentencia C – 530 del año 2003 al analizar una demanda de nulidad por inconstitucionalidad de uno de los apartes del artículo 129 del Código Nacional de Tránsito, también establecía que no se podía vincular automáticamente al propietario del vehículo al proceso contravencional sin que existieran elementos de prueba que permitieran inferir que el propietario era el infractor.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En palabras de la Corte:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>LA RESPONSABILIDAD SOLIDARIA EXISTENTE ENTRE EL CONDUCTOR Y EL PROPIETARIO DEL VEHÍCULO, POR LAS INFRACCIONES DETECTADAS POR MEDIOS TECNOLÓGICOS (FOTOMULTAS), ES INCONSTITUCIONAL, AL NO EXIGIR EXPRESAMENTE, PARA SER SANCIONADO CON MULTA, QUE LA FALTA LE SEA PERSONALMENTE IMPUTABLE Y PERMITIR, POR LO TANTO, UNA FORMA DE RESPONSABILIDAD SANCIONATORIA POR EL HECHO AJENO.</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Luego de precisar el alcance del principio de responsabilidad personal en materia sancionatoria, que exige imputación personal de las infracciones, como garantía imprescindible frente al ejercicio del poder punitivo estatal (ius puniendi) y de diferenciarlo del principio de culpabilidad, concluyó este tribunal que la solidaridad prevista en la legislación civil no es plenamente aplicable a las sanciones impuestas por el Estado, al estar involucrados principios constitucionales ligados al ejercicio del poder punitivo estatal por lo que: (i) la solidaridad en materia sancionatoria administrativa es constitucional, a condición de (a) garantizar el debido proceso de los obligados, lo que implica que la carga de la prueba de los elementos de la responsabilidad, incluida la imputación personal de la infracción, le corresponde al Estado, en razón de la presunción de inocencia y que a quienes se pretenda endilgar una responsabilidad solidaria, deben ser vinculados al procedimiento administrativo en el que se impondría la respectiva sanción, para permitir el ejercicio pleno y efectivo de su derecho a la defensa; (b) respetar el principio de responsabilidad personal de las sanciones, lo que implica demostrar que la infracción fue cometida por aquel a quien la ley le atribuye responsabilidad solidaria o participó de alguna manera efectiva en su realización; y (c) demostrar que la infracción fue cometida de manera culpable, es decir, sin que sea factible una forma de responsabilidad objetiva.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Determinó la Corte que la norma demandada adolece de ambigüedades en su redacción y por consiguiente, genera incertidumbre en cuanto al respeto de garantías constitucionales ineludibles en el ejercicio del poder punitivo del Estado. Así, (i) aunque garantiza nominalmente el derecho a la defensa, al prever la vinculación del propietario del vehículo al procedimiento administrativo, vulnera, en realidad, dicha garantía constitucional, porque omite de la defensa lo relativo a la imputabilidad y la culpabilidad, al hacer directamente responsable al propietario del vehículo, por el solo hecho de ser el titular del mismo -imputación real, mas no personal-. (ii) Desconoce el principio de responsabilidad personal o imputabilidad personal, porque no exige que la comisión de la infracción le sea personalmente imputable al propietario del vehículo, quien podría ser una persona jurídica y (iii) vulnera la presunción de inocencia, porque aunque no establece expresamente que la responsabilidad es objetiva o que existe presunción de culpa, al no requerir imputabilidad personal de la infracción, tampoco exige que la autoridad de tránsito demuestre que la infracción se cometió de manera culpable. Ante el incumplimiento de garantías mínimas del ejercicio legítimo del poder punitivo del Estado, la Sala Plena de la Corte Constitucional declaró, por consiguiente, la inexequibilidad de la norma demandada.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">En concepto número <b>C – 6417 expediente D – 12519 del 19 de julio de 2018</b> de la Procuraduría General de la Nación, dicha corporación le solicitó a la Corte Constitucional que declarara inexequible el parágrafo 1 del artículo 8 de la ley 1843 de 2017 que establece que serán solidariamente responsables el conductor y el dueño del vehículo por las <b>FOTODETECCIONES</b>. Eso significa que ya la Procuraduría estableció que no hay razón para que una persona que ni siquiera ha sido notificada ni se ha enterado de sanción de tránsito alguna deba ser endilgada con una serie de multas que ni siquiera cometió. La Procuraduría también habla de cómo no se puede imponer la carga de la prueba al ciudadano para que demuestre su inocencia sino como es el estado o más bien quien acusa (el tránsito) quien debe demostrar la culpabilidad. También habla de como si bien en nuestro ordenamiento jurídico se establece la posibilidad de la responsabilidad objetiva, esta no es óbice para violar el debido proceso u obligarle a pagar por una actuación que no cometió o que no se demostró que cometió.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Igualmente, se debe tener en cuenta el <b><u>principio de la LEGALIDAD establecido en los artículos 6, 209 y 230 de la Constitución Política de Colombia</u></b> el cual se resume en que ningún funcionario público puede actuar sino en base a las leyes válidas y vigentes y no puede omitir o excederse en el ejercicio de sus funciones.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Por otro lado, es preciso recordar los términos establecidos para la respuesta de los derechos de petición consagrados en la ley 1437 de 2011 en su artículo 14 (modificado por la ley 1755 de 2015):</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">ARTÍCULO 14. Salvo norma legal especial y <b><u>so pena de sanción disciplinaria</u></b>, toda petición deberá resolverse dentro de los quince (15) días siguientes a su recepción.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SALVEDAD:</b> A partir de lo preceptuado en el presente párrafo, solo será válido la correspondiente firma del peticionario, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula, condición o petición que afecte la estructura o adultere los hechos del peticionario.</span>
</div>
<div class="row m2">
  <span class="font-size2"><b>NOTIFICACIÓN:</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Recibo respuesta a este derecho de petición, en el municipio de <b>'.mb_strtoupper($datos['newPetitionerCity']).'</b>, más específicamente, en el barrio '.$datos['newPetitionerNeighborhood'].' en la '.$datos['newPetitionerAddress'].', el teléfono de contacto es el No. '.$datos['newPetitionerPhone'].', mi Email '.$datos['newPetitionerEmail'].'.</span>
</div>
</body>
</html>';
$footer='<table>
  <tr>
    <td>
    <div class="row">
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
      <span class="font-size2 m4"><b>Cordialmente,</b></span><br>
      <span class="font-size3"><b>'.mb_strtoupper($datos['newPetitioner']).'</b><br>
      <b>'.$datos['newPetitionerIdType'].'</b> No.<b>'.$datos['newPetitionerId'].'</b>';
      if (isset($datos['newPetitionerExpedition']) && $datos['newPetitionerExpedition'] != '' && $datos['newPetitionerExpedition'] != null) {
         $footer.=' de <b>'.mb_strtoupper($datos['newPetitionerExpedition']).'</b>';
       }
       $footer.='</span>
    </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/ptransito_'.$codigo.'.pdf';
        $mpdf->Output('Views/documents/'.$document->product->code.'/ptransito_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newPetitioner']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();          
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfPoderNatural(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
      $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
      $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row{
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-top: 20px;
}
.m4{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body>
<div class="row m1">
  <span class="font-size2">'.ucfirst($datos['newDocumentCity']).', '.$hoy->format('d').' de '.$mes.' de '.$hoy->format('Y').'.</span>
</div>
<div class="row m2">
  <span class="font-size2">SEÑOR:<br>
  <b>'.mb_strtoupper($datos['newReceiver']).'</b><br>
  E.S.D</span>
</div>
<div class="row m2">
  <span class="font-size2">ASUNTO: PODER ESPECIAL AMPLIO Y SUFICIENTE ';
       if ($datos['newRepresentedNumber'] > 0) {
          $html .='EN REPRESENTACIÓN';
       }
      $html .= '</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>'.mb_strtoupper($datos['newGrantor']).'</b>, mayor y vecino de la ciudad de <b>'.ucfirst($datos['newGrantorCity']).'</b>, identificado (a) con <b>'.$datos['newGrantorIdType'].'</b> No. <b>'.number_format($datos['newGrantorId']).'</b> expedida en <b>'.mb_strtoupper($datos['newGrantorExpedition']).'</b> obrando ';
       if ($datos['newRepresentedNumber'] > 0) {
          $html .='como representante de: ';
        for ($i = 1; $i <= $datos['newRepresentedNumber']; $i++) {
          if ($i>1) {
            $html .=', ';
          }
          $key1='newRepresented'.$i.'Type';
          $key2='newRepresented'.$i;
          $html .=$datos[$key1].' <b>'.mb_strtoupper($datos[$key2]).'</b>';
        }
       }else{
          $html .='a nombre propio';
       }
      $html .= ', en uso de mis enteras facultades, en ejercicio';
       if ($datos['newRepresentedNumber'] > 0) {
          $html .=' como guardador de mi(s) representado(s)';
       }else{
          $html .=' de mis derechos legales y constitucionales';
       }
      $html .= ', muy comedidamente manifiesto a Usted que, por medio del presente escrito confiero poder especial amplio y suficiente a '.$datos['newAgentTitle'].' <b>'.mb_strtoupper($datos['newAgent']).'</b>, identificado (a) con <b>'.$datos['newAgentIdType'].'</b>, No. <b>'.number_format($datos['newAgentId']).'</b> expedida en <b>'.mb_strtoupper($datos['newAgentExpedition']).'</b>';
       if ($datos['newAgentGraduate'] == 'Si') {
          $html .=' y portador de la tarjeta profesional No. <b>'.number_format($datos['newAgentGraduateNumber']).'</b> del C.S.J';
       }
      $html .= ', para que formule ante su despacho '.$datos['newFormulation'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Mi apoderado, queda facultado para desistir, conciliar, renunciar, reasumir, notificar(se), suscribir y aclarar escrituras públicas, retirar peticiones con todos sus anexos; al igual que, con cada una de las facultades para transigir, recibir y sustituir el poder con todas sus potestades en otro abogado, esto, sumado a todas las demás facultades inherentes para el buen desarrollo de su función, como mi apoderado y en beneficio de mis intereses como su representado, además, de todo cuanto en Derecho sea necesario, para el cabal cumplimiento de este mandato, en los términos del artículo 77 del código general del proceso.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Sírvase Señor <b>'.mb_strtoupper($datos['newReceiver']).'</b>, reconocer personería a mi apoderado para los efectos y dentro de los términos del presente mandato, y para los todos los efectos de ley estipulados en el presente poder.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SALVEDAD:</b> A partir de lo preceptuado en el presente párrafo, solo será válido las correspondientes firmas, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula, condición o petición que afecte la estructura o adultere la información del mismo.</span>
</div>
</body>
</html>';
$footer='<table>
  <tr>
    <td>
    <div class="row">
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
      <span class="font-size2 m4">Atentamente, </span><br>
      <span class="font-size3"><b>'.mb_strtoupper($datos['newGrantor']).'</b><br>
      <b>'.$datos['newGrantorIdType'].' No. '.number_format($datos['newGrantorId']).' de '.$datos['newGrantorExpedition'].'</b></span>
    </div>
    <br>
    <br>
    <br>
    <div class="row">
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
      <span class="font-size2"><b>ACEPTO</b></span><br>
      <span class="font-size3"><b>'.mb_strtoupper($datos['newAgent']).'</b><br>
      <b>'.$datos['newAgentIdType'].' No. '.number_format($datos['newAgentId']).' de '.$datos['newAgentExpedition'].'</b>';
       if ($datos['newAgentGraduate'] == 'Si') {
          $footer .='<br>TP No. '.number_format($datos['newAgentGraduateNumber']).'<b>';
       }
      $footer .= '</span>
    </div>
    </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/pnatural_'.$codigo.'.pdf';
        $mpdf->Output('Views/documents/'.$document->product->code.'/pnatural_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newGrantor']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();          
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfPoderCEO(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
      $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
      $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row{
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-top: 20px;
}
.m4{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body>
<div class="row m1">
  <span class="font-size2">'.ucfirst($datos['newDocumentCity']).', '.$hoy->format('d').' de '.$mes.' de '.$hoy->format('Y').'.</span>
</div>
<div class="row m2">
  <span class="font-size2">SEÑOR:<br>
  <b>'.mb_strtoupper($datos['newReceiver']).'</b><br>
  E.S.D</span>
</div>
<div class="row m2">
  <span class="font-size2">ASUNTO: PODER ESPECIAL AMPLIO Y SUFICIENTE OTORGADO POR REPRESENTANTE LEGAL DE PERSONA JURÍDICA</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>'.mb_strtoupper($datos['newGrantor']).'</b>, mayor y vecino de la ciudad de <b>'.mb_strtoupper($datos['newGrantorCity']).'</b>, identificado (a) con <b>'.$datos['newGrantorIdType'].'</b> Nro. <b>'.$datos['newGrantorId'].'</b> expedida en <b>'.mb_strtoupper($datos['newGrantorExpedition']).'</b> obrando en mi condicion de representante legal de la Empresa <b>'.mb_strtoupper($datos['newCompany']).'</b> registrada con <b>'.$datos['newCompanyIdType'].'</b> No. <b>'.$datos['newCompanyId'].'</b>, sociedad domiciliada en <b>'.mb_strtoupper($datos['newCompanyCity']).'</b>, en uso de mis enteras facultades, en ejercicio de mis derechos legales y constitucionales, muy comedidamente manifiesto a Usted que, por medio del presente escrito confiero poder especial amplio y suficiente a '.$datos['newAgentTitle'].' <b>'.mb_strtoupper($datos['newAgent']).'</b>, identificado (a) con <b>'.$datos['newAgentIdType'].'</b>, expedida en <b>'.$datos['newAgentExpedition'].'</b>';
       if ($datos['newAgentGraduate'] == 'Si') {
          $html .=' y portador de la tarjeta profesional No. '.$datos['newAgentGraduateNumber'].' del C.S.J';
       }
      $html .= ', para que formule ante su despacho '.$datos['newFormulation'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Mi apoderado, queda facultado para desistir, conciliar, renunciar, reasumir, notificar(se), suscribir y aclarar escrituras públicas, retirar peticiones con todos sus anexos; al igual que, con cada una de las facultades para transigir, recibir y sustituir el poder con todas sus potestades en otro abogado, esto, sumado a todas las demás facultades inherentes para el buen desarrollo de su función, como mi apoderado y en beneficio de mis intereses como su representado, además, de todo cuanto en Derecho sea necesario, para el cabal cumplimiento de este mandato, en los términos del artículo 77 del código general del proceso.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Sírvase Señor <b>'.mb_strtoupper($datos['newReceiver']).'</b>, reconocer personería a mi apoderado para los efectos y dentro de los términos del presente mandato, y para los todos los efectos de ley estipulados en el presente poder.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SALVEDAD:</b> A partir de lo preceptuado en el presente párrafo, solo será válido las correspondientes firmas, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula, condición o petición que afecte la estructura o adultere la información del mismo.</span>
</div>
</body>
</html>';
$footer='<table>
  <tr>
    <td>
    <div class="row">
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
      <span class="font-size2 m4">Atentamente, </span><br>
      <span class="font-size3"><b>'.mb_strtoupper($datos['newGrantor']).'</b><br>
      <b>'.$datos['newGrantorIdType'].'</b> No. <b>'.$datos['newGrantorId'].'</b> de <b>'.mb_strtoupper($datos['newGrantorExpedition']).'</b></span>
    </div>
    <br>
    <br>
    <br>
    <div class="row">
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
      <span class="font-size2"><b>ACEPTO</b></span><br>
      <span class="font-size3"><b>'.mb_strtoupper($datos['newAgent']).'</b><br>
      <b>'.$datos['newAgentIdType'].'</b> No. <b>'.$datos['newAgentId'].'</b> de <b>'.mb_strtoupper($datos['newAgentExpedition']).'</b>';
       if ($datos['newAgentGraduate'] == 'Si') {
          $footer .='<br><b>TP</b> No.<b>'.$datos['newAgentGraduateNumber'].'</b>';
       }
      $footer .= '</span>
    </div>
    </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/pceo_'.$codigo.'.pdf';
        $mpdf->Output('Views/documents/'.$document->product->code.'/pceo_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newGrantor']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();          
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfConfidencialidad(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newSendEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>ACUERDO DE CONFIDENCIALIDAD</b></span>
</div>
    <table style="width:100%">
      <tr>
        <td class="col-4" colspan="2" style="padding-top:5px;padding-bottom:5px;background-color:#bfbfbf; text-align:center"><span class="font-size2"><b>PARTE REVELADORA</b></span></td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>NOMBRE COMPLETO: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>DIRECCIÓN: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>TELÉFONO: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newFirstPhone'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>EMAIL: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newFirstEmail'].'</span>
        </td>
       </tr>';
       if ($datos['newFirstType'] != 'Si mismo') {
           $html .='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>ACTUA EN NOMBRE DE: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newFirstCompany'].'</span>
        </td>
       </tr><tr>
        <td class="col-4">
          <span class="font-size-2"><b>'.$datos['newFirstCompanyIdType'].': </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newFirstCompanyId'].'</span>
        </td>
       </tr>';
       }
      $html .= '</table>
    <table style="width:100%" class="m3">
      <tr>
        <td class="col-4" colspan="2" style="padding-top:5px;padding-bottom:5px;background-color:#bfbfbf; text-align:center"><span class="font-size2"><b>PARTE RECEPTORA</b></span></td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>NOMBRE COMPLETO: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newSecondPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>DIRECCIÓN: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newSecondAddress'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>TELÉFONO: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newSecondPhone'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>EMAIL: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newSecondEmail'].'</span>
        </td>
       </tr>';
       if ($datos['newSecondType'] != 'Si mismo') {
           $html .='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>ACTUA EN NOMBRE DE: </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newSecondCompany'].'</span>
        </td>
       </tr><tr>
        <td class="col-4">
          <span class="font-size-2"><b>'.$datos['newSecondCompanyIdType'].': </b></span>
        </td>
        <td class="col-8" style="text-align:right">
          <span class="font-size-2">'.$datos['newSecondCompanyId'].'</span>
        </td>
       </tr>';
       }
      $html .= '</table>';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='<div class="row m2" style="text-align:left;">
    <span class="font-size2"><b>IDENTIFICACIÓN DEL CARGO </b>('.$datos['newCharge'].')</span>
</div>';
       }
      $html .= '<div class="row m2" style="text-align:justify;">Entre los abajo firmantes, identificados precedentemente, habremos de convenir en celebrar el presente <b>ACUERDO DE CONFIDENCIALIDAD</b> previa las siguientes: </span>
</div>
<div class="row m2" style="text-align:center;"><b>CONSIDERACIONES</b></span>
</div>
<div class="row" style="text-align:justify;">
    <ol>
        <li class="font-size2 m2">Debido a la naturaleza del convenio de las partes, la información compartida en virtud del presente acuerdo concierne única y exclusivamente a ';
       if ($datos['newFirstType'] != 'Si mismo') {
           if ($datos['newContractType'] == 'Contrato') {
               $html .='la COMPAÑÍA '.$datos['newFirstCompany'].' ';
           }else{
               $html .='el (la) señor (a) '.$datos['newFirstCompany'].' ';
           }
       }else{
           $html .='el (la) señor (a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b> ';
       }
      $html .= ' la misma, es considerada altamente sensible y de carácter restringido en su publicidad, administración y utilización. Dicha información, es compartida en virtud ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='del adelanto del CARGO '.$datos['newCharge'].' como quedó identificado (a) previamente';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='de la propuesta de un convenio comercial';
       }else{
           $html .='de la propuesta de una sociedad';
       }
      $html .= '.</li>
      <li class="font-size2 m2">La información de propiedad de ';
       if ($datos['newFirstType'] != 'Si mismo') {
           if ($datos['newContractType'] == 'Contrato') {
               $html .='la COMPAÑÍA '.$datos['newFirstCompany'].',';
           }else{
               $html .='el (la) señor (a) '.$datos['newFirstCompany'].',';
           }
       }else{
           $html .='el (la) señor (a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b>,';
       }
      $html .= ', ha sido desarrollada u obtenida de manera legal, como resultado de todos y cada uno de sus procesos, programas o proyectos y, en consecuencia, abarca documentos, datos, tecnología y/o material que considera único y confidencial, o que es objeto de amparo a título de secreto industrial.</li>
      <li class="font-size2 m2">El presente <b>CONVENIO</b>, se realiza por un lado entre la <b>PARTE RECEPTORA</b> de la información como ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='integrante del CARGO '.$datos['newCharge'].' ';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='contraparte de la propuesta de un convenio comercial para la COMPAÑÍA '.$datos['newSecondCompany'].' ';
       }else{
           $html .='contraparte de la propuesta de una sociedad ';
       }
      $html .= 'y por otro lado el (la) señor (a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b> que, para el presente caso, actúa como la <b>PARTE REVELADORA</b>, quien guarda y administra la información ';
       if ($datos['newFirstType'] != 'Si mismo') {
           if ($datos['newContractType'] == 'Contrato') {
               $html .='de propiedad de la COMPAÑÍA '.$datos['newFirstCompany'].',';
           }else{
               $html .='de propiedad de el (la) señor (a) '.$datos['newFirstCompany'].',';
           }
       }else{
           $html .='de su propiedad';
       }
      $html .= '</li>
    </ol>
</div>
<div class="row m2" style="text-align:left;">
    <span class="font-size2">
    En consecuencia, <b>AMBAS PARTES</b> asienten pactar las siguientes condiciones:
    </span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Primera. Objeto del convenio:</b> El objeto del presente <b>ACUERDO DE CONFIDENCIALIDAD</b> es fijar los términos y condiciones bajo los cuales ambas partes se obligan a mantener la confidencialidad o a no divulgar de forma directa o indirecta, próxima a remotamente, ni a través de ninguna otra persona o de sus subalternos o funcionarios, asesores o cualquier persona relacionada con ella, los datos e información intercambiados entre ellas, incluyendo información objeto de derecho de autor, patentes, técnicas, modelos, invenciones, know-how, procesos, algoritmos, programas, ejecutables, investigaciones, detalles de diseño, información financiera, lista de clientes, inversionistas, empleados, relaciones de negocios y contractuales, pronósticos de negocios, planes de mercadeo y cualquier información revelada sobre terceras personas etc. perteneciente a ';
       if ($datos['newFirstType'] != 'Si mismo') {
           if ($datos['newContractType'] == 'Contrato') {
               $html .=' la COMPAÑÍA '.$datos['newFirstCompany'].',';
           }else{
               $html .=' el (la) señor (a) '.$datos['newFirstCompany'].',';
           }
       }else{
           $html .=' el (la) señor (a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b>,';
       }
      $html .= '  así como también a no utilizar dicha información en beneficio propio ni de terceros.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Segunda. Definición de INFORMACIÓN CONFIDENCIAL:</b> Se entiende como <b>INFORMACIÓN CONFIDENCIAL</b>, para tales efectos del presente <b>ACUERDO</b>:</span>
</div>
<div class="row" style="text-align:justify;">
    <ol>
        <li class="font-size2 m2">La información que no sea pública y sea conocida por la <b>PARTE RECEPTORA</b> con ocasión de ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='el desarrollo del <b>CARGO '.$datos['newCharge'].'.</b>';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='la propuesta de un convenio comercial con la <b>COMPAÑÍA '.$datos['newSecondCompany'].'</b>';
       }else{
           $html .='la propuesta de una sociedad ';
       }
      $html .= '.</li>
      <li class="font-size2 m2">Cualquier información de carácter societario, técnico, jurídico, financiero, comercial, de mercado, estratégico, de productos, nuevas tecnologías, patentes, modelos de utilidad, diseños industriales, modelos de negocios y/o cualquier otra información relacionada con ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='el desarrollo del <b>CARGO '.$datos['newCharge'].'.</b>';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='la propuesta de un convenio comercial con la <b>COMPAÑÍA '.$datos['newSecondCompany'].'</b>';
       }else{
           $html .='la propuesta de una sociedad ';
       }
      $html .= ', sus fines, y/o cualquier otro ente relacionado con su estructura organizacional, bien sea que la misma sea escrita, oral o sensorial, o en cualquier forma tangible o no tangible, incluidos los mensajes de datos en la forma definida por la ley, de la cual, la PARTE RECEPTORA tenga conocimiento o a la que tenga acceso por cualquier medio o circunstancia en virtud de las reuniones sostenidas y/o documentación suministrada.</li>
      <li class="font-size2 m2">Es aquella que como conjunto o por la configuración o conformación exacta de todos y cada uno de sus componentes, no sea ordinariamente conocida entre los expertos en los campos correspondientes, o que no sea de fácil acceso, y aquella información que no esté sujeta a medidas de protección razonables, de acuerdo con las circunstancias del caso, a fin de mantener su carácter confidencial.</li>
      <li class="font-size2 m2">La información que se deba considerar como tal, para garantizar el derecho constitucional a la intimidad, la honra y el buen nombre de las personas ya sea naturales o jurídicas y a la que deba guardársele la debida diligencia en su mesura y dirección en el desempeño de sus funciones.</li>
    </ol>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Tercera. Origen de la información confidencial:</b>: Las partes acuerdan que la misma ha de provenir de la documentación suministrada en ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='el desarrollo del <b>CARGO '.$datos['newCharge'].'.</b>';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='la propuesta de un convenio comercial con la <b>COMPAÑÍA '.$datos['newSecondCompany'].'</b>';
       }else{
           $html .='la propuesta de una sociedad ';
       }
      $html .= ' y que tal información intercambiada, tiene que ver con las creaciones del intelecto, a la naturaleza, medios, formas de distribución, comercialización de productos o de prestación de los servicios, transmitida de manera oral, sensorial o material, ya sea por escrito en los documentos, medios electrónicos, discos ópticos, microfilmes, películas, e-mails u otros elementos similares suministrados de manera palpable o impalpable, independiente de su origen o soporte y sin que requiera advertir su carácter confidencial o privada.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Cuarta. Obligaciones de la PARTE RECEPTORA:</b> ha de considerase como <b>PARTE RECEPTORA</b> de la <b>INFORMACIÓN CONFIDENCIAL</b> a la persona la cual recibe la información, o que tenga acceso a ella, y estará obligada de manera inmediata a:</span>
</div>
<div class="row" style="text-align:justify;">
    <ol>
        <li class="font-size2 m2">Conservar la <b>INFORMACIÓN CONFIDENCIAL</b> segura y en reserva total, hasta cuando la misma, adquiera el carácter de publica; darle el uso solamente en los términos y para los designios relacionados con él, en caso de ser solicitada por la <b>PARTE REVELADORA</b>, ha de ser devuelta en su totalidad incluyendo las copias que hayan sido extraídas de esta, en el momento en que ya no se requiera hacer uso de la misma, al igual que cuando termine la relación ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='contractual';
        }else{
           $html .='comercial';
       }
      $html .= ' caso en el cual, deberá entregarse dicha información antes de la terminación de dicha relación.</li>
      <li class="font-size2 m2">Salvaguardar la <b>INFORMACIÓN CONFIDENCIAL</b>, sea oral, escrita, sensorial, palpable, impalpable o que, en su defecto, haya sido recibida por cualquier otro medio, siendo legitima dueña de la misma ';
       if ($datos['newFirstType'] != 'Si mismo') {
           if ($datos['newContractType'] == 'Contrato') {
               $html .=' la COMPAÑÍA '.$datos['newFirstCompany'].',';
           }else{
               $html .=' el (la) señor (a) '.$datos['newFirstCompany'].',';
           }
       }else{
           $html .=' el (la) señor (a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b>,';
       }
      $html .= ' limitando con ello, su uso exclusivo a las personas que tengan imperiosa necesidad de conocerla.</li>
      <li class="font-size2 m2">Inhibirse de hacer pública la <b>INFORMACIÓN CONFIDENCIAL</b>, que ha sido puesta en custodia, se tome o se intercambie, con ocasión de las reuniones sostenidas con la <b>PARTE REVELADORA</b> o sus clientes en razón de ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='el desarrollo del <b>CARGO '.$datos['newCharge'].'.</b>';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='el convenio comercial con la <b>COMPAÑÍA '.$datos['newSecondCompany'].'</b>';
       }else{
           $html .='las actividades de la sociedad';
       }
      $html .= '.</li>
      <li class="font-size2 m2">Utilizar la <b>INFORMACIÓN CONFIDENCIAL</b> que se le asigne, única y exclusivamente para los fines señalados ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='al instante de su designación.';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='en el convenio';
       }else{
           $html .='por la sociedad';
       }
      $html .= ' Respondiendo ante el cliente, por el mal uso que sus representantes o consultores le den a la misma.</li>
      <li class="font-size2 m2">Almacenar y reservar la <b>INFORMACIÓN CONFIDENCIAL</b> como máximo diligencia y compromiso, como la misma <b>PARTE REVELADORA</b> la protege la <b>INFORMACIÓN CONFIDENCIAL.</b></li>
      <li class="font-size2 m2">La <b>PARTE RECEPTORA</b> se obliga a no trasferir, informar, develar o de alguna u otra manera divulgar en su totalidad o parcial, ya sea pública o privadamente, la <b>INFORMACIÓN CONFIDENCIAL</b> sin el previo consentimiento por escrito por parte de la <b>PARTE REVELADORA.</b></li>
    </ol>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Parágrafo:</b> Cualquier divulgación autorizada de la <b>INFORMACIÓN CONFIDENCIAL</b> a terceras personas estará sujeta a las mismas obligaciones de confidencialidad derivadas del presente <b>ACUERDO</b> y la <b>PARTE RECEPTORA</b>, deberá informar estas restricciones incluyendo la identificación de la información como confidencial.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Quinta. Obligaciones de la PARTE REVELADORA:</b> Son obligaciones de la <b>PARTE REVELADORA:</b></span>
</div>
<div class="row" style="text-align:justify;">
    <ol>
      <li class="font-size2 m2">Mantener la reserva de la <b>INFORMACIÓN CONFIDENCIAL</b> hasta tanto adquiera el carácter de pública.</li>
      <li class="font-size2 m2">Evidenciar toda la <b>INFORMACIÓN CONFIDENCIAL</b> que divulgue de manera escrita, verbal o sensorial, mediante documentos, medios electrónicos, discos ópticos, microfilmes, películas, e-mails u otros elementos similares o en cualquier forma palpable o impalpable, incluidos los mensajes de datos, como registro de la misma para la determinación de su alcance, e indicar específicamente y de manera clara, precisa e inequívoca, el carácter confidencial de la información suministrada de la <b>PARTE RECEPTORA</b>.</li>
    </ol>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Sexta. Exclusiones a la confidencialidad:</b> La <b>PARTE RECEPTORA</b> queda eximida o absuelta de toda la obligación de dar confidencialidad, única y exclusivamente en los siguientes casos:</span>
</div>
<div class="row" style="text-align:justify;">
    <ol>
      <li class="font-size2 m2">Cuando la <b>INFORMACIÓN CONFIDENCIAL</b> haya sido o sea de dominio público. Si la información se hace de dominio público durante el plazo del presente acuerdo, por un hecho ajeno a la <b>PARTE RECEPTORA</b>, esta ha de conservar su deber de reserva sobre la parte de la información que no haya sido afectada.</li>
      <li class="font-size2 m2">Cuando la <b>INFORMACIÓN CONFIDENCIAL</b> deba ser revelada por sentencia en firme de un juez, un tribunal o por requerimiento expreso de autoridades competentes, en desarrollo de sus funciones constitucionales y legales, mismos que ordenen el levantamiento de la reserva y soliciten el suministro de esta información. No obstante, a ello, en este caso, la <b>PARTE REVELADORA</b> será la encargada de dar cumplimiento a la orden, limitando la divulgación de la información estrictamente necesaria, y en el evento de que la confidencialidad se mantenga, no eximirá a la <b>PARTE RECEPTORA</b> del deber de reserva.</li>
      <li class="font-size2 m2">Cuando la <b>PARTE RECEPTORA</b> pruebe que la <b>INFORMACIÓN CONFIDENCIAL</b> ha sido obtenida por otras fuentes o cuando la misma ya la tenía en su poder la <b>PARTE RECEPTORA</b> antes de la entrega de la información reservada.</li>
    </ol>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Séptima. Duración:</b> Este acuerdo, regirá durante el tiempo que dure ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='el contrato de trabajo, o de prestación de servicios en el CARGO DE '.$datos['newCharge'];
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='el convenio comercial';
       }else{
           $html .='la sociedad';
       }
      $html .= ', y de manera indefinida hasta que el propietario de la información autorice específicamente su divulgación o en su defecto, hasta que dicha información se haga legalmente del dominio público.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Octava. Responsabilidad:</b> La parte que contravenga el citado acuerdo, será responsable ante la otra parte o ante terceros de buena fe, sobre los cuales se demuestre que se han visto afectados por la inobservancia del presente <b>CONVENIO</b>, por los perjuicios morales y materiales que estos puedan sobrellevar como resultado del incumplimiento de las obligaciones aquí contenidas.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Novena. Derechos de propiedad:</b> Toda información intercambiada es de propiedad exclusiva de la parte de donde proceda. En consecuencia, ninguna de las partes utilizará información de la otra para su propio uso.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decima. Solución de controversias:</b> Las partes se comprometen a esforzarse en resolver mediante los mecanismos alternativos de solución de conflictos, contenidas en la ley 640 de 2001 y otras normas que la complementan, cualquier diferencia que surja, con motivo de la ejecución del presente <b>ACUERDO</b>. En caso de no llegar a una solución de manera directa de la controversia planteada, someterán el asunto en cuestión controvertido, a las leyes colombianas y a la jurisdicción competente en el momento de presentarse la diferencia, más específicamente, en el lugar donde se haya realizado la firma del <b>ACUERDO</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimoprimera. Legislación Aplicable:</b> Este <b>convenio</b> se regirá por las leyes de la República de Colombia y se interpretará de acuerdo con las mismas.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimosegunda. Modificación o Terminación:</b> Este acuerdo solo podrá ser modificado o darse por terminado con el consentimiento expreso por escrito de ambas partes.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimotercera. Aceptación del Acuerdo:</b> Las partes han leído y asimilado de manera detenida el contenido, los términos y condiciones del presente <b>Convenio</b> y por tanto manifiestan estar conformes y aceptan todas las condiciones.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimocuarta. Validez y Perfeccionamiento:</b>El presente Acuerdo requiere para su validez y perfeccionamiento la firma de las partes, las cuales se dan a continuación.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimoquinta.</b> A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Dada en la ciudad de '.$datos['newCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>PARTE REVELADORA</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newFirstPart']).'</b><br>
      <b>'.$datos['newFirstIdType'].'</b> No. <b>'.$datos['newFirstId'].'</b></span>
  </td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>PARTE RECEPTORA</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].'</b> No. <b>'.$datos['newSecondId'].'</b></span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_confidencialidad_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_confidencialidad_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function mesLetras($hoy){
        switch ($hoy->format('m')) {
            case '01':
                return 'enero';
                break;
            case '02':
                return 'febrero';
                break;
            case '03':
                return 'marzo';
                break;
            case '04':
                return 'abril';
                break;
            case '05':
                return 'mayo';
                break;
            case '06':
                return 'junio';
                break;
            case '07':
                return 'julio';
                break;
            case '08':
                return 'agosto';
                break;
            case '09':
                return 'septiembre';
                break;
            case '10':
                return 'octubre';
                break;
            case '11':
                return 'noviembre';
                break;
            case '12':
                return 'diciembre';
                break;
            default:
                return "";
                break;
        }
      }
    public function pdfServicios(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
        $dias = floor($inicio->diffInDays($fin));
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE PRESTACIÓN DE SERVICIOS ';
    if (isset($datos['newSecondCardNumber']) && $datos['newSecondCardNumber']!="" && $datos['newSecondCardNumber']!=null) {
       $html.='PROFESIONALES ';
     } 
     $html.='DE '.mb_strtoupper($datos['newCharge']).'</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del ';
          if ($datos['newFirstType'] == 'PJ') {
             $html.='representante';
           }else {
             $html.='contratante';
           }
           $html.= ': </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b></span>
        </td>
      </tr>';
      if ($datos['newFirstType'] == 'PJ') {
        $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>';
      }
      $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del contratista: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>';
          if ($datos['newSecondType'] == 'PN') {
            $html.=mb_strtoupper($datos['newSecondPart']);
          }else {
            $html.=mb_strtoupper($datos['newSecondCompany']);
          }
          $html.='</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>';
          if ($datos['newSecondType'] == 'PN') {
            $html.=mb_strtoupper($datos['newSecondIdType']);
          }else {
            $html.=mb_strtoupper($datos['newSecondCompanyIdType']);
          }
          $html.='</b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">';
          if ($datos['newSecondType'] == 'PN') {
            $html.=mb_strtoupper($datos['newSecondId']);
          }else {
            $html.=mb_strtoupper($datos['newSecondCompanyId']);
          }
          $html.='</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio del contratista: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAddress'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Teléfono del contratista: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondPhone'].'</span>
        </td>
      </tr>';
      if ($datos['newSecondType']=='PN') {
        $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Lugar y fecha de Nacimiento: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondBornSite'].' el dia '.$datos['newSecondBornDate'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nacionalidad: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondNationality'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>EPS: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondEPS'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>AFP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAFP'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>ARP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondARP'].'</span>
        </td>
       </tr>';
      }else{
        $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Camara de comercio: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondCompanyCamera'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Número de registro: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondCompanyCameraNumber'].'</span>
        </td>
       </tr>';
      }
      $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Cargo: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newCharge'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Honorarios: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">$ '.number_format($datos['newSalary'],2).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Fecha de iniciación de labores: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newStartDate'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Ciudad de contratación: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.ucfirst($datos['newContractCity']).'</span>
        </td>
       </tr>
       </table>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre';
       if ($datos['newFirstType']=='PJ') {
          $html.=' la empresa '.$datos['newFirstCompany'].', empresa legalmente constituida Identificada con el '.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].' y con domicilio principal en la '.$datos['newFirstAddress'].', Teléfono No. '.$datos['newFirstPhone'].'., misma representada legalmente por el (la) señor(a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b>, Identificado (a) con '.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].'</b>';
          if (isset($datos['newFirstExpedition']) && $datos['newFirstExpedition'] != '' && $datos['newFirstExpedition'] != null) {
              $html.=' de <b>'.$datos['newFirstExpedition'].'</b>';
            }
          $html .=' según certificado de la Cámara de Comercio '.$datos['newFirstCompanyCamera'].' No. '.$datos['newFirstCompanyCameraNumber'];
        }else {
          $html.=''.mb_strtoupper($datos['newFirstPart']).'</b>, identificado (a) con '.$datos['newFirstIdType'].' No. <b>'.$datos['newFirstId'].'</b>';
         if (isset($datos['newFirstExpedition']) && $datos['newFirstExpedition'] != '' && $datos['newFirstExpedition'] != null) {
            $html.=' de <b>'.$datos['newFirstExpedition'].'</b>';
          }
        }
        $html.=', quien en adelante se denominará <b>CONTRATANTE</b> y por otra parte ';
       if ($datos['newSecondType']=='PJ') {
          $html.=' la empresa '.$datos['newSecondCompany'].', empresa legalmente constituida Identificada con el '.$datos['newSecondCompanyIdType'].' No. '.$datos['newSecondCompanyId'].' y con domicilio principal en la '.$datos['newSecondAddress'].', Teléfono No. '.$datos['newSecondPhone'].'., misma representada legalmente por el (la) señor(a) <b>'.mb_strtoupper($datos['newSecondPart']).'</b>, Identificado (a) con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].'</b>';
          if (isset($datos['newSecondExpedition']) && $datos['newSecondExpedition'] != '' && $datos['newSecondExpedition'] != null) {
              $html.=' de <b>'.$datos['newSecondExpedition'].'</b>';
            }
          $html .=' según certificado de la Cámara de Comercio '.$datos['newSecondCompanyCamera'].' No. '.$datos['newSecondCompanyCameraNumber'];
        }else {
          $html.=''.mb_strtoupper($datos['newSecondPart']).'</b>, identificado (a) con '.$datos['newSecondIdType'].' No. <b>'.$datos['newSecondId'].'</b>';
         if (isset($datos['newSecondExpedition']) && $datos['newSecondExpedition'] != '' && $datos['newSecondExpedition'] != null) {
            $html.=' de <b>'.$datos['newSecondExpedition'].'</b>';
          }
        }
        if ($datos['newSecondCard']=='Si') {
          $html.=', y tarjeta profesional No. '.$datos['newSecondProfesionalCard'];
        }
        $html.=', quien en adelante se denominará <b>CONTRATISTA</b>,hemos convenido en celebrar un contrato de prestación de servicios ';
        if (isset($datos['newSecondCardNumber']) && $datos['newSecondCardNumber']!="" && $datos['newSecondCardNumber']!=null) {
           $html.='profesionales ';
         } 
         $html.='que se regulará por las cláusulas que a continuación se expresan y en general por las disposiciones del título XXVIII capítulo I del Libro Cuarto del Código Civil y Código de Comercio aplicables a la materia de qué trata este contrato: </span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>.</b> Objeto.<b>El CONTRATISTA</b>, de manera independiente, sin subordinación o dependencia, utilizando sus propios medios, elementos de trabajo, todo tipo de gestión, con el más alto sentido de diligencia, ética y responsabilidad profesionales, prestará el servicio de '.$datos['newCharge'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>.</b>Término del Contrato. Este Contrato de Prestación de Servicios, se extenderá hasta la terminación del mandato específico de '.$dias.' dias.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>.</b>Honorarios. – <b>El CONTRATANTE</b> pagará al <b>CONTRATISTA</b> por concepto de sus honorarios la suma cierta de $ '.number_format($datos['newSalary'],2).' ('.$valorLetrasTotal.'); estos asumidos directamente por <b>EL CONTRATANTE</b>, ';
      if($datos['newPaymentType']=='Deposito'){
        $html .='realizados por transferencia bancaria en la '.$datos['newPaymentAccount'].' <b>No.</b>'.$datos['newPaymentNumber'].' del banco'.$datos['newPaymentBank'].'.';
      }else{
        $html .='pagados en efectivo.';
      }
       if ($datos['newSalaryPayment'] == '100a') {
           $html .='Dicho pago se realizará en una cuota anticipada al cierre de la negociación el día '.$inicio->format('d').' de '.$mes.' de '.$inicio->format('Y').', misma fecha que se dará inicio de la prestación del servicio.';
       }elseif ($datos['newSalaryPayment'] == '50-50') {
           $html .='Dicho pago se realizará en dos cuotas, la primera mitad o 50 % al cierre de la negociación el día '.$inicio->format('d').' de '.$mesInicio.' de '.$inicio->format('Y').', misma fecha que se dará inicio de la prestación del servicio y la segunda mitad u otro 50% al terminar el mandato especifico.';
       }else{
           $html .='Dicho pago se realizará en una cuota al terminar el mandato especifico.';
       }
      $html .= 'El valor que, en consenso se aplica, no excederá las tarifas estipuladas por la ley o los contratantes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>.</b>. Tramite. El trámite o servicio se realizará en';
  if ($datos['newServiceLocation']=='Si') {
     $html.=' la dirección '.$datos['newServiceAddress'];
   }else {
     $html.=' los puntos acordados por las partes';
   }
   $html.=', los costos de traslado al sitio de herramientas y equipos, serán asumidos por el <b>CONTRATANTE</b>, mientras que el traslado personal del <b>CONTRATISTA</b> al sitio de prestación del servicio, se hará por sus propios medios.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>.</b>Facultades. - EL <b>CONTRATISTA</b> queda expresamente facultado para '.$datos['newFaculties'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>.</b>. Obligaciones del <b>CONTRATISTA</b>. Son obligaciones del <b>CONTRATISTA:</b> 1. Obrar con seriedad y diligencia en el servicio contratado, 2. Informar constantemente al <b>CONTRATANTE</b> sobre el proceso de la prestación de su servicio. 3. Atender las solicitudes y recomendaciones que haga <b>EL CONTRATANTE</b> o sus delegados, con la mayor prontitud.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEPTIMA</u>.</b>Obligaciones del <b>CONTRATANTE</b>. Son obligaciones del <b>CONTRATANTE:</b> 1. Cancelar los honorarios fijados al <b>CONTRATISTA</b>, según la forma que se pactó dentro de los términos debidos, so pena de incurrir en intereses por mora en la cancelación o devolución de estos. 2. Entregar toda la información, equipo. Herramientas y logística que solicite el <b>CONTRATISTA</b> para poder desarrollar con normalidad su labor independiente.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>.</b>Cláusula compromisoria. – El incumplimiento de las obligaciones de este contrato, dará lugar a la parte allanada a cumplir, la potestad de exigir de la otra el pago total del dinero contratado o la devolución de una porción de esta, según el alcance y las labores realizadas en el contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>.</b>Controversias. - Toda controversia o diferencia relativa a este contrato, su ejecución y liquidación, se resolverá por un tribunal de arbitramento que por economía será designado por las partes, mismas que pactan que sea el municipio de Medellín, o en su defecto, en el domicilio donde se debió ejecutar el servicio contratado. El tribunal de Arbitramento se sujetará a lo dispuesto en el decreto 1818 de 1998 o estatuto orgánico de los sistemas alternativos de solución de conflictos y demás normas concordantes. En todo caso, este contrato <b>PRESTA MÉRITO EJECUTIVO</b> por ser una obligación clara, expresa y exigible para las partes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA</u>.</b>A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Este Contrato de Prestación de Servicios se firma en dos ejemplares para las partes en '.ucfirst($datos['newContractCity']).', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL CONTRATANTE</b></span><br>';
  if ($datos['newFirstType']=='PJ') {
    $footer.='<span class="font-size3 start">'.$datos['newFirstCompany'].'<br>
      <b>'.$datos['newFirstCompanyIdType'].'</b> No. <b>'.$datos['newFirstCompanyId'].'</span>';
  }else{
    $footer.='<span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].'</span>';
  }
  $footer.='</td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL CONTRATISTA</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_servicios_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_servicios_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);      
        $mpdf->Output();
    }
    public function pdfArrendamiento(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
        $mesFin = $this->mesLetras($fin);
        $dias = floor($inicio->diffInDays($fin));
        $meses = floor($inicio->diffInMonths($fin));
        $canon = ludcis\NumeroALetras::convertir($datos['newCanon'], 'pesos colombianos', 'centavos');
        $canon .=' (COP).';
        $frente = ludcis\NumeroALetras::convertir($datos['newWidth']);
        $fondo = ludcis\NumeroALetras::convertir($datos['newHeight']);
        $diasPago = ludcis\NumeroALetras::convertir($datos['newPaymentDays']);
        $prorroga = ludcis\NumeroALetras::convertir($datos['newProrrogue']);
        $incremento = ludcis\NumeroALetras::convertir($datos['newCharge']);
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE ARRENDAMIENTO DE ESTABLECIMIENTO DE COMERCIO Y/O LOCAL COMERCIAL</b></span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">En la ciudad de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').' entre los suscritos a saber: el (la) señor (a) <b>'.mb_strtoupper($datos['newFirstPart']).'</b> mayor de edad y residente en '.$datos['newFirstCity'].', identificado (a) con '.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].' expedida en '.$datos['newFirstExpedition'].'</b>, respectivamente en adelante llamado <b>EL ARRENDATARIO</b>, de una parte y el (la) señor (a) <b>'.mb_strtoupper($datos['newSecondPart']).'</b> mayor de edad y residente en '.$datos['newSecondCity'].', identificado (a) con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].' expedida en '.$datos['newSecondExpedition'].'</b>, en adelante denominado <b>EL ARRENDADOR</b>, acuerdan celebrar el presente contrato de arrendamiento de <b>ESTABLECIMIENTO DE COMERCIO</b> ubicado en '.$datos['newContractCity'].', en el departamento de '.$datos['newContractDepartment'].', el cual se regirá por las siguientes cláusulas y lo no pactado en ellas por las leyes colombianas vigentes y aplicables a este asunto especialmente las consagradas en los capítulos II y III, Título XXVI, Libro 4 del Código Civil, Libro 3º Título I y ss. Del código de comercio.</span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2"><b>EL PRESENTE CONTRATO, SE REGIRÁ POR LAS SIGUIENTES CLÁUSULAS:</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>.-Objeto:</b> Conceder el uso y goce del inmueble local comercial localizado en '.$datos['newContractCity'].', en el departamento de '.$datos['newContractDepartment'].', junto con la explotación económica, el uso de la razón social y del good will del establecimiento comercial denominado '.$datos['newPropertyName'].', con linderos a) por el Occidente con '.$datos['newWest'].', b) por el Oriente con '.$datos['newEast'].', c) por el Norte con '.$datos['newNorth'].', d) y por el sur con '.$datos['newSouth'].'; con aproximadamente '.$frente.' ('.$datos['newWidth'].') mts. de frente y '.$fondo.' ('.$datos['newHeight'].') mts. de fondo, con toda la maquinaria, muebles y relación de materia prima que recibe debidamente inventariado en la fecha y todos y cada uno de los elementos que hacen parte de este y que se relacionan de manera detallada en el inventario que hace parte de este documento, mismo que deberá ser entregado en las mismas condiciones o bajo las mismas características al termino o cancelación de este contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>.-Valor de la renta mensual:</b>el valor del canon mensual es de '.$canon.' ($ '.number_format($datos['newCanon'],2).' M/L) pagaderos dentro de los '.$diasPago.' ('.$datos['newPaymentDays'].') primeros días de cada mensualidad en el lugar y sitio indicados adelante; empero, ante el silencio de las partes tres meses antes de expirar el término principal estipulado, el término se entenderá prorrogado por periodos '.$prorroga.' ('.$datos['newProrrogue'].') meses cada uno.</span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b><u>Parágrafo:</u></b>este canon, se aumentará anualmente en un '.$datos['newCharge'].'% del valor pactado y a partir de la fecha pactada de entrega, misma que se estipula el día '.$inicio->format('d').' de '.$mesInicio.' de '.$inicio->format('Y').'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>.- Duración:</b>El término de duración del contrato será de '.$dias.' días, contados a partir del día '.$inicio->format('d').' de '.$mesInicio.' de '.$inicio->format('Y').'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>.- Pena de incumplimiento: </b> Esta penalidad, estará estrictamente ligada al término del contrato, por consiguiente, su valor, será el valor de los cánones de arrendamiento que faltaren para el término de '.$meses.' meses que conlleva este contrato, el cual es de '.$canon.' ($ '.number_format($datos['newCanon'],2).' M/L) pagaderos dentro de los '.$diasPago.' ('.$datos['newPaymentDays'].') primeros días de cada mensualidad en el lugar y sitio indicados adelante.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>..-. Obligaciones del ARRENDATARIO:</b> Además de cancelar el valor de la renta en la suma y forma convenidas, el ARRENDATARIO se obliga a: 
1) Mantener la confidencialidad de este negocio; 2) Pagar en tiempo el valor de todos los impuestos a que haya lugar en desarrollo del negocio, tales como: el impuesto de renta y complementarios, el IVA, el ICA, y demás costos fiscales derivados de la actividad comercial, ante las entidades del orden nacional, departamental y municipal; 3) Cancelar el pago de renovación de matrícula mercantil y demás obligaciones ante la Cámara de Comercio; 4) Cancelar en tiempo los valores correspondientes a las EPS, fondos de pensiones, etc. por concepto de los valores de cotización por pensiones, salud y riesgos profesionales y todas las obligaciones inherentes al personal vinculado al establecimiento de comercio, 5) Cancelar los valores parafiscales derivados de la obligación laborales, tales como Sena, ICBF, cajas de compensación familiar, etc.; 6) Continuar con la contabilidad del negocio en los mismos términos en que viene funcionando; 7) velar por el inventario de materia prima entregada a la fecha de la firma de este contrato, el cual deberá ser entregado en las mismas condiciones al termino del contrato. 8) Obtener una cuenta bancaria propia para el manejo financiero de la Empresa; 9) EL ARRENDATARIO tendrá total autonomía y por tanto tendrá derecho para disponer libremente del manejo laboral sobre sus empleados. Las obligaciones laborales por todo concepto del total de los trabajadores y causadas o derivadas de los contratos de trabajo, incluyendo las indemnizaciones por despidos, serán de cargo del ARRENDATARIO a partir de la fecha de firma del presente contrato; sin embargo, en caso de despedir a cualquiera de los trabajadores, el ARRENDATARIO, asumirá <b>EN SU TOTALIDAD EL COSTO DE LAS INDEMNIZACIONES A QUE HAYA LUGAR</b>, y será de su entera responsabilidad todo costo que pueda resultar del manejo de las relaciones laborales, 10) Responder de manera personal, por todas las obligaciones que contraiga durante la vigencia del contrato y, al finalizar el mismo, las obligaciones que quedaren pendientes de cancelar. 11) No podrá cambiar el rubro comercial, ni cambiar el destino comercial de la actividad del establecimiento, ni mucho menos suprimir actividades comerciales que se vienen desarrollando conforme a la costumbre de la Empresa. En caso de querer introducir algún nuevo producto o realizar algún cambio, deberá informar al ARRREDADOR y obtener de él el respectivo permiso. 12) Entregará los excedentes de cartera recaudados y que correspondan a saldos a favor de la ARRENDADOR SEGÚN EL CORTE DE CUENTAS presentado por el Contador en el momento de la firma del presente contrato, en forma inmediata cuando sean recaudados. 13) EL ARRENDATARIO se obliga a presentar cada dos meses al ARRENDADOR los recibos de pago de todos los servicios públicos, así como un “paz y salvo” de todas y cada una de las entidades EPS, FONDOS y demás establecimientos que tienen que ver con los aportes parafiscales. 14) Suministrar a su Contador, que podrá ser el mismo que tiene actualmente el ARRENDADOR, toda la documentación pertinente para el correcto y oportuno asiento contable, con el fin de llevar al día la contabilidad, así como para poder presentar las respectivas declaraciones de IVA, ICA, Retenciones y todas las obligaciones tributarias. 15) Proporcionar el mantenimiento preventivo y correctivo a todos los equipos, maquinaria y artículos de cocina que recibe, utilizando técnicos y repuestos idóneos, que le garantice el normal funcionamiento, debiéndose en esta misma forma ser entregados en caso de terminación del contrato.
</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>.- Derechos del ARRENDATARIO:</b> Para el desarrollo del fin comercial, EL ARRENDATARIO podrá: 1) Explotar la venta de artículos de consumo y otros; 2) Explotar la venta de comidas preparadas y rápidas; 3) Elaboración y venta de artes autóctonos del sector; 4) Disponer DE TODO el personal para el desarrollo de la labor comercial, con respeto y dignidad. Igualmente tendrá derecho a que se le vendan los inventarios que hay en la Empresa hasta la fecha de inicio de este contrato en las condiciones previamente estipuladas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEPTIMA</u>.- Forma y Pago de la renta:</b> EL ARRENDATARIO se obliga a pagar la renta acordada dentro de los primeros '.$diasPago.' ('.$datos['newPaymentDays'].') días de cada mes'; 
      if($datos['newPaymentType']=='Deposito'){
        $html .='consignando en la '.$datos['newPaymentAccount'].' <b>No.</b>'.$datos['newPaymentNumber'].' del banco'.$datos['newPaymentBank'].'.';
      }else{
        $html .='pagando en efectivo.';
      }
      $html .=', a favor del ARRENDADOR.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>.- Destinación.</b>EL ARRENDATARIO se obliga a usar el inmueble y el establecimiento comercial que en él se encuentra, única y exclusivamente, para la explotación comercial del mismo y no podrá cambiar su destinación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>.- Subarriendo y cesión:</b>EL ARRENDATARIO <b>NO PODRÁ</b>, en ninguna circunstancia, subarrendar el inmueble, ni el establecimiento comercial, ni tampoco ceder el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b><u>Parágrafo:</u>:</b>En caso de intención de venta del establecimiento de comercio y/o local comercial por parte de <b>EL ARRENDADOR</b>, la primera opción de compra privilegia a <b>EL ARRENDATARIO</b> de este, que al momento se encuentre usufructuando este.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA</u>.-Mora.</b>La mora en el pago de la renta mensual en la oportunidad y forma acordada, así como la suspensión o corte de los servicios públicos por culpa del no pago por parte del ARRENDATARIO, facultará al ARRENDADOR, para hacer cesar el arriendo y exigir judicial o extrajudicialmente la restitución del bien arrendado, sin menoscabo del cobro de las deudas pendientes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA PRIMERA</u>.- Reparaciones.</b> EL ARRENDATARIO se obliga a efectuar las reparaciones locativas y aquellas que sean necesarias por hechos de él o de alguno de sus dependientes, así como por el deterioro natural del inmueble.</span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b><u>Parágrafo:</u>:</b>Cualquier mejora realizada por EL ARRENDATARIO y no autorizada por el ARRENDADOR, quedará a favor del inmueble, sin que este deba ser cancelado por el ARRENDADOR.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEGUNDA</u>.- Inspección.</b> EL ARRENDATARIO permitirá en cualquier tiempo las visitas que el ARRENDADOR o sus representantes, tengan a bien realizar para constatar el estado de conservación del inmueble, de las máquinas y demás elementos o artículos de cocina, que conforman el negocio, en el desarrollo y cumplimiento de este contrato, además de otras circunstancias que sean de su interés.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA TERCERA</u>.- Entrega.</b> El término del presente contrato es de '.$meses.' meses, por lo cual, el ARRENDADOR se obliga a entregar el inmueble y el establecimiento comercial al ARRENDATARIO el día '.$fin->format('d').' de '.$mesFin.' de '.$fin->format('Y').', junto con los elementos que lo integran, los que se detallarán en escrito separado firmado por los contratantes, el cual hace parte integral de este contrato.</span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b><u>Parágrafo:</u>:</b> El ARRENDADOR se encargará de las cuentas por pagar hasta el día '.$fin->format('d').' de '.$mesFin.' de '.$fin->format('Y').', así como es de su derecho el monto total de la Cartera por Cobrar a la misma fecha. A partir de la entrega del Establecimiento al ARRENDATARIO, las cuentas por pagar quedarán a cargo de éste, pudiendo disponer de la cartera por cobrar con vencimientos a partir de '.$fin->format('d').' de '.$mesFin.' de '.$fin->format('Y').', hasta el monto de las cuentas por pagar, debiendo rembolsar al ARRENDATARIO el saldo a su favor en caso de haberlo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA CUARTA</u>.- Servicios públicos.</b> Serán de cargo del ARRENDATARIO el pago de los servicios de energía eléctrica, acueducto y, alcantarillado, aseo y gas. Si por culpa del ARRENDATARIO los servicios fueren suspendidos o cortados, a más de quedar en obligación de cancelados, pagará su reconexión y demás gastos que exijan las empresas respectivas, además pagará al ARRENDADOR una suma igual a la cláusula penal aquí contemplada.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA QUINTA</u>.- Restitución.</b> EL ARRENDATARIO restituirá el inmueble, junto con el establecimiento comercial al ARRENDADOR a la terminación del contrato en las mismas condiciones en que lo recibe, junto con todos los elementos que lo integran, los que se detallan en escrito separado, conformando el inventario de este, que se firma por los contratantes, el cual se considera parte integrante de este contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEXTA</u>.- Incumplimiento.</b> El incumplimiento o violación de cualquiera de las obligaciones de este contrato por parte del ARRENDATARIO otorgará al ARRENDADOR el derecho para dar por terminado el contrato, exigir la entrega inmediata del inmueble y del establecimiento comercial sin necesidad del desahucio y de los requerimientos previstos en la ley. El ARRENDATARIO renuncia al derecho de oponerse mediante la caución establecida en el artículo 2.035 del Código Civil.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEPTIMA</u>.- Cláusula penal.</b> El incumplimiento por parte del arrendatario de cualquiera de las obligaciones de este contrato lo constituirá en deudor del ARRENDADOR por la suma igual a la renta del número total de meses que faltare por cumplir del contrato, sin menoscabo del cobro de la renta y de los perjuicios que pudieren ocasionar como consecuencia del incumplimiento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA OCTAVA</u>.- Recibo y estado.</b> EL ARRENDATARIO declara que ha recibido del ARRENDADOR el inmueble y establecimiento comercial, así como los equipos, maquinarias y artículos de cocina objeto de este contrato, en las condiciones detalladas en el inventario que se anexa y a plena satisfacción.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA NOVENA</u>.- Renovación del contrato.</b> Para la renovación de este contrato el ARRENDATARIO pagará al ARRENDADOR un incremento igual al '.$incremento.' por ciento ('.$datos['newCharge'].'%) del valor aquí pactado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGESIMA</u>.</b> Las partes aceptan solucionar sus diferencias por trámite conciliatorio en el Centro de Conciliación de la Cámara de Comercio de Medellín.  En el evento que la conciliación resulte fallida, se obligan a someter sus diferencias a la decisión de un tribunal arbitral el cual fallará en derecho, renunciando a hacer sus pretensiones ante los jueces ordinarios, este tribunal se conformara conforme a las reglas del centro de conciliación y arbitraje de la cámara de comercio de Medellín, quien designara los árbitros requeridos conforme a la cuantía de las pretensiones del conflicto sometido a su conocimiento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGESIMA PRIMERA</u>.</b> A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Esta constancia se firma en '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL ARRENDATARIO</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newFirstPart']).'</b><br>
      <b>'.$datos['newFirstIdType'].'</b> No. <b>'.$datos['newFirstId'].'</span>
  </td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL ARRENDADOR</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].'</b> No. <b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_arrendamiento_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_arrendamiento_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);      
        $mpdf->Output();
    }
    public function pdfComodato(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
        $dias = floor($inicio->diffInDays($fin));
        $valor = ludcis\NumeroALetras::convertir($datos['newValue'], 'pesos colombianos', 'centavos');
        $valor .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE COMODATO</b></span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre los suscritos <b>'.mb_strtoupper($datos['newComodante']).'</b>, mayor de edad, vecino de '.$datos['newComodanteCity'].', identificado (a) con '.$datos['newComodanteIdType'].' número '.$datos['newComodanteId'];
       if ($datos['newComodanteExpedition'] != '' && $datos['newComodanteExpedition'] != null) {
          $html.=' expedida en '.$datos['newComodanteExpedition'].'</b>';
        }
        $html.=', quien en adelante se denominará <b>EL COMODANTE</b>, de una parte, y <b>'.mb_strtoupper($datos['newComodatario']).'</b> también mayor de edad, vecino de '.$datos['newComodatarioCity'].', identificado (a) con '.$datos['newComodatarioIdType'].' número '.$datos['newComodatarioId'];
        if ($datos['newComodatarioExpedition'] != '' && $datos['newComodatarioExpedition'] != null) {
           $html.=' expedida en '.$datos['newComodatarioExpedition'].'</b>';
         }
         $html.=', quien para efectos del presente instrumento se designará como <b>EL COMODATARIO</b>, de otra parte, manifestamos que hemos convenido celebrar el presente <b>CONTRATO DE COMODATO</b> que se regirá por las cláusulas que a continuación se señalan y en su defecto por la normatividad correspondiente del Código Civil.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PRIMERA: - EL COMODANTE</b> entrega a <b>EL COMODATARIO</b> y éste recibe, a título de <b>COMODATO O PRÉSTAMO DE USO</b>, ';
       if ($datos['newGoodsNumber'] > 1) {
          $html .='los bienes que se relacionan a continuación: ';
          for ($i = 1; $i <= $datos['newGoodsNumber'] ; $i++) {
            if ($i != '1') {
             $html .=', ';
            }
            $key = 'newGood'.$i;
            $html .= '<b>'.$datos[$key].'</b>';
          }
          $html .= '; los cuales pertenecen';
       }elseif ($datos['newGoodsNumber'] == 1) {
           $html .='el bien: '.$datos['newGood1'].', el cual pertenece';
       }
      $html .= ' a <b>EL COMODANTE.</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SEGUNDA: - </b> Los bienes anteriormente descritos deberán permanecer durante la vigencia del presente contrato en la siguiente dirección: '.$datos['newGoodsLocation'].' de la ciudad de '.$datos['newGoodsCity'].'. En consecuencia, de lo anterior, EL COMODATARIO no podrá cambiar el sitio de ubicación de los bienes entregados en COMODATO sin la previa y escrita autorización de EL COMODANTE.</span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b>Parágrafo:</b>Esta locación aplica para los bienes muebles, si existen bienes inmuebles deben permanecer integros en su ubicación por la duración del presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>TERCERA:- EL COMODATARIO</b> podrá utilizar los bienes objeto de este contrato única y exclusivamente para los siguientes fines: '.$datos['newGoals'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>CUARTA:- EL COMODATARIO </b>Se obliga a: '.$datos['newConditions'].'.</span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b>Parágrafo:</b>Las anteriores obligaciones son complementarias más no excluyetes del cuidado y mantenimiento de los bienes recibidos, la responsabilidad por daños, detrimento o perdida de los mismos, su restitución y según el caso su aseguramiento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>QUINTA:-</b> Declara EL COMODATARIO haber recibido los bienes objeto del presente contrato a su entera satisfacción.
</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SEXTA:-</b> El presente contrato tiene una duración de '.$dias.' días, contados a partir de el dia '.$inicio->format('d').' de '.$mesInicio.' de '.$inicio->format('Y').'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SEPTIMA:- </b>A la terminación del presente CONTRATO DE COMODATO, surge la obligación para EL COMODATARIO de restituir los bienes en perfecto estado de funcionamiento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>OCTAVA:- </b>Acuerdan ambas partes, estimar el valor de los bienes dados en COMODATO en la suma cierta de '.$valor.' pesos ($ '.number_format($datos['newValue'],2).'), suma que deberá pagar EL COMODATARIO a EL COMODANTE si éste ejerce la facultad que en su favor consagra el artículo 2203 del Código Civil.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>NOVENA:-</b>Manifiestan las partes que al momento de firmarse el presente contrato los bienes entregados en comodato se encuentran en perfecto estado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>DECIMA:-</b>Los gastos que se deriven de protocolización, inscripción o cualquier otra erogación para el desarrollo del presente contrato, serán cubiertos por '.$datos['newContractPayer'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>DECIMA PRIMERA:- </b>Toda controversia o diferencia relativa a este contrato, en cuanto a su ejecución y liquidación, se resolverá por un tribunal de arbitramento que, por economía procesal, será designado por las partes, o en su defecto, en el domicilio donde se debe ejecutar el respectivo CONTRATO DE COMODATO. El tribunal de Arbitramento se sujetará a lo dispuesto en el decreto 1818 de 1998 o estatuto orgánico de los sistemas alternativos de solución de conflictos y demás normas concordantes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>DECIMA SEGUNDA:-</b> Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este CONTRATO DE COMODATO, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>DECIMA TERCERA:-</b> A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Esta constancia se firma en dos (2) ejemplares similares, del mismo tenor y valor, para cada una de las partes, en el municipio de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL COMODATARIO</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newComodatario']).'</b><br>
      <b>'.$datos['newComodatarioIdType'].'</b> No. <b>'.$datos['newComodatarioId'].'</span>
  </td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL COMODANTE</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newComodante']).'</b><br>
      <b>'.$datos['newComodanteIdType'].'</b> No. <b>'.$datos['newComodanteId'].'</span>
  </td>
  </tr>';
       if ($datos['newWitnessNumber'] == '1') {
          $footer .='<tr>
            <td class="col-12" colspan="2">
            <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
            <span class="font-size2 start"><b>EL TESTIGO</b></span><br>
                <span class="font-size3 start">'.$datos['newWitness1'].'<br>
                <b>'.$datos['newWitness1IdType'].'</b> No. <b>'.$datos['newWitness1Id'].'</span>
            </td>
          </tr>';
       }elseif ($datos['newWitnessNumber'] == '2') {
          $footer .='<tr>
            <td class="col-6">
            <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
            <span class="font-size2 start"><b>EL TESTIGO 1</b></span><br>
                <span class="font-size3 start">'.$datos['newWitness1'].'<br>
                <b>'.$datos['newWitness1IdType'].'</b> No. <b>'.$datos['newWitness1Id'].'</span>
            </td>
            <td class="col-6">
            <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
            <span class="font-size2 start"><b>EL TESTIGO 2</b></span><br>
                <span class="font-size3 start">'.$datos['newWitness2'].'<br>
                <b>'.$datos['newWitness2IdType'].'</b> No. <b>'.$datos['newWitness2Id'].'</span>
            </td>
          </tr>';         
       }
      $footer .= '
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_comodato_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_comodato_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newComodante']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);      
        $mpdf->Output();
    }
    public function pdfCesion(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE CESIÓN</b></span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre los suscritos a saber, de una parte <b>'.mb_strtoupper($datos['newGrantor']).'</b> ';
        if($datos['newGrantorIdType']=='NIT'){
          $html .='empresa legalmente constituida';
        }else{
          $html .='mayor de edad ';
        }
        $html .=' identificado (a) con '.$datos['newGrantorIdType'].' No. '.number_format($datos['newGrantorId']);
        if (isset($datos['newGrantorExpedition']) && $datos['newGrantorExpedition'] != '' && $datos['newGrantorExpedition'] != null) {
           $html.=' expedida en '.$datos['newGrantorExpedition'].'</b>';
         }
         $html.=' con domicilio en la '.$datos['newGrantorAddress'].' en el municipio de <b>'.ucfirst($datos['newGrantorCity']).'</b> a quien en lo sucesivo se denominará <b>EL CEDENTE</b>; y de otra parte <b>'.mb_strtoupper($datos['newAssign']).'</b> ';
        if($datos['newAssignIdType']=='NIT'){
          $html .='empresa legalmente constituida';
        }else{
          $html .='mayor de edad ';
        }
        $html .=' identificado (a) con '.$datos['newAssignIdType'].' No. '.number_format($datos['newAssignId']);
        if (isset($datos['newAssignExpedition']) && $datos['newAssignExpedition'] != '' && $datos['newAssignExpedition'] != null) {
           $html.=' expedida en '.$datos['newAssignExpedition'].'</b>';
         }
         $html .=' con domicilio en la '.$datos['newAssignAddress'].' en el municipio de <b>'.ucfirst($datos['newAssignCity']).'</b> a quien en lo sucesivo se denominará <b>EL CESIONARIO</b>; con la intervención de '.$datos['newCeded'].' ';
        if($datos['newCededIdType']=='NIT'){
          $html .='empresa legalmente constituida';
        }else{
          $html .='mayor de edad ';
        }
        $html .=' identificado (a) con '.$datos['newCededIdType'].' No. '.number_format($datos['newCededId']);
        if (isset($datos['newCededExpedition']) && $datos['newCededExpedition'] != '' && $datos['newCededExpedition'] != null) {
           $html.=' expedida en '.$datos['newCededExpedition'].'</b>';
         }
         $html.=' con domicilio en la '.$datos['newCededAddress'].' en el municipio de <b>'.ucfirst($datos['newCededCity']).'</b> a quien en lo sucesivo se denominará <b>EL CEDIDO</b>; en los términos contenidos en las cláusulas siguientes:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>.-</b> Con fecha '.$datos['newStartDate'].' <b>EL CEDIDO</b> y <b>EL CEDENTE</b>, celebraron un contrato '.$datos['newContractType'].' del cual el primero '.$datos['newGrantorRol'].' cedió en favor del segundo '.$datos['newCededRol'].' la ejecución del contrato firmado entre estos';
        if($datos['newContractNotarialized']=='Si'){
          $html .=', identificado (a) e inscrito en la Notaria N.º '.$datos['newNotarie'].' de el Círculo '.$datos['newCircle'].' en los términos y condiciones pactadas en el referido contrato,';
        }
        $html .=' que <b>EL CEDIDO</b> declara conocer.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>.-</b> Las partes dejan constancia de que, a la fecha de celebración del presente acto, el contrato de '.$datos['newContractType'].' a que se refiere la cláusula anterior viene siendo cumplido cabalmente por ambas, manteniendo plena vigencia hasta el día '.$datos['newEndDate'].' fecha en la cual vence el plazo pactado en el mismo. No obstante, <b>EL CEDENTE</b> ha decidido apartarse del contrato por razones personales, motivo por el cual se celebra este acto.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>.- EL CESIONARIO</b> declara conocer todas y cada una de las estipulaciones del contrato en mención, así como el estado actual de la relación contractual existente entre <b>EL CEDIDO</b> y <b>EL CEDENTE</b>, habiendo tenido a la vista los documentos correspondientes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>.-</b> Por el presente contrato, <b>EL CEDENTE</b> manifiesta su voluntad de ceder, a favor de <b>EL CESIONARIO</b>, la posición contractual que ostenta en el contrato de '.$datos['newContractType'].' a que se refiere la cláusula primera. A su turno, <b>EL CESIONARIO</b> asume dicha posición contractual en el mencionado contrato, obligándose a cumplir todas y cada una de las prestaciones pactadas originalmente en el mismo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>..-. En armonía con lo establecido por el artículo 887 de Código de Comercio, <b>EL CEDIDO</b> manifiesta en este acto su conformidad con la cesión de posición contractual convenida en la cláusula precedente, admitiendo como nuevo arrendatario a <b>EL CESIONARIO</b> sin restricción ni limitación alguna.
</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>.-</b> A consecuencia de la cesión de posición contractual pactada en la cláusula cuarta, queda entendido que, a partir de la fecha de suscripción del presente documento, <b>EL CESIONARIO</b> asume la calidad de contratante en el descrito en la cláusula primera y, por consecuencia, queda obligado frente a <b>EL CEDIDO</b> a cumplir todas y cada una de las prestaciones allí establecidas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEPTIMA</u>.-</b> Queda entendido que tanto <b>EL CEDIDO</b> como <b>EL CESIONARIO</b> tienen expeditas las acciones legales correspondientes para exigirse recíprocamente el cumplimiento de las obligaciones derivadas del contrato de '.$datos['newContractType'].' a que alude la cláusula primera.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>.-</b> Si bien en virtud del presente acto <b>EL CEDENTE</b> se aparta de sus derechos y obligaciones derivadas del contrato, asumirá la responsabilidad correspondiente durante el resto del plazo de dicho contrato, en caso de que <b>EL CESIONARIO</b> incumpla cualquiera de las obligaciones cedidas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>.-</b> Las partes dejan establecido que la presente cesión de posición contractual tiene carácter absolutamente gratuito, por lo que <b>EL CESIONARIO</b> no está obligado al pago de contraprestación alguna en favor de <b>EL CEDENTE</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA</u>.- EL CEDENTE</b> declara que la validez del contrato a que alude la cláusula primera, así como su vigencia, se encuentran plenamente acreditadas, por lo que aquél se obliga a garantizar y responder frente a <b>EL CESIONARIO</b> en caso de que se pruebe lo contrario.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA PRIMERA</u>.-</b> Las partes acuerdan que todos los gastos y tributos que origine la celebración y ejecución de este contrato serán asumidos por <b>EL CESIONARIO</b>, salvo que es de cargo de <b>EL CEDENTE</b>./span>
</div>
<div class="row m2" style="text-align:justify;"> 
  <span class="font-size2"><b><u>Parágrafo:</u></b>Cualquier mejora realizada por EL ARRENDATARIO y no autorizada por el ARRENDADOR, quedará a favor del inmueble, sin que este deba ser cancelado por el ARRENDADOR.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEGUNDA</u>.-</b> Toda controversia o diferencia relativa a este contrato, en cuanto a su ejecución y liquidación, se resolverá por un tribunal de arbitramento que, por economía procesal, será designado por las partes, mismas que pactan que sea en el municipio '.ucfirst($datos['newContractCity']).', o en su defecto, en el domicilio donde se debió ejecutar el servicio contratado. El tribunal de Arbitramento se sujetará a lo dispuesto en el decreto 1818 de 1998 o estatuto orgánico de los sistemas alternativos de solución de conflictos y demás normas concordantes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA TERCERA</u>.-</b> Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA CUARTA</u>.-</b> A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">En señal de conformidad, este CONTRATO DE CESION, se firma tres (3) ejemplares similares, del mismo tenor y valor, para cada una de las partes, en '.ucfirst($datos['newContractCity']).', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL CEDENTE</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newGrantor']).'</b><br>
      <b>'.$datos['newGrantorIdType'].' No. '.$datos['newGrantorId'].'</b></span>
  </td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL CESIONARIO</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newAssign']).'</b><br>
      <b>'.$datos['newAssignIdType'].' No. '.$datos['newAssignId'].'</b></span>
  </td>
  </tr>
  <tr>
  <td class="col-6">
  <br><br><br>
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL CEDIDO</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newCeded']).'</b><br>
      <b>'.$datos['newCededIdType'].' No. '.$datos['newCededId'].'</b></span>
  </td>
  <td class="col-6">
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_cesion_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_cesion_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newGrantor']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);      
        $mpdf->Output();
    }
    public function pdfDomestico(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        if (isset($datos['newEndDate'])) {
          $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
          $dias = floor($inicio->diffInDays($fin));
        }
        $diasPrueba = ludcis\NumeroALetras::convertir($datos['newTestDays']);
        $diasAviso = ludcis\NumeroALetras::convertir($datos['newAlertDays']);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
        $valorLetrasEspecia = ludcis\NumeroALetras::convertir($datos['newSpiceSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasEspecia .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE TRABAJO DE SERVICIO DOMÉSTICO.</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del empleador: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Documento del empleador: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstIdType'].'. '.$datos['newFirstId'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del (la) trabajador(a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newSecondPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>'.$datos['newSecondIdType'].': </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondId'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio del (la) trabajador(a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAddress'].' del Barrio '.$datos['newSecondNeighborhood'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Teléfono del (la) trabajador(a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondPhone'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Lugar y fecha de Nacimiento: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondBornSite'].' el dia '.$datos['newSecondBornDate'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nacionalidad: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondNationality'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>EPS: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondEPS'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>AFP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAFP'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>ARP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondARP'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Cargo: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">Servicio doméstico</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Salario: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">$ '.number_format($datos['newSalary'],2).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Fecha de iniciación de labores: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newStartDate'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Ciudad de contratación: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newContractCity'].'</span>
        </td>
       </tr>
       </table>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre las partes (<b>EMPLEADOR (A)</b> y <b>EMPLEADO (A)</b>), ambos (as) mayores de edad, capaces para contratar, identificadas como se anota anteriormente, libre y voluntariamente, suscribimos <b>EL PRESENTE CONTRATO DE TRABAJO DE SERVICIO DÓMESTICO '.mb_strtoupper($datos['newContractType']).'</b>, y lo hacemos fundamentados en la Buena Fe, en especial, en el respeto a los principios del Derecho al Trabajo; con sujeción a las declaraciones y estipulaciones contenidas en las siguientes clausulas: </span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>.- Lugar: EL (LA) EMPLEADOR (A),</b> quien tiene su domicilio en la '.$datos['newFirstAddress'].', requiere contratar los servicios de una persona para las labores domésticas, en el domicilio antes señalado. <b>EL (LA) TRABAJADOR(A)</b>, desarrollará el objeto del <b>CONTRATO DE SERVICIO DOMÉSTICO <b>'.mb_strtoupper($datos['newContractType']).'</b> en la residencia <b>EL (LA) EMPLEADOR (A)</b>. En caso que este último, cambie de domicilio dentro de la misma ciudad, el contrato se entenderá modificado respecto al sitio de prestación de la labor.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>.- Funciones: </b>Con los antecedentes expuestos, <b>EL (LA) EMPLEADOR (A)</b>, contrata los servicios lícitos y personales de <b>EL (LA) TRABAJADOR(A)</b> del servicio doméstico para efectuar labores domésticas, tales como aseo, planchado, preparación de alimentos, cuidado de niños, lavado de ropa y todos los oficios inherentes al hogar. Comprometiéndose este último, a prestar sus servicios de carácter personal en calidad de <b>TRABAJADOR (A) DEL SERVICIO DOMÉSTICO</b> poniendo al servicio de <b>EL (LA) EMPLEADOR (A)</b>, toda su  capacidad normal de trabajo, en forma exclusiva, en el desempeño de las funciones propias del oficio mencionado y en las labores anexas y complementarias del mismo, de conformidad con las órdenes e instrucciones que le imparta <b>EL (LA) EMPLEADOR (A)</b> o sus representantes, las funciones y procedimientos establecidos para este, observando en su cumplimiento, la diligencia, honestidad, eficacia y el cuidado necesarios, durante la vigencia de este contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>.- Elementos de trabajo: </b>Corresponde a <b>EL (LA) EMPLEADOR (A)</b>, suministrar los elementos necesarios para el normal desempeño de las funciones del cargo contratado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>.- Obligaciones del (la) empleado (a): </b>En lo que respecta a las obligaciones, derechos y prohibiciones de <b>EL (LA) EMPLEADOR (A)</b> y <b>EL (LA) TRABAJADOR(A)</b>, estos se sujetan estrictamente a lo dispuesto en los artículos 57 y ss. del CST, por su parte, <b>EL (LA) TRABAJADOR(A)</b>, prestará los servicios domésticos en lugar señalado en la cláusula primera o en el sitio que corresponda por cambio de la misma, mientras se encuentre en ejecución el presente contrato, sin jornada de trabajo específica, pero sin exceder el máximo legal diario de diez (10) horas, para lo cual empleará su mejor ánimo y voluntad cuidando los objetos y elementos entregados y obedeciendo las órdenes que le imparta la empleadora, relacionadas con las funciones inherentes que no afectarán la dignidad humana.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>.- Término del contrato: </b>El presente contrato tendrá un término de duración ';
  if (isset($dias)) {
     $html .= 'de '.$dias.' días';
   }else{
     $html .= 'indefinida';
   } 
   $html .=', pero podrá darse por terminado por cualquiera de las partes, cumpliendo con las exigencias legales del articulo 61 y ss. del CST al respecto.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>.- Periodo de prueba:</b> Acuerdan las partes fijar como periodo de prueba los primeros '.$diasPrueba.' ('.$datos['newTestDays'].') días de servicio, a partir de la vigencia de este contrato. Durante este periodo, las partes pueden dar por terminado unilateralmente el contrato. Este periodo de prueba solo es para el contrato inicial y no se aplica en las prórrogas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEPTIMA</u>.- Justas causas de terminación del contrato:</b> Son justas causas para dar por terminado de manera unilateral, el presente contrato por cualquiera de las partes, las expresadas en los artículos 57 y ss. del Código sustantivo del Trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>.- Salario en moneda legal: EL (LA) EMPLEADOR (A),</b> cancelará a <b>EL (LA) TRABAJADOR(A)</b> del <b>SERVICIO DOMÉSTICO</b> un salario mensual de '.$valorLetrasTotal.' pesos moneda corriente ($ '.number_format($datos['newSalary'],2).'), pagaderos en el lugar de trabajo, el día '.$datos['newPaymentDay'].' de cada mes.<br>Salario en especie: El empleador pagará además del pago en dinero, una suma en especie equivalente a '.$valorLetrasEspecia.' ($ '.number_format($datos['newSpiceSalary'],2).'), por concepto de alimentación y hospedaje que se da en el lugar de trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Parágrafo:</b> Ambos valores, tanto en dinero como en especie conforman el salario y se suman para liquidar prestaciones sociales.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>.- Trabajo extra, en dominicales y festivos:</b> El trabajo suplementario o en horas extras, así como el trabajo en domingo o festivo que correspondan a descanso, al igual que los nocturnos, será remunerado conforme al código Sustantivo de trabajo. Es de advertir, que dicho trabajo debe ser autorizado u ordenado por <b>EL (LA) EMPLEADOR (A)</b>, para efectos de su reconocimiento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA</u>.- Afiliación y pago a seguridad social:</b> Es obligación de <b>EL (LA) EMPLEADOR (A)</b>, afiliar a <b>EL (LA) TRABAJADOR(A)</b> del <b>SERVICIO DOMÉSTICO</b>, a la seguridad social como es salud, pensión y riesgos profesionales, autorizando esta última, el descuento en su salario, de los valores que le corresponda aportar, en la proporción establecida por la ley.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA PRIMERA</u>.- Cláusula de confidencialidad: EL (LA) TRABAJADOR(A),</b> se obliga a guardar absoluta reserva de la información y documentación de la cual llegare a tener conocimiento, en cumplimiento de las funciones para las cuales fue contratado, en especial, no entregará, ni divulgará a terceros, salvo autorización previa y expresa de la Gerencia, información calificada por <b>EL (LA) EMPLEADOR (A)</b> como confidencial, reservada o estratégica. No podrá bajo ninguna circunstancia revelar información a persona natural o jurídica que afecte los intereses de <b>EL (LA) EMPLEADOR (A)</b>, durante su permanencia en el cargo, ni después de su retiro, so pena de incurrir en las acciones legales pertinentes consagradas para la protección de esta clase de información.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEGUNDA</u>.- Prorrogas: </b>Si el aviso de terminación unilateral del contrato, no se da o se da con una anticipación menor a '.$diasAviso.'('.$datos['newAlertDays'].') días, el contrato, se prorroga por un periodo igual a la inicial, siempre que subsistan las causas que lo originaron y la materia del trabajo. </span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA TERCERA</u>.- Modificaciones: </b>Cualquier modificación al presente contrato, debe ser pactado entre las partes y efectuarse por escrito, anexándolo a este documento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA CUARTA</u>.- Concordancia:</b> Este contrato ha sido redactado, estrictamente de acuerdo con la ley y la jurisprudencia; será interpretado de buena fe y en consonancia con el Código Sustantivo del Trabajo, cuyo objeto, definido en su artículo 1º, es lograr la justicia en las relaciones entre <b>EMPLEADORES</b> y <b>TRABAJADORES</b> dentro de un espíritu de coordinación económica y equilibrio social.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA QUINTA</u>.- Efectos jurídicos: </b>El presente contrato, reemplaza en su integridad y deja sin efecto, cualquier otro contrato verbal o escrito celebrado entre las partes, con anterioridad. Las modificaciones que se acuerden al presente contrato, se anotarán a continuación de su texto.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEXTA</u>.- Jurisdicción y competencia: </b>En caso de suscitarse discrepancias, ya sea en la interpretación, en el cumplimiento y/o la ejecución del presente Contrato; en su defecto cuando no fuere posible llegar a un acuerdo entre las Partes, estas, se someterán a los jueces laborales competentes, del lugar en que este contrato haya sido celebrado, así como al procedimiento determinado por la Ley.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA SEPTIMA</u>.- Suscripción y validez:</b> Las partes, se ratifican en todas y cada una de las cláusulas precedentes, donde para constancia y plena validez de lo estipulado, firman este contrato en original y dos (2) ejemplares de igual tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA OCTAVA</u>.</b>- A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Se firma en la ciudad de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL (LA) EMPLEADOR(A)</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newFirstPart']).'</b><br>
      <b>'.$datos['newFirstIdType'].'</b> No. <b>'.$datos['newFirstId'].'
  </td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL (LA) TRABAJADOR(A)</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].'</b> No. <b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_domestico_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_domestico_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);      
        $mpdf->Output();
    }
    public function pdfTrabajo(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $datos = $_POST;
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        if (isset($datos['newEndDate'])) {
          $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
          $mesFin = $this->mesLetras($fin);
        }
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $diasPrueba = $datos['newTestDays'];
        if ($_POST['newContractType']=='A término fijo inferior a un año') {
          $diff = $inicio->diffInDays($fin);
          $limite = floor($diff/5);
          if ($diasPrueba>$limite) {
            $diasPrueba=$limite;
          }
        }else{
          if ($diasPrueba>60) {
            $diasPrueba=60;
          }
        }
        $document->email = $datos['newFirstEmail'];
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $mesInicio = $this->mesLetras($inicio);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $prueba = ludcis\NumeroALetras::convertir($diasPrueba);
        $valorLetrasTotal .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE TRABAJO <b>'.mb_strtoupper($datos['newContractType']).'</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del empleador o representante: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b></span>
        </td>
      </tr>';
      if ($datos['newFirstType']=='PJ') {
        $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>';
      }
      $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newSecondPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>'.$datos['newSecondIdType'].': </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondId'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAddress'].' del barrio '.$datos['newSecondNeighborhood'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Teléfono del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondPhone'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Lugar y fecha de Nacimiento: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondBornSite'].' el dia '.$datos['newSecondBornDate'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nacionalidad: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondNationality'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>EPS: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondEPS'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>AFP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAFP'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>ARP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondARP'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Cargo: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newCharge'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Salario: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">$ '.number_format($datos['newSalary'],2).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Fecha de iniciación de labores: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newStartDate'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Ciudad de contratación: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newContractCity'].'</span>
        </td>
       </tr>
       </table>
       <div class="row m2" style="text-align:justify;">Las partes, que suscribimos <b>EL PRESENTE CONTRATO DE TRABAJO <b>'.mb_strtoupper($datos['newContractType']).'</b>, lo hacemos fundamentados en la Buena Fe, y en especial en el respeto a los principios del Derecho de Trabajo.</span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b>, identificado (a) con '.$datos['newFirstIdType'].' No. <b>'.$datos['newFirstId'].'</b>';
       if (isset($datos['newFirstExpedition']) && $datos['newFirstExpedition'] != '' && $datos['newFirstExpedition'] != null) {
          $html.=' de <b>'.$datos['newFirstExpedition'].'</b>';
        }
        $html.=', en mi calidad de empleador';
        if ($datos['newFirstType']=='PJ') {
           $html.=' en representación de la empresa '.$datos['newFirstCompany'].', Identificada con '.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].', con domicilio comercial en la '.$datos['newFirstAddress'].' de la ciudad de '.$datos['newFirstCity'];
         }
         $html.=', quien en adelante se denominará EMPLEADOR y <b>'.mb_strtoupper($datos['newSecondPart']).'</b>, identificado (a) con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].' residente en la ciudad de '.$datos['newSecondCity'].', quien en adelante se denominará TRABAJADOR, quien desempeñará el cargo de '.$datos['newCharge'].' acuerdan celebrar el presente CONTRATO INDIVIDUAL DE TRABAJO <b>'.mb_strtoupper($datos['newContractType']).'</b>, para ser ejecutado en la ciudad de '.$datos['newContractCity'].', el cual se regirá por las siguientes cláusulas:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>. - EL EMPLEADOR</b> contrata los servicios personales de <b>EL TRABAJADOR</b> y éste se obliga: a) A poner al servicio del <b>EMPLEADOR</b> toda su  capacidad normal de trabajo, en forma exclusiva, en el desempeño de las funciones propias del oficio mencionado y en las labores anexas y complementarias del mismo, de conformidad con las órdenes e instrucciones que le imparta <b>EL EMPLEADOR</b> o sus representantes, las funciones y procedimientos establecidos para este, observando en su cumplimiento, la diligencia, honestidad, eficacia y el cuidado necesarios; y b) A no prestar directa ni indirectamente servicios laborales a otros <b>EMPLEADORES</b>, ni a trabajar por cuenta propia en el mismo oficio, en las instalaciones de la empresa y horarios laborales, durante la vigencia de este contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>. - </b>Las partes declaran que, en el presente contrato, se entienden incorporadas en lo pertinente, las disposiciones legales que regulan las relaciones entre <b>LA EMPRESA</b> y sus <b>TRABAJADORES</b>, en especial, las del contrato de trabajo para el oficio que se suscribe, fuera de las obligaciones consignadas en los reglamentos de trabajo y de higiene y seguridad industrial de la empresa.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>. - </b>En relación con la actividad propia del <b>EMPLEADO</b>, éste la ejecutará dentro de las siguientes modalidades que implican claras obligaciones para <b>EL EMPLEADO</b> así:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>OBLIGACIONES ESPECIALES DEL TRABAJADOR: El TRABAJADOR </b>se obliga especialmente.</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2"> A laborar la jornada ordinaria en los turnos y dentro de horas que asigne <b>LA EMPRESA</b>, pudiendo esta, ordenar los cambios o ajustes que sean necesarios para el adecuado funcionamiento de las actividades y labores.  Entendiendo que la jornada de trabajo se inicia cuando <b>EL TRABAJADOR</b> está listo y disponible en el sitio donde se desarrolla la labor.</li>
        <li class="font-size2 m2">A no desempeñar labor alguna, ni ejercer otra actividad, fuera de las horas de trabajo al servicio del <b>EMPLEADOR</b>, que pueda afectar o poner en peligro su seguridad, su salud o su descanso.</li>
        <li class="font-size2 m2">Prestar el servicio para el que fue contratado personalmente, en el lugar del territorio de la Republica de Colombia, que indicare <b>EL EMPLEADOR</b> y excepcionalmente, fuera de dicho territorio cuando las necesidades del servicio así lo requieran.</li>
        <li class="font-size2 m2">Observar rigurosamente las normas que le fije la empresa para la realización de la labor a que se refiere el presente contrato.</li>
        <li class="font-size2 m2">A prestar toda la colaboración necesaria en caso de siniestro o de riesgo que afecte o amenace a las personas o a los bienes de <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A no atender durante las horas de trabajo, asuntos o actividades distintas de las que <b>LA EMPRESA</b> le señale, sin previa autorización de esta; Y por ninguna circunstancia, actividades de carácter personal y ajenas al objeto de este contrato.</li>
        <li class="font-size2 m2">A asistir puntualmente al turno asignado, todos los días laborales, salvo que se lo impida una justa causa comprobada, además, de dedicar la totalidad de su jornada de trabajo a cumplir a cabalidad con las funciones establecidas de acuerdo al cargo.</li>
        <li class="font-size2 m2">Ejecutar por sí mismo las funciones asignadas y cumplir estrictamente las instrucciones, manuales, procesos y procedimientos que le sean dadas por <b>LA EMPRESA</b>, o por quienes la representen, respecto del desarrollo de sus actividades, programando y elaborando diariamente su trabajo de forma eficiente.</li>
        <li class="font-size2 m2">Si luego de finalizada la tarea de la jornada, aún no ha terminado la jornada de trabajo, el trabajador, prestará sus servicios en las labores que le asigne <b>LA EMPRESA</b> que hagan parte de la labor y en los oficios para los cuales se encuentre apto y capacitado.</li>
        <li class="font-size2 m2">A aceptar, los cambios de oficio o de tareas a realizar, dentro de la labor arriba descrita, siempre y cuando, <b>EL TRABAJADOR</b> se encuentre en condiciones de desempeñarse en tales oficios.</li>
        <li class="font-size2 m2">A laborar el tiempo extra y los días festivos o dominicales que sean señalados por <b>LA EMPRESA</b>, cuando por razones técnicas, administrativas o del servicio así se requiera.</li>
        <li class="font-size2 m2">Se Podrán cambiar en forma permanente o intermitente de día de descanso semanal al sábado, dependiendo de las características del servicio que este prestando <b>EL EMPLEADOR</b>, en cuyo caso se remunerará conforme a lo ordenado por la ley 789 de 2002.</li>
        <li class="font-size2 m2">A cumplir a cabalidad con los manuales, reglamentos internos, políticas y procedimientos de <b>LA EMPRESA</b>, los cuales declara conocer a la firma de este contrato.</li>
        <li class="font-size2 m2">A cumplir las normas de seguridad y prevención de accidentes que tenga <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A asistir puntualmente, a las reuniones y capacitaciones programadas por <b>EL EMPLEADOR</b>, en aras de mejorar la formación del trabajador, la productividad y calidad de la empresa.</li>
        <li class="font-size2 m2">A conservar y restituir en buen estado, los instrumentos, los insumos, útiles y herramientas, que le haya entregado <b>EL EMPLEADOR</b>, bajo su cuidado y custodia para la ejecución de sus tareas o labores.</li>
        <li class="font-size2 m2">A conservar completa armonía y comprensión con los clientes, proveedores, autoridades de vigilancia y control, con sus superiores y compañeros de trabajo, en sus relaciones interpersonales y en la ejecución de su labor, preservando el respeto y la cordialidad que debe mantenerse en las relaciones sociales y de trabajo.</li>
        <li class="font-size2 m2">A Guardar absoluta reserva, salvo autorización expresa de <b>LA EMPRESA</b>, de todas aquellas informaciones que lleguen a su conocimiento, en razón de su trabajo y que sean por naturaleza privadas.</li>
        <li class="font-size2 m2">A cuidar permanentemente los intereses, instalaciones, muebles y equipos de oficina, de cómputo, enseres, vehículos, maquinaria, herramientas, materias primas, material de empaque y productos elaborados de <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A cumplir permanentemente, sus labores con espíritu de lealtad, compañerismo, colaboración y disciplina con <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A Avisar oportunamente y por escrito a <b>LA EMPRESA</b>, todo cambio en su dirección, teléfono o ciudad de residencia.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PROHIBICIONES DEL TRABAJADOR: </b>Acuerdan las partes las siguientes prohibiciones al trabajador, además, de las consagradas en la ley y en el reglamento interno de Trabajo:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">La inasistencia a laborar, sin una excusa suficiente que lo justifique, Los retrasos reiterados en la iniciación de la jornada de trabajo sin una justa causa que lo amerite, además, de la ejecución de actividades a las propias de su oficio en horas de trabajo, para terceros ya fueren remuneradas o no, o para su provecho personal.</li>
        <li class="font-size2 m2">Emplear en su trabajo y en el trato con sus compañeros de trabajo, clientes o visitantes un vocabulario soez, descortés, altanero, indecente o poco decoroso.</li>
        <li class="font-size2 m2">Todo acto de violencia, deslealtad, injuria, actos indecentes o inmorales, malos tratos o grave indisciplina, en que incurra <b>EL TRABAJADOR</b> en sus labores contra <b>LA EMPRESA</b>, el personal directivo, sus compañeros de trabajo o sus superiores, clientes o proveedores.</li>
        <li class="font-size2 m2">Dar a las herramientas o equipos de trabajo, un uso o destino contrario a aquel para el cual fueron entregados.</li>
        <li class="font-size2 m2">Ceder, cambiar, los equipos o herramientas asignados, de los cuales es responsable mientras se encuentran en su poder.</li>
        <li class="font-size2 m2">Solicitar préstamos especiales o ayuda económica a los compañeros de trabajo, clientes, o proveedores del <b>EMPLEADOR</b>, aprovechándose de su cargo u oficio o en su defecto, aceptarles donaciones de cualquier clase sin la previa autorización escrita del <b>EMPLEADOR</b>.</li>
        <li class="font-size2 m2">Pedir o recibir dinero de los clientes de <b>LA EMPRESA</b> y darles un destino diferente a estos o no entregarlo en su debida oportunidad, a quien corresponda en la oficina de <b>LA EMPRESA</b> y/o retener dinero o hacer efectivo cheques recibidos para <b>EL EMPLEADOR</b>.</li>
        <li class="font-size2 m2">Autorizar o ejecutar sin ser de su competencia, operaciones que afecten los intereses del <b>EMPLEADOR</b> o negociar bienes y/o mercancías del empleador en provecho propio.</li>
        <li class="font-size2 m2">Presentar cuentas de gastos ficticias o reportar como cumplidas visitas o tareas no efectuadas.</li>
        <li class="font-size2 m2">Cualquier actitud en los compromisos comerciales, personales o en las relaciones sociales, que pueda afectar en forma nociva la reputación de <b>EL EMPLEADOR.</b></li>
        <li class="font-size2 m2">Retirar de las instalaciones donde funcione <b>LA EMPRESA</b> elementos, maquinaria, materia prima y útiles de propiedad del <b>EMPLEADOR</b> sin su autorización escrita.</li>
    </ul>
</div>';
if($datos['newCharge'] == "Conductor" || $datos['newCharge'] == "conductor" || $datos['newCharge'] == "CONDUCTOR") {
  $html .='<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Parágrafo 1º: </b>En relación con la actividad propia y especifica del <b>EMPLEADO</b>, en el cargo de <b>'.$datos['newCharge'].'</b>, éste, ejecutará su servicio, en relación o estará estrictamente ligado, a cumplir con el manual de funciones de su cargo, y en especial, cumplirá las siguientes obligaciones:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">Revisar a diario el vehículo en su parte mecánica y eléctrica antes de salir, a realizar cualquier entrega o recogida de materia prima o enseres, con el fin, de prevenir posibles averías y accidentes, las cuales deben ser reportadas de inmediato al propietario del vehículo automotor.</li>
        <li class="font-size2 m2">Programar con antelación las fechas de cambio de aceite, revisiones mecánicas y mantenimiento en general.</li>
        <li class="font-size2 m2">Mantener el vehículo aseado.</li>
        <li class="font-size2 m2">Cancelar las infracciones o FOTUMULTAS realizadas al vehículo a su cargo, y que se encuentren inmersas en los horarios de prestación de su servicio, presentando paz y salvo por el SIMIT, tanto al comienzo, como a la terminación del contrato, so pena del no pago de liquidaciones y otros emolumentos, hasta no presentar el respectivo paz y salvo.</li>
        <li class="font-size2 m2">Estar al pendiente del vencimiento de los documentos (SOAT, TECNICOMECANICA, seguros a terceros etc.), del vehículo a su cargo, so pena de tener que realizar de su propio peculio, pago de cualquier infracción al mismo, por estos conceptos.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Parágrafo 2º: </b>Puesto que, conducir, se convierte en una actividad de alto riesgo, se le prohíbe al <b>TRABAJADOR</b> que ocupe, este las siguientes:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">Utilizar el vehículo en mención para realizar actividades diferentes a las establecidas por la empresa.</li>
        <li class="font-size2 m2">Que personas diferentes al responsable conduzca el vehículo.</li>
        <li class="font-size2 m2">Desplazar el vehículo fuera del perímetro urbano de '.$datos['newContractCity'].', salvo previa autorización escrita, de la gerencia de la compañía.</li>
        <li class="font-size2 m2">Utilizar el vehículo en horas y días distintos a las establecidas por la empresa.</li>
        <li class="font-size2 m2">Conducir en estado de embriaguez o bajo cualquier otro efecto ya sea alucinógeno, psicotrópico, entre otro estado que limite sus capacidades, tanto físicas como mentales.</li>
        <li class="font-size2 m2">Movilizar el vehículo cuando presente averías de orden mecánico, eléctrico o insuficiente combustible.</li>
        <li class="font-size2 m2">Movilizar el vehículo sin los documentos respectivos, tanto del conductor como del vehículo.</li>
    </ul>
</div>';
}
$html .='
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>. - </b>Se remunerará con un salario básico mensual de '.$valorLetrasTotal.' M/L ($ '.number_format($datos['newSalary'],2).'), pagaderos quincenalmente. Dentro de este pago se encuentra incluida la remuneración de los descansos dominicales y festivos de que tratan los capítulos I y II del título VII del Código Sustantivo del Trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Las partes hacen constar que en esta remuneración queda incluido el pago de los servicios que <b>EL TRABAJADOR</b> se obliga a realizar durante el tiempo estipulado en el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - </b>Si <b>EL TRABAJADOR</b> prestare su servicio en día dominical o festivo, sin previa autorización por escrito del <b>EMPLEADOR</b>, no tendrá derecho a reclamar remuneración alguna por este día.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO TERCERO. - EL EMPLEADOR</b> no suministra ninguna clase de salario en especie.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO CUARTO. - </b>Cuando por causa emanada directa o indirectamente de la relación contractual existan obligaciones de tipo económico a cargo del <b>TRABAJADOR</b> y a favor del <b>EMPLEADOR</b>, éste procederá a efectuar las deducciones a que hubiere lugar en cualquier tiempo y, más concretamente, a la terminación del presente contrato, así lo autoriza desde ahora <b>EL TRABAJADOR</b>, entendiendo expresamente las partes que la presente autorización cumple las condiciones, de orden escrita previa, aplicable para cada caso.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>. - </b>Las partes en el citado contrato, acuerdan expresamente que lo entregado en dinero o en especie por parte del <b>EMPLEADOR</b> al <b>TRABAJADOR</b> por concepto de beneficios cualquiera sea su denominación de acuerdo al artículo 15 de la ley 50 de 1990, no constituyen salario, en especial: los auxilios o contribuciones que otorgue <b>EL EMPLEADOR</b> por concepto de alimentación para <b>EL TRABAJADOR</b>, de bonificaciones extraordinarias y demás auxilios otorgados por mera liberalidad del <b>EMPLEADOR</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Cualquier beneficio que se entregue al <b>TRABAJADOR</b> sólo se le otorgará como mera liberalidad del empleador, por tanto, no constituye salario, ni pago laboral que sea base para el cálculo y pago de prestaciones sociales, aportes parafiscales, a cajas de compensación, <b>SENA</b> o <b>ICBF</b>, entre otros; como tampoco es base para la determinación de las contribuciones o aportes al Sistema de Seguridad Social Integral, tales como: salud, pensión, riesgos profesionales, fondo de solidaridad, etc. Las partes acuerdan desde ahora que en ningún caso los pagos que se entreguen como auxilios o beneficios constituyen salario o son base para las cotizaciones, contribuciones o aportes antes descritos.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>. - </b>Todo trabajo suplementario o en horas extras y todo trabajo en día domingo o festivo en los que legalmente debe concederse descanso, se remunerará conforme a la ley, así como los correspondientes recargos nocturnos. Para el reconocimiento y pago del trabajo suplementario, dominical o festivo, <b>EL EMPLEADOR</b> o sus representantes, deben autorizarlo previamente por escrito. Cuando la necesidad de este trabajo se presente de manera imprevista o inaplazable, deberá ejecutarse y darse cuenta de él por escrito, o en forma verbal, a la mayor brevedad al <b>EMPLEADOR</b> o a sus representantes. <b>EL EMPLEADOR</b>, en consecuencia, no reconocerá ningún trabajo suplementario o en días de descanso legalmente obligatorio que no haya sido autorizado previamente o avisado inmediatamente, como queda dicho.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEPTIMA</u>. - EL TRABAJADOR</b> se obliga a laborar la jornada ordinaria en los turnos y dentro de las horas señalados por <b>EL EMPLEADOR</b>, pudiendo hacer éste ajustes o cambios de horario cuando lo estime conveniente. <b>(IUS VARIANDI)</b> Por el acuerdo expreso o tácito de las partes, podrán repartirse las horas de la jornada ordinaria en la forma prevista en el artículo 164 del Código Sustantivo del Trabajo, modificado por el artículo 23 de la Ley 50 de 1990, teniendo en cuenta que los tiempos de descanso entre las secciones de la jornada no se computan dentro de la misma, según el artículo 167 ibídem. Así mismo el empleador y el trabajador podrán acordar que la jornada semanal de cuarenta y ocho (48) horas se realice mediante jornadas diarias flexibles de trabajo, distribuidas en máximo seis (6) días a la semana con un (1) día de descanso obligatorio, que podrá coincidir con el domingo. En éste, el número de horas de trabajo diario podrá repartirse de manera variable durante la respectiva semana y podrá ser de mínimo cuatro (4) horas continuas y hasta diez (10) horas diarias sin lugar a ningún recargo por trabajo suplementario, cuando el número de horas de trabajo no exceda el promedio de cuarenta y ocho (48) horas semanales dentro de la jornada ordinaria de 6 a.m. a 10 p.m.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>. - </b>Este contrato es un <b>CONTRATO INDIVIDUAL DE TRABAJO <b>'.mb_strtoupper($datos['newContractType']).'</b>, a partir del día '.$inicio->format('d').' mes '.$mesInicio.' año '.$inicio->format('Y'); 
  if (isset($fin)) {
    $html .='y hasta el día '.$fin->format('d').' mes '.$mesFin.' año '.$inicio->format('Y');
  }
  $html.=' , permaneciendo este, mientras subsistan las causas que le dieron origen a ese contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARAGRAFO. - </b>Los primeros ('.$diasPrueba.') '.$prueba.' días del presente contrato, se consideran como <b>PERÍODO DE PRUEBA</b> y, por consiguiente, cualquiera de las partes podrá terminar el contrato unilateralmente, en cualquier momento durante dicho período.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>. - </b>Son justas causas para dar por terminado unilateralmente este contrato por cualquiera de las partes, las enumeradas en el artículo 7º del Decreto 2351 de 1965; y, además, por parte del <b>EMPLEADOR</b>, las faltas que para el efecto se califiquen como graves contempladas en el reglamento interno de trabajo y en el espacio reservado para cláusulas adicionales en el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA</u>. - CLÁUSULA DE CONFIDENCIALIDAD: EL TRABAJADOR</b> se obliga a guardar absoluta reserva de la información y documentación de la cual llegare a tener conocimiento, en cumplimiento de las funciones para las cuales fue contratado, en especial, no entregará, ni divulgará a terceros, salvo autorización previa y expresa de la Gerencia, información calificada Por <b>EL EMPLEADOR</b> como confidencial, reservada o estratégica. No podrá bajo ninguna circunstancia revelar información a persona natural o jurídica que afecte los intereses de <b>EL EMPLEADOR</b>, durante su permanencia en el cargo, ni después de su retiro, so pena de incurrir en las acciones legales pertinentes consagradas para la protección de esta clase de información.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DECIMA PRIMERA</u>. - </b>Este contrato ha sido redactado estrictamente de acuerdo con la ley y la jurisprudencia; será interpretado de buena fe y en consonancia con el Código Sustantivo del Trabajo, cuyo objeto, definido en su artículo 1º, es lograr la justicia en las relaciones entre <b>EMPLEADORES</b> y <b>TRABAJADORES</b> dentro de un espíritu de coordinación económica y equilibrio social.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SEGUNDA</u>. - </b>El presente contrato reemplaza en su integridad y deja sin efecto, cualquier otro contrato verbal o escrito celebrado entre las partes con anterioridad. Las modificaciones que se acuerden al presente contrato, se anotarán a continuación de su texto. Para constancia se firma en dos (2) o más ejemplares del mismo tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA TERCERA</u>. - </b>A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Se firma en la ciudad de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL EMPLEADOR</b></span><br>';
  if ($datos['newFirstType']=='PJ') {
    $footer.='<span class="font-size3 start">'.$datos['newFirstCompany'].'<br>
      <b>'.$datos['newFirstCompanyIdType'].'</b> No. <b>'.$datos['newFirstCompanyId'].'</span>';
  }else{
    $footer.='<span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].'</b> No. <b>'.$datos['newFirstId'].'</span>';
  }
  $footer.='</td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL TRABAJADOR</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].'</b> No. <b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_trabajo_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_trabajo_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfTeletrabajo(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $datos = $_POST;
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        if (isset($datos['newEndDate'])) {
          $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
          $mesFin = $this->mesLetras($fin);
        }
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $diasPrueba = $datos['newTestDays'];
        if ($_POST['newContractType']=='A término fijo inferior a un año') {
          $diff = $inicio->diffInDays($fin);
          $limite = floor($diff/5);
          if ($diasPrueba>$limite) {
            $diasPrueba=$limite;
          }
        }else{
          if ($diasPrueba>60) {
            $diasPrueba=60;
          }
        }
        $document->email = $datos['newFirstEmail'];
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $mesInicio = $this->mesLetras($inicio);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $prueba = ludcis\NumeroALetras::convertir($diasPrueba);
        $valorLetrasTotal .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
body {
    background: url("Views/img/plantilla/FONDO_DOCUMENTO.png") no-repeat 0 0;
    background-image-resize: 6;
}
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #f1f2f2;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head> 
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE TRABAJO MODALIDAD TELETRABAJO <b>'.mb_strtoupper($datos['newContractType']).'</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del empleador o representante: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b></span>
        </td>
      </tr>';
      if ($datos['newFirstType']=="PJ") {
        $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>';
      }
      $html.='<tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newSecondPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>'.$datos['newSecondIdType'].': </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newSecondId'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newSecondAddress'].' del barrio '.$datos['newSecondNeighborhood'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Teléfono del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newSecondPhone'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Lugar y fecha de Nacimiento: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newSecondBornSite'].' el dia '.$datos['newSecondBornDate'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nacionalidad: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newSecondNationality'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>EPS: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.mb_strtoupper($datos['newSecondEPS']).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>AFP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.mb_strtoupper($datos['newSecondAFP']).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>ARP: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.mb_strtoupper($datos['newSecondARP']).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Cargo: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newCharge'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Salario: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">$ '.number_format($datos['newSalary'],2).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Periodicidad: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newPaymentCicle'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Fecha de iniciación de labores: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newStartDate'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Ciudad de contratación: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.ucfirst($datos['newContractCity']).'</span>
        </td>
       </tr>
       </table>
       <div class="row m2" style="text-align:justify;">Las partes, que suscribimos <b>EL PRESENTE CONTRATO DE TRABAJO '.mb_strtoupper($datos['newContractType']).'</b>, lo hacemos fundamentados en la Buena Fe, y en especial en el respeto a los principios del Derecho de Trabajo.</span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b>, identificado (a) con '.$datos['newFirstIdType'].' No. <b>'.$datos['newFirstId'].'</b>';
       if ($datos['newFirstExpedition'] != '' && $datos['newFirstExpedition'] != null) {
          $html.=' de <b>'.$datos['newFirstExpedition'].'</b>';
        }
        $html.=', en mi calidad de <b>EMPLEADOR</b>';
        if ($datos['newFirstType']=='PJ') {
           $html.=' en representación de la empresa '.$datos['newFirstCompany'].', Identificada con '.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].', con domicilio comercial en la '.$datos['newFirstAddress'].' de la ciudad de '.ucfirst($datos['newFirstCity']);
         }
         $html.=', quien en adelante se denominará <b>EMPLEADOR</b> y <b>'.mb_strtoupper($datos['newSecondPart']).'</b>, identificado (a) con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].' residente en la ciudad de '.ucfirst($datos['newSecondCity']).', quien en adelante se denominará <b>TELETRABAJADOR</b>, quien desempeñará el cargo de '.$datos['newCharge'].' acuerdan celebrar el presente CONTRATO INDIVIDUAL DE TELETRABAJO <b>'.mb_strtoupper($datos['newContractType']).'</b>, para ser ejecutado en la '.$datos['newWorkAddress'].', el cual se regirá por las siguientes cláusulas:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>. - EL EMPLEADOR</b> contrata los servicios personales de <b>EL TELETRABAJADOR</b> y éste se obliga: a) A poner al servicio del <b>EMPLEADOR</b> toda su  capacidad normal de trabajo, en forma exclusiva, en el desempeño de las funciones propias del oficio mencionado y en las labores anexas y complementarias del mismo, de conformidad con las órdenes e instrucciones que le imparta <b>EL EMPLEADOR</b> o sus representantes, las funciones y procedimientos establecidos para este, observando en su cumplimiento, la diligencia, honestidad, eficacia y el cuidado necesarios; y b) A no prestar directa ni indirectamente servicios laborales a otros <b>EMPLEADORES</b>, ni a trabajar por cuenta propia en el mismo oficio, en su lugar de teletrabajo y horarios laborales, durante la vigencia de este contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>. - </b>Las partes declaran que, en el presente contrato, se entienden incorporadas en lo pertinente, las disposiciones legales que regulan las relaciones entre <b>LA EMPRESA</b> y sus <b>TRABAJADORES</b>, en especial, las del contrato de trabajo para el oficio que se suscribe, fuera de las obligaciones consignadas en los reglamentos de trabajo y de higiene y seguridad industrial de la empresa.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>. - Lugar de Trabajo.</b>Para efectos del presente acuerdo, el <b>TRABAJADOR</b> desempeñara las funciones propias de su puesto de trabajo, bajo la modalidad de <b>TELETRABAJO</b>, en la '.$datos['newWorkAddress'].'. En dicho lugar el <b>TRABAJADOR</b> realizará su trabajo '.$datos['newWeekDays'].' días por semana.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Un día (1) a la semana u ocasionalmente (según lo acordado), <b>EL TELETRABAJADOR</b> deberá presentarse en las instalaciones del <b>EMPLEADOR</b>, para capacitaciones, evaluación de resultados, etc., sin que su presencia en las instalaciones signifique sustitución de <b>EL TELETRABAJO</b> por trabajo presencial.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - </b> En caso de que <b>EL EMPLADOR</b> o <b>EL TELETRABAJADOR</b> quisieran modificar el lugar de trabajo, por trabajo presencial, se deberá acordar de mutuo acuerdo dicha modificación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>. - Espacio de Trabajo. El TELETRABAJADOR</b> deberá realizar sus actividades laborales en el espacio acordado previamente por ÉL, <b>EL EMPLEADOR</b> y la <b>ARL</b>. No podrá ser en otros lugares que no cumplan con las condiciones de seguridad e higiene adecuadas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>. - Derechos del Teletrabajador. El TELETRABAJADOR</b> tendrá derecho a disfrutar de todos los derechos mínimos consagrados en el C.S.T., en especial, los referentes a descansos, vacaciones, prestaciones sociales, afiliación integral a Seguridad Social. Asimismo, <b>EL TELETRABAJADOR</b> tendrá las mismas obligaciones laborales que los demás empleados, las cuales, en relación con la actividad propia de <b>EL EMPLEADO</b>, éste la ejecutará dentro de las siguientes modalidades que implican claras obligaciones para este así:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>OBLIGACIONES ESPECIALES DEL TRABAJADOR: El TRABAJADOR </b>se obliga especialmente.</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">A laborar la jornada ordinaria en los turnos y dentro de horas que asigne <b>LA EMPRESA</b>, pudiendo esta, ordenar los cambios o ajustes que sean necesarios para el adecuado funcionamiento de las actividades y labores.  Entendiendo que la jornada de trabajo se inicia cuando <b>EL TELETRABAJADOR</b> está listo y disponible en el sitio donde se desarrolla la labor.</li>
        <li class="font-size2 m2">A no desempeñar labor alguna, ni ejercer otra actividad, fuera de las horas de trabajo al servicio del <b>EMPLEADOR</b>, que pueda afectar o poner en peligro su seguridad, su salud o su descanso.</li>
        <li class="font-size2 m2">Prestar el servicio para el que fue contratado personalmente, en el lugar del territorio de la Republica de Colombia, que indicare <b>EL EMPLEADOR</b> y excepcionalmente, fuera de dicho territorio cuando las necesidades del servicio así lo requieran.</li>
        <li class="font-size2 m2">Observar rigurosamente las normas que le fije la empresa para la realización de la labor a que se refiere el presente contrato.</li>
        <li class="font-size2 m2">A prestar toda la colaboración necesaria en caso de siniestro o de riesgo que afecte o amenace a las personas o a los bienes de <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A no atender durante las horas de trabajo, asuntos o actividades distintas de las que <b>LA EMPRESA</b> le señale, sin previa autorización de esta; Y por ninguna circunstancia, actividades de carácter personal y ajenas al objeto de este contrato.</li>
        <li class="font-size2 m2">A comenzar puntualmente al turno asignado, todos los días laborales, salvo que se lo impida una justa causa comprobada, además, de dedicar la totalidad de su jornada de trabajo a cumplir a cabalidad con las funciones establecidas de acuerdo al cargo.</li>
        <li class="font-size2 m2">Ejecutar por sí mismo las funciones asignadas y cumplir estrictamente las instrucciones, manuales, procesos y procedimientos que le sean dadas por <b>LA EMPRESA</b>, o por quienes la representen, respecto del desarrollo de sus actividades, programando y elaborando diariamente su trabajo de forma eficiente.</li>
        <li class="font-size2 m2">Si luego de finalizada la tarea de la jornada, aún no ha terminado la jornada de teletrabajo, <b>EL TELETRABAJADOR</b>, prestará sus servicios en las labores que le asigne <b>LA EMPRESA</b> que hagan parte de la labor y en los oficios para los cuales se encuentre apto y capacitado.</li>
        <li class="font-size2 m2"> A aceptar, los cambios de oficio o de tareas a realizar, dentro de la labor arriba descrita, siempre y cuando, <b>EL TELETRABAJADOR</b> se encuentre en condiciones de desempeñarse en tales oficios.</li>
        <li class="font-size2 m2">A laborar el tiempo extra y los días festivos o dominicales que sean señalados por <b>LA EMPRESA</b>, cuando por razones técnicas, administrativas o del servicio así se requiera.</li>
        <li class="font-size2 m2">Se Podrán cambiar en forma permanente o intermitente de día de descanso semanal al sábado, dependiendo de las características del servicio que este prestando <b>EL EMPLEADOR</b>, en cuyo caso se remunerará conforme a lo ordenado por la ley 789 de 2002.</li>
        <li class="font-size2 m2">A cumplir a cabalidad con los manuales, reglamentos internos, políticas y procedimientos de <b>LA EMPRESA</b>, los cuales declara conocer a la firma de este contrato.</li>
        <li class="font-size2 m2">A cumplir las normas de seguridad y prevención de accidentes que tenga <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A participar puntualmente, en las reuniones y capacitaciones programadas por <b>EL EMPLEADOR</b>, en aras de mejorar la formación del <b>TRABAJADOR</b>, la productividad y calidad de la empresa.</li>
        <li class="font-size2 m2">A conservar y restituir en buen estado, los instrumentos, los insumos, útiles y herramientas, que le haya entregado <b>EL EMPLEADOR</b>, bajo su cuidado y custodia para la ejecución de sus tareas o labores.</li>
        <li class="font-size2 m2">A conservar completa armonía y comprensión con los clientes, proveedores, autoridades de vigilancia y control, con sus superiores y compañeros de trabajo, en sus relaciones interpersonales y en la ejecución de su labor, preservando el respeto y la cordialidad que debe mantenerse en las relaciones sociales y de trabajo.</li>
        <li class="font-size2 m2">A Guardar absoluta reserva, salvo autorización expresa de <b>LA EMPRESA</b>, de todas aquellas informaciones que lleguen a su conocimiento, en razón de su trabajo y que sean por naturaleza privadas.</li>
        <li class="font-size2 m2">A cuidar permanentemente los intereses, instalaciones, muebles y equipos de oficina, de cómputo, enseres, vehículos, maquinaria, herramientas, materias primas, material de empaque y productos elaborados de <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A cumplir permanentemente, sus labores con espíritu de lealtad, compañerismo, colaboración y disciplina con <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A Avisar oportunamente y por escrito a <b>LA EMPRESA</b>, todo cambio en su dirección, teléfono o ciudad de residencia.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PROHIBICIONES DEL TRABAJADOR: </b>Acuerdan las partes las siguientes prohibiciones al <b>TRABAJADOR</b>, además, de las consagradas en la ley y en el reglamento interno de Trabajo:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">La inejecución de las labores, sin una excusa suficiente que lo justifique, los retrasos reiterados en la iniciación de la jornada de trabajo sin una justa causa que lo amerite, además, de la ejecución de actividades a las propias de su oficio en horas de trabajo, para terceros ya fueren remuneradas o no, o para su provecho personal.</li>
        <li class="font-size2 m2">Emplear en su trabajo y en el trato con sus compañeros de trabajo, clientes o visitantes un vocabulario soez, descortés, altanero, indecente o poco decoroso.</li>
        <li class="font-size2 m2">Todo acto de violencia, deslealtad, injuria, actos indecentes o inmorales, malos tratos o grave indisciplina, en que incurra <b>EL TELETRABAJADOR</b> en sus labores contra <b>LA EMPRESA</b>, el personal directivo, sus compañeros de trabajo o sus superiores, clientes o proveedores, por cualquier medio.</li>
        <li class="font-size2 m2">Dar a las herramientas o equipos de trabajo, un uso o destino contrario a aquel para el cual fueron entregados.</li>
        <li class="font-size2 m2">Ceder, cambiar, los equipos o herramientas asignados, de los cuales es responsable mientras se encuentran en su poder.</li>
        <li class="font-size2 m2">Solicitar préstamos especiales o ayuda económica a los compañeros de trabajo, clientes, o proveedores del <b>EMPLEADOR</b>, aprovechándose de su cargo u oficio o en su defecto, aceptarles donaciones de cualquier clase sin la previa autorización escrita del <b>EMPLEADOR</b>.</li>
        <li class="font-size2 m2">Pedir o recibir dinero de los clientes de <b>LA EMPRESA</b> y darles un destino diferente a estos o no entregarlo en su debida oportunidad, a quien corresponda en la oficina de <b>LA EMPRESA</b> y/o retener dinero o hacer efectivo cheques recibidos para <b>EL EMPLEADOR</b>.</li>
        <li class="font-size2 m2">Autorizar o ejecutar sin ser de su competencia, operaciones que afecten los intereses del <b>EMPLEADOR</b> o negociar bienes y/o mercancías del <b>EMPLEADOR</b> en provecho propio.</li>
        <li class="font-size2 m2">Presentar cuentas de gastos ficticios o reportar como cumplidas visitas o tareas no efectuadas.</li>
        <li class="font-size2 m2">Cualquier actitud en los compromisos comerciales, personales o en las relaciones sociales, que pueda afectar en forma nociva la reputación de <b>EL EMPLEADOR.</b></li>
        <li class="font-size2 m2">Retirar de las instalaciones donde funcione <b>LA EMPRESA</b> elementos, maquinaria, materia prima y útiles de propiedad del <b>EMPLEADOR</b> sin su autorización escrita.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>. - Equipos informáticos. EL EMPLEADOR</b> proporcionará, instalará y mantendrá en buen estado los equipos informáticos necesarios para el correcto desempeño de las funciones del <b>TELETRABAJADOR</b>. <b>EL TELETRABAJADOR</b> tiene la obligación de cuidado de los equipos suministrados por <b>EL EMPLEADOR</b>, y el uso adecuado y responsable del correo electrónico corporativo y no podrá recolectar o distribuir material ilegal a través de internet, ni darle ningún otro uso que no sea determinado por <b>EL CONTRATO DE TRABAJO</b>. <b>EL TELETRABAJADOR</b> se compromete a cuidar los elementos de trabajo, así como las herramientas que <b>LA EMPRESA</b> ponga a su disposición y a utilizarlas exclusivamente con los fines laborales que previamente se hayan fijado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Finalizado la modalidad de teletrabajo, el <b>TELETRABAJADOR</b> debe reintegrar los equipos informáticos que se le haya asignado, en el estado en que se le entregaron, salvo el desgaste natural de las cosas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - EL TELETRABAJADOR</b> podrá hacer uso de elementos propios para el desempeño de sus labores, previo acuerdo con <b>EL EMPLEADOR</b>, respetando la confidencialidad y las demas clausulas y prohibiciones del presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SÉPTIMA</u>. - </b>Se remunerará con un salario básico mensual de '.$valorLetrasTotal.' M/L ($ '.number_format($datos['newSalary'],2).'), pagaderos con una periodicidad '.$datos['newPaymentCicle'].' . Dentro de este pago se encuentra incluida la remuneración de los descansos dominicales y festivos de que tratan los capítulos I y II del título VII del Código Sustantivo del Trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Las partes hacen constar que en esta remuneración queda incluido el pago de los servicios que <b>EL TELETRABAJADOR</b> se obliga a realizar durante el tiempo estipulado en el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - </b>Si <b>EL TELETRABAJADOR</b> prestare su servicio en día dominical o festivo, sin previa autorización por escrito del <b>EMPLEADOR</b>, no tendrá derecho a reclamar remuneración alguna por este día.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO TERCERO. - EL EMPLEADOR</b> no suministra ninguna clase de salario en especie.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO CUARTO. - </b>Cuando por causa emanada directa o indirectamente de la relación contractual existan obligaciones de tipo económico a cargo del <b>TELETRABAJADOR</b> y a favor del <b>EMPLEADOR</b>, éste procederá a efectuar las deducciones a que hubiere lugar en cualquier tiempo y, más concretamente, a la terminación del presente contrato, así lo autoriza desde ahora <b>EL TELETRABAJADOR</b>, entendiendo expresamente las partes que la presente autorización cumple las condiciones, de orden escrita previa, aplicable para cada caso.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>. - </b>Las partes en el citado contrato, acuerdan expresamente que lo entregado en dinero o en especie por parte del <b>EMPLEADOR</b> al <b>TELETRABAJADOR</b> por concepto de beneficios cualquiera sea su denominación de acuerdo con el artículo 15 de la ley 50 de 1990, no constituyen salario, en especial: los auxilios o contribuciones que otorgue <b>EL EMPLEADOR</b> por concepto de alimentación para <b>EL TELETRABAJADOR</b>, de bonificaciones extraordinarias y demás auxilios otorgados por mera liberalidad del <b>EMPLEADOR</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Cualquier beneficio que se entregue al <b>TELETRABAJADOR</b> sólo se le otorgará como mera liberalidad del <b>EMPLEADOR</b>, por tanto, no constituye salario, ni pago laboral que sea base para el cálculo y pago de prestaciones sociales, aportes parafiscales, a cajas de compensación, <b>SENA</b> o <b>ICBF</b>, entre otros; como tampoco es base para la determinación de las contribuciones o aportes al Sistema de Seguridad Social Integral, tales como: salud, pensión, riesgos profesionales, fondo de solidaridad, etc. Las partes acuerdan desde ahora que en ningún caso los pagos que se entreguen como auxilios o beneficios constituyen salario o son base para las cotizaciones, contribuciones o aportes antes descritos.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>. - Costos:</b> Se reconoce al <b>TELETRABAJADOR</b> el valor de $ '.number_format($datos['newPlusSalary'],2).' COP pagaderos con una periodicidad '.$datos['newPaymentCicle'].' como compensación por los gastos de Internet, energía eléctrica, mismos que de ninguna manera (NO) hacen parte del salario.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA</u>. - </b>Todo trabajo suplementario o en horas extras y todo trabajo en día domingo o festivo en los que legalmente debe concederse descanso, se remunerará conforme a la ley, así como los correspondientes recargos nocturnos. Para el reconocimiento y pago del trabajo suplementario, dominical o festivo, <b>EL EMPLEADOR</b> o sus representantes, deben autorizarlo previamente por escrito. Cuando la necesidad de este trabajo se presente de manera imprevista o inaplazable, deberá ejecutarse y darse cuenta de él por escrito, o en forma verbal, a la mayor brevedad al <b>EMPLEADOR</b> o a sus representantes. <b>EL EMPLEADOR</b>, en consecuencia, no reconocerá ningún trabajo suplementario o en días de descanso legalmente obligatorio que no haya sido autorizado previamente o avisado inmediatamente, como queda dicho.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA PRIMERA</u>. - EL TELETRABAJADOR</b> se obliga a laborar la jornada ordinaria en los turnos y dentro de las horas señalados por <b>EL EMPLEADOR</b>, pudiendo hacer éste ajustes o cambios de horario cuando lo estime conveniente. <b>(IUS VARIANDI)</b> Por el acuerdo expreso o tácito de las partes, podrán repartirse las horas de la jornada ordinaria en la forma prevista en el artículo 164 del Código Sustantivo del Trabajo, modificado por el artículo 23 de la Ley 50 de 1990, teniendo en cuenta que los tiempos de descanso entre las secciones de la jornada no se computan dentro de la misma, según el artículo 167 ibídem. Así mismo el <b>EMPLEADOR</b> y el <b>TRABAJADOR</b> podrán acordar que la jornada semanal de cuarenta y ocho (48) horas se realice mediante jornadas diarias flexibles de trabajo, distribuidas en máximo seis (6) días a la semana con un (1) día de descanso obligatorio, que podrá coincidir con el domingo. En éste, el número de horas de trabajo diario podrá repartirse de manera variable durante la respectiva semana y podrá ser de mínimo cuatro (4) horas continuas y hasta diez (10) horas diarias sin lugar a ningún recargo por trabajo suplementario, cuando el número de horas de trabajo no exceda el promedio de cuarenta y ocho (48) horas semanales dentro de la jornada ordinaria de 6 a.m. a 10 p.m.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SEGUNDA</u>. - Control y supervisión. EL EMPLEADOR</b> controlará y supervisará la actividad del <b>TELETRABAJADOR</b> mediante medios telemáticos, informáticos y electrónicos. Si por motivos de trabajo fuese necesaria la presencia física de representantes de la compañía en el lugar de trabajo de <b>EL TELETRABAJADOR</b> y este fuera su propio domicilio, se hará siempre previa notificación y consentimiento de éste. EL <b>TELETRABAJADOR</b> consiente libremente realizar reuniones a través de videoconferencias con <b>EL EMPLEADOR</b> y que en ningún caso se entiende como violación del domicilio privado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Tecnologías que se utilizará para mantener el contacto con el <b>TELETRABAJADOR</b>:</b> '.$datos['newTools'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Objetivos/ Metas a cumplir por parte del <b>TELETRABAJADOR</b> semanal/mensual:</b> '.$datos['newGoals'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Disponibilidad/Horas:</b> '.$datos['newHours'].'.</span>
</div>';
if (isset($datos['newChecker']) && $datos['newChecker'] != '' && $datos['newChecker'] != null) {
  $html.='<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Supervisor:</b> '.mb_strtoupper($datos['newChecker']).'.</span>
</div>';
}
$html.='<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA TERCERA</u>. - Medidas de Seguridad y Previsión de Riesgos en el Teletrabajo. El TELETRABAJADOR</b> autoriza a la <b>ARL</b> y a <b>EL EMPLEADOR</b> visitas periódicas a su domicilio que permitan comprobar si el lugar de trabajo es seguro y está libre de riesgos, de igual forma autoriza las visitas asistencia para actividades de salud ocupacional, con los preavisos descritos en la Cláusula <b>DECIMA SEGUNDA</b>.  No obstante, el <b>TELETRABAJADOR</b>, debe cumplir las condiciones especiales sobre la prevención de riesgos laborales que se encuentran definidas en el Reglamento Interno de Trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA CUARTA</u>. - Seguridad de la Información.</b> El acceso a los diferentes entornos y sistemas informáticos de <b>EL EMPLEADOR</b> será efectuado siempre y en todo momento bajo el control y la responsabilidad de <b>EL TELETRABAJADOR</b> siguiendo los procedimientos establecidos por <b>LA EMPRESA</b>, los cuales se encuentran definidos en el reglamento interno de trabajo y hace parte integral del presente acuerdo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA QUINTA</u>. - Protección de datos personales. EL TELETRABAJADOR</b> Ese compromete a respetar la legislación en materia de protección de datos, las políticas de privacidad y de seguridad de la información que la empresa ha implementado, como también a:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">Utilizar los datos de carácter personal a los que tenga acceso único y exclusivamente para cumplir con sus obligaciones para con <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">Cumplir con las medidas de seguridad que <b>LA EMPRESA</b> haya implementado para asegurar la confidencialidad, secreto e integridad de los datos de carácter personal a los que tenga acceso, así como no a no ceder en ningún caso a terceras personas los datos de carácter personal a los que tenga acceso, ni tan siquiera a efectos de su conservación.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SEXTA</u>. - </b>Este contrato es un <b>CONTRATO INDIVIDUAL DE TRABAJO '.mb_strtoupper($datos['newContractType']).'</b>, a partir del día '.$inicio->format('d').' mes '.$mesInicio.' año '.$inicio->format('Y'); 
  if (isset($fin)) {
    $html .='y hasta el día '.$fin->format('d').' mes '.$mesFin.' año '.$inicio->format('Y');
  }
  $html.=' , permaneciendo este, mientras subsistan las causas que le dieron origen a ese contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARAGRAFO. - </b>Los primeros ('.$diasPrueba.') '.$prueba.' días del presente contrato, se consideran como <b>PERÍODO DE PRUEBA</b> y, por consiguiente, cualquiera de las partes podrá terminar el contrato unilateralmente, en cualquier momento durante dicho período.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SÉPTIMA</u>. - </b>Son justas causas para dar por terminado unilateralmente este contrato por cualquiera de las partes, las enumeradas en el artículo 7º del Decreto 2351 de 1965; y, además, por parte del <b>EMPLEADOR</b>, las faltas que para el efecto se califiquen como graves contempladas en el reglamento interno de trabajo y en el espacio reservado para cláusulas adicionales en el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA OCTAVA</u>. - Propiedad Intelectual.</b>Los derechos de Propiedad intelectual e industrial que se generen en virtud del presente acuerdo le pertenecen al <b>EMPLEADOR</b>. <b>El TELETRABAJADOR</b> no tendrá las facultades de podrá realizar actividad alguna de uso, reproducción, comercialización, comunicación pública o transformación sobre el resultado de sus funciones, ni tendrá derecho a ejercitar cualquier otro derecho, sin la previa autorización expresa del <b>EMPLEADOR</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA NOVENA</u>. - CLÁUSULA DE CONFIDENCIALIDAD: EL TELETRABAJADOR</b> se obliga y compromete a guardar la absoluta reserva de la información y documentación de la cual llegare a tener conocimiento, en cumplimiento de las funciones para las cuales fue contratado, en especial, no entregará, ni divulgará a terceros, salvo autorización previa y expresa de la Gerencia, información calificada por <b>EL EMPLEADOR</b> como confidencial, reservada o estratégica. No podrá en ninguna circunstancia revelar información a persona natural o jurídica, por ningún medio físico o electrónico, así como a no publicar la información que afecte los intereses de <b>EL EMPLEADOR</b>, durante su permanencia en el cargo, y debido a <b>EL TELETRABAJO</b>, ni después de su retiro, so pena de incurrir en las acciones legales pertinentes consagradas para la protección de esta clase de información.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA</u>. - Disposiciones finales.</b>Este contrato ha sido redactado estrictamente de acuerdo con la ley y la jurisprudencia; será interpretado de buena fe y en consonancia con el Código Sustantivo del Trabajo, cuyo objeto, definido en su artículo 1º, es lograr la justicia en las relaciones entre <b>EMPLEADORES</b> y <b>TRABAJADORES</b> dentro de un espíritu de coordinación económica y equilibrio social.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA PRIMERA</u>. - </b>El presente contrato reemplaza en su integridad y deja sin efecto, cualquier otro contrato verbal o escrito celebrado entre las partes con anterioridad. Las modificaciones que se acuerden al presente contrato, se anotarán a continuación de su texto. Para constancia se firma en dos (2) o más ejemplares del mismo tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA SEGUNDA</u>. - </b>A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Se firma en la ciudad de '.ucfirst($datos['newContractCity']).', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL EMPLEADOR</b></span><br>';
  if ($datos['newFirstType']=='PJ') {
    $footer.='<span class="font-size3 start">'.$datos['newFirstCompany'].'<br>
      <b>'.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].'</span>';
  }else{
    $footer.='<span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].'</span>';
  }
  $footer.='</td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL TRABAJADOR</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->defaultheaderfontstyle='';
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_teletrabajo_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_teletrabajo_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfOtrosiTeletrabajo(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $datos = $_POST;
        $inicio = Carbon::createFromFormat('d/m/Y',$datos['newStartDate']);
        if (isset($datos['newEndDate'])) {
          $fin = Carbon::createFromFormat('d/m/Y',$datos['newEndDate']);
          $mesFin = $this->mesLetras($fin);
        }
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $document->email = $datos['newFirstEmail'];
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $mesInicio = $this->mesLetras($inicio);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
body {
    background: url("Views/img/plantilla/FONDO_DOCUMENTO.png") no-repeat 0 0;
    background-image-resize: 6;
}
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #f1f2f2;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head> 
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>OTRO SÍ MODIFICATORIO DEL CONTRATO DE TRABAJO <b>'.mb_strtoupper($datos['newContractType']).' A MODALIDAD TELETRABAJO</b></span>
</div>
    <table style="width:100%" class="m2">';
      if ($_POST['newLastContract'] != "" && $_POST['newLastContract'] != null) {
        $html .= '<tr>
      <td class="col-4">
          <span class="font-size-2"><b> OTRO SÍ AL CONTRATO No. </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newLastContract']).'</b></span>
        </td>
      </tr>';
      }
        if ($datos['newFirstType']=='PJ') {
          $html.='<tr>
      <td class="col-4">
          <span class="font-size-2"><b>Nombre de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstCompany']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>';
        }
        $html.='<tr>
      <td class="col-4">
          <span class="font-size-2"><b>Nombre del empleador o representate: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newFirstPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newSecondPart']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>'.$datos['newSecondIdType'].': </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.number_format($datos['newSecondId']).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Salario: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">$ '.number_format($datos['newSalary'],2).'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Periodicidad: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newPaymentCicle'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Fecha de iniciación de labores: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newStartDate'].'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Fecha de modificación: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$hoy->format('d/m/Y').'</span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Objeto del otro sí: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">Modificación a teletrabajo</span>
        </td>
       </tr>

      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Ciudad de contratación: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#f1f2f2">
          <span class="font-size-2">'.$datos['newContractCity'].'</span>
        </td>
       </tr>
       </table>
       <div class="row m2" style="text-align:justify;">Las partes, que suscribimos el presete <b>OTRO SÍ</b> al <b>CONTRATO DE TRABAJO '.mb_strtoupper($datos['newContractType']).'</b>, lo hacemos fundamentados en la Buena Fe, y en especial en el respeto a los principios del Derecho de Trabajo contenidos en la carta magna de la república de Colombia y la normatividad laboral colombiana.</span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre los suscritos a saber, por una parte, <b>'.mb_strtoupper($datos['newFirstPart']).'</b>, identificado (a) con '.$datos['newFirstIdType'].' No. <b>'.number_format($datos['newFirstId']).'</b>';
       if ($datos['newFirstExpedition'] != '' && $datos['newFirstExpedition'] != null) {
          $html.=' de <b>'.$datos['newFirstExpedition'].'</b>';
        }
        $html.=', en mi calidad de <b>EMPLEADOR</b>';
        if ($datos['newFirstType']=='PJ') {
           $html.=' en representación de la empresa '.$datos['newFirstCompany'].', Identificada con '.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].', con domicilio comercial en la '.$datos['newFirstAddress'].' de la ciudad de '.$datos['newFirstCity'];
         }
         $html.=', quien en adelante se denominará <b>EMPLEADOR</b> y por otra parte <b>'.mb_strtoupper($datos['newSecondPart']).'</b>, identificado (a) con <b>'.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].'</b> residente en la ciudad de '.$datos['newSecondCity'].', quien en adelante se denominará <b>TRABAJADOR</b>, hemos convenido de mutuo acuerdo en modificar el presente contrato de trabajo fechado '.$datos['newLastContractDate'].' y celebrado entre las partes <b>EL TRABAJADOR Y EL EMPLEADOR</b>, el cual quedará de la siguiente manera:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>.';
  if ($datos['newChargeChange']==1) {
    $html .= '- MODIFICACIÓN DE CARGO:</b> Se modifica el cargo anterior que <b>EL TRABAJADOR</b> ostenta en la empresa y que se describe en la parte introductoria del contrato. Este será reemplazado por el cargo de <b>'.mb_strtoupper($datos['newCharge']).'</b>.';
  }else{
    $html .= '- NO MODIFICACIÓN DE CARGO:</b> Se mantiene el cargo anterior que <b>EL TRABAJADOR</b> ostenta en la empresa y que se describe en la parte introductoria del contrato.';
  }
$html .= '</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEGUNDA</u>. - </b>Las partes declaran que, en el presente <b>OTRO SÍ</b>, se entienden incorporadas en lo pertinente, las disposiciones legales que regulan las relaciones entre <b>LA EMPRESA</b> y sus <b>TRABAJADORES</b>, en especial, las del contrato de trabajo para el oficio que se suscribe, fuera de las obligaciones consignadas en los reglamentos de trabajo y de higiene y seguridad industrial de la empresa.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>TERCERA</u>. - Lugar de Trabajo.</b>Para efectos del presente acuerdo, <b>EL TRABAJADOR</b> desempeñara las funciones propias de su puesto de trabajo, bajo la modalidad de <b>TELETRABAJO</b>, en la '.$datos['newWorkAddress'].'. En dicho lugar <b>EL TRABAJADOR</b> realizará su trabajo '.$datos['newWeekDays'].' días por semana.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Un día (1) a la semana u ocasionalmente (según lo acordado), <b>EL TELETRABAJADOR</b> deberá presentarse en las instalaciones del <b>EMPLEADOR</b>, para capacitaciones, evaluación de resultados, etc., sin que su presencia en las instalaciones signifique sustitución de <b>EL TELETRABAJO</b> por trabajo presencial.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - </b> En caso de que <b>EL EMPLADOR</b> o <b>EL TELETRABAJADOR</b> quisieran modificar el lugar de trabajo, por trabajo presencial, se deberá acordar de mutuo acuerdo dicha modificación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>CUARTA</u>. - Espacio de Trabajo. El TELETRABAJADOR</b> deberá realizar sus actividades laborales en el espacio acordado previamente por ÉL, <b>EL EMPLEADOR</b> y la <b>ARL</b>. No podrá ser en otros lugares que no cumplan con las condiciones de seguridad e higiene adecuadas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>. - Derechos del Teletrabajador. El TELETRABAJADOR</b> tendrá derecho a disfrutar de todos los derechos mínimos consagrados en el C.S.T., en especial, los referentes a descansos, vacaciones, prestaciones sociales, afiliación integral a Seguridad Social. Asimismo, <b>EL TELETRABAJADOR</b> tendrá las mismas obligaciones laborales que los demás empleados, las cuales, en relación con la actividad propia de <b>EL EMPLEADO</b>, éste la ejecutará dentro de las siguientes modalidades que implican claras obligaciones para este así:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>OBLIGACIONES ESPECIALES DEL TRABAJADOR: El TRABAJADOR </b>se obliga especialmente.</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">A laborar la jornada ordinaria en los turnos y dentro de horas que asigne <b>LA EMPRESA</b>, pudiendo esta, ordenar los cambios o ajustes que sean necesarios para el adecuado funcionamiento de las actividades y labores.  Entendiendo que la jornada de trabajo se inicia cuando <b>EL TELETRABAJADOR</b> está listo y disponible en el sitio donde se desarrolla la labor.</li>
        <li class="font-size2 m2">A no desempeñar labor alguna, ni ejercer otra actividad, fuera de las horas de trabajo al servicio del <b>EMPLEADOR</b>, que pueda afectar o poner en peligro su seguridad, su salud o su descanso.</li>
        <li class="font-size2 m2">Prestar el servicio para el que fue contratado personalmente, en el lugar del territorio de la Republica de Colombia, que indicare <b>EL EMPLEADOR</b> y excepcionalmente, fuera de dicho territorio cuando las necesidades del servicio así lo requieran.</li>
        <li class="font-size2 m2">Observar rigurosamente las normas que le fije la empresa para la realización de la labor a que se refiere el presente contrato.</li>
        <li class="font-size2 m2">A prestar toda la colaboración necesaria en caso de siniestro o de riesgo que afecte o amenace a las personas o a los bienes de <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A no atender durante las horas de trabajo, asuntos o actividades distintas de las que <b>LA EMPRESA</b> le señale, sin previa autorización de esta; Y por ninguna circunstancia, actividades de carácter personal y ajenas al objeto de este contrato.</li>
        <li class="font-size2 m2">A comenzar puntualmente al turno asignado, todos los días laborales, salvo que se lo impida una justa causa comprobada, además, de dedicar la totalidad de su jornada de trabajo a cumplir a cabalidad con las funciones establecidas de acuerdo al cargo.</li>
        <li class="font-size2 m2">Ejecutar por sí mismo las funciones asignadas y cumplir estrictamente las instrucciones, manuales, procesos y procedimientos que le sean dadas por <b>LA EMPRESA</b>, o por quienes la representen, respecto del desarrollo de sus actividades, programando y elaborando diariamente su trabajo de forma eficiente.</li>
        <li class="font-size2 m2">Si luego de finalizada la tarea de la jornada, aún no ha terminado la jornada de teletrabajo, <b>EL TELETRABAJADOR</b>, prestará sus servicios en las labores que le asigne <b>LA EMPRESA</b> que hagan parte de la labor y en los oficios para los cuales se encuentre apto y capacitado.</li>
        <li class="font-size2 m2"> A aceptar, los cambios de oficio o de tareas a realizar, dentro de la labor arriba descrita, siempre y cuando, <b>EL TELETRABAJADOR</b> se encuentre en condiciones de desempeñarse en tales oficios.</li>
        <li class="font-size2 m2">A laborar el tiempo extra y los días festivos o dominicales que sean señalados por <b>LA EMPRESA</b>, cuando por razones técnicas, administrativas o del servicio así se requiera.</li>
        <li class="font-size2 m2">Se Podrán cambiar en forma permanente o intermitente de día de descanso semanal al sábado, dependiendo de las características del servicio que este prestando <b>EL EMPLEADOR</b>, en cuyo caso se remunerará conforme a lo ordenado por la ley 789 de 2002.</li>
        <li class="font-size2 m2">A cumplir a cabalidad con los manuales, reglamentos internos, políticas y procedimientos de <b>LA EMPRESA</b>, los cuales declara conocer a la firma de este contrato.</li>
        <li class="font-size2 m2">A cumplir las normas de seguridad y prevención de accidentes que tenga <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A participar puntualmente, en las reuniones y capacitaciones programadas por <b>EL EMPLEADOR</b>, en aras de mejorar la formación del <b>TRABAJADOR</b>, la productividad y calidad de la empresa.</li>
        <li class="font-size2 m2">A conservar y restituir en buen estado, los instrumentos, los insumos, útiles y herramientas, que le haya entregado <b>EL EMPLEADOR</b>, bajo su cuidado y custodia para la ejecución de sus tareas o labores.</li>
        <li class="font-size2 m2">A conservar completa armonía y comprensión con los clientes, proveedores, autoridades de vigilancia y control, con sus superiores y compañeros de trabajo, en sus relaciones interpersonales y en la ejecución de su labor, preservando el respeto y la cordialidad que debe mantenerse en las relaciones sociales y de trabajo.</li>
        <li class="font-size2 m2">A Guardar absoluta reserva, salvo autorización expresa de <b>LA EMPRESA</b>, de todas aquellas informaciones que lleguen a su conocimiento, en razón de su trabajo y que sean por naturaleza privadas.</li>
        <li class="font-size2 m2">A cuidar permanentemente los intereses, instalaciones, muebles y equipos de oficina, de cómputo, enseres, vehículos, maquinaria, herramientas, materias primas, material de empaque y productos elaborados de <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A cumplir permanentemente, sus labores con espíritu de lealtad, compañerismo, colaboración y disciplina con <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">A Avisar oportunamente y por escrito a <b>LA EMPRESA</b>, todo cambio en su dirección, teléfono o ciudad de residencia.</li>
        <li class="font-size2 m2"> A Avisar oportunamente y por escrito a <b>LA EMPRESA</b>, todo cambio en su dirección, teléfono o ciudad de residencia.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PROHIBICIONES DEL TRABAJADOR: </b>Acuerdan las partes las siguientes prohibiciones al <b>TRABAJADOR</b>, además, de las consagradas en la ley y en el reglamento interno de Trabajo:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">La inejecución de las labores, sin una excusa suficiente que lo justifique, los retrasos reiterados en la iniciación de la jornada de trabajo sin una justa causa que lo amerite, además, de la ejecución de actividades a las propias de su oficio en horas de trabajo, para terceros ya fueren remuneradas o no, o para su provecho personal.</li>
        <li class="font-size2 m2">Emplear en su trabajo y en el trato con sus compañeros de trabajo, clientes o visitantes un vocabulario soez, descortés, altanero, indecente o poco decoroso.</li>
        <li class="font-size2 m2">Todo acto de violencia, deslealtad, injuria, actos indecentes o inmorales, malos tratos o grave indisciplina, en que incurra <b>EL TELETRABAJADOR</b> en sus labores contra <b>LA EMPRESA</b>, el personal directivo, sus compañeros de trabajo o sus superiores, clientes o proveedores, por cualquier medio.</li>
        <li class="font-size2 m2">Dar a las herramientas o equipos de trabajo, un uso o destino contrario a aquel para el cual fueron entregados.</li>
        <li class="font-size2 m2">Ceder, cambiar, los equipos o herramientas asignados, de los cuales es responsable mientras se encuentran en su poder.</li>
        <li class="font-size2 m2">Solicitar préstamos especiales o ayuda económica a los compañeros de trabajo, clientes, o proveedores del <b>EMPLEADOR</b>, aprovechándose de su cargo u oficio o en su defecto, aceptarles donaciones de cualquier clase sin la previa autorización escrita del <b>EMPLEADOR</b>.</li>
        <li class="font-size2 m2">Pedir o recibir dinero de los clientes de <b>LA EMPRESA</b> y darles un destino diferente a estos o no entregarlo en su debida oportunidad, a quien corresponda en la oficina de <b>LA EMPRESA</b> y/o retener dinero o hacer efectivo cheques recibidos para <b>EL EMPLEADOR</b>.</li>
        <li class="font-size2 m2">Autorizar o ejecutar sin ser de su competencia, operaciones que afecten los intereses del <b>EMPLEADOR</b> o negociar bienes y/o mercancías del <b>EMPLEADOR</b> en provecho propio.</li>
        <li class="font-size2 m2">Presentar cuentas de gastos ficticios o reportar como cumplidas visitas o tareas no efectuadas.</li>
        <li class="font-size2 m2">Cualquier actitud en los compromisos comerciales, personales o en las relaciones sociales, que pueda afectar en forma nociva la reputación de <b>EL EMPLEADOR.</b></li>
        <li class="font-size2 m2">Retirar de las instalaciones donde funcione <b>LA EMPRESA</b> elementos, maquinaria, materia prima y útiles de propiedad del <b>EMPLEADOR</b> sin su autorización escrita.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>. - Equipos informáticos. EL EMPLEADOR</b> proporcionará, instalará y mantendrá en buen estado los equipos informáticos necesarios para el correcto desempeño de las funciones del <b>TELETRABAJADOR</b>. <b>EL TELETRABAJADOR</b> tiene la obligación de cuidado de los equipos suministrados por <b>EL EMPLEADOR</b>, y el uso adecuado y responsable del correo electrónico corporativo y no podrá recolectar o distribuir material ilegal a través de internet, ni darle ningún otro uso que no sea determinado por <b>EL CONTRATO DE TRABAJO</b>. <b>EL TELETRABAJADOR</b> se compromete a cuidar los elementos de trabajo, así como las herramientas que <b>LA EMPRESA</b> ponga a su disposición y a utilizarlas exclusivamente con los fines laborales que previamente se hayan fijado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Finalizado la modalidad de teletrabajo, el <b>TELETRABAJADOR</b> debe reintegrar los equipos informáticos que se le haya asignado, en el estado en que se le entregaron, salvo el desgaste natural de las cosas.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - EL TELETRABAJADOR</b> podrá hacer uso de elementos propios para el desempeño de sus labores, previo acuerdo con <b>EL EMPLEADOR</b>, respetando la confidencialidad y las demas clausulas y prohibiciones del presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SÉPTIMA</u>. - </b>Como resultado de lo anterior, se modifica la cláusula '.$datos['newLastSalary'].' del contrato de trabajo, misma que habla sobre la remuneración, la cual, quedará de la siguiente manera:<br><b>Calusula '.$datos['newLastSalary'].'</b> - Se remunerará con un salario básico mensual de '.$valorLetrasTotal.' M/L ($ '.number_format($datos['newSalary'],2).'), pagaderos con una periodicidad '.$datos['newPaymentCicle'].' . Dentro de este pago se encuentra incluida la remuneración de los descansos dominicales y festivos de que tratan los capítulos I y II del título VII del Código Sustantivo del Trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Las partes hacen constar que en esta remuneración queda incluido el pago de los servicios que <b>EL TRABAJADOR</b> se obliga a realizar durante el tiempo estipulado en el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO SEGUNDO. - </b>Si <b>EL TRABAJADOR</b> prestare su servicio en día dominical o festivo, sin previa autorización por escrito del <b>EMPLEADOR</b>, no tendrá derecho a reclamar remuneración alguna por este día.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO TERCERO. - EL EMPLEADOR</b> no suministra ninguna clase de salario en especie.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO CUARTO. - </b>Cuando por causa emanada directa o indirectamente de la relación contractual existan obligaciones de tipo económico a cargo del <b>TRABAJADOR</b> y a favor del <b>EMPLEADOR</b>, éste procederá a efectuar las deducciones a que hubiere lugar en cualquier tiempo y, más concretamente, a la terminación del presente contrato, así lo autoriza desde ahora <b>EL TRABAJADOR</b>, entendiendo expresamente las partes que la presente autorización cumple las condiciones, de orden escrita previa, aplicable para cada caso.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>. - </b>Las partes en el citado contrato, acuerdan expresamente que lo entregado en dinero o en especie por parte del <b>EMPLEADOR</b> al <b>TELETRABAJADOR</b> por concepto de beneficios cualquiera sea su denominación de acuerdo con el artículo 15 de la ley 50 de 1990, no constituyen salario, en especial: los auxilios o contribuciones que otorgue <b>EL EMPLEADOR</b> por concepto de alimentación para <b>EL TELETRABAJADOR</b>, de bonificaciones extraordinarias y demás auxilios otorgados por mera liberalidad del <b>EMPLEADOR</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Cualquier beneficio que se entregue al <b>TELETRABAJADOR</b> sólo se le otorgará como mera liberalidad del <b>EMPLEADOR</b>, por tanto, no constituye salario, ni pago laboral que sea base para el cálculo y pago de prestaciones sociales, aportes parafiscales, a cajas de compensación, <b>SENA</b> o <b>ICBF</b>, entre otros; como tampoco es base para la determinación de las contribuciones o aportes al Sistema de Seguridad Social Integral, tales como: salud, pensión, riesgos profesionales, fondo de solidaridad, etc. Las partes acuerdan desde ahora que en ningún caso los pagos que se entreguen como auxilios o beneficios constituyen salario o son base para las cotizaciones, contribuciones o aportes antes descritos.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>. - Costos:</b> Se reconoce al <b>TELETRABAJADOR</b> el valor de $ '.number_format($datos['newPlusSalary'],2).' COP pagaderos con una periodicidad '.$datos['newPaymentCicle'].' como compensación por los gastos de Internet, energía eléctrica, mismos que de ninguna manera <b>(NO)</b> hacen parte del salario.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA</u>. - </b>Todo trabajo suplementario o en horas extras y todo trabajo en día domingo o festivo en los que legalmente debe concederse descanso, se remunerará conforme a la ley, así como los correspondientes recargos nocturnos. Para el reconocimiento y pago del trabajo suplementario, dominical o festivo, <b>EL EMPLEADOR</b> o sus representantes, deben autorizarlo previamente por escrito. Cuando la necesidad de este trabajo se presente de manera imprevista o inaplazable, deberá ejecutarse y darse cuenta de él por escrito, o en forma verbal, a la mayor brevedad al <b>EMPLEADOR</b> o a sus representantes. <b>EL EMPLEADOR</b>, en consecuencia, no reconocerá ningún trabajo suplementario o en días de descanso legalmente obligatorio que no haya sido autorizado previamente o avisado inmediatamente, como queda dicho.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA PRIMERA</u>. - EL TRABAJADOR</b> se obliga a laborar la jornada ordinaria en los turnos y dentro de las horas señalados por <b>EL EMPLEADOR</b>, pudiendo hacer éste ajustes o cambios de horario cuando lo estime conveniente. <b>(IUS VARIANDI)</b> Por el acuerdo expreso o tácito de las partes, podrán repartirse las horas de la jornada ordinaria en la forma prevista en el artículo 164 del Código Sustantivo del Trabajo, modificado por el artículo 23 de la Ley 50 de 1990, teniendo en cuenta que los tiempos de descanso entre las secciones de la jornada no se computan dentro de la misma, según el artículo 167 ibídem. Así mismo el <b>EMPLEADOR</b> y el <b>TRABAJADOR</b> podrán acordar que la jornada semanal de cuarenta y ocho (48) horas se realice mediante jornadas diarias flexibles de trabajo, distribuidas en máximo seis (6) días a la semana con un (1) día de descanso obligatorio, que podrá coincidir con el domingo. En éste, el número de horas de trabajo diario podrá repartirse de manera variable durante la respectiva semana y podrá ser de mínimo cuatro (4) horas continuas y hasta diez (10) horas diarias sin lugar a ningún recargo por trabajo suplementario, cuando el número de horas de trabajo no exceda el promedio de cuarenta y ocho (48) horas semanales dentro de la jornada ordinaria de 6 a.m. a 10 p.m.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SEGUNDA</u>. - Control y supervisión. EL EMPLEADOR</b> controlará y supervisará la actividad del <b>TELETRABAJADOR</b> mediante medios telemáticos, informáticos y electrónicos. Si por motivos de trabajo fuese necesaria la presencia física de representantes de la compañía en el lugar de trabajo de <b>EL TELETRABAJADOR</b> y este fuera su propio domicilio, se hará siempre previa notificación y consentimiento de éste. EL <b>TELETRABAJADOR</b> consiente libremente realizar reuniones a través de videoconferencias con <b>EL EMPLEADOR</b> y que en ningún caso se entiende como violación del domicilio privado.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Tecnologías que se utilizará para mantener el contacto con el TELETRABAJADOR:</b> '.$datos['newTools'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Objetivos/ Metas a cumplir por parte del TELETRABAJADOR semanal/mensual:</b> '.$datos['newGoals'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Disponibilidad/Horas:</b> '.$datos['newHours'].'.</span>
</div>';
if (isset($datos['newChecker']) && $datos['newChecker'] != '' && $datos['newChecker'] != null) {
  $html.='<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>Supervisor:</b> '.mb_strtoupper($datos['newChecker']).'.</span>
</div>';
}
$html.='<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA TERCERA</u>. - Medidas de Seguridad y Previsión de Riesgos en el Teletrabajo. El TELETRABAJADOR</b> autoriza a la <b>ARL</b> y a <b>EL EMPLEADOR</b> visitas periódicas a su domicilio que permitan comprobar si el lugar de trabajo es seguro y está libre de riesgos, de igual forma autoriza las visitas asistencia para actividades de salud ocupacional, con los preavisos descritos en la Cláusula <b>DECIMA SEGUNDA</b>.  No obstante, el <b>TELETRABAJADOR</b>, debe cumplir las condiciones especiales sobre la prevención de riesgos laborales que se encuentran definidas en el Reglamento Interno de Trabajo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA CUARTA</u>. - Seguridad de la Información.</b> El acceso a los diferentes entornos y sistemas informáticos de <b>EL EMPLEADOR</b> será efectuado siempre y en todo momento bajo el control y la responsabilidad de <b>EL TELETRABAJADOR</b> siguiendo los procedimientos establecidos por <b>LA EMPRESA</b>, los cuales se encuentran definidos en el reglamento interno de trabajo y hace parte integral del presente acuerdo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA QUINTA</u>. - Protección de datos personales. EL TELETRABAJADOR</b> Ese compromete a respetar la legislación en materia de protección de datos, las políticas de privacidad y de seguridad de la información que la empresa ha implementado, como también a:</span>
</div>
<div class="row" style="text-align:justify;">
    <ul>
        <li class="font-size2 m2">Utilizar los datos de carácter personal a los que tenga acceso único y exclusivamente para cumplir con sus obligaciones para con <b>LA EMPRESA</b>.</li>
        <li class="font-size2 m2">Cumplir con las medidas de seguridad que <b>LA EMPRESA</b> haya implementado para asegurar la confidencialidad, secreto e integridad de los datos de carácter personal a los que tenga acceso, así como no a no ceder en ningún caso a terceras personas los datos de carácter personal a los que tenga acceso, ni tan siquiera a efectos de su conservación.</li>
    </ul>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SEXTA</u>. - </b>Las partes acuerdan estipular que, este <b>OTRO SÍ</b>, rige a partir del día '.$inicio->format('d').' mes '.$mesInicio.' año '.$inicio->format('Y'); 
  if (isset($fin)) {
    $html .='y hasta el día '.$fin->format('d').' mes '.$mesFin.' año '.$inicio->format('Y');
  }
  $html.=' , permaneciendo este, mientras subsistan las causas que le dieron origen a ese contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA SÉPTIMA</u>. - </b>Son justas causas para dar por terminado unilateralmente este contrato por cualquiera de las partes, las enumeradas en el artículo 7º del Decreto 2351 de 1965; y, además, por parte del <b>EMPLEADOR</b>, las faltas que para el efecto se califiquen como graves contempladas en el reglamento interno de trabajo y en el espacio reservado para cláusulas adicionales en el presente contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA OCTAVA</u>. - PROPIEDAD INTELECTUAL.</b>Los derechos de Propiedad intelectual e industrial que se generen en virtud del presente acuerdo le pertenecen al <b>EMPLEADOR</b>. <b>El TELETRABAJADOR</b> no tendrá las facultades de podrá realizar actividad alguna de uso, reproducción, comercialización, comunicación pública o transformación sobre el resultado de sus funciones, ni tendrá derecho a ejercitar cualquier otro derecho, sin la previa autorización expresa del <b>EMPLEADOR</b>.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>DÉCIMA NOVENA</u>. - CLÁUSULA DE CONFIDENCIALIDAD: EL TELETRABAJADOR</b> se obliga y compromete a guardar la absoluta reserva de la información y documentación de la cual llegare a tener conocimiento, en cumplimiento de las funciones para las cuales fue contratado, en especial, no entregará, ni divulgará a terceros, salvo autorización previa y expresa de la Gerencia, información calificada por <b>EL EMPLEADOR</b> como confidencial, reservada o estratégica. No podrá en ninguna circunstancia revelar información a persona natural o jurídica, por ningún medio físico o electrónico, así como a no publicar la información que afecte los intereses de <b>EL EMPLEADOR</b>, durante su permanencia en el cargo, y debido a <b>EL TELETRABAJO</b>, ni después de su retiro, so pena de incurrir en las acciones legales pertinentes consagradas para la protección de esta clase de información.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA</u>. - Disposiciones finales.</b> El presente <b>OTRO SÍ</b>, ha sido redactado estrictamente de acuerdo con la ley y la jurisprudencia; será interpretado de buena fe y en consonancia con el Código Sustantivo del Trabajo, cuyo objeto, definido en su artículo 1º, es lograr la justicia en las relaciones entre <b>EMPLEADORES</b> y <b>TRABAJADORES</b> dentro de un espíritu de coordinación económica y equilibrio social.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARÁGRAFO PRIMERO. - </b> Ambas partes pactan que, las demás cláusulas que no fueron modificadas por el presente <b>OTRO SÍ</b>, se mantienen igual por la vigencia del contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA PRIMERA</u>. - </b>El presente contrato reemplaza en su integridad y deja sin efecto, cualquier otro contrato verbal o escrito celebrado entre las partes con anterioridad. Las modificaciones que se acuerden al presente contrato, se anotarán a continuación de su texto. Para constancia se firma en dos (2) o más ejemplares del mismo tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA SEGUNDA</u>. - </b>El presente <b>OTRO SÍ, NO</b> reemplaza en su totalidad y mucho menos deja sin efecto, cualquier otro contrato verbal o escrito celebrado entre las partes con anterioridad. Solo lo hace en lo concerniente a sus parámetros específicos o las modificaciones que se acuerden en el presente documento, las cuales se han de anotar anterior a este numeral. Las modificaciones que se acuerden al presente, de manera posterior, se realizaran en un nuevo <b>OTRO SÍ.</b></span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>VIGÉSIMA TERCERA</u>. - </b>A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el otro sí y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Para constancia se firma en dos (2) o más ejemplares del mismo tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación:</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Se firma en la ciudad de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL EMPLEADOR</b></span><br>';
  if ($datos['newFirstType']=='PJ') {
    $footer.='<span class="font-size3 start">'.$datos['newFirstCompany'].'<br>
      <b>'.$datos['newFirstCompanyIdType'].'</b> No. <b>'.$datos['newFirstCompanyId'].'</span>';
  }else{
    $footer.='<span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].'</b> No. <b>'.$datos['newFirstId'].'</span>';
  }
  $footer.='</td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL TRABAJADOR</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSecondPart']).'</b><br>
      <b>'.$datos['newSecondIdType'].'</b> No. <b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->defaultheaderfontstyle='';
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/otrosi_teletrabajo_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/otrosi_teletrabajo_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newFirstPart']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfCompraventa(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newAmount'], 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
        $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:16px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body><div class="row m1" style="text-align:center;">
    <span class="font-size"><b>CONTRATO DE COMPRAVENTA DE VEHÍCULO AUTOMOTOR</b></span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre los suscritos <b>'.mb_strtoupper($datos['newSeller']).'</b>, mayor de edad, vecino de '.$datos['newSellerCity'].', identificado (a) con '.$datos['newSellerIdType'].' número '.$datos['newSellerId'];
       if (isset($datos['newSellerExpedition']) && $datos['newSellerExpedition'] != '' && $datos['newSellerExpedition'] != null) {
          $html.=' expedida en <b>'.$datos['newSellerExpedition'].'</b>';
        }
        $html.=', quien en adelante se denominará <b>EL VENDEDOR</b>, de una parte, y <b>'.mb_strtoupper($datos['newBuyer']).'</b> también mayor de edad, vecino de '.$datos['newBuyerCity'].', identificado (a) con '.$datos['newBuyerIdType'].' número '.$datos['newBuyerId'];
        if (isset($datos['newBuyerExpedition']) && $datos['newBuyerExpedition'] != '' && $datos['newBuyerExpedition'] != null) {
           $html.=' expedida en <b>'.$datos['newBuyerExpedition'].'</b>';
         }
         $html.=', quien para efectos del presente instrumento se designará como <b>EL COMPRADOR</b>, de otra parte, manifestamos que hemos convenido celebrar el presente <b>CONTRATO DE COMPRAVENTA DE VEHÍCULO AUTOMOTOR</b> que se regirá por las cláusulas que a continuación se señalan.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PRIMERA: EL VENDEDOR</b> transfiere a título e venta a favor de <b>EL COMPRADOR</b> y éste mismo recibe en los términos y condiciones que aquí se determinan el <b>VEHÍCULO AUTOMOTOR</b>:</span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>CLASE: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newClass']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>MARCA: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newBrand']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>TIPO DE CARROCERÍA: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newType']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>COLOR: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newColor']).'</b></span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>MODELO: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newModel']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>No. MOTOR: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newEngineNumber']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>No. SERIE: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newSerialNumber']).'</b></span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>PLACA: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newPlate']).'</b></span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>LÍNEA: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newLine']).'</b></span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>CILINDRAJE: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newMotorSize']).'</b></span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>SERVICIO: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newService']).'</b></span>
        </td>
       </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>CAPACIDAD: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2"><b>'.mb_strtoupper($datos['newCapacity']).' '.mb_strtoupper($datos['newCapacityType']).'</b></span>
        </td>
       </tr>
       </table>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SEGUNDA:</b> El <b>VEHÍCULO AUTOMOTOR</b> ';
       if ($datos['newAduanaType'] == 'Acta') {
          $html .='anteriormente presenta Acta de aduana número '.$datos['newAduanaNumber'].', de fecha '.$datos['newAduanaDate'].' de la ciudad de '.$datos['newAduanaCity'].', ';
       }elseif ($datos['newAduanaType'] == 'Manifiesto') {
           $html .='anteriormente presenta Manifiesto de aduana número '.$datos['newAduanaNumber'].', de fecha '.$datos['newAduanaDate'].' de la ciudad de '.$datos['newAduanaCity'].', ';
       }
      $html .= 'posee sus accesorios correspondientes y se encuentra y entrega en '.$datos['newVehicleState'].', a entera satisfacción del comprador, circunstancia que éste declara expresamente, para lo cual, es plena prueba de recibo el presente documento.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>TERCERA:</b> Las partes acuerdan como precio de venta del VEHÍCULO AUTOMOTOR objeto del presente contrato, la suma cierta de '.$valorLetrasTotal.' pesos M/L ($ '.number_format($datos['newAmount'],2).'), cantidad que el comprador entrega y el vendedor recibe a la firma de este instrumento ';
       if ($datos['newPaymentType'] == 'Efectivo') {
          $html .='en Efectivo';
       }elseif ($datos['newAduanaType'] == 'Deposito') {
           $html .='En un deposito en la '.$datos['newPaymentAccount'].' número '.$datos['newPaymentNumber'].' del banco '.$datos['newPaymentBank'];
       }
      $html .= '.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>CUARTA:</b> Manifiesta <b>EL VENDEDOR</b> que responde al comprador por su calidad de propietario del <b>VEHÍCULO AUTOMOTOR</b> vendido y declara no haberlo dejado enajenado antes, encontrarse libre de gravámenes, limitaciones o condiciones resolutorias, en tal caso, se obliga a salir al saneamiento de lo vendido en los eventos previstos por la ley colombiana.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>QUINTA:</b> Los gastos que se deriven del traspaso del <b>VEHÍCULO AUTOMOTOR</b> serán cubiertos por '.$datos['newVehicleExpenses'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SEXTA:</b> Toda controversia o diferencia relativa a este contrato, en cuanto a su ejecución y liquidación, se resolverá por un tribunal de arbitramento que, por economía procesal, será designado por las partes, mismas que pactan que sea en el municipio de '.$datos['newContractCity'].', o en su defecto, en el domicilio donde se debe ejecutar la respectiva <b>COMPRAVENTA DE VEHÍCULO AUTOMOTOR</b>. El tribunal de Arbitramento se sujetará a lo dispuesto en el decreto 1818 de 1998 o estatuto orgánico de los sistemas alternativos de solución de conflictos y demás normas concordantes.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>SEPTIMA:</b> Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>OCTAVA:</b> A partir de la presente clausula, solo será válido el parágrafo que advierte del número de copias o ejemplares, la fecha y/o lugar en que se desarrolla el contrato y las correspondientes firmas de las partes, puesto que, todo espacio que aparezca en blanco en el actual documento no puede ser rellenado con ninguna otra clausula o condición, que afecte u obligue a cualquiera de las partes contratantes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">En señal de conformidad, este <b>CONTRATO DE COMPRAVENTA DE VEHÍCULO AUTOMOTOR</b>, se firma dos (2) ejemplares similares, del mismo tenor y valor, para cada una de las partes, en el municipio de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL VENDEDOR</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newSeller']).'</b><br>
      <b>'.$datos['newSellerIdType'].'</b> No. <b>'.$datos['newSellerId'].'</span>
  </td>
  <td class="col-6">
  <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
  <span class="font-size2 start"><b>EL COMPRADOR</b></span><br>
      <span class="font-size3 start"><b>'.mb_strtoupper($datos['newBuyer']).'</b><br>
      <b>'.$datos['newBuyerIdType'].'</b> No. <b>'.$datos['newBuyerId'].'</span>
  </td>
  </tr>';
       if ($datos['newWitnessNumber'] == '1') {
          $footer .='<tr>
            <td class="col-12" colspan="2">
            <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
            <span class="font-size2 start"><b>EL TESTIGO</b></span><br>
                <span class="font-size3 start">'.$datos['newWitness1'].'<br>
                <b>'.$datos['newWitness1IdType'].'</b> No. <b>'.$datos['newWitness1Id'].'</span>
            </td>
          </tr>';
       }elseif ($datos['newWitnessNumber'] == '2') {
          $footer .='<tr>
            <td class="col-6">
            <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
            <span class="font-size2 start"><b>EL TESTIGO 1</b></span><br>
                <span class="font-size3 start">'.$datos['newWitness1'].'<br>
                <b>'.$datos['newWitness1IdType'].'</b> No. <b>'.$datos['newWitness1Id'].'</span>
            </td>
            <td class="col-6">
            <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>
            <span class="font-size2 start"><b>EL TESTIGO 2</b></span><br>
                <span class="font-size3 start">'.$datos['newWitness2'].'<br>
                <b>'.$datos['newWitness2IdType'].'</b> No. <b>'.$datos['newWitness2Id'].'</span>
            </td>
          </tr>';         
       }
      $footer .= '
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_trabajo_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_trabajo_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newSeller']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public function pdfCobro(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        if ($document->product->value > 0 && $document->payment_state == 0) {
          return redirect('/');        
        }
        if ($document->document_state == 1) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
      $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate($document->product->page);
      $html='<html>
<head>
<style>
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
tr{
  width:100%;
}
*.backgroung-gray{
background-color: #666666;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row{
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
.font-size{
  font-size:25px;
}
.font-size-2{
  font-size:18px;
}
.font-size-3{
  font-size:16px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
.m1{
    margin-bottom: 60px;
    margin-top: 25px;
}
.m2{
    margin-bottom: 15px;
}
.m3{
    margin-top: 20px;
}
.m4{
    margin-bottom: 60px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
</style>
</head>
<body>
<div class="row m1">
  <span class="font-size2">'.ucfirst($datos['newDocumentCity']).', '.$hoy->format('d').' de '.$mes.' de '.$hoy->format('Y').'.</span>
</div>
<div class="row m2">
  <span class="font-size2">';
      if ($datos['newDebtorClass']=="la señora") {
        $html .='Señora:';
      }elseif ($datos['newDebtorClass']=="el señor"){
        $html .='Señor:';
      }else{
        $html .='Señores:';
      }
$html .= '<br>
  <b>'.mb_strtoupper($datos['newDebtor']).'</b><br> 
  E.S.M</span>
</div>
<div class="row m2">
  <span class="font-size2">Asunto: <b>COBRO PREJURÍDICO</b> </span>
</div>
<div class="row m2">
  <span class="font-size2">Cordial saludo:</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">';
      if ($datos['newDebtorClass']=="la señora") {
        $html .='Señora ';
      }elseif ($datos['newDebtorClass']=="el señor"){
        $html .='Señor ';
      }else{
        $html .='Señores ';
      }
$html .='<b>'.mb_strtoupper($datos['newDebtor']).'</b>, identificado (a) con <b>'.$datos['newDebtorIdType'].' Nro. '.number_format($datos['newDebtorId']).'</b> nos dirigimos a usted con el fin de comunicarle que, sus obligaciones originadas de'.$datos['newContract'].' '.strtolower($datos['newContractType']).'</b> con '.$datos['newCreditorClass'].' <b>'.mb_strtoupper($datos['newCreditor']).'</b> se encuentran en mora, no obstante, el tiempo transcurrido y pese a las diversas gestiones realizadas de nuestra parte, aun dicha obligación continúa en el mismo estado; por lo anterior, le requerimos muy comedidamente que de manera inmediata realice el pago correspondiente al valor total de la obligación o en su defecto, se acerque a nosotros a realizar un arreglo.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Tomando en cuenta la renuencia de su parte a una justa negociación, se impulsará el Cobro Jurídico en su contra, recuerde que todos los gastos de horarios derivados del proceso jurídico, sumado a las costas procesales que correspondan al proceso, corren a su cargo y serán incluidos dentro del monto a Cancelar.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2">Así las cosas y en aras de encontrar una alternativa que evite el avance a estas acciones judiciales, le sugerimos se acerque y realice una negociación de manera inmediata y así evite un proceso jurídico en su contra. Le reiteramos que, es esta la última oportunidad para que usted normalice su obligación, al realizar una conciliación extrajudicial que conlleve a la culminación del proceso jurídico; por lo tanto, esperamos su pronta respuesta.</span>
</div>
<table>
  <tr>
    <td>
      <span class="font-size2 m4"><b>Atentamente</b></span><br>
      <span class="font-size2" style="width: 50%; font-family:sans-serif">___________________________________________________</span><br>';
      if ($datos['newCreditorClass'] == 'la copropiedad' || $datos['newCreditorClass'] == 'el conjunto residencial'|| $datos['newCreditorClass'] == 'la empresa') {
        $html .='
      <span class="font-size3"><b>'.mb_strtoupper($datos['newAgent']).'</b><br>
      <b>'.$datos['newCharge'].'</b> de </span>';
      }
$html .= '
      <span class="font-size3"><b>'.mb_strtoupper($datos['newCreditor']).'</b><br>
      <b>'.$datos['newCreditorIdType'].' No. '.number_format($datos['newCreditorId']).'</b><br>
      Domiciliado en '.$datos['newCreditorCity'].' en la '.$datos['newCreditorAddress'].'<br>
      Teléfono: '.$datos['newCreditorPhone'].'</span>
    </td>
  </tr>
</table>
</body>
</html>';
$footer='<div class="row" style="text-align:center;">
  <span class="font-size2">"Si a la fecha de recepción del presente documento, ya realizó la normalización de su deuda, favor hacer caso omiso a este y agradeceremos haga llegar con prontitud el respectivo soporte de pago al correo electrónico '.$datos['newCreditorEmail'].'"</span>
</div>';
        $mpdf = new \Mpdf\Mpdf([ 
            'mode' => 'UTF-8', 
            'default_font' => 'rubik',
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetHeader('Codigo ludcis.com|'.$_POST['newCode'].'|{PAGENO}');
        $mpdf->SetProtection(array('print','print-highres'), '', '');
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/prejuridico_'.$codigo.'.pdf';
        $mpdf->Output('Views/documents/'.$document->product->code.'/prejuridico_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo(ucwords($datos['newCreditor']),$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'),$document->product->page,$qr,$document->product->pdf);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();          
        }
        $document->document_state=1;
        $document->save();
        unlink($document->link);
        $mpdf->Output();
    }
    public static function pdfFactura($id){
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $bill = ludcis\Bill::find($id);
        $numeroFactura = $bill->number;
        $nombreCliente = $bill->name;
        $tipoDocumento = $bill->id_type;
        $documento = $bill->id_number;
        $code = $bill->document->product->code;
        $producto = $bill->document->product->name;
        $qr = QrCode::format('png')->size(400)->errorCorrection('H')->color(60,60,59)->generate('https://documentos.ludcis.com/documentos/'.$bill->document->hash);
        $email = $bill->email;
        $valor = $bill->document->product->value/1.19;
        $codigoDescuento = ludcis\Code::where('code',$bill->code)->first();
        if (is_object($codigoDescuento)) {
          if ($codigoDescuento->porcentual=='1') {
            $descuento = $valor * $codigoDescuento->amount /100;
            $descuento = $descuento / 1.19;
          }else{
            $descuento=$codigoDescuento->amount/1.19;
          }
        }else{
          $descuento = 0;
        }
        $neto = $bill->base;
        $iva = $bill->tax;
        $total = $bill->total;
        $hoy = Carbon::now();
        $valorLetras = ludcis\NumeroALetras::convertir($total, 'pesos colombianos', 'centavos');
        $resolution = $bill->resolution;
        $numeroRes = $resolution->res_number;
        $minRes = $resolution->start_number;
        $maxRes = $resolution->end_number;
        $fechaRes = $resolution->res_expedition;
        $html='<html>
<head>
<style>
body{
  background: url("/Views/img/plantilla/AF_FONDO.jpg");
  background-image-resize:6;
  background-repeat: no-repeat;
  background-position: center;
  font-size: 12px;
}
* {
  box-sizing: border-box;
}
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
}
td{
  padding:5px;
}
*.backgroung-gray{
background-color: #56b688;
}
.row::after {
  content: "";
  clear: both;
  display: table;
}
.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
    width100%;
}
[class*="col-"] {
  float: left;
  padding: 15px;
}
*.col-1 {width: 8.33%;}
*.col-2 {width: 16.66%;}
*.col-3 {width: 25%;}
*.col-4 {width: 33.33%;}
*.col-5 {width: 41.66%;}
*.col-6 {width: 50%;}
*.col-7 {width: 58.33%;}
*.col-8 {width: 66.66%;}
*.col-9 {width: 75%;}
*.col-10 {width: 83.33%;}
*.col-11 {width: 91.66%;}
*.col-12 {width: 100%;}
.center {
  text-align: center important!;
}
.end {
  text-align: end;
}
.start {
  text-align: start;
}
tr{
  width:100%;
}
.border-gray{
border: 1px solid gray
}
td.backgroung-gray{
border: 1px solid black; 
background-color: #56b688
}
.border-black{
border: 1px solid black
}
.color-black{
color: #000
}
.color-gray{
color: #808080
}
.border-black{
border: 1px solid black
}
.font-size{
  font-size:15px;
}
.font-size-2{
  font-size:13px;
}
.font-size-3{
  font-size:11px;
}
.font-size-4{
  font-size:10px;
}
.font-size-5{
  font-size:10px;
}
.w-100{
  width:100%;
}
.pl{
  padding-left:5%
}
</style>
</head>
<body>
<table class="w-100" style="padding-top:50px">
      <tr>
        <td class="col-12" style="text-align: right">
          <span class="font-size-2 color-black">Ludcis S.A.S.<br><span class="color-gray">NIT: 901.323.761-1<br>Teléfono: 302 323 3242<br>soporte@ludcis.com<br>Régimen Común</span></span>
        </td>
      </tr>
</table>
    <table class="w-100" style="padding-top:25px">
      <tr>
        <td class="col-12" style="text-align: right">
          <span class="font-size color-black"><b>FACTURA DE VENTA: '.$numeroFactura.'</b></span>
        </td>
      </tr>
      </table>
      <table class="w-100">   
      <tr>
        <td class="col-2" style="text-align:right; width: 16.67%">
          <span class="color-gray"><b>Cliente:<br>'.$tipoDocumento.':<br>e-mail:<br>Fecha:</b></span>
        </td>  
        <td class="col-10">
          <span class="color-gray">'.mb_strtoupper($nombreCliente).'<br>'.$documento.'<br>'.$email.'<br>'.$hoy->format('d/m/y').'</span>
        </td>
      </tr>
    </table>
    <table class="w-100" style="padding-top:40px">
      <tr>
        <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Ítem</span></td>
        <td style="text-align: center; border-bottom: 3px solid black; width: 40%" class="col-4"><b class="font-size-2">Descripción</span></td>
        <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Cantidad</span></td>
        <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Valor</span></td>
        <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Valor Total</span></td>
      </tr>
      <tr>
        <td style="text-align: center" class="col-2"><span>'.$code.'</span></td>
        <td style="text-align: left" class="col-4"><span>'.ucfirst($producto).'</span></td>
        <td style="text-align: center" class="col-2"><span>1</span></td>
        <td style="text-align: right" class="col-2"><span>$ '.number_format($valor,2).'</span></td>
        <td style="text-align: right" class="col-2"><span>$ '.number_format($valor,2).'</span></td>
      </tr>
      <tr>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: left;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
      </tr>
      <tr>
        <td style="text-align: right;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;padding-top:185px" class="col-4"><span class="font-size-3"></span></td>
        <td style="text-align: right;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: left;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
      </tr>
      <tr>
        <td style="text-align: right; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right; border-bottom: 3px solid black" class="col-4"><span class="font-size-3"></span></td>
        <td style="text-align: right; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: left; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
      </tr>
      <tr>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right" class="col-2"><span class="font-size-3">Subtotal:</span></td>
        <td style="text-align: right" class="col-2"><span>$ '.number_format($valor,2).'</span></td>
      </tr>
      <tr>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right" class="col-2"><span class="font-size-3">Descuento:</span></td>
        <td style="text-align: right" class="col-2"><span>$ '.number_format($descuento,2).'</span></td>
      </tr>
      <tr>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
        <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        <td style="text-align: right" class="col-2"><span class="font-size-3">Neto:</span></td>
        <td style="text-align: right" class="col-2"><span>$ '.number_format($neto,2).'</span></td>
      </tr>
      <tr>
        <td style="text-align: center;" class="col-10" colspan="3" rowspan="4"><span class="font-size-5 color-gray">'.mb_strtoupper('Representación de factura en papel Resolución DIAN '.$numeroRes.' numeración autorizada de '.$minRes.' a '.$maxRes.' de '.$fechaRes.'. Esta factura es informativa, generada únicamente para fines tributarios.', 'UTF-8').'</span></td>
        <td style="text-align: right" class="col-2"><span class="font-size-3">IVA:</span></td>
        <td style="text-align: right" class="col-2"><span>$ '.number_format($iva,2).'</span></td>
      </tr>
      <tr>
        <td style="text-align: right" class="col-2"><span class="font-size-3">Retefuente:</span></td>
        <td style="text-align: right" class="col-2"><span>$ 0.00</span></td>
      </tr>
      <tr>
        <td style="text-align: right" class="col-2"><span class="font-size-3">ReteIVA:</span></td>
        <td style="text-align: right" class="col-2"><span>$ 0.00</span></td>
      </tr>
      <tr>
        <td style="text-align: right" class="col-2"><span class="font-size-3">ReteICA:</span></td>
        <td style="text-align: right" class="col-2"><span>$ 0.00</span></td>
      </tr>
      <tr>
        <td style="text-align: right;" class="col-2"><b>SON:</b><span class="color-black"></span></td>
        <td style="text-align: left;" class="col-6" colspan="2">'.ucfirst(strtolower($valorLetras)).' (COP).<span class="color-black font-size-3"></span></td>
        <td style="text-align: right" class="col-2"><span><b>Valor Total:</b></span></td>
        <td style="text-align: right" class="col-2"><span class="font-size-2">$ '.number_format($total,2).'</span></td>
      </tr>
    </table>';
        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'rubik',
            'mode' => 'utf-8', 
            'format' => 'Letter',
            'margin_left' => 10,     // 15 margin_left
            'margin_right' => 10,
            'margin_header' => 10,     // 9 margin header
            'margin_footer' => 10,]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $numeroFactura = $bill->number;
        $bill->pdf = 'Views/bills/factura_'.$numeroFactura.'.pdf';
        $bill->save();
        $mpdf->Output('Views/bills/factura_'.$numeroFactura.'.pdf','F');
        $envio = ControladorGeneral::correoFactura(ucfirst($nombreCliente),$bill->email,$bill->pdf,$hoy->format('d/m/Y'),$bill->document->hash,$qr);
        unlink($bill->pdf);
    }
    public static function pdfInformeFacturas($date1,$date2){
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date1=Carbon::createFromFormat('d-m-Y',$date1);
        $date2=Carbon::createFromFormat('d-m-Y',$date2);
        $bills = ludcis\Bill::whereNotNull('number')->where('number','!=','')->whereBetween('created_at',[$date1,$date2])->get();
          $mpdf = new \Mpdf\Mpdf([
              'default_font' => 'rubik',
              'mode' => 'utf-8', 
              'format' => 'Letter',
              'margin_left' => 10,     // 15 margin_left
              'margin_right' => 10,
              'margin_header' => 10,     // 9 margin header
              'margin_footer' => 10,]);
          $mpdf->SetDisplayMode('fullpage');
          $mpdf->shrink_tables_to_fit = 1;
          $mpdf->defaultfooterline = 0;
          $first = true;
        foreach ($bills as $bill) {
          if ($first == true) {
            $first = 0;
          }else{
            $mpdf->AddPage();
          }
          $numeroFactura = $bill->number;
          $nombreCliente = $bill->name;
          $tipoDocumento = $bill->id_type;
          $documento = $bill->id_number;
          $code = $bill->document->product->code;
          $producto = $bill->document->product->name;
          $email = $bill->email;
          $valor = $bill->document->product->value/1.19;
          $codigoDescuento = ludcis\Code::where('code',$bill->code)->first();
          if (is_object($codigoDescuento)) {
            if ($codigoDescuento->porcentual=='1') {
              $descuento = $valor * $codigoDescuento->amount /100;
              $descuento = $descuento / 1.19;
            }else{
              $descuento=$codigoDescuento->amount/1.19;
            }
          }else{
            $descuento = 0;
          }
          $neto = $bill->base;
          $iva = $bill->tax;
          $total = $bill->total;
          $hoy = Carbon::parse($bill->created_at);
          $valorLetras = ludcis\NumeroALetras::convertir($total, 'pesos colombianos', 'centavos');
          $resolution = $bill->resolution;
          $numeroRes = $resolution->res_number;
          $minRes = $resolution->start_number;
          $maxRes = $resolution->end_number;
          $fechaRes = $resolution->res_expedition;
          $html='<html>
  <head>
  <style>
  body{
    background: url("/Views/img/plantilla/AF_FONDO.jpg");
    background-image-resize:6;
    background-repeat: no-repeat;
    background-position: center;
    font-size: 12px;
  }
  * {
    box-sizing: border-box;
  }
  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    max-width: 100%;
  }
  td{
    padding:5px;
  }
  *.backgroung-gray{
  background-color: #56b688;
  }
  .row::after {
    content: "";
    clear: both;
    display: table;
  }
  .row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -7.5px;
    margin-left: -7.5px;
      width100%;
  }
  [class*="col-"] {
    float: left;
    padding: 15px;
  }
  *.col-1 {width: 8.33%;}
  *.col-2 {width: 16.66%;}
  *.col-3 {width: 25%;}
  *.col-4 {width: 33.33%;}
  *.col-5 {width: 41.66%;}
  *.col-6 {width: 50%;}
  *.col-7 {width: 58.33%;}
  *.col-8 {width: 66.66%;}
  *.col-9 {width: 75%;}
  *.col-10 {width: 83.33%;}
  *.col-11 {width: 91.66%;}
  *.col-12 {width: 100%;}
  .center {
    text-align: center important!;
  }
  .end {
    text-align: end;
  }
  .start {
    text-align: start;
  }
  tr{
    width:100%;
  }
  .border-gray{
  border: 1px solid gray
  }
  td.backgroung-gray{
  border: 1px solid black; 
  background-color: #56b688
  }
  .border-black{
  border: 1px solid black
  }
  .color-black{
  color: #000
  }
  .color-gray{
  color: #808080
  }
  .border-black{
  border: 1px solid black
  }
  .font-size{
    font-size:15px;
  }
  .font-size-2{
    font-size:13px;
  }
  .font-size-3{
    font-size:11px;
  }
  .font-size-4{
    font-size:10px;
  }
  .font-size-5{
    font-size:10px;
  }
  .w-100{
    width:100%;
  }
  .pl{
    padding-left:5%
  }
  </style>
  </head>
  <body>
  <table class="w-100" style="padding-top:50px">
        <tr>
          <td class="col-12" style="text-align: right">
            <span class="font-size-2 color-black">Ludcis S.A.S.<br><span class="color-gray">NIT: 901.323.761-1<br>Teléfono: 302 323 3242<br>soporte@ludcis.com<br>Régimen Común</span></span>
          </td>
        </tr>
  </table>
      <table class="w-100" style="padding-top:25px">
        <tr>
          <td class="col-12" style="text-align: right">
            <span class="font-size color-black"><b>FACTURA DE VENTA: '.$numeroFactura.'</b></span>
          </td>
        </tr>
        </table>
        <table class="w-100">   
        <tr>
          <td class="col-2" style="text-align:right; width: 16.67%">
            <span class="color-gray"><b>Cliente:<br>'.$tipoDocumento.':<br>e-mail:<br>Fecha:</b></span>
          </td>  
          <td class="col-10">
            <span class="color-gray">'.mb_strtoupper($nombreCliente).'<br>'.$documento.'<br>'.$email.'<br>'.$hoy->format('d/m/y').'</span>
          </td>
        </tr>
      </table>
      <table class="w-100" style="padding-top:40px">
        <tr>
          <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Ítem</span></td>
          <td style="text-align: center; border-bottom: 3px solid black; width: 40%" class="col-4"><b class="font-size-2">Descripción</span></td>
          <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Cantidad</span></td>
          <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Valor</span></td>
          <td style="text-align: center; border-bottom: 3px solid black; width: 15%" class="col-2"><b class="font-size-2">Valor Total</span></td>
        </tr>
        <tr>
          <td style="text-align: center" class="col-2"><span>'.$code.'</span></td>
          <td style="text-align: left" class="col-4"><span>'.ucfirst($producto).'</span></td>
          <td style="text-align: center" class="col-2"><span>1</span></td>
          <td style="text-align: right" class="col-2"><span>$ '.number_format($valor,2).'</span></td>
          <td style="text-align: right" class="col-2"><span>$ '.number_format($valor,2).'</span></td>
        </tr>
        <tr>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: left;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
        </tr>
        <tr>
          <td style="text-align: right;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;padding-top:185px" class="col-4"><span class="font-size-3"></span></td>
          <td style="text-align: right;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: left;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;padding-top:185px" class="col-2"><span class="font-size-3"></span></td>
        </tr>
        <tr>
          <td style="text-align: right; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right; border-bottom: 3px solid black" class="col-4"><span class="font-size-3"></span></td>
          <td style="text-align: right; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: left; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right; border-bottom: 3px solid black" class="col-2"><span class="font-size-3"></span></td>
        </tr>
        <tr>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right" class="col-2"><span class="font-size-3">Subtotal:</span></td>
          <td style="text-align: right" class="col-2"><span>$ '.number_format($valor,2).'</span></td>
        </tr>
        <tr>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right" class="col-2"><span class="font-size-3">Descuento:</span></td>
          <td style="text-align: right" class="col-2"><span>$ '.number_format($descuento,2).'</span></td>
        </tr>
        <tr>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-4"><span class="font-size-3"></span></td>
          <td style="text-align: right;" class="col-2"><span class="font-size-3"></span></td>
          <td style="text-align: right" class="col-2"><span class="font-size-3">Neto:</span></td>
          <td style="text-align: right" class="col-2"><span>$ '.number_format($neto,2).'</span></td>
        </tr>
        <tr>
          <td style="text-align: center;" class="col-10" colspan="3" rowspan="4"><span class="font-size-5 color-gray">'.mb_strtoupper('Representación de factura en papel Resolución DIAN '.$numeroRes.' numeración autorizada de '.$minRes.' a '.$maxRes.' de '.$fechaRes.'. Esta factura es informativa, generada únicamente para fines tributarios.', 'UTF-8').'</span></td>
          <td style="text-align: right" class="col-2"><span class="font-size-3">IVA:</span></td>
          <td style="text-align: right" class="col-2"><span>$ '.number_format($iva,2).'</span></td>
        </tr>
        <tr>
          <td style="text-align: right" class="col-2"><span class="font-size-3">Retefuente:</span></td>
          <td style="text-align: right" class="col-2"><span>$ 0.00</span></td>
        </tr>
        <tr>
          <td style="text-align: right" class="col-2"><span class="font-size-3">ReteIVA:</span></td>
          <td style="text-align: right" class="col-2"><span>$ 0.00</span></td>
        </tr>
        <tr>
          <td style="text-align: right" class="col-2"><span class="font-size-3">ReteICA:</span></td>
          <td style="text-align: right" class="col-2"><span>$ 0.00</span></td>
        </tr>
        <tr>
          <td style="text-align: right;" class="col-2"><b>SON:</b><span class="color-black"></span></td>
          <td style="text-align: left;" class="col-6" colspan="2">'.ucfirst(strtolower($valorLetras)).' (COP).<span class="color-black font-size-3"></span></td>
          <td style="text-align: right" class="col-2"><span><b>Valor Total:</b></span></td>
          <td style="text-align: right" class="col-2"><span class="font-size-2">$ '.number_format($total,2).'</span></td>
        </tr>
      </table>';
          $mpdf->WriteHTML($html);
      } 
        $mpdf->Output();
    }
}