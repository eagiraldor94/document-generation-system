<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use ludcis;

use Mail;

use QrCode;

use Storage;

use Exception;

use Carbon\Carbon;

class ControladorGeneral extends Controller
{
    public static function obtenerIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        $ips = explode(",", $ip); //En el servidor llegan 2 ips por cloudflare
        return $ips[0];
    }  

    protected function generarRegistro($product,$code){
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        // Retirada para probar desde localhost
        // $ip = $this->obtenerIp();
        $ip = "181.141.228.208";
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        $ch = curl_init("http://ipinfo.io/".$ip);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //Ocurrio un error?.
        if(curl_errno($ch)){
            $ch2 = curl_init("http://api.ipstack.com/".$ip."?access_key=c260a5b912df34f845703139d76170c8");
            curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            $response2 = curl_exec($ch2);
            //Ocurrio un error?.
            if(curl_errno($ch2)){
            }else{
                $details = json_decode($response2);
                $country = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                }, $details->country_code);
                $city = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                }, $details->city);
            }
              // throw new Exception(curl_error($ch));
        }else{
            $details = json_decode($response);
            $country = $details->country;
            $city = $details->city;
        }
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        if (!isset($country)) {
          header("Location: /error_conexion");
          exit();
        }
        $documento->country = $country;
        $documento->city = $city;
        if ($documento->save()) {
          return $documento;
        }
        
    }  

    public static function correo($name,$mail,$document,$link,$fecha,$page,$qr,$link2){
      $to_name = $name;
      $to_email = $mail;
      $data = array('name'=>$name, "document" => $document, "fecha" => $fecha, "page" => $page, "qr" => $qr, "link2" => $link2);
      try {
          Mail::send('emails.document', $data, function($message) use ($to_name, $to_email, $document, $link,$qr,$link2) {
              $message->to($to_email, $to_name)
                      ->subject('Envio de '.$document);
              $message->from('documentos@ludcis.com','Servicio de documentos LUDCIS');
              $message->attach($link, [
                    'as' => mb_strtoupper($document).'.pdf',
                    'mime' => 'application/pdf',
                ]);
              $message->attach($link2, [
                    'as' => 'MANUAL DE USO.pdf',
                    'mime' => 'application/pdf',
                ]);
          });      
          return 'ok';
      } catch (Exception $ex) {
          return $ex;
        }

    }

    public static function correoFactura($name,$mail,$link,$fecha,$hash,$qr){
      $to_name = $name;
      $to_email = $mail;
      $document = "Factura";
      $data = array('name'=>$name, "document" => $document, "fecha" => $fecha, "hash" => $hash, "qr" => $qr);
      try {
          Mail::send('emails.bill', $data, function($message) use ($to_name, $to_email, $document, $link, $hash,$qr) {
              $message->to($to_email, $to_name)
                      ->subject('Envio de '.$document);
              $message->from('documentos@ludcis.com','Servicio de documentos LUDCIS');
              $message->attach($link, [
                    'as' => $document.'.pdf',
                    'mime' => 'application/pdf',
                ]);
          });      
          return 'ok';
      } catch (Exception $ex) {
          return $ex;
        }

    }

    public static function correoPayU($post){
      $to_name = 'Ludcis';
      $to_email = 'ludcis.sas@gmail.com';
      $document = 'prueba';
      $data = array('name'=>'Ludcis','post'=>$post,'document'=>$document);
      try {
          Mail::send('emails.payu', $data, function($message) use ($to_name, $to_email, $post) {
              $message->to($to_email, $to_name)
                      ->subject('Datos payu');
              $message->from('documentos@ludcis.com','Servicio de documentos LUDCIS');
          });      
          return 'ok';
      } catch (Exception $ex) {
          return $ex;
        }

    }

    public function checkDocumento($hash){
        $document = ludcis\Document::where('hash',$hash)->first();
        if ($document != "" && $document != null) {
          if ($document->payment_state=='1'&& $document->document_state=='0') {
            return view($document->product->view,['code'=>$hash]);
          }else{
            return view('layouts.not_paid',['code'=>$hash]);
          }
        }else{
          return view('layouts.not_found');
        }
  }

    public function vistaPrueba(){
        $code = 'TEST000';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
          // $bill = ludcis\Bill::find(1);
          // $hoy = Carbon::now();
          // $qr = QrCode::generate('hola');
          // $envio = ControladorGeneral::correoFactura(ucfirst($bill->name),$bill->email,$bill->pdf,$hoy->format('d/m/Y'),$bill->document->hash,$qr);
        }
  }
    public function vistaPagare(){
        $code = 'DCP001';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaTransito(){
        $code = 'DPT007';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaCobro(){
        $code = 'DCP008';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaConfidencialidad(){
        $code = 'DCC002';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaTeletrabajo(){
        $code = 'DTT014';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaOtrosiTeletrabajo(){
        $code = 'OTT015';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaTrabajo(){
        $code = 'DCT003';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaServicios(){
        $code = 'DCS004';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaDomestico(){
        $code = 'DCD005';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaArrendamiento(){
        $code = 'DCA006';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
  }
    public function vistaCesion(){
        $code = 'DCC009';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
      }
    public function vistaPoderNatural(){
        $code = 'DPN010';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
      }
    public function vistaPoderCEO(){
        $code = 'DPC011';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
      }
    public function vistaComodato(){
        $code = 'DCC012';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
      }
    public function vistaCompraventa(){
        $code = 'DCC013';
        $product = ludcis\Product::where('code',$code)->first();
        $document = $this->generarRegistro($product,$code);
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$document]);
        }else{
          return view($product->view,['code'=>$document->hash]);
        }
      }













    
  // public static function correoPrueba($post){
  //   $to_name = 'Prueba ludcis';
  //   $to_email = 'ludcis.sas@gmail.com';
  //   $data = array('name'=>"Documentos", "body" => "Este es un correo de prueba de ludcis",'post'=>$post);
        
  //   Mail::send('emails.test', $data, function($message) use ($to_name, $to_email) {
  //       $message->to($to_email, $to_name)
  //               ->subject('Prueba de envio de mail ludcis');
  //       $message->from('documentos@ludcis.com','Servicio de documentos LUDCIS');
  //   });
  // }
  // public function pruebaIp(){
  //       $ip = '181.141.228.208';
  //       $code = 'DCP001';
  //       setlocale(LC_TIME, 'es_ES');
  //       date_default_timezone_set('America/Bogota');
  //       $date = date('Y-m-d-h-i-s');
  //       $hash = $date.'-'.$code.'-'.$ip;
  //       mb_internal_encoding('UTF-8');
  //       //Initiate cURL
  //       // $ch = curl_init("http://ipinfo.io/".$ip);
  //       $ch = curl_init("http://suputamadre.melasura/".$ip);

  //       //Tell cURL that it should only spend 10 seconds
  //       //trying to connect to the URL in question.
  //       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);

  //       //A given cURL operation should only take
  //       //30 seconds max.
  //       curl_setopt($ch, CURLOPT_TIMEOUT, 30);

  //       //Tell cURL to return the response output as a string.
  //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //       //Execute the request.
  //       $response = curl_exec($ch);

  //       //Did an error occur? If so, dump it out.
  //       if(curl_errno($ch)){
  //         $ch2 = curl_init("http://api.ipstack.com/".$ip."?access_key=c260a5b912df34f845703139d76170c8");
  //         $response2 = curl_exec($ch2);
  //         $response2 = json_decode($response2);
  //         var_dump($response2);
  //         echo"Medell\u00edn <br>";
  //         $str= "Medell\u00edn";
  //         $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
  //             return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
  //         }, $str);
  //         echo $str;
  //           // throw new Exception(curl_error($ch));
  //       }else{
  //         $response = json_decode($response);
  //         var_dump($response);
  //       }

  // }
}
