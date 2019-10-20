<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use ludcis;

use Carbon\Carbon;

use Mpdf\Mpdf;

use Mail;

class ControladorInformes extends Controller
{
    public function pdfPagare(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newEmail'];
        setlocale(LC_TIME, 'es_ES');
    		date_default_timezone_set('America/Bogota');
        $valorLetras = ludcis\NumeroALetras::convertir($datos['newAmount'], 'pesos colombianos', 'centavos');
        $valorLetras .=' (COP).';
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $fechaFin = Carbon::parse($datos['newPaymentDate']);
        $meses = floor($hoy->diffInMonths($fechaFin));
        $interes = $datos['newAmount']*$meses*$datos['newInterest']/100;
        $total = $interes + $datos['newAmount'];
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($total, 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
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
<body><div class="row m1" style="text-align:center;">
	<span class="font-size"><b>PAGARÉ</b></span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2">Yo, '.$datos['newDebtor'].', mayor de edad y residente de la ciudad de '.$datos['newDebtorCity'].', identificado (a) con '.$datos['newDebtorIdType'].' <b>No. </b>'.$datos['newDebtorId'].' expedida en el municipio de '.$datos['newDebtorExpedition'].', actuando en nombre propio, por medio del presente escrito manifiesto, lo siguiente: </span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>PRIMERO:</u></b> Que debo y pagare, de manera incondicional y solidariamente, a la orden de (el) (la) señor (a) '.$datos['newCreditor'].', identificado (a) con '.$datos['newCreditorIdType'].' <b>No. </b>'.$datos['newCreditorId'].' expedida en el municipio de '.$datos['newCreditorExpedition'].', o en su defecto, a la persona ya sea natural o jurídica, a quien el (la) mencionado (a) acreedor (a) (el (la) señor (a) '.$datos['newCreditor'].'), ceda o endose sus derechos sobre este pagaré, la suma real y cierta de: <u>$ '.number_format($total,2).' ('.$valorLetrasTotal.') </u> <b>M/L</b>. mismos que derivan de préstamo a título personal, con fines de libre inversión. En dicho importe, se encuentran sumados los intereses del préstamo, en favor del (la) señor (a) '.$datos['newCreditor'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>SEGUNDO:</u></b> Que el pago total de la citada obligación se efectuara en';
    	if ($datos['newFeesNumber']>1) {
    		$html .='un total de '.$datos['newFeesNumber'].' cuotas de la siguiente manera: </span>';
    	}else{
    		$html .='sola cuota o contado de la siguiente manera: </span><br>';
    	}
$html .= '
	<span class="font-size2">
		<ul>
			<li>';
    	if ($datos['newFeesNumber']>1) {
    		$html .='El '.substr($fechaFin,0,10).', habré pagado en favor del acreedor o a quién este último, ceda o endose sus derechos sobre este pagaré, la suma cierta de: <u>$ '.number_format($total,2).' ('.$valorLetrasTotal.')</u> mismos que derivan del préstamo para libre inversión, en la ciudad de '.$datos['newCreditCity'].', ';
    		if($datos['newPaymentType']=='Deposito'){
    			$html .='en la '.$datos['newPaymentAccount'].' <b>No.</b>'.$datos['newPaymentNumber'].' del banco'.$datos['newPaymentBank'].'en favor y a nombre del suscrito acreedor.';
    		}else{
    			$html .='en efectivo a favor del suscrito acreedor';
    		}
    	}else{
    		$html .='El '.substr($fechaFin,0,10).', pagaré en favor del acreedor o a quién este último, ceda o endose sus derechos sobre este pagaré, la suma cierta de: <u>$ '.number_format($total,2).' ('.$valorLetrasTotal.')</u> mismos que derivan del préstamo para libre inversión, en la ciudad de '.$datos['newCreditCity'].', ';
    		if($datos['newPaymentType']=='Deposito'){
    			$html .='en la '.$datos['newPaymentAccount'].' <b>No.</b>'.$datos['newPaymentNumber'].' del banco'.$datos['newPaymentBank'].'en favor y a nombre del suscrito acreedor.';
    		}else{
    			$html .='en efectivo a favor del suscrito acreedor';
    		}
    	}
$html .= '</li>';
    	if ($datos['newFeesNumber']>1) {
	    	for ($i = 1; $i <= $datos['newFeesNumber'] ; $i++) {
	    		$key = 'newPaymentDate'.$i;
	    		$html .= '<li>
	    			El pago número '.$i.' se realizará la fecha '.$datos[$key].'
	    		</li>';
	    	}
    	}
    	$html .= '<li>Los abonos correspondientes a los intereses serán causados mensualmente, estos, se harán por un valor del '.$datos['newInterest'].' de interés efectivo mensual, sobre el valor del prestamo y deberán ';
		if($datos['newPaymentType']=='Deposito'){
			$html .='consignarse en la '.$datos['newPaymentAccount'].' <b>No.</b>'.$datos['newPaymentNumber'].' del banco'.$datos['newPaymentBank'].'en favor y a nombre del suscrito acreedor, ';
		}else{
			$html .='pagarse en efectivo a favor del suscrito acreedor, ';
		}
    	$html .= 'o en su defecto, a la persona ya sea natural o jurídica, a quien el (la) mencionado (a) acreedor (a) el (la) señor (a) '.$datos['newCreditor'].', ceda o endose sus derechos sobre este pagaré</li>';
$html .= '</ul>
	</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>TERCERO:</u></b> La fecha de pago de este título valor será el dia: <u>'.$datos['newPaymentDate'].'</u>.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>CUARTO:</u></b> Que, en caso de mora, yo, pagaré a el (la) señor (a) '.$datos['newCreditor'].', identificada con '.$datos['newCreditorIdType'].' No. '.$datos['newCreditorId'].' expedida en la ciudad de '.$datos['newCreditorExpedition'].', o a la persona natural o jurídica, a quien el (la) mencionado (a) acreedor (a) ceda o endose sus derechos sobre este pagaré; intereses de mora, a la más alta tasa permitida por la Ley, desde el día siguiente a la fecha de exigibilidad del presente pagaré, y hasta cuando su pago total se efectúe.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>QUINTO:</u></b> Expresa, precisa y claramente, declaro excusado, el protesto del presente pagaré; además, de todos y cada uno de los requerimientos judiciales o extrajudiciales para la constitución en mora.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2"><b><u>SEXTO:</u></b> En caso de que haya lugar al recaudo judicial o extrajudicial de la obligación, contenida en el presente título valor, esta, será a mi cargo en su totalidad, junto con las costas jurídicas y/o los honorarios que se causaren por razón del proceso contencioso que devenga del no pago de esta obligación.</span>
</div>
<div class="row m2" style="text-align:justify;">
	<span class="font-size2">En constancia y por consecuencia de lo anterior, se firma y suscribe este título valor, en la ciudad de '.$datos['newCreditCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div></body>
</html>';
$footer='<table>
  <tr>
    <td>
      <span class="font-size2 m4"><b>EL DEUDOR,</b></span>
    <br>
    <br>
    <br>
    <br>
    <div class="row">
      <span class="font-size2" style="width: 50%; border-bottom: 1px solid #000000">_________________________________</span><br>
      <span class="font-size3">'.$datos['newDebtor'].'<br>
      <b>'.$datos['newDebtorIdType'].' No.</b>'.$datos['newDebtorId'].' de '.$datos['newDebtorExpedition'].'</span>
    </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/pagare_'.$codigo.'.pdf';
        $mpdf->Output('Views/documents/'.$document->product->code.'/pagare_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo($datos['newCreditor'],$document->email,$document->product->name,$document->link);
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();          
        }
        $document->document_state=1;
        $document->save();
        $mpdf->Output();
    }
    public function pdfConfidencialidad(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newSendEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
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
          <span class="font-size-2">'.$datos['newFirstPart'].'</span>
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
          <span class="font-size-2">'.$datos['newSecondPart'].'</span>
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
       </tr>';
       }
      $html .= '</table>';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='<div class="row m2" style="text-align:left;">
    <span class="font-size2"><b>IDENTIFICACIÓN DEL CARGO </b>('.$datos['newCharge'].')</span>
</div>';
       }
      $html .= '<div class="row m2" style="text-align:justify;">Entre los abajo firmantes, identificados precedentemente, habremos de convenir en celebrar el presente <b>ACUERDO</b> de confidencialidad previa las siguientes: </span>
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
           $html .='el (la) señor (a) '.$datos['newFirstPart'].' ';
       }
      $html .= ' la misma, es considerada altamente sensible y de carácter restringido en su publicidad, administración y utilización. Dicha información, es compartida en virtud ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='del adelanto del CARGO '.$datos['newCharge'].' como quedó identificado previamente';
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
           $html .='el (la) señor (a) '.$datos['newFirstPart'].',';
       }
      $html .= ', ha sido desarrollada u obtenida de manera legal, como resultado de todos y cada uno de sus procesos, programas o proyectos y, en consecuencia, abarca documentos, datos, tecnología y/o material que considera único y confidencial, o que es objeto de amparo a título de secreto industrial.</li>
      <li class="font-size2 m2">3.  El presente <b>CONVENIO</b>, se realiza por un lado entre la <b>PARTE RECEPTORA</b> de la información como ';
       if ($datos['newContractType'] == 'Contrato') {
           $html .='integrante del CARGO '.$datos['newCharge'].' ';
       }elseif ($datos['newContractType'] == 'Convenio') {
           $html .='contraparte de la propuesta de un convenio comercial para la COMPAÑÍA '.$datos['newSecondCompany'].' ';
       }else{
           $html .='contraparte de la propuesta de una sociedad ';
       }
      $html .= 'y por otro lado el (la) señor (a) '.$datos['newFirstPart'].' que, para el presente caso, actúa como la PARTE REVELADORA, quien guarda y administra la información ';
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
           $html .=' el (la) señor (a) '.$datos['newFirstPart'].',';
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
           $html .=' el (la) señor (a) '.$datos['newFirstPart'].',';
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
    <span class="font-size2"><b>Undécima. Legislación Aplicable:</b> Este <b>convenio</b> se regirá por las leyes de la República de Colombia y se interpretará de acuerdo con las mismas.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Duodécima. Modificación o Terminación:</b> Este acuerdo solo podrá ser modificado o darse por terminado con el consentimiento expreso por escrito de ambas partes.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimotercera. Aceptación del Acuerdo:</b> Las partes han leído y asimilado de manera detenida el contenido, los términos y condiciones del presente <b>Convenio</b> y por tanto manifiestan estar conformes y aceptan todas las condiciones.</span>
