<?php
//error_reporting("E_ALL");
#ini_set('display_errors',1);
//ini_set('error_reporting', 'E_ALL && ~E_WARNING && ~ E_NOTICE');
//ini_set('error_reporting', 'E_ALL && ~E_WARNING && ~ E_NOTICE');
/*  Clase de Funciones De Ejecucion en la Base de Datos */
include("config_bd.php");

class dbMysql
{
    #Declaracion de Variables
    private $Servidor,$User,$Pass,$Bd,$Valores;
    protected $Link;
    public $Merror,$Nerror,$MsgError,$Estatus="Desconectado",$Error=0,$Sql,$Affected,$LastID;

    #Setteo de Variables
    private function setServidor($server)
    {
        $this->Servidor=$server;
    }
    private function setUser($user)
    {
        $this->User=$user;
    }
    private function setPass($pass)
    {
        $this->Pass=$pass;
    }
    private function setBd($bd)
    {
        $this->Bd=$bd;
    }
    public function setSql($sql)
    {
        $this->Sql=$sql;
    }
    private function setError($Error=null)
    {
        $this->Error=1;
        $this->Merror=@$this->Link->error;
        $this->Nerror=@$this->Link->errno;
        //$this->MsgError="<center>".$Error." Error Nro: ".$this->Nerror." ".$this->Merror."</center>";
        //$this->MsgError="Error Nro: ".$this->Nerror." ".$this->Merror;
        //if (!empty($this->Nerror))
        $this->MsgError="<center>".@$this->dbErrorMySql()."</center>";
    }

    #Getteo de Variables
    private function getServidor()
    {
        return $this->Servidor;
    }
    private function getUser()
    {
        return $this->User;
    }
    private function getPass()
    {
        return $this->Pass;
    }
    private function getBd()
    {
        return $this->Bd;
    }
    public function getSql()
    {
        return $this->Sql;
    }
    public function dbEscape($cadena)
    {
        return  $this->Link->real_escape_string($cadena);
    }

    protected function getLink()
    {
        return $this->Link;
    }

    public function Moerror()
    {
        if ($this->Error) {
            $this->MsgError="<center>Error Nro [".$this->Nerror."] ".$this->Merror;
            return 1;
        }
    }

    public function __construct($server = null, $user = null, $pass = null, $bd = null)
    {
        if (empty($server)) {
            $this->setServidor($GLOBALS['ServName']);
        } else {
            $this->setServidor($server);
        }
        if (empty($user)) {
            $this->setUser($GLOBALS['UserName']);
        } else {
            $this->setUser($user);
        }
        if (empty($pass)) {
            $this->setPass($GLOBALS['PassWord']);
        } else {
            $this->setPass($pass);
        }
        if (empty($bd)) {
            $this->setBd($GLOBALS['DataBase']);
        } else {
            $this->setBd($bd);
        }
    }

    /*
    public function dbMysql($server=null, $user=null, $pass=null, $bd=null)
    {
        if (empty($server)) {
            $this->setServidor($GLOBALS['ServName']);
        } else {
            $this->setServidor($server);
        }
        if (empty($user)) {
            $this->setUser($GLOBALS['UserName']);
        } else {
            $this->setUser($user);
        }
        if (empty($pass)) {
            $this->setPass($GLOBALS['PassWord']);
        } else {
            $this->setPass($pass);
        }
        if (empty($bd)) {
            $this->setBd($GLOBALS['DataBase']);
        } else {
            $this->setBd($bd);
        }
    }
    */

    public function dbConectar()
    {
        @$this->Link = new mysqli($this->getServidor(), $this->getUser(), $this->getPass(), $this->getBd());
        if (!$this->Link->connect_errno) {
            $this->Estatus="Conectado";
        } else {
            $this->Error=1;
            $this->MsgError="Sin Conexion a la Base de Datos, Acceso Denegado Para Usuario: ".$this->getUser();
            $this->Estatus="Desconectado";
        }
        return;
    }
    public function dbDesconectar()
    {
        $this->Link->close();
    }
    public function dbRows($consulta)
    {
        return $consulta->num_rows;
    }
    public function AutoCommit($estado=true)
    {
        $this->Link->autocommit($estado);
    }
    public function Commit()
    {
        return $this->Link->commit();
    }
    public function RollBack()
    {
        $this->Link->rollback();
    }
    private function dbQuery()
    {
        try {
            $consulta=$this->Link->query($this->getSql());
            if ($consulta==false) {
                throw new Exception("Sentencia SQL Invalida");
            } else {
                return $consulta;
            }
        } catch (Exception $e) {
            $this->Error=1;
            $this->setError($e->getMessage());
            return false;
        }
        //return $this->Link->query($this->getSql());
    }

