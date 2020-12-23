<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use ludcis;

use Carbon\Carbon;

class EpaycoController extends Controller
{
    //
    public function redirigirPago(){
    	if (isset($_POST['newPayment'])) {
    		$document = ludcis\Document::where('hash',$_POST['newCode'])->first();
    		if (is_object($document) && filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)) {
	    		$product = $document->product;
	    		$base = $product->value/1.19;
	    		$base= round($base, 0, PHP_ROUND_HALF_UP);
	    		$tax = $product->value*0.19/1.19; 
	    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
    			if (isset($_POST['newDiscount']) && $_POST['newDiscount'] != "" && $_POST['newDiscount'] != null) {
    				$discCode=$_POST['newDiscount'];
    				$discount = ludcis\Code::where('code',$discCode)->first();
    				if (is_object($discount))  {
	    				if ($discount->active=="1") {
	    					if ($discount->restricted=="1") {
	    						switch ($discount->res_type) {
	    							case "ip":
	       								$ip = ControladorGeneral::obtenerIp();
	    								// $ip = "181.141.228.221";
	    								if ($ip==$discount->res_value) {
				    						if ($discount->porcentual=="1") {
									    		$base = $product->value*(1-($discount->amount/100))/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = $product->value*(1-($discount->amount/100))*0.19/1.19; 
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}else{
									    		$base = ($product->value-$discount->amount)/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = ($product->value-$discount->amount)/1.19*0.19;
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}
	    								}
	    							break;
	    							
	    							case "date":
	    								$hoy = Carbon::now();
	    								$limite = Carbon::parse($discount->res_value);
	    								if ($limite>$hoy) {
				    						if ($discount->porcentual=="1") {
									    		$base = $product->value*(1-($discount->amount/100))/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = $product->value*(1-($discount->amount/100))*0.19/1.19; 
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}else{
									    		$base = ($product->value-$discount->amount)/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = ($product->value-$discount->amount)/1.19*0.19;
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}
	    								}
	    							break;
	    							case "document":
	    								if ($document->product->code == $discount->res_value) {
				    						if ($discount->porcentual=="1") {
									    		$base = $product->value*(1-($discount->amount/100))/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = $product->value*(1-($discount->amount/100))*0.19/1.19; 
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}else{
									    		$base = ($product->value-$discount->amount)/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = ($product->value-$discount->amount)/1.19*0.19;
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}
	    								}
	    							break;
	    							case "email":
	    								if ($_POST['newEmail'] == $discount->res_value) {
				    						if ($discount->porcentual=="1") {
									    		$base = $product->value*(1-($discount->amount/100))/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = $product->value*(1-($discount->amount/100))*0.19/1.19; 
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}else{
									    		$base = ($product->value-$discount->amount)/1.19;
									    		$base= round($base, 0, PHP_ROUND_HALF_UP);
									    		$tax = ($product->value-$discount->amount)/1.19*0.19;
									    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
				    						}
	    								}
	    							break;
	    							default:
	    								// code...
	    							break;
	    						}
	    						
	    					}else{
	    						if ($discount->porcentual=="1") {
						    		$base = $product->value*(1-($discount->amount/100))/1.19;
						    		$base= round($base, 0, PHP_ROUND_HALF_UP);
						    		$tax = $product->value*(1-($discount->amount/100))*0.19/1.19; 
						    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
	    						}else{
						    		$base = ($product->value-$discount->amount)/1.19;
						    		$base= round($base, 0, PHP_ROUND_HALF_UP);
						    		$tax = ($product->value-$discount->amount)/1.19*0.19;
						    		$tax= round($tax, 0, PHP_ROUND_HALF_DOWN);
	    						}
	    					}
		    				if ($discount->burnable=='1') {
		    					$discount->active=0;
		    					$discount->save();
		    				}
	    				}
    				}
    			}else{
    				$discCode="";
    			}
    			if ($base>2000) {
					$docName= $product->name;
					$amount = $base + $tax;
					$hash = $document->hash;
					$bill = new ludcis\Bill();
					$bill->document_id = $document->id;
					$bill->name = $_POST['newBuyer'];
					$bill->id_type = $_POST['newIdType'];
					$bill->id_number = $_POST['newId'];
					$bill->email = $_POST['newEmail'];
					$bill->code = $discCode;
					$bill->save();
					$document->disc_code = $discCode;
					$document->save();

		    		return view('layouts.epayco_send',['post'=>$_POST,'base'=>$base,'tax'=>$tax,'docName'=>$docName,'amount'=>$amount,'hash'=>$hash]); 
    				
    			}else{
    			    $document->payment_state = 1;
					$document->disc_code = $discCode;
    			    $document->save();
           			return view($document->product->view,['code'=>$document->hash]);
    			}
    		}
    	}else{
    		return redirect('/');
    	}
    }
    public function store(){
    	$keyMap = ['AM'=>'American Express','BA'=>'Baloto','CR'=>'Credencial','DC'=>'Diners Club','EF'=>'Efecty','GA'=>'Gana','PR'=>'Punto Red','RS'=>'Red Servi','MC'=>'Mastercard','PSE'=>'PSE','SP'=>'SafetyPay','VS'=>'Visa'];
		$p_cust_id_cliente = '';
		$p_key             = '';

		$x_ref_payco      = $_REQUEST['x_ref_payco'];
		$x_transaction_id = $_REQUEST['x_transaction_id'];
		$x_amount         = $_REQUEST['x_amount'];
		$x_currency_code  = $_REQUEST['x_currency_code'];
		$x_signature      = $_REQUEST['x_signature'];



		$signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code);

		$x_response     = $_REQUEST['x_response'];
		$x_motivo       = $_REQUEST['x_response_reason_text'];
		$x_autorizacion = $_REQUEST['x_approval_code'];
		$extra1 		= $_REQUEST['x_extra1'];
		$x_franchise    = $_REQUEST['x_franchise'];
		$x_tax    = $_REQUEST['x_tax'];

		//Validamos la firma
		if ($x_signature == $signature) {
		    /*Si la firma esta bien podemos verificar los estado de la transacción*/
		    $x_cod_response = $_REQUEST['x_cod_response'];
		    switch ((int) $x_cod_response) {
		        case 1:
					$document = ludcis\Document::where('hash',$extra1)->first();
					$estadoTx = "Transacción aprobada";
					if ($document->payment_state =='0') {
						$document->payment_state =1;
						$document->save();
						$payment = new ludcis\Payment();
						$payment->document_id = $document->id;
						$payment->transaction_id = $x_transaction_id;
						$payment->payu_code = $x_autorizacion;
						$payment->method_type = $x_franchise;
						if (isset($keyMap[$x_franchise])) {
							$payment->method = $keyMap[$x_franchise];
						}else{
							$payment->method = 'PayPal';
						}
						$payment->total = $x_amount;
						$payment->tax = $x_tax;
						$payment->base = ($x_amount-$x_tax);
						$payment->save();
						$bill = ludcis\Bill::where('document_id',$document->id)->first();
						$bill->document_id = $document->id;
						$bill->total = $x_amount;
						$bill->tax = $x_tax;
						$bill->base = ($x_amount-$x_tax);
						$lastBill = ludcis\Bill::whereNotNull('number')->orderBy('number','desc')->first();
						if (is_object($lastBill)) {
							$resolution = $lastBill->resolution;
							if ($resolution->end_number==$lastBill->number) {
								$resolution = ludcis\Resolution::find($resolution->id+1);
							}
							$bill->number = $lastBill->number+1;
						}else{
							$resolution = ludcis\Resolution::where('start_number','1001')->first();
							$bill->number = 1001;
						}
						$bill->resolution_id = $resolution->id;
						$bill->save();
						$facturaPDF = ControladorDocumentos::pdfFactura($bill->id);
					}
					return "ok";
		            break;
		        case 2:
		            # code transacción rechazada
		            //echo "transacción rechazada";
		            break;
		        case 3:
		            # code transacción pendiente
		            //echo "transacción pendiente";
		            break;
		        case 4:
		            # code transacción fallida
		            //echo "transacción fallida";
		            break;
			return "ok";

		    }
		} else {
		    die("Firma no valida");
		}

    }
    public function goBack(){

    	$keyMap = ['AM'=>'American Express','BA'=>'Baloto','CR'=>'Credencial','DC'=>'Diners Club','EF'=>'Efecty','GA'=>'Gana','PR'=>'Punto Red','RS'=>'Red Servi','MC'=>'Mastercard','PSE'=>'PSE','SP'=>'SafetyPay','VS'=>'Visa'];
		$p_cust_id_cliente = '';
		$p_key             = '';

		$x_ref_payco      = $_REQUEST['x_ref_payco'];
		$x_transaction_id = $_REQUEST['x_transaction_id'];
		$x_amount         = $_REQUEST['x_amount'];
		$x_currency_code  = $_REQUEST['x_currency_code'];
		$x_signature      = $_REQUEST['x_signature'];



		$signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code);

		$x_response     = $_REQUEST['x_response'];
		$x_motivo       = $_REQUEST['x_response_reason_text'];
		$x_autorizacion = $_REQUEST['x_approval_code'];
		$extra1 		= $_REQUEST['x_extra1'];
		$x_franchise    = $_REQUEST['x_franchise'];
		$x_tax    = $_REQUEST['x_tax'];

		//Validamos la firma
		if ($x_signature == $signature) {
		    /*Si la firma esta bien podemos verificar los estado de la transacción*/
		    $x_cod_response = $_REQUEST['x_cod_response'];
		    switch ((int) $x_cod_response) {
		        case 1:
					$document = ludcis\Document::where('hash',$extra1)->first();
					$estadoTx = "Transacción aprobada";
					if (is_object($document)) {
						if ($document->payment_state =='0') {
							$document->payment_state =1;
							$document->save();
							$payment = new ludcis\Payment();
							$payment->document_id = $document->id;
							$payment->transaction_id = $x_transaction_id;
							$payment->payu_code = $x_autorizacion;
							$payment->method_type = $x_franchise;
							if (isset($keyMap[$x_franchise])) {
								$payment->method = $keyMap[$x_franchise];
							}else{
								$payment->method = 'PayPal';
							}
							$payment->total = $x_amount;
							$payment->tax = $x_tax;
							$payment->base = ($x_amount-$x_tax);
							$payment->save();
							$bill = ludcis\Bill::where('document_id',$document->id)->first();
							$bill->document_id = $document->id;
							$bill->total = $x_amount;
							$bill->tax = $x_tax;
							$bill->base = ($x_amount-$x_tax);
							$lastBill = ludcis\Bill::whereNotNull('number')->orderBy('number','desc')->first();
							if (is_object($lastBill)) {
								$resolution = $lastBill->resolution;
								if ($resolution->end_number==$lastBill->number) {
									$resolution = ludcis\Resolution::find($resolution->id+1);
								}
								$bill->number = $lastBill->number+1;
							}else{
								$resolution = ludcis\Resolution::where('start_number','1001')->first();
								$bill->number = 1001;
							}
							$bill->resolution_id = $resolution->id;
							$bill->save();
							$facturaPDF = ControladorDocumentos::pdfFactura($bill->id);
						}
						return redirect('documentos/'.$document->hash);
					}
          			return view('layouts.not_found');
		            break;
		        case 2:
					$estadoTx = "Transacción rechazada";
					return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$x_ref_payco]);
		            break;
		        case 3:
					$estadoTx = "Transacción pendiente";
					return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$x_ref_payco]);
		            break;
		        case 4:
					$estadoTx = "Transacción fallida";
					return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$x_ref_payco]);
		            break;
		        default:
					$estadoTx=$x_motivo;
					return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$x_ref_payco]);
		        	break;
		    }
		} else {
			return view('layouts.sign_error',['referencia'=>$x_ref_payco]);
		}
	}

}
