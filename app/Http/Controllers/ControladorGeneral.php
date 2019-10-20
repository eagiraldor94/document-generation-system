<?php

namespace ludcis\Http\Controllers;

use Illuminate\Http\Request;

use ludcis;

use Mail;

class ControladorGeneral extends Controller
{
    public function obtenerIp(){
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
        return $ip;
    }  
    public static function correo($name,$mail,$document,$link,$fecha){
      $to_name = $name;
      $to_email = $mail;
      $data = array('name'=>$name, "document" => $document, "fecha" => $fecha);
      try {
          Mail::send('emails.document', $data, function($message) use ($to_name, $to_email, $document, $link) {
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
            return view('layouts.not_paid');
          }
        }else{
          return view('layouts.not_found');
        }
  }
    public function vistaPrueba(){
        $code = 'TEST000';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        if ($product->value > 0) {
          return view('layouts.pay_form',['product'=>$product,'document'=>$documento]);
        }else{
          return view($product->view,['code'=>$hash]);
        }
  }
    public function vistaPagare(){
        $code = 'DCP001';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        return view('layouts.pagare',['code'=>$hash]);
  }
    public function vistaConfidencialidad(){
        $code = 'DCC002';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        return view('layouts.confidentiality',['code'=>$hash]);
  }
    public function vistaTrabajo(){
        $code = 'DCT003';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        return view('layouts.work_contract',['code'=>$hash]);
  }
    public function vistaServicios(){
        $code = 'DCS004';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        return view('layouts.services_contract',['code'=>$hash]);
  }
    public function vistaDomestico(){
        $code = 'DCD005';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        return view('layouts.domestic_contract',['code'=>$hash]);
  }
    public function vistaArrendamiento(){
        $code = 'DCA006';
        $product = ludcis\Product::where('code',$code)->first();
        setlocale(LC_TIME, 'es_ES');
        date_default_timezone_set('America/Bogota');
        $date = date('Y-m-d-h-i-s');
        $ip = $this->obtenerIp();
        $hash = $date.'-'.$code.'-'.$ip;
        $hash = hash('sha256',$hash);
        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
        // Retirada para probar desde localhost
        $details = json_decode(file_get_contents("http://ipinfo.io/181.141.228.208"));
        $country = $details->country;
        $city = $details->city;
        $documento = new ludcis\Document();
        $documento->product_id = $product->id;
        $documento->hash = $hash;
        $documento->ip = $ip;
        $documento->country = $country;
        $documento->city = $city;
        $documento->save();
        return view('layouts.rent_contract',['code'=>$hash]);
  }












    
  // public function correoPrueba(){
  //   $to_name = 'Prueba ludcis';
  //   $to_email = 'ludcis.sas@gmail.com';
  //   $data = array('name'=>"Documentos", "body" => "Este es un correo de prueba de ludcis");
        
  //   Mail::send('correo_prueba', $data, function($message) use ($to_name, $to_email) {
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
  //       $hash = password_hash($hash,PASSWORD_DEFAULT);
        
  //       echo $code.'<br><br>'.$date.'<br><br>'.$ip.'<br><br>'.$hash.'<br><br>';

        // $details = json_decode(file_get_contents("http://ipinfo.io/".$ip));
  //       var_dump($details); // -> "US"

  // }
}
