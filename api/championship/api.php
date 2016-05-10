 <?php  
 /***********************************Definicion De Variables******************************/
 require_once("restconfig.php");  
 class api extends restconfig {  
   const servidor = "31.220.16.175";  
   const usuario_db = "u359976767_admin";  
   const pwd_db = "bargon3";  
   const nombre_db = "u359976767_rest";  
   private $_conn = NULL;  
   private $_metodo;  
   private $_argumentos;  
   public function __construct() {  
     parent::__construct();  
     $this->conectarDB();  
   }  
     /*******************************Conexion a BD**************************************/

   private function conectarDB() {  
     $dsn = 'mysql:dbname=' . self::nombre_db . ';host=' . self::servidor;  
     try {  
       $this->_conn = new PDO($dsn, self::usuario_db, self::pwd_db);  
     } catch (PDOException $e) {  
       echo 'Falló la conexión: ' . $e->getMessage();  
     }  
   }  

   /****************************manejado de errores***********************************/

   private function devolverError($id) {  
     $errores = array(  
       array('estado' => "error", "msg" => "petición no encontrada"),  
       array('estado' => "error", "msg" => "petición no aceptada"),  
       array('estado' => "error", "msg" => "petición sin contenido"),  
       array('estado' => "error", "msg" => "email o password incorrectos"),  
       array('estado' => "error", "msg" => "error borrando usuario"),  
       array('estado' => "error", "msg" => "error actualizando nombre de usuario"),  
       array('estado' => "error", "msg" => "error buscando usuario por email"),  
       array('estado' => "error", "msg" => "error creando usuario"),  
       array('estado' => "error", "msg" => "usuario ya existe")  
     );  
     return $errores[$id];  
   }  

   /*****************************Llamada de Rest Api**********************************/

   public function procesarLLamada() {  
     if (isset($_REQUEST['url'])) {  
       //si por ejemplo pasamos explode('/','////controller///method////args///') el resultado es un array con elem vacios;
       //Array ( [0] => [1] => [2] => [3] => [4] => controller [5] => [6] => [7] => method [8] => [9] => [10] => [11] => args [12] => [13] => [14] => )
       $url = explode('/', trim($_REQUEST['url']));  
       //con array_filter() filtramos elementos de un array pasando función callback, que es opcional.
       //si no le pasamos función callback, los elementos false o vacios del array serán borrados 
       //por lo tanto la entre la anterior función (explode) y esta eliminamos los '/' sobrantes de la URL
       $url = array_filter($url);  
       $this->_metodo = strtolower(array_shift($url));  
       $this->_argumentos = $url;  
       $func = $this->_metodo;  
       if ((int) method_exists($this, $func) > 0) {  
         if (count($this->_argumentos) > 0) {  
           call_user_func_array(array($this, $this->_metodo), $this->_argumentos);  
         } else {//si no lo llamamos sin argumentos, al metodo del controlador  
           call_user_func(array($this, $this->_metodo));  
         }  
       }  
       else  
         $this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);  
     }  
     $this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);  
   } 

   /*************************************Convertir Data a JSon*************************/

   private function convertirJson($data) {  
     return json_encode($data);  
   }  
   

   /*************************************Servicio TOP**********************************/

   public function top() {  
     if ($_SERVER['REQUEST_METHOD'] != "GET") {  
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);  
     }  
     $query = $this->_conn->query("SELECT player FROM `puntuacion` ORDER BY victories DESC ");  
     $filas = $query->fetchAll(PDO::FETCH_ASSOC);  
     $num = count($filas);  
     if ($num > 0) {  
        
       $respuesta['Players :'] = $filas;
       $this->mostrarRespuesta($this->convertirJson($respuesta), 200);  
     }  
     $this->mostrarRespuesta($this->devolverError(2), 204);  
   }  
       
   /************************************Servicio Result***********************************/

   public function result() {  
     if ($_SERVER['REQUEST_METHOD'] != "POST") {  
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);  
     }  

     if (isset( $this->datosPeticion['player'], $this->datosPeticion['victories'], $this->datosPeticion['championship_number'])) { 

       $player = $this->datosPeticion['player'];  
       $victories = $this->datosPeticion['victories'];  
       $championship_number = $this->datosPeticion['championship_number']; 

       if (!$this->existeUsuario($player)) {  

         $query = $this->_conn->prepare("INSERT INTO puntuacion (player,victories,championship_number) VALUES (:player,:victories,:championship_number)");  
         $query->bindValue(":player", $player);  
         $query->bindValue(":victories", $victories);  
         $query->bindValue(":championship_number", sha1($championship_number));  
         $query->execute();  

         if ($query->rowCount() == 1) {  

           $id = $this->_conn->lastInsertId();  
           $respuesta['estado'] = 'correcto';  
           $respuesta['msg'] = 'jugador agregado correctamente';  
           $respuesta['puntuacion']['player'] = $player;  
           $respuesta['puntuacion']['victories'] = $victories;  
           $respuesta['puntuacion']['championship_number'] = $championship_number;  
           $this->mostrarRespuesta($this->convertirJson($respuesta), 200);  

         }  
         else  
           $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);  
       }  
       else  
         $this->mostrarRespuesta($this->convertirJson($this->devolverError(8)), 400);  
     } 
        else {  
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);  
     }  
   }  
 }  

 public function new() {  
     if ($_SERVER['REQUEST_METHOD'] != "POST") {  
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);  
     }  

     if (isset( $this->datosPeticion['player'], $this->datosPeticion['victories'], $this->datosPeticion['championship_number'])) { 

       $player = $this->datosPeticion['player'];  
       $victories = $this->datosPeticion['victories'];  
       $championship_number = $this->datosPeticion['championship_number']; 

       if (!$this->existeUsuario($player)) {  

         $query = $this->_conn->prepare("INSERT INTO puntuacion (player,victories,championship_number) VALUES (:player,:victories,:championship_number)");  
         $query->bindValue(":player", $player);  
         $query->bindValue(":victories", $victories);  
         $query->bindValue(":championship_number", sha1($championship_number));  
         $query->execute();  

         if ($query->rowCount() == 1) {  

           $id = $this->_conn->lastInsertId();  
           $respuesta['estado'] = 'correcto';  
           $respuesta['msg'] = 'jugador agregado correctamente';  
           $respuesta['puntuacion']['player'] = $player;  
           $respuesta['puntuacion']['victories'] = $victories;  
           $respuesta['puntuacion']['championship_number'] = $championship_number;  
           $this->mostrarRespuesta($this->convertirJson($respuesta), 200);  

         }  
         else  
           $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);  
       }  
       else  
         $this->mostrarRespuesta($this->convertirJson($this->devolverError(8)), 400);  
     } 
        else {  
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);  
     }  
   }  
 }  

 $api = new api();  
 $api->procesarLLamada();
 ?>