    public function dbErrorMySql()
    {
        switch ($this->Nerror) {
                case "2002":    return " SQL Nro [".$this->Nerror."] Servidor \"".$this->getServidor()."\" Desconocido<br />";  break;
                case "1049":    return " SQL Nro [".$this->Nerror."] Base de Datos \"".$this->getBd()."\" No Existe<br />";  break;
                case "1045":    return " SQL Nro [".$this->Nerror."] Acceso Denegado Al Servidor de Base de Datos \"".$this->getServidor()."\" Con Usuario \"".$this->getUser()."\"<br />";  break;
                case "1062":    return " Disculpe Este Registro Ya Existe en Nuestra Base de Datos";   break;
                case "1451":    return " Disculpe Con La Finalidad de Mantener la Integridad de la Base de Datos, No Se Pueden Borrar Registros Que Contengan Informacion Asociada";   break;
                default: return " Error SQL, Nro: ".$this->Nerror." ".$this->Merror;   break;
        }
    }
    public function dbConsultar($sql, $valores=null)
    {
        if ($this->Estatus=="Desconectado") {
            $this->Error=1;
            $this->MsgError="Disculpe No Estas Conectado a la Base de Datos";
            return;
        }
        $this->setSql($this->dbPrepare($sql, $valores));
        $consulta=$this->dbQuery();
        if (is_object($consulta)) {
            return $consulta;
        } else {
            $this->setError();
        }
    }
    public function dbFunction($sql, $valores=null)
    {
        $this->setSql($this->dbPrepare($sql, $valores));
        $consulta=$this->dbQuery();
        //$consulta->errorInfo();
        if (!$consulta) {
            $this->setError("Procedimiento No Ejecutado ");
            return false;
        }
        return true;
    }
    public function dbFc($sql, $valores=null)
    {
        $this->setSql($this->dbPrepare($sql, $valores));
        $consulta=$this->dbQuery();
        //$consulta->errorInfo();
        if (!$consulta) {
            $this->setError("Procedimiento No Ejecutado ");
            return false;
        }
        return $consulta;
    }
    public function dbInsertar($sql, $valores)
    {
        $this->setSql($this->dbPrepare($sql, $valores));
        $agregar=$this->dbQuery();
        if ($agregar) {
            return "Registro Agregado Correctamente";
        } else {
            $this->setError();
        }
    }
    public function dbActualizar($sql, $valores)
    {
        $this->setSql($this->dbPrepare($sql, $valores));
        $actualizar=$this->dbQuery();

        list($filas, $changed, $warnings) = sscanf($this->Link->info, "Rows matched: %d Changed: %d Warnings: %d");
        if ($actualizar) {
            $this->Affected=$this->Link->affected_rows;
            if ($this->Link->affected_rows==1) {
                return "Registro Actualizado Correctamente";
            }
            if ($this->Link->affected_rows>1) {
                return "Registros Actualizados Correctamente";
            }
            if ($this->Link->affected_rows==0) {
                return "Registro No Modificado";
            }
        }
    }
    public function dbBorrar($sql, $valores)
    {
        $this->setSql($this->dbPrepare($sql, $valores));
        $borrar=$this->dbQuery();
        if ($borrar) {
            $this->Affected=$this->Link->affected_rows;
            if ($this->Link->affected_rows==1) {
                return "Registro Eliminado Correctamente";
            }
            if ($this->Link->affected_rows>1) {
                return "Registros Eliminados Correctamente";
            }
            if ($this->Link->affected_rows==0) {
                return "Registro No Eliminado";
            }
        }
    }

    public function dbLastID($tabla, $campo)
    {
        $consultaLastID=$this->dbConsultar("select max($campo) from $tabla");
        if ($consultaLastID->num_rows>0) {
            $LastID=$consultaLastID->fetch_row();
        } else {
            $LastID[0]=0;
        }
        return $LastID[0];
    }

