<?php


function Mail_Cabecera($origen, $destino, $nombre, $nombreO = null)
{
    //para el env�o en formato HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    //direcci�n del remitente
    $headers .= "From: {$nombreO} <".$destino.">\r\n";
    //direcci�n de respuesta, si queremos que sea distinta que la del remitente
    #$headers .= "Reply-To: {$nombre}<".$origen.">\r\n";
    //ruta del mensaje desde origen a destino
    $headers .= "Return-path: {$origen}\r\n";
    //direcciones que recibi�n copia
    $headers .= "Cc: {$nombre} <{$origen}>\r\n";
    //direcciones que recibir�n copia oculta
//        $headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n";
    return $headers;
}
function bannerMail($titulo)
{
    return '
            <div style="height: 120px;" id="Banner"><img src="http://'.$_SERVER['SERVER_NAME'].'/imagenes/bannermail.png" /></div>
            <div style="background-color: #CEF0FF; border-top: #0098DB solid 1px; border-left: #0098DB solid 1px; border-right: #0098DB solid 1px; padding-top: 8px;  padding-bottom: 8px; font-size: 14px;  font-weight: bold; text-align:center">
                <center><strong>'.$titulo.'</strong></center>
            </div>
            <div style="padding:25px; border: #0098DB solid 1px;  -moz-border-radius: 0px 0px 5px 5px;  -webkit-border-radius: 0px 0px 5px 5px;  border-radius: 0px 0px 5px 5px;">
    ';
}
function beginBodyMail($titulo)
{
    return "<div style='min-width: 600px; max-width:600px; width:auto; margin: auto;'>".bannerMail($titulo);
}
function endBodyMail()
{
    return "</div>";
}

function Mail_Html_Inicio($titulo)
{
    return '<html><head><meta http-equiv="content-type" content="text/html" /><title>Registro Inicial de Participantes</title></head>'.
            '<body>'.
            '<div style="min-width: 600px; max-width:600px; width:auto; margin: auto;"><div style="height: 120px;" id="Banner"><img src="http://'.$_SERVER['SERVER_NAME'].'/imagenes/bannermail.png" /></div>'.
            '<div style="background-color: #CEF0FF; border-top: #0098DB solid 1px; border-left: #0098DB solid 1px; border-right: #0098DB solid 1px; padding-top: 8px;  padding-bottom: 8px; font-size: 14px;  font-weight: bold; text-align:center">
                <center><strong>'.$titulo.'</strong></center>
            </div>
            <div style="padding:25px; border: #0098DB solid 1px;  -moz-border-radius: 0px 0px 5px 5px;  -webkit-border-radius: 0px 0px 5px 5px;  border-radius: 0px 0px 5px 5px;">';
}

