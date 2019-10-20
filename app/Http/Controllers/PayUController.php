<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use ludcis;

class PayUController extends Controller
{
    //
    public function redirigirPago(){
    	if (isset($_POST['newPayment'])) {
    		$document = ludcis\Document::where('hash',$_POST['newCode'])->first();
    		if (is_object($document) && filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)) {

	    		$product = $document->product;
				$apiKey = "4Vj8eK4rloUd272L48hsrarnUA";
	    		$base = $product->value/1.19;
	    		$base= round($base, 0, PHP_ROUND_HALF_UP);
	    		$tax = $product->value*0.19/1.19; 
	    		$tax= round($tax, 0, PHP_ROUND_HALF_UP);
				$refCode = sha1($document->hash.date('Y-m-d-H-i-s'));
				$merchant = "508029";
				$account = "512321";
				$docName= $product->name;
				$amount = $product->value;
				$hash = $document->hash;
				$sign = $apiKey."~".$merchant."~".$refCode."~".$amount."~COP";
				$sign = hash("SHA256",$sign);
				$bill = new ludcis\Bill();
				$bill->document_id = $document->id;
				$bill->name = $_POST['newBuyer'];
				$bill->id_type = $_POST['newIdType'];
				$bill->id_number = $_POST['newId'];
				$bill->email = $_POST['newEmail'];
				$bill->save();

	    		return view('layouts.payu_send',['post'=>$_POST,'base'=>$base,'tax'=>$tax,'refCode'=>$refCode,'merchant'=>$merchant,'account'=>$account,'docName'=>$docName,'amount'=>$amount,'hash'=>$hash,'sign'=>$sign]); 
    		}
    	}else{
    		return redirect('/');
    	}
    }
    public function store(){

		$ApiKey = "4Vj8eK4rloUd272L48hsrarnUA";
		$merchant_id = $_REQUEST['merchant_id'];
		$reference_sale = $_REQUEST['reference_sale'];
		$value = $_REQUEST['value'];
		$tax = $_REQUEST['tax'];
		if (substr($value,-1,1)=='0') {
			$New_value = number_format($value, 1, '.', '');
		}else{
			$New_value = number_format($value, 2, '.', '');
		}
		$currency = $_REQUEST['currency'];
		$state_pol = $_REQUEST['state_pol'];
		$firma_cadena = "$ApiKey~$merchant_id~$reference_sale~$New_value~$currency~$state_pol";
		$firmacreada = hash("SHA256",$firma_cadena);
		$firma = $_REQUEST['sign'];
		$reference_pol = $_REQUEST['reference_pol'];
		$extra1 = $_REQUEST['extra1'];
		$payment_method_name = $_REQUEST['payment_method_name'];
		$payment_method_type = $_REQUEST['payment_method_type'];
		$email_buyer = $_REQUEST['email_buyer'];
		$transaction_id = $_REQUEST['transaction_id'];

		if (strtoupper($firma) == strtoupper($firmacreada)) {

			if ($_REQUEST['state_pol'] == 4 ) {
				$document = ludcis\Document::where('hash',$extra1)->first();
				$estadoTx = "Transacción aprobada";
				if ($document->payment_state != 1) {
					$document->payment_state =1;
					$document->save();
					$payment = new ludcis\Payment();
					$payment->document_id = $document->id;
					$payment->transaction_id = $transaction_id;
					$payment->payu_code = $reference_pol;
					$payment->method_type = $payment_method_type;
					$payment->method = $payment_method_name;
					$payment->total = $value;
					$payment->tax = $tax;
					$payment->base = ($value-$tax);
					$payment->save();
					$bill = ludcis\Bill::where('document_id',$document->id)->first();
					$bill->document_id = $document->id;
					$bill->total = $value;
					$bill->tax = $tax;
					$bill->base = ($value-$tax);
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
				}
				return "ok";
			}
			return "ok";
		}else{
			ControladorGeneral::correoPayU('error en la firma');
		}

    }
    public function goBack(){

		$ApiKey = "4Vj8eK4rloUd272L48hsrarnUA";
		$merchant_id = $_REQUEST['merchantId'];
		$referenceCode = $_REQUEST['referenceCode'];
		$TX_VALUE = $_REQUEST['TX_VALUE'];
		$TX_TAX = $_REQUEST['TX_TAX'];
		$New_value = number_format($TX_VALUE, 1, '.', '');
		$currency = $_REQUEST['currency'];
		$transactionState = $_REQUEST['transactionState'];
		$firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
		$firmacreada = md5($firma_cadena);
		$firma = $_REQUEST['signature'];
		$reference_pol = $_REQUEST['reference_pol'];
		$extra1 = $_REQUEST['extra1'];
		$extra2 = $_REQUEST['extra2'];
		$lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
		$lapPaymentMethodType = $_REQUEST['lapPaymentMethodType'];
		$buyerEmail = $_REQUEST['buyerEmail'];
		$transactionId = $_REQUEST['transactionId'];

		if (strtoupper($firma) == strtoupper($firmacreada)) {

			if ($_REQUEST['transactionState'] == 4 ) {
				$estadoTx = "Transacción aprobada";
				$document = ludcis\Document::where('hash',$extra1)->first();
				if (is_object($document)) {
					if ($document->payment_state=='0') {
						$document->payment_state =1;
						$document->save();
						$payment = new ludcis\Payment();
						$payment->document_id = $document->id;
						$payment->transaction_id = $transactionId;
						$payment->payu_code = $reference_pol;
						$payment->method_type = $lapPaymentMethodType;
						$payment->method = $lapPaymentMethod;
						$payment->total = $TX_VALUE;
						$payment->tax = $TX_TAX;
						$payment->base = ($TX_VALUE-$TX_TAX);
						$payment->save();
						$bill = $document->bill;
						$bill->document_id = $document->id;
						$bill->number = 0;
						$bill->total = $TX_VALUE;
						$bill->tax = $TX_TAX;
						$bill->base = ($TX_VALUE-$TX_TAX);
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
						
					}
					
				}
				return redirect('documentos/'.$document->hash);
			}

			else if ($_REQUEST['transactionState'] == 6 ) {
				$estadoTx = "Transacción rechazada";
				return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$reference_pol]);
			}

			else if ($_REQUEST['transactionState'] == 104 ) {
				$estadoTx = "Error";
				return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$reference_pol]);
			}

			else if ($_REQUEST['transactionState'] == 7 ) {
				$estadoTx = "Transacción pendiente";
				return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$reference_pol]);
			}

			else {
				$estadoTx=$_REQUEST['mensaje'];
				return view('layouts.transaction_error',['error'=>$estadoTx,'referencia'=>$reference_pol]);
			}

		}else{
			return view('layouts.sign_error',['referencia'=>$reference_pol]);
		}
    }
}
