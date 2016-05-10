<?php  
 class restconfig {  
   public $tipo = "application/json";  
   public $datosPeticion = array();  
   private $_codEstado = 200;  
   public function __construct() {  
     $this->tratarEntrada();  
   }  
   public function mostrarRespuesta($data, $estado) {  
     $this->_codEstado = ($estado) ? $estado : 200;//si no se envía $estado por defecto será 200  
     $this->setCabecera();  
     echo $data;  
     exit;  
   }  
   private function setCabecera() {  
     header("HTTP/1.1 " . $this->_codEstado . " " . $this->getCodEstado());  
     header("Content-Type:" . $this->tipo . ';charset=utf-8');  
   }  
   private function limpiarEntrada($data) {  
     $entrada = array();  
     if (is_array($data)) {  
       foreach ($data as $key => $value) {  
         $entrada[$key] = $this->limpiarEntrada($value);  
       }  
     } else {  
       if (get_magic_quotes_gpc()) {     
         $data = trim(stripslashes($data));  
       }  
       //eliminamos etiquetas html y php  
       $data = strip_tags($data);  
       //Conviertimos todos los caracteres aplicables a entidades HTML  
       $data = htmlentities($data);  
       $entrada = trim($data);  
     }  
     return $entrada;  
   }  
   private function tratarEntrada() {  
     $metodo = $_SERVER['REQUEST_METHOD'];  
     switch ($metodo) {  
       case "GET":  
         $this->datosPeticion = $this->limpiarEntrada($_GET);  
         break;  
       case "POST":  
         $this->datosPeticion = $this->limpiarEntrada($_POST);  
         break;  
       case "DELETE"://"falling though". Se ejecutará el case siguiente  
       case "PUT":   
         parse_str(file_get_contents("php://input"), $this->datosPeticion);  
         $this->datosPeticion = $this->limpiarEntrada($this->datosPeticion);  
         break;  
       default:  
         $this->response('', 404);  
         break;  
     }  
   }  
   private function getCodEstado() {  
     $estado = array(  
       200 => 'OK',  
       201 => 'Created',  
       202 => 'Accepted',  
       204 => 'No Content',  
       301 => 'Moved Permanently',  
       302 => 'Found',  
       303 => 'See Other',  
       304 => 'Not Modified',  
       400 => 'Bad Request',  
       401 => 'Unauthorized',  
       403 => 'Forbidden',  
       404 => 'Not Found',  
       405 => 'Method Not Allowed',  
       500 => 'Internal Server Error');  
     $respuesta = ($estado[$this->_codEstado]) ? $estado[$this->_codEstado] : $estado[500];  
     return $respuesta;  
   }  
 }  
 ?> 