function Send_Mail($Tipo, $Origen, $Destino, $Nombre, $Texto)
{
    switch ($Tipo) {
        case "inicial":
            $Titulo='Registro Inicial de Participación';
            $Obs='<br /><div style="font-size: 12px;"><center>Haz recibido este correo al registrarte como participante en <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com">Fondo Interactivo de Negocios </a> Luego de realizar el deposito ve <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com/paginas/activar.php" >Activar Participacion</a> y registrar su deposito para empezar a distrutar de los beneficios de formar parte de la franquicia de <strong>Participación de Capitales</strong></center></div>';
        break;
        case "registro":
            $Titulo='Actualizacion de Registro Inicial de Participación No Aprovechada';
            $Obs='<br /><div style="font-size: 12px;"><center>Haz recibido este correo al registrarte como participante en <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com">Fondo Interactivo de Negocios </a> Luego de realizar el deposito ve <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com/paginas/activar.php" >Activar Participacion</a> y registrar su deposito para empezar a distrutar de los beneficios de formar parte de la franquicia de <strong>Participación de Capitales</strong></center></div>';
        break;
        case "renova":
            $Titulo='Registro Para Re-Activacion de Participación Vencida';
            $Obs='<br /><div style="font-size: 12px;"><center>Haz recibido este correo al registrarte como participante en <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com">Fondo Interactivo de Negocios </a> Luego de realizar el deposito ve <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com/paginas/activar.php" >Activar Participacion</a> y registrar su deposito para empezar a distrutar de los beneficios de formar parte de la franquicia de <strong>Participación de Capitales</strong></center></div>';
        break;
        case "activacion":
            //Cuando el Participante Desea Activarse y Registra su Deposito
            $Titulo='Registro de Deposito Para Activación';
            $Obs='<br /><div style="font-size: 12px;"><center>Haz recibido este correo electronico al registrar el deposito de activación, este sera revisado por parte del administrador de la franquicia para verificar su validez y luego la cuenta sera activada y se le enviara su clave de escritorio con la cual podra <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com/paginas/activar.php?cedula=&clave=">ACCEDER A SU CUENTA</a> </center></div>';
        break;
        case "autorizar":
            //Cuando el Participante Desea Activarse y Registra su Deposito
            $Titulo='Activacion de la Participación';
            $Obs='<br /><div style="font-size: 12px;"><center>Haz recibido este correo electronico al registrar el deposito de activación, este sera revisado por parte del administrador de la franquicia para verificar su validez y luego la cuenta sera activada y se le enviara su clave de escritorio con la cual podra <a style="text-decoration: none;" href="https://www.fondointeractivodenegocios.com/paginas/activar.php?cedula=&clave=">ACCEDER A SU CUENTA</a> </center></div>';
        break;
    }
    $Cuerpo=Mail_Html_Inicio($Titulo).$Texto.$Obs.Mail_Html_Fin();
    $Cabecera=Mail_Cabecera($Origen, $Destino, $Nombre);

    //echo $Cuerpo;
    //Envio de Correo
    if (@mail($Destino, $Titulo, $Cuerpo, $Cabecera)) {
        return "Se ha enviado un correo a su {$Origen} con todos los datos suministrados ";
    } else {
        return "Disculpe, a fallado el intento de enviar el correo con informacion de su registro, por favor escriba a ".$Origen." y plantee su problematica";
    }
}
function carta_iniciacion($var = array())
{
    $var = array_merge(array(
        'nombre'=>'',
        'cinvita'=>'',
        'cconexion'=>'',
        'asociador'=>'',
        'fecha'=>'',
        'hora'=>'',
        'cupon'=>'',
        'cuentas'=>array(
            'title'=>'CUENTAS DISPONNIBLES PARA REALIZAR DEPOSITOS O TRANSFERENCIAS',
            'details'=>array(
                'Banco'=>'',
                'Titular'=>'',
                'Tipo'=>'',
                'Cuenta'=>''
            )
        )
    ), $var);

    return "
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <style>
            body > div {width: 600px; height:800px; text-align: justify; margin-left: auto;margin-right: auto;border: 1px solid #000;font-size: 14px;}
            p {margin: 10px 10px 10px 10px;}
            li {margin-left: 10px;margin-right: 10px;}
            li span {font-size: 20px;color: rgb(30, 129, 171);}
            span.azul {font-size: 14px;font-weight: bold;color: rgb(30, 129, 171);}
            span.red {font-style: italic;text-decoration: underline;font-size: 14px;color:red;}
            .black {font-size: 18px;font-weight: bold;color:#000;}
            .black2 {font-size: 18px;text-align: left;font-weight: bold;color:#000;margin-left: 300px;}
        </style>
    </head>
    <body>
    ".beginBodyMail('Carta de Iniciación')."
    <table border='0'>
    <tr>
    <td>
            <p>
                ¡Hola… {$var['nombre']} ¡ haz tomado la decisión correcta al registrarte en el “Plan de Negocios Libertad” y
                optar a ser Miembro Fundador de la Universidad para Emprendedores<br><br>
                Si estás leyendo esto es porque un buen amigo confía en ti y te ofrece la oportunidad de alcanzar tú felicidad.<br><br>
                Sera una experiencia nueva y muy emocionante. Donde a partir de ahora.<br><br>
            </p>
            <ul>
                <li><span>Si... </span>ganaras dinero.</li>
                <li><span>Si... </span>recibirás las herramientas que construirán tú futuro.</li>
                <li><span>Si... </span>aprenderás a pensar y actuar como un millonario. <span class='red'>Porque lo serás.</span></li>
                <li><span>Si... </span>encontrar la felicidad que mereces.</li>
                <li><span>Si... </span>dirás adiós a la pobreza.</li>
                <li><span>Si... </span>Vivirás en Libertad.</li>
            </ul>
            <p>
                Por lo pronto esta son tus claves secretas:
            </p>
            <ul>
                <li><span class='azul'>Invitación: {$var['cinvita']}</span> Donde podrás invitar, sin limitaciones a todos los amigos que harán tu equipo perfecto.</li>
                <li><span class='azul'>Conexión: {$var['cconexion']}</span> Aquí reflejaras el compromiso que asumes con tu futuro. Es el aporte de inversión.</li>
            </ul>
            <p>
                A partir de este momento dispones de quince (15) días en promoción para activarte a partir de tan solo el 10% de la inversión mínima
                pudiendo así familiarizarte con el sistema e iniciar el Plan de Negocio Libertad.
            </p>
            <p>
                <strong>Para activarte en promoción</strong> es simple:
            </p>
            <ol>
                <li>Efectúa una inversión a partir del 0.02% (10.00 US$), en cualquiera de la cuentas. Para Venezuela se recibe moneda nacional
                bolívares fuertes con valor del dólar a cambio internacional. </li>
                <li>Realiza el seminario
                    <ul>
                        <li><strong>“¿Sabes como alcanzar tu meta?”</strong> el cual es el punto de partida para iniciar el Plan de Negocio
                        Libertad, a dictarse online a través de nuestra escuela de negocios en día {$var['fecha']} a las {$var['hora']} (América/Caracas).
                        Gracias a la invitación de tu amigo {$var['asociador']} disfrutaras de un fabulo descuento del 100% al activar el
                        siguiente cupón {$var['cupon']} de descuento</li>
                    </ul>
                </li>
            </ol>
            <p>
                <center><span class='azul'>Este seminario te hará comprender la magnitud de tu empresa.</span> </center>
            </p>
            <p>
                Si pasara los quince (15) días de la <strong>promoción</strong> podrás regístrate a partir de 100.00 dólares americanos hasta un
                máximo de 50.000.00 dólares americanos y realizar el seminario “¿Sabes cómo alcanzar tu meta?” para lograr la activación.
            </p>
            <p>
                <center><span class='black'>Toma la decisión y actúa ya …</span></center>
                <div class='black2'>Este el secreto de tu triunfo</div>
            </p>
            <p>
                Estas son las modalidades para la formalización de la tu inversión: Depósitos, transferencias, efectivo, y próximamente Paypal.<br><br>
                ".table_contents($var['cuentas'])."
                Sus preguntas son importantes y podrá efectuarla por nuestra sección de contacto.  Este revisando nuestra página web así como todas nuestras redes sociales.<br><br>
                Bienvenido al éxito.
            </p>
        </td>
        </tr>
        </table>
        ".endBodyMail()."
    </body>
    </html>";
}
function table_contents($cuentas = array())
{
    if (is_array($cuentas) && count($cuentas)>0) {
        $cuentas = (object) $cuentas;
        $table='<table border="1" align="center"><caption>'.$cuentas->title.'</caption>';
        $table.="<thead><tr align='center'>";
        foreach (array_keys($cuentas->details[0]) as $key) {
            $table.="<td>".str_replace('_', ' ', $key)."</td>";
        }
        $table.="</tr><thead><tbody>";
        foreach ($cuentas->details as $key => $cuenta) {
            $table.='<tr>';
            foreach ($cuenta as $key => $value) {
                $table.="<td>{$value}</td>";
            }
            $table.='</tr>';
            #$table.="<tr><td>{$cuenta['Titular']}</td><td>{$cuenta['Banco']}</td><td><{$cuenta['Cuenta']}</td><td>{$cuenta['Tipo']}</td><td>{$cuenta['Banco']}</td></tr>";
        }
        $table.='</tbody></table><br><br>';
        return $table;
    }
}
function carta_activacion($var = array())
{
    $var = array_merge(array(
        'nombre'=>'',
        'cescritorio'=>'',
        'transacciones'=>array(
            'title'=>'',
            'details'=>array(
                'Banco'=>'',
                'Cuenta'=>'',
                'Referencia'=>'',
                'Fecha'=>'',
                'Monto_Base'=>0,
                'Monto_Oficial'=>0
            )
        )
    ), $var);
    return "
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <title>Document</title>
        <style>
            body > div {width: 600px; text-align: justify; margin-left: auto;margin-right: auto;border: 1px solid #000;font-size: 14px;}
            p {margin: 10px 10px 10px 10px;}
            li {margin-left: 10px;margin-right: 10px;}
            li span {font-size: 30px;color: rgb(30, 129, 171);}
            span.azul {font-size: 15px;font-weight: bold;color: rgb(30, 129, 171);}
            span.red {font-weight: bold; font-size: 14px;color:red;}
            table {
                width: 95%;
                border-collapse: collapse;
                border-spacing: 0;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }
            td, th { border: 1px solid #CCC; }
        </style>
    </head>
    <body>
    ".beginBodyMail('Carta de Activación')."
        <div>
            <p>
                ¡Hola… {$var['nombre']}<br><br>
            </p>
            <p>
                Es hora de DIVERTIRSE… Ganando dinero en acción.
            </p>
            <ol>
                <li>Lee todo con detenimiento.</li>
                <li>Relaciónese… buscando súper amigos.</li>
                <li>Asiste a los seminarios del “PLAN DE NEGOCIOS LIBERTAD”.</li>
                <li>INVITA a tus amigos a hagan el seminario inicial <span class='azul'>“Sabes cómo alcanzar tu meta”</span> <strong>es <span class='red'>SIN COSTO</span>  para quien tú registre.</strong></li>
                <li>Mantente observando la página del <strong>Fondo Interactivo de Negocios y sus redes sociales</strong>.</li>
                <li>Aclara tus dudas. Realiza las preguntas  a:  consultas@fondointerctivodenegocios.com.ve.</li>
                <li>Enfócate en tu meta siempre…</li>
                <li>Haz invitaciones para otros crezcan en lo personal al igual que tú.</li>
                <li>No es obligatorio que vendas publicidad De hacerlo tendrás ingresos ADICIONALES de inmediatos. Es cuestión de intentar. Comienza por lo más simple los CLASIFICADOS.</li>
            </ol>
            <p>
                Esta es la CLAVE DE TÚ ESCRITORIO  :<span class='azul'>{$var['cescritorio']}</span>
            </p>
            <p>
                A continuación se presenta la relación de depósitos utilizados para activar tu cuenta<br><br>
                ".table_contents($var['transacciones'])."
            </p>

            <p>
                Monto Total de la Participación en Bolívares:   {$var['totalmoficial']}<br><br>
                Monto Total de la Participación en Dólares:   {$var['totalmbase']}<br><br>
                Periodo de Apertura: {$var['desde']} - {$var['hasta']}<br><br>
                <a href='https://www.fondointeractivodenegocios.com.ve/paginas/index.php?op=Conexión#Escritorio Virtual'>CLICK AQUÍ PARA ir directamente  a Tú ESCRITORIO VIRTUAL</a>
            </p>
        </div>
        ".endBodyMail()."
    </body>
    </html>
    ";
}

function Mail_Html_Fin()
{
    return "</div></body></html>";
}
