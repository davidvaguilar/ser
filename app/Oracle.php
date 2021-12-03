<?php

namespace App;

class Oracle{

    var $sid, $connection;
    var $query;
    var $host;
    var $total_record,$rec_position;
    var $total_fields, $field_name;
    var $vlsUsuario; // nombre de Usuario
    var $vlsClave; // Clave de Usuario


    private $_oLinkId; //objeto resource que indicara si se ha conectado
    private $_sServidor; //servidor
    private $_sNombreBD; // nombre base de datos
    private $_sUsuario; // nombre de Usuario
    public static $sMensaje; // mensajes
    private static $_oSelf = null; //Almacenara un objeto de tipo Conexion

    /*This function connect to the database . This function is called
      whenever object is created.
    */
    public function __construct() {

        $this->_sServidor = config('app.DB_HOST'); //Nombre de servidor de base de datos
        $this->_sNombreBD = config('app.DB_DATABASE');

        $this->_sPuerto =  '1521'; // puerto de comunicacion
    }

    /**
     * Este es el pseudo constructor singleton
     * Comprueba si la variable privada $_oSelf tiene un objeto
     * de esta misma clase, si no lo tiene lo crea y lo guarda
     * @static
     * @return resource
     */

    function getInstancia($vlsUsuario, $vlsPassword) {
        
        if (!self::$_oSelf instanceof self) {
            $instancia = new self(); //new self ejecuta __construct()
            self::$_oSelf = $instancia;
            if (!is_resource($instancia->conectar($vlsUsuario, $vlsPassword))) {
                self::$_oSelf = null;
            }
        }
        $conex = self::$_oSelf;
        return $conex->_oLinkId; //Se devuelve el link a la conexion
    }

    /**
     * Realiza la conexion
     * @return link para exito, o false
     */

    private function conectar($vlsUsuario,$vlsPassword)
    {

        $this->_oLinkId = null;
        $intentos = 0;
        while (!is_resource($this->_oLinkId) && $intentos < 1) {
            $intentos++;
        
            $this->_oLinkId = oci_connect($vlsUsuario, $vlsPassword, "(DESCRIPTION = (LOAD_BALANCE = yes)
                            (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP) (HOST = {$this->_sServidor}) (PORT = {$this->_sPuerto}) ) )
                            (CONNECT_DATA = (FAILOVER_MODE = (TYPE = select) (METHOD = basic) (RETRIES = 180) (DELAY = 5) )
                            (SERVICE_NAME = {$this->_sNombreBD}) ) )",'AL32UTF8');
            if (!$this->_oLinkId) {
                $e = oci_error();
                if ($e['code']==1017) { $this->_oLinkId = $e['code'];}
                break;
            }
        }
        return $this->_oLinkId;
    }

    /**
     * Este método verifica si había una conexión abierta anteriormenete. Si había la cierra.
     *
     * @static
     * @return boolean true si existía la conexión, false si no existía.
     */

     public static function desconectar() {

        $conexion_activa = false;
        if (self::$_oSelf instanceof self) {
            $conexion_activa = true;
            $instancia = self::$_oSelf;
            oci_close($instancia->_oLinkId); //cierro la conexion activa
            self::$_oSelf = null; //destruyo el objeto
        }
        return $conexion_activa;
    }

    /*This function query at database */
    function db_query($query_str="")
    {
        $this->sql=$query_str;
        $this->rec_position=0;
        // if($query_str==""){
        //   $query_str=$this->query_stmt;
        //}
        $this->query = @ociparse($this->connection, $query_str);
        ociexecute($this->query)or die($this->get_error_msg($this->query ,"Query Error : ".$query_str));
    }

    /*This function query at database which returns TRUE if SUCCESSFUL and FALSE if UNSUCCESSFUL */
    function db_query_return($query_str="",$db=""){
        if($query_str==""){
            $query_str=$this->query_stmt;
        }
        $this->query = ociparse($this->connection, $query_str);
        if($db=="Default") {
            return ociexecute($this->query,OCI_DEFAULT);
        } else {
            return ociexecute($this->query);
        }
    }

    function free(){
        ocifreestatement($this->query);
        ocilogoff($this->connection);
        #unset($this);
    }
}