    public function dbArray($consulta)
    {
        return $this->Link->fetch_array(MYSQLI_NUM);
    }

    public function dbComboSimple($sql, $params, $nombre, $codigo, $nombres, $select)
    {
        $consulta=$this->dbConsultar($sql, $params);
        if ($this->Error) {
            echo $this->Moerror();
        }
        $comboini="<select name='$nombre' id='$nombre'>\n".
               "<option value='0'>Seleccione</option>\n";
        while ($fila=$consulta->fetch_row()) {
            unset($gen);
            for ($i=0;$i<count($nombres);$i++) {
                $gen.=$fila[$nombres[$i]]." ";
            }
            if ($select==$fila[$codigo]) {
                $sel="selected='selected'";
            } else {
                unset($sel);
            }
            $combooption.="<option $sel value='$fila[$codigo]' title='$fila[$codigo]'>".trim($gen)."</option>\n";
        }
        $combofin.="</select>";
        return $comboini.$combooption.$combofin;
    }

    public function dbComboCompuesto($sql, $params, $nombre, $codigo, $nombres, $select, $evento)
    {
        $consulta=$this->dbConsultar($sql, $params);
        if ($this->Error) {
            echo $this->Moerror();
        }
        $comboini="<select name='$nombre' id='$nombre' $evento>\n".
               "<option value='0'>Seleccione</option>\n";
        while ($fila=$consulta->fetch_row()) {
            unset($gen);
            for ($i=0;$i<count($nombres);$i++) {
                $gen.=$fila[$nombres[$i]]." ";
            }
            if ($select==$fila[$codigo]) {
                $sel="selected='selected'";
            } else {
                unset($sel);
            }
            $combooption.="<option $sel value='$fila[$codigo]' title='$fila[$codigo]'>".trim($gen)."</option>\n";
        }
        $combofin.="</select>";
        return $comboini.$combooption.$combofin;
    }

    private function dbPrepare($sql, $valores)
    {
        for ($i=0; $i<sizeof($valores); $i++) {
            if (is_bool($valores[$i])) {
                $valores[$i] = $valores[$i]? 1:0;
            } elseif (is_double($valores[$i])) {
                $valores[$i] = str_replace(',', '.', $valores[$i]);
            } elseif (is_numeric($valores[$i])) {
                $valores[$i] = $this->dbEscape($valores[$i]);
            } elseif (is_null($valores[$i])) {
                $valores[$i] = "NULL";
            } else {
                $valores[$i] = str_replace(array("'\'", "\''"), array("'", "'"), "'".$this->dbEscape($valores[$i])."'");
            }
        }
        $this->Valores = $valores;
        $q = preg_replace_callback("/(\?)/i", array($this, "replaceParams"), $sql);
        return $q;
    }
    private function replaceParams($coincidencias)      //Para Sustituir en la Cadena a Insertar en la Consulta
    {
        $b=current($this->Valores);
        next($this->Valores);
        return $b;
    }
    public function GenAlfa($longitud)
    {
        $Chars=array(array(0,1,2,3,4,5,6,7,8,9),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'));
        for ($i=0;$i<$longitud;$i++) {
            $tipo=rand(0, 1);
            switch ($tipo) {
                case 0: $max=9; break;
                case 1: $max=25;    break;
            }
            $id=rand(0, $max);
            $cadena.=$Chars[$tipo][$id];
        }
        return $cadena;
    }

    public function Edad($fecha)
    {
        $anio=(int) substr($fecha, 0, 4);
        $mes =(int) substr($fecha, 5, 2);
        $dia =(int) substr($fecha, 8, 2);
        //echo $mes.$dia;
        $anioa=(int) date("Y");
        $aniodif =  ($anioa-$anio);
        $mesdif = date("m")-$mes;
        $diadif = date("d")-$dia;
        if ($diadif < 0 || $mesdif < 0) {
            $aniodif--;
        }
        return $aniodif;
    }
}

/*
try{  throw new Exception("Ocurrio un Error "); }
catch (ErrorException $e){
        // este bloque no se ejecuta, no coincide el tipo de excepci�n
   echo 'ErrorException' . $e->getMessage();
}
catch (Exception $e) {    echo 'Exception: ' . $e->getMessage();    }
*/;
