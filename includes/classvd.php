<?php

//Solo Caracteres preg_match('/[^A-Za-zñáéíóúÑÁÉÍÓÚ ]/',$nombre,$x);


class Validar{
    public $error;
    /*
    public function letras($cadena,$lmin,$lmax,$campo){
        $this->error=NULL;
        if (preg_match_all('/[^A-Za-zñáéíóúÑÁÉÍÓÚ ]/',$nombre,$malos)){ $this->error="Campo ".$campo." Contiene Caracter(es) Invalido(s) > [".implode("",$malos[0]). "]"; return 1;   }
        return $this->longitud($cadena,$lmin,$lmax,$campo);        
    }
    public function alfa($cadena,$lmin,$lmax,$campo){
        $this->error=NULL;
        if (preg_match_all('/[^A-Za-zñáéíóúÑÁÉÍÓÚ0-9 ]/',$alfa,$malos)){ $this->error="Campo ".$campo." Contiene Caracter(es) Invalido(s) > [".implode("",$malos[0])."]"; return 1;   }
        return $this->longitud($cadena,$lmin,$lmax,$campo);    
    }
    public function numeros($cadena,$lmin,$lmax,$campo){
        $this->error=NULL;
        if (preg_match_all('/[^0-9]/',$cadena,$malos)){   $this->error="Campo ".$campo." Contiene  Caracter(es) Invalido(s) > [".implode("",$malos[0])."]"; return 1;      }
        return $this->longitud($cadena,$lmin,$lmax,$campo);
    }
    public function montos($cadena,$lmin,$lmax,$campo){
        $this->error=NULL;
        if (preg_match_all('/[^0-9.]/',$cadena,$malos)){  $this->error=$campo." Caracter(es) Invalido(s) > [".implode("",$malos[0])."]"; return 1;     }
        return $this->longitud($cadena,$lmin,$lmax,$campo);
    }
    */
    public function letras($cadena,$lmin,$lmax,$campo){
        $tipo=" Caracteres";
        $this->error=NULL;
        if (preg_match_all("/[^A-Za-záéíóúñÁÉÍÓÚÑ ]/",utf8_decode($cadena),$errores)){    $this->error="Campo ".$campo." Contiene Caracteres Invalidos ".implode("",$errores[0])."";   return 1; }        
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }
                
    }
    public function alfa($cadena,$lmin,$lmax,$campo){
        $tipo=" Caracteres";
        $this->error=NULL;        
        if (!empty($cadena))    
            if (preg_match_all("/[^A-Za-z0-9.,áéíóúñÁÉÍÓÚÑ ]/",utf8_decode($cadena),$errores)){    $this->error="Campo ".$campo." Contiene Caracteres Invalidos ".implode("",$errores[0])."";   return 1; }
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }                
    }
    public function file($cadena,$lmin,$lmax,$campo){
        $tipo=" Caracteres";
        $this->error=NULL;
        if (preg_match_all("/[^A-Za-z0-9_]/",utf8_decode($cadena),$errores)){    $this->error="El Nombre de ".$campo." Debe Contener Solo Letras y Numeros, Sin Espacios, &ntilde;, Ni Acentos";   return 1; }        
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }                
    }    
    public function rif($cadena,$lmin,$lmax,$campo){        
        $tipo=" Caracteres";
        $this->error=NULL;
        if ( (preg_match_all('/[^VEJPGvejpg]{1}/',substr($cadena,0,1),$errores) + preg_match_all('/[^0-9]{1,9}/',substr($cadena,1),$errores2))!=0)              
            if (is_array($errores) || is_array($errores2)){  $this->error ="Campo Rif invalido, Formato Correcto 'V111111112'"; return 1;   }     
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }                                        
    }
    public function numeros($cadena,$lmin,$lmax,$campo){
        $tipo=" Numeros";
        $this->error=NULL;
        if (preg_match_all("/[^0-9]/",$cadena,$errores)){    $this->error="Campo ".$campo." Contiene Caracteres Invalidos ".implode("",$errores[0])."";   return 1; }        
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }
    }
    public function montos($cadena,$lmin,$lmax,$campo){
        $tipo=" Numeros";
        $this->error=NULL;
        if (preg_match_all("/[^0-9.,]/",$cadena,$errores)){    $this->error="Campo ".$campo." Contiene Caracteres Invalidos ".implode("",$errores[0])."";   return 1; }        
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }
    }
    public function minimo($cadena,$minimo,$campo){
        $this->error=NULL;
        if ($cadena<$minimo){   $this->error= "Campo ".$campo." Debe ser mayor a ".$minimo; return 1;    }
    }
    public function maximo($cadena,$maximo,$campo){
        $this->error=NULL;
        if ($cadena>$maximo){   $this->error= "Campo ".$campo." Debe ser menor a ".$maximo; return 1;}
    }
    public function fecha($fecha,$fmin,$fmax,$campo){
        $this->error=NULL;
        //$msg="El Campo <span class='admin_contenido'>".$nombre."</span> Debe Contener Una Fecha Valida";		
	   if (!checkdate(substr($fecha,3,2),substr($fecha,0,2),substr($fecha,6,4))){       $this->error=$campo." Es Una Fecha Invalida ";   return 1;       
	   }
       elseif(empty($fecha)){
            $this->error=$campo." Debe Indicar Una Fecha Valida"; 
       }elseif(!empty($fmax)) {
	        if ( mktime(0,0,0,substr($fecha,3,2),substr($fecha,0,2),substr($fecha,6,4))> mktime(0,0,0, substr($fmax,3,2),substr($fmax,0,2),substr($fmax,6,4)) ){ $this->error= $campo." Debe ser Menor a ".$fmax;   return 1;}
	   }elseif(!empty($fmin)){
	       if ( mktime(0,0,0,substr($fecha,3,2),substr($fecha,0,2),substr($fecha,6,4))< mktime(0,0,0, substr($fmin,3,2),substr($fmin,0,2),substr($fmin,6,4)) ){ $this->error=  $campo." Debe ser Mayor a ".$fmin;   return 1;}    
	   }       
    }
    public function seleccion($cadena,$campo){
        $this->error=NULL;
        if (strlen(trim($cadena))<=0) {  $this->error="Campo ".$campo." Falta Por Seleccionar ";   return 1;  }
        elseif ($cadena=='0'){  $this->error="Campo ".$campo." Falta Por Seleccionar";   return 1;  }
    }
    public function email($cadena,$lmin,$lmax,$campo){
        $tipo=" Caracteres";
        $this->error=NULL;
        if ($this->check_email_address($cadena))
            return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);
        else{   $this->error="Campo ".$campo." Invalido";   return 1;}
               
    }        
    public function Nulo($cadena,$campo){
        if (empty($cadena)){
            $this->error="Campo ".$campo.", No Puede Ser Nulo";
            return 1;
        }
        return 0;
    }
    public function check_email_address($email) 
    {
	     // Primero, checamos que solo haya un símbolo @, y que los largos sean correctos
       //if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
       //preg_match('/'.$patron.'/', $cadena_texto);
       if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) 
	    {   return false;  /* correo inválido por número incorrecto de caracteres en una parte, o número incorrecto de símbolos @ */  }
        
        // se divide en partes para hacerlo más sencillo
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) 
	    {  if (!preg_match("/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+$/", $local_array[$i])){ return false;    }  } 
        
        // se revisa si el dominio es una IP. Si no, debe ser un nombre de dominio válido
	    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) 
	    { 
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {   return false; /* No son suficientes partes o secciones para ser un dominio */  }
            for ($i = 0; $i < sizeof($domain_array); $i++) 
		    {   if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])){  return false;  }   }
        }
        return true;
    }
    public function telefono($cadena,$lmin,$lmax,$campo){
        $tipo=" Caracteres";
        $this->error=NULL;
        if (!preg_match("/^[0-9]{4,4}([- ]?[0-9]{7,7})?$/",$cadena)){   $this->error="Campo ".$campo." es Invalido, Formato Valido 9999-9999999";   return 1;}
        if ($lmin>0 || $lmax>0){     return $this->longitud($cadena,$lmin,$lmax,$campo,$tipo);    }
    } 
    /*
    public function ComboVal($cadena,$campo){
        $this->error=NULL;
        if ((empty($cadena)) || ($cadena==0)){   $this->error= $campo." Seleccion Una Opcion";  return 1;   } 
    }
    */
    /*
    public function longitud($cadena,$lmin,$lmax,$campo){
        $this->error=NULL;
        $len=strlen($cadena);         
        if (($lmin==0) && ($len>$lmax)){    $this->error= $campo." Excede la Longitud Maxima ".$lmax;   return 1;   }
        if (($lmax==0) && ($len<$lmin)){    $this->error= $campo." Longitud Minima ".$lmin; return 1;   }  
        if (($len<$lmin) || ($len>$lmax)){  $this->error= $campo." Longitud Minima $lmin, Maxima $lmax";    return 1;   }  
    }
    */
    public function longitud($cadena,$lmin,$lmax,$campo,$tipo){   
        $this->error=NULL;
        $len=strlen(trim($cadena));
        if ($lmin==0 && $len>$lmax){$this->error="Campo ".$campo." Excede la Longitud Maxima [$lmax] ".$tipo; return 1;}
        if ($lmax==0 && $len<$lmin){$this->error="Campo ".$campo." Debe Tener Minimo [$lmin] ".$tipo; return 1;}
        if ($lmin==$lmax && $len!=$lmin)          {$this->error="Campo ".$campo." Debe Tener [$lmin] ".$tipo; return 1;}
        if ($len<$lmin || $len>$lmax && $lmin>0 && $lmax>0){$this->error="Campo ".$campo." Debe Contener Entre [$lmin y $lmax] ".$tipo; return 1;}        
    }   
}
?>