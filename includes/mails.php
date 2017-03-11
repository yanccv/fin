<?php

class mails
{
    public $Origen;
    public $Destino;
    public function __contruct()
    {
    }
    private function setOrigen($var)
    {
        $this->origen=(object) $var;
        $this->origen->nomMail=$this->origen->nombre.' <'.$this->origen->mail.'>';
    }

    private function setDestino($var)
    {
        $this->destino=(object) $var;
        $this->destino->nomMail=$this->destino->nombre.' <'.$this->destino->mail.'>';
    }

    /**
     * Funcion para el envio de Correo
     * @param  array  $origen nombre=>nombre de quien envia;  mail=>direccion de correo desde donde se envia
     * @param  array  $destino nombre=>Nombre de quien recibe; mail=>direccion de correo a donde se envia
     * @param  array  $titulo titulo con el que llega el correo
     * @param  array  $cuerpo cuerpo del correo
     * @return bool devuelve true = se envio el correo; false= fallo el envio del correo
    */
    public function send(
        $origen = array('nombre'=>'', 'mail'=>''),
        $destino = array('nombre'=>'', 'mail'=>''),
        $titulo = null,
        $cuerpo = null
    ) {
        $this->setOrigen($origen);
        $this->setDestino($destino);
        //return @mail($this->destino->nomMail, $titulo, $Cuerpo, $this->head());
        if (!empty($this->origen->mail) && !empty($this->destino->mail)) {
            try {
                mail($this->destino->nomMail, $titulo, $cuerpo, $this->head());
            } catch (Exception $e) {
                return "Disculpe, a fallado el envio de correo a {$this->destino->mail}, por favor escriba a".
                    " {$this->origen->mail} y plantee su problematica";
            }
            return "Se ha enviado un correo a {$this->destino->mail} con toda la información suministrada";
        } else {
            echo 'Mail No Encontrado';
        }
    }

    /**
     * [headMail description]
     * @param  string $type Formato en el que se enviara el correo por defecto esta en html
     * @return string devuelve la cabezera del correo a enviar
     */
    private function head($type = 'html')
    {
        //para el envio en formato HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        //direcci�n del remitente
        $headers .= "From: {$this->origen->nomMail}\r\n";
        //direcci�n de respuesta, si queremos que sea distinta que la del remitente
        #$headers .= "Reply-To: {$nombre}<".$origen.">\r\n";
        //ruta del mensaje desde origen a destino
        $headers .= "Return-path: {$this->origen->nomMail}>\r\n";
        //direcciones que recibi�n copia
        $headers .= "Cc: {$this->origen->nomMail}>\r\n";
        //direcciones que recibir�n copia oculta
        //$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n";
        return $headers;
    }
}