</div>
<div class="row m2" style="text-align:justify;">
    <span class="font-size2"><b>Decimocuarta. Validez y Perfeccionamiento:</b>El presente Acuerdo requiere para su validez y perfeccionamiento la firma de las partes, las cuales se dan a continuación.</span>
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
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>PARTE REVELADORA</b></span><br>
      <span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].' No.</b>'.$datos['newFirstId'].'</span>
  </td>
  <td class="col-6">
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>PARTE RECEPTORA</b></span><br>
      <span class="font-size3 start">'.$datos['newSecondPart'].'<br>
      <b>'.$datos['newSecondIdType'].' No.</b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_confidencialidad_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_confidencialidad_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo($datos['newFirstPart'],$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'));
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();        
        $mpdf->Output();
    }
    public function mesLetras($hoy){
        switch ($hoy->format('m')) {
            case '01':
                return 'Enero';
                break;
            case '02':
                return 'Febrero';
                break;
            case '03':
                return 'Marzo';
                break;
            case '04':
                return 'Abril';
                break;
            case '05':
                return 'Mayo';
                break;
            case '06':
                return 'Junio';
                break;
            case '07':
                return 'Julio';
                break;
            case '08':
                return 'Agosto';
                break;
            case '09':
                return 'Septiembre';
                break;
            case '10':
                return 'Octubre';
                break;
            case '11':
                return 'Noviembre';
                break;
            case '12':
                return 'Diciembre';
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
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::parse($datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::parse($datos['newEndDate']);
        $dias = floor($inicio->diffInDays($fin));
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
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
    <span class="font-size"><b>CONTRATO DE PRESTACIÓN DE SERVICIOS PROFESIONALES DE '.$datos['newCharge'].'</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del contratante: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstPart'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del (la) contratista: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondPart'].'</span>
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
          <span class="font-size-2"><b>Domicilio del (la) contratista: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondAddress'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Teléfono del (la) contratista: </b></span>
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
          <span class="font-size-2">'.$datos['newContractCity'].'</span>
        </td>
       </tr>
       </table>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre la empresa '.$datos['newFirstCompany'].', empresa legalmente constituida Identificada con el '.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].' y con domicilio principal en la '.$datos['newFirstAddress'].', Teléfono No. '.$datos['newFirstPhone'].'., misma representada legalmente por el (la) señor(a) '.$datos['newFirstPart'].', Identificado(a) con '.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].' de '.$datos['newFirstExpedition'].' según certificado de la Cámara de Comercio '.$datos['newFirstCompanyCamera'].' No. '.$datos['newFirstCompanyCameraNumber'].', quien en adelante se denominará CONTRATANTE y por otra parte el (la) señor(a) '.$datos['newSecondPart'].', identificado con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].' de '.$datos['newSecondExpedition'].', y tarjeta profesional No. '.$datos['newSecondProfesionalCard'].' y quien en adelante se denominará CONTRATISTA,hemos convenido en celebrar un contrato de prestación de servicios profesionales que se regulará por las cláusulas que a continuación se expresan y en general por las disposiciones del título XXVIII capítulo I del Libro Cuarto del Código Civil y Código de Comercio aplicables a la materia de qué trata este contrato: </span>
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
  <span class="font-size2"><b><u>CUARTA</u>.</b>. Tramite. El trámite o servicio se realizará en la dirección '.$datos['newFirstAddress'].', los costos de traslado al sitio de herramientas y equipos, serán asumidos por el <b>CONTRATANTE</b>, mientras que el traslado personal del <b>CONTRATISTA</b> al sitio de prestación del servicio, se hará por sus propios medios.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>QUINTA</u>.</b>Facultades. - EL CONTRATISTA queda expresamente facultado para '.$datos['newFaculties'].'.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEXTA</u>.</b>. Obligaciones del <b>CONTRATISTA</b>. Son obligaciones del <b>CONTRATISTA:</b> 1. Obrar con seriedad y diligencia en el servicio contratado, 2. Informar constantemente al <b>CONTRATANTE</b> sobre el proceso de la prestación de su servicio. 3. Atender las solicitudes y recomendaciones que haga <b>EL CONTRATANTE</b> o sus delegados, con la mayor prontitud.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>SEPTIMA</u>.</b>Obligaciones del <b>CONTRATANTE</b>. Son obligaciones del <b>CONTRATANTE:</b> 1. Cancelar los honorarios fijados al <b>CONTRATISTA</b>, según la forma que se pactó dentro de los términos debidos, so pena de incurrir en intereses por mora en la cancelación o devolución de los mismos. 2. Entregar toda la información, equipo. Herramientas y logística que solicite el <b>CONTRATISTA</b> para poder desarrollar con normalidad su labor independiente.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>OCTAVA</u>.</b>Terminación anticipada o anormal. – Incumplir las obligaciones propias de cada una de las partes, dará lugar a la otra para terminar unilateralmente el Contrato de Prestación de Servicio.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>NOVENA</u>.</b>Cláusula compromisoria. – Toda controversia o diferencia relativa a este contrato, su ejecución y liquidación, se resolverá por un tribunal de arbitramento que por economía será designado por las partes y será del domicilio donde se debió ejecutar el servicio contratado o en su defecto en el domicilio de la parte que lo convoque. El tribunal de Arbitramento se sujetará a lo dispuesto en el decreto 1818 de 1998 o estatuto orgánico de los sistemas alternativos de solución de conflictos y demás normas concordantes. En todo caso, este contrato presta mérito ejecutivo por ser una obligación clara, expresa y exigible para las partes.</span>
</div>
<div class="row m3" style="text-align:justify;">
    <span class="font-size2">Este Contrato de Prestación de Servicios se firma en dos ejemplares para las partes en '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').'.</span>
</div>
<br>
<br>
</body>
</html>';
$footer=
'<table style="width:100%">
  <tr>
  <td class="col-6">
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL CONTRATANTE</b></span><br>
      <span class="font-size3 start">'.$datos['newFirstCompany'].'<br>
      <b>'.$datos['newFirstCompanyIdType'].' No.</b>'.$datos['newFirstCompanyId'].'<br>
      Representante Legal</span>
  </td>
  <td class="col-6">
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL CONTRATISTA</b></span><br>
      <span class="font-size3 start">'.$datos['newSecondPart'].'<br>
      <b>'.$datos['newSecondIdType'].' No.</b>'.$datos['newSecondId'].'<br>
      Prestador</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_servicios_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_servicios_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo($datos['newFirstPart'],$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'));
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();      
        $mpdf->Output();
    }
    public function pdfArrendamiento(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::parse($datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::parse($datos['newEndDate']);
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
       <div class="row m2" style="text-align:justify;"><span class="font-size2">En la ciudad de '.$datos['newContractCity'].', a los '.$hoy->format('d').' días del mes de '.$mes.' del año '.$hoy->format('Y').' entre los suscritos a saber: el (la) señor (a) '.$datos['newFirstPart'].' mayor de edad y residente en '.$datos['newFirstCity'].', identificado (a) con '.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].' expedida en '.$datos['newFirstExpedition'].', respectivamente en adelante llamado <b>EL ARRENDATARIO</b>, de una parte y el (la) señor (a) '.$datos['newSecondPart'].' mayor de edad y residente en '.$datos['newSecondCity'].', identificado (a) con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].' expedida en '.$datos['newSecondExpedition'].', en adelante denominado <b>EL ARRENDADOR</b>, acuerdan celebrar el presente contrato de arrendamiento de <b>ESTABLECIMIENTO DE COMERCIO</b> ubicado en '.$datos['newContractCity'].', en el departamento de '.$datos['newContractDepartment'].', el cual se regirá por las siguientes cláusulas y lo no pactado en ellas por las leyes colombianas vigentes y aplicables a este asunto especialmente las consagradas en los capítulos II y III, Título XXVI, Libro 4 del Código Civil, Libro 3º Título I y ss. Del código de comercio.</span>
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
  <span class="font-size2"><b><u>Parágrafo:</u>:</b>este canon, se aumentará anualmente en un '.$datos['newCharge'].'% del valor pactado y a partir de la fecha pactada de entrega, misma que se estipula el día '.$inicio->format('d').' de '.$mesInicio.' de '.$inicio->format('Y').'.</span>
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
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL ARRENDATARIO</b></span><br>
      <span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].' No.</b>'.$datos['newFirstId'].'</span>
  </td>
  <td class="col-6">
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL ARRENDADOR</b></span><br>
      <span class="font-size3 start">'.$datos['newSecondPart'].'<br>
      <b>'.$datos['newSecondIdType'].' No.</b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_arrendamiento_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_arrendamiento_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo($datos['newFirstPart'],$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'));
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();      
        $mpdf->Output();
    }
    public function pdfDomestico(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::parse($datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::parse($datos['newEndDate']);
        $dias = floor($inicio->diffInDays($fin));
        $diasPrueba = ludcis\NumeroALetras::convertir($datos['newTestDays']);
        $diasAviso = ludcis\NumeroALetras::convertir($datos['newAlertDays']);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasTotal .=' (COP).';
        $valorLetrasEspecia = ludcis\NumeroALetras::convertir($datos['newSpiceSalary'], 'pesos colombianos', 'centavos');
        $valorLetrasEspecia .=' (COP).';
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
    <span class="font-size"><b>CONTRATO DE PRESTACIÓN DE TRABAJO DE SERVICIO DOMÉSTICO.</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del empleador: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstPart'].'</span>
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
          <span class="font-size-2">'.$datos['newSecondPart'].'</span>
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
          <span class="font-size-2">'.$datos['newSecondAddress'].'</span>
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
       <div class="row m2" style="text-align:justify;"><span class="font-size2">Entre las partes (<b>EMPLEADOR (A)</b> y <b>EMPLEADO (A)</b>), ambos (as) mayores de edad, capaces para contratar, identificadas como se anota anteriormente, libre y voluntariamente, suscribimos el presente Contrato de Trabajo de servicio doméstico, y lo hacemos fundamentados en la Buena Fe, en especial, en el respeto a los principios del Derecho al Trabajo; con sujeción a las declaraciones y estipulaciones contenidas en las siguientes clausulas: </span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b><u>PRIMERA</u>.- Lugar: EL (LA) EMPLEADOR (A),</b> quien tiene su domicilio en la '.$datos['newFirstAddress'].', requiere contratar los servicios de una persona para las labores domésticas, en el domicilio antes señalado. <b>EL (LA) TRABAJADOR(A)</b>, desarrollará el objeto del <b>CONTRATO DE SERVICIO DOMÉSTICO</b> en la residencia <b>EL (LA) EMPLEADOR (A)</b>. En caso que este último, cambie de domicilio dentro de la misma ciudad, el contrato se entenderá modificado respecto al sitio de prestación de la labor.</span>
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
  <span class="font-size2"><b><u>QUINTA</u>.- Término del contrato: </b>El presente contrato tendrá un término de duración de '.$dias.' días, pero podrá darse por terminado por cualquiera de las partes, cumpliendo con las exigencias legales del articulo 61 y ss. del CST al respecto.</span>
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
  <span class="font-size2"><b><u>DECIMA SEPTIMA</u>.- Suscripción y validez:</b> Las partes, se ratifican en todas y cada una de las cláusulas precedentes, donde para constancia y plena validez de lo estipulado, firman este contrato en original y dos (2) ejemplares de igual tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación:</span>
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
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL (LA) EMPLEADOR(A)</b></span><br>
      <span class="font-size3 start">'.$datos['newFirstPart'].'<br>
      <b>'.$datos['newFirstIdType'].' No.</b>'.$datos['newFirstId'].'
  </td>
  <td class="col-6">
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL (LA) TRABAJADOR(A)</b></span><br>
      <span class="font-size3 start">'.$datos['newSecondPart'].'<br>
      <b>'.$datos['newSecondIdType'].' No.</b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_domestico_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_domestico_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo($datos['newFirstPart'],$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'));
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();      
        $mpdf->Output();
    }
    public function pdfTrabajo(){
        $document = ludcis\Document::where('hash',$_POST['newCode'])->first();
        if (!is_object($document)) {
          return redirect('/');        
        }
        $datos = $_POST;
        $document->email = $datos['newFirstEmail'];
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now();
        $mes = $this->mesLetras($hoy);
        $inicio = Carbon::parse($datos['newStartDate']);
        $mesInicio = $this->mesLetras($inicio);
        $fin = Carbon::parse($datos['newEndDate']);
        $mesFin = $this->mesLetras($fin);
        $valorLetrasTotal = ludcis\NumeroALetras::convertir($datos['newSalary'], 'pesos colombianos', 'centavos');
        $prueba = ludcis\NumeroALetras::convertir($datos['newTestDays']);
        $valorLetrasTotal .=' (COP).';
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
    <span class="font-size"><b>CONTRATO DE TRABAJO A TÉRMINO FIJO, INFERIOR UN AÑO</b></span>
</div>
    <table style="width:100%" class="m2">
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del empleador: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstPart'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Domicilio de la empresa: </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newFirstAddress'].'</span>
        </td>
      </tr>
      <tr>
        <td class="col-4">
          <span class="font-size-2"><b>Nombre del (la) trabajador (a): </b></span>
        </td>
        <td class="col-8" style="text-align:right; background-color:#bfbfbf">
          <span class="font-size-2">'.$datos['newSecondPart'].'</span>
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
          <span class="font-size-2">'.$datos['newSecondAddress'].'</span>
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
       <div class="row m2" style="text-align:justify;">Las partes, que suscribimos el presente Contrato de Trabajo a Término Fijo, lo hacemos fundamentados en la Buena Fe, y en especial en el respeto a los principios del Derecho de Trabajo.</span>
</div>
       <div class="row m2" style="text-align:justify;"><span class="font-size2">'.$datos['newFirstPart'].', identificado con '.$datos['newFirstIdType'].' No. '.$datos['newFirstId'].' de '.$datos['newFirstExpedition'].', en mi calidad de empleador y Representante Legal de la empresa '.$datos['newFirstCompany'].', Identificada con '.$datos['newFirstCompanyIdType'].' No. '.$datos['newFirstCompanyId'].', con domicilio comercial en la '.$datos['newFirstAddress'].' de la ciudad de '.$datos['newFirstCity'].', quien en adelante se denominará EMPLEADOR y '.$datos['newSecondPart'].', identificado con '.$datos['newSecondIdType'].' No. '.$datos['newSecondId'].' residente en la ciudad de '.$datos['newSecondCity'].', quien en adelante se denominará TRABAJADOR, quien desempeñará el cargo de '.$datos['newCharge'].' acuerdan celebrar el presente CONTRATO INDIVIDUAL DE TRABAJO A TÉRMINO FIJO, INFERIOR UN AÑO, para ser ejecutado en la ciudad de '.$datos['newContractCity'].', el cual se regirá por las siguientes cláusulas:</span>
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
  <span class="font-size2"><b><u>OCTAVA</u>. - </b>Este contrato es un <b>CONTRATO INDIVIDUAL DE TRABAJO A TÉRMINO FIJO, INFERIOR UN AÑO</b>, a partir del día '.$inicio->format('d').' mes '.$mesInicio.' año '.$inicio->format('Y').' y hasta el día '.$fin->format('d').' mes '.$mesFin.' año '.$inicio->format('Y').' , permaneciendo este, mientras subsistan las causas que le dieron origen a ese contrato.</span>
</div>
<div class="row m2" style="text-align:justify;">
  <span class="font-size2"><b>PARAGRAFO. - </b>Los primeros '.$prueba.' ('.$datos['newTestDays'].') días del presente contrato, se consideran como <b>PERÍODO DE PRUEBA</b> y, por consiguiente, cualquiera de las partes podrá terminar el contrato unilateralmente, en cualquier momento durante dicho período.</span>
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
  <span class="font-size2"><b><u>DÉCIMA SEGUNDA</u>. - </b>El presente contrato reemplaza en su integridad y deja sin efecto, cualquier otro contrato verbal o escrito celebrado entre las partes con anterioridad. Las modificaciones que se acuerden al presente contrato, se anotarán a continuación de su texto. Para constancia se firma en dos (2) o más ejemplares del mismo tenor y valor, sin necesidad de testigos, en la ciudad y fecha que se indican a continuación:</span>
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
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL EMPLEADOR</b></span><br>
      <span class="font-size3 start">'.$datos['newFirstCompany'].'<br>
      <b>'.$datos['newFirstCompanyIdType'].' No.</b>'.$datos['newFirstCompanyId'].'</span>
  </td>
  <td class="col-6">
  <span class="font-size2 start" style="border-bottom: 1px solid #000000">_________________________________</span><br>
  <span class="font-size2 start"><b>EL TRABAJADOR</b></span><br>
      <span class="font-size3 start">'.$datos['newSecondPart'].'<br>
      <b>'.$datos['newSecondIdType'].' No.</b>'.$datos['newSecondId'].'</span>
  </td>
  </tr>
</table>';
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'Letter',]);;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->WriteHTML($html);
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter($footer);
        $codigo = sha1($document->id);
        $document->link = 'Views/documents/'.$document->product->code.'/contrato_trabajo_'.$codigo.'.pdf';
        $document->save();
        $mpdf->Output('Views/documents/'.$document->product->code.'/contrato_trabajo_'.$codigo.'.pdf','F');
        $envio = ControladorGeneral::correo($datos['newFirstPart'],$document->email,$document->product->name,$document->link,$hoy->format('d/m/Y'));
        if ($envio=='ok') {
          $enviado = new ludcis\Mail_log();
          $enviado->document_id = $document->id;
          $enviado->email = $document->email;
          $enviado->save();  
        }
        $document->document_state=1;
        $document->save();
        $mpdf->Output();
    }
}
