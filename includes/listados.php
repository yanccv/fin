<?php
	include ("classdb.php");
    include ("funcion.php");
	//include('func_str.php');
	class LISTA extends dbMysql{
		var $Campo,$Titulos,$Aling,$LFiltro,$Orden,$Result,$fila,$Columnas,$Group;
		var $sizeTb=0;	                                            //Ancho de la Tabla
		var $sizeTd,$NoLabel,$Iconos,$Img,$ImgOrd,$ImgPag=array();	//Ancho de la Celda
		var $clM,$clR;                                              //Css del Membrete y de los Registros
		var $bd;
		var $ClaseCSS;	                                            //Css del Membrete y de los Registros
		var $RutaImg;	                                            //Ruta de las Imagenes a Mostrar
		var $Morden=true;                                          //Permite Establecer o Esconder los Botones Para Ordenar
        var $Forms,$Editable;                                       //Variable Para Indicar el Nombre del Listado,{Nombre Interno}
        var $Fecha;                                                 //Indica en que Indice el Campo es Tipo Fecha
        var $FechaHora;
        var $Busca=true;                                            //Define Si Estan Habilitadas las Busquedas
        var $Pagina=true;                                           //Define si se activa o no la paginacion
                                                   //Indica en Que Indice el Campo es Tipo DateTime
		function declaraciones(){
			$this->Img['E']=$this->RutaImg."/editar.png";   //Editar
			$this->Img['D']=$this->RutaImg."/borrar.png";   //Borrar
			$this->Img['C']=$this->RutaImg."/consul.png";   //Consultar
			$this->Img['R']=$this->RutaImg."/senmai.png";   //Enviar Correo
			$this->Img['V']=$this->RutaImg."/votos.png";	//Ver Votos
            $this->Img['A']=$this->RutaImg."/autor.png";    //Autorizar
            $this->Img['S']=$this->RutaImg."/envia.png";    //Enviar
            $this->Img['D']=$this->RutaImg."/desau.png";    //Desautorizar
            $this->Img['P']=$this->RutaImg."/print.png";    //Desautorizar
            $this->Img['N']=$this->RutaImg."/newss.png";    //Desautorizar
            $this->Img['U']=$this->RutaImg."/renov.png";    //Actualizar
            $this->Img['T']=$this->RutaImg."/carnet.png";   //Carnet
            $this->Img['I']=$this->RutaImg."/imagen.png";   //Imagen

			$this->ImgOrd['A']=$this->RutaImg."/asc.png";
			$this->ImgOrd['D']=$this->RutaImg."/des.png";
			$this->ImgPag['P']=$this->RutaImg."/pri.png";
			$this->ImgPag['A']=$this->RutaImg."/ant.png";
			$this->ImgPag['S']=$this->RutaImg."/sig.png";
			$this->ImgPag['U']=$this->RutaImg."/ult.png";
		}
		function setSizeTb($size){	$this->sizeTb=$size; 	}
		function setBd($bd){	$this->bd=$bd;	}
		function Listados($campos,$titulos,$tabla,$filtro,$orden,$limit)
		{
			//echo $GLOBALS['ServerBd'];
			if (!empty($orden)) $this->Orden=$orden;
			$this->declaraciones();

            $this->dbConectar();

			if ($this->Error){ echo "Error".$this->Moerror(); exit(); }
            if (!empty($this->LFiltro)) $this->LFiltro="where ".$this->LFiltro;

			//if (empty($this->Sql))		$this->setSql("select $campos from $tabla $this->FiltroCampo order by $orden limit $limit[0],$limit[1]");
			$this->Result=$this->dbConsultar("select $campos from $tabla $this->LFiltro $this->Group order by $orden limit ".(($limit[0]-1)*$limit[1]).",$limit[1]");
            //echo "Sql: ".$this->Sql."<br />";

            if ($this->Error){ echo "Errasnando ".$this->getSql();  echo $this->MsgError; exit();  }

            //echo $this->getSql();
			//if (!empty($this->Error)){ echo $this->Merror."<br>".$this->sql; exit(); }

			$this->Campo=explode(",",$campos);
			$this->Titulos=explode(",",$titulos);

			$this->Registros();
		}
		//Crea una Tabla
		function OTb(){	return "<table align='center' id='".$this->ClaseCSS."' cellpadding='1' cellspacing='0' width='".$this->sizeTb."' border='0'>";	}
		//Cierra una Tabla
		function CTb(){ return "</table>";	}
		//Crear Fila
		function OTr($cl){ echo "<tr class='".$cl."' id='".$this->fila[0]."' >";	}
		//Cerrar Fila
		function CTr(){ echo "</tr>";	}
		//Crear Celda
		function OTd($al,$size){  return "<td align='".$al."' width='".$size."' class=''>"; }
		//Cerrar Celda
		function CTd(){ return "</td>"; }

		//Funcion Maestra Para Mostrar el Listado
		function Registros(){
			echo $this->OTb();
			$this->filaMembrete();
            while($this->fila = $this->Result->fetch_row()){   if ($f==1) $f=0; else $f++;	$this->filaRegistro($f);  }
            $this->Result->close();
			echo $this->CTb();
		}

		//Imprime Una Fila Tipo Membrete
		function filaMembrete(){

			$this->OTr('titulo');
			for ($i=0;$i<count($this->Titulos);$i++)
			{
				if (!in_array($i,$this->NoLabel))
					$this->ImpColM($i);
			}
            if (!empty($this->Iconos)){
              $this->ImpColI('');
            }
			$this->CTr();
		}
		//Imprime Una Fila Tipo Registro
		function filaRegistro($f){
			$this->OTr('detalle'.$f);

			$i=0;
			for ($i;$i<count($this->fila);$i++)
				if (!in_array($i,$this->NoLabel))
					$this->ImpColC($i);
            //,$this->Aling[$i],$this->fila[$i]
			$ali="center";
			$i=0;
			for ($i;$i<count($this->Iconos);$i++)
			{
				if (empty($this->Iconos[$i][0])) continue;
				if ($this->Iconos[$i][0]==="Cambio") continue;
				switch($this->Iconos[$i][0]){
				    case "URLN":
                        $mostrar="<a href='".$this->Iconos[$i][1]."?".$this->AsiVal($this->Iconos[$i][2],$this->Iconos[$i][3])."' target='_blank' ><img src='".$this->Img[$this->Iconos[$i][4]]."' alt='".$this->Iconos[$i][5]."' title='".$this->Iconos[$i][5]."' border='0'></a> ";
					break;
                    case "URL":
                        $mostrar="<a href='#' onClick=\"LoadEditar('".$this->Iconos[$i][1]."','".$this->AsiVal($this->Iconos[$i][2],$this->Iconos[$i][3])."')\" ><img src='".$this->Img[$this->Iconos[$i][4]]."' alt='".$this->Iconos[$i][5]."' title='".$this->Iconos[$i][5]."' border='0'></a> ";
					break;
					case "URLCLIENTE":
                        $mostrar="<a href='#' onClick=\"LoadEditarCliente('".$this->Iconos[$i][1]."','".$this->AsiVal($this->Iconos[$i][2],$this->Iconos[$i][3])."')\" ><img src='".$this->Img[$this->Iconos[$i][4]]."' alt='".$this->Iconos[$i][5]."' title='".$this->Iconos[$i][5]."' border='0'></a> ";						
					break;
					case "DEL":
						$mostrar="<a href='#' onClick=\"".$this->Iconos[$i][1]."(".$this->FunVal($this->Iconos[$i][2],$this->Iconos[$i][3])."); return false;"."\"><img src='".$this->Img[$this->Iconos[$i][4]]."' alt='".$this->Iconos[$i][5]."' title='".$this->Iconos[$i][5]."' border='0'></a>";
					break;
                    case "FUN":
						#$mostrar="<a href='#' onClick=\"".$this->Iconos[$i][1]."(".$this->FunVal($this->Iconos[$i][2],$this->Iconos[$i][3])."); return false;"."\"><img src='".$this->Img[$this->Iconos[$i][4]]."' alt='".$this->Iconos[$i][5]."' title='".$this->Iconos[$i][5]."' border='0'></a>";
                        $this->Iconos[$i][1]($this->GetVal($this->Iconos[$i][2]));
					break;

				}
				//$mostrar=$this->Iconos[$i][0];
				$this->ImpColI($mostrar);
			}
			$this->CTr();
		}
		//Imprime Una Columna del Membrete (Alineacion,Menbrete,CampodeBasedeDatos)
		function ImpColM($i){
			echo $this->OTd($this->Aling[$i],$this->sizeTd[$i]).utf8_decode($this->Titulos[$i]).$this->ImpOrd($this->Columnas[$i]).$this->Ctd();
		}
		//Imprime Columnas de Iconos
		function ImpColI($item){
			if (empty($item))	echo "<td colspan='".count($this->Iconos)."'>&nbsp;</td>";
			else	echo $this->OTd("center",20).$item.$this->Ctd();
		}
		//Imprime Una Columna de Los Registros (Alineacion,Campo)
		function ImpColC($i){
			//Verifico Si hay algun Llamado para Cambio de Valor
			for ($k=0;$k<count($this->Iconos);$k++)
				if ( ($this->Iconos[$k][0]=="Cambio") && ($i==$this->Iconos[$k][2]) )
				{
					$f=$this->Iconos[$k][1];
					$this->fila[$i]=$this->$f($this->fila[$i],$this->Iconos[$k][3],$this->Iconos[$k][4]);
				}
			echo $this->OTd($this->Aling[$i],'').substr(preg_replace("[\n|\r|\n\r]", ' ',strip_tags($this->fila[$i])),0,500)."$enc&nbsp;".$this->Ctd();
		}
		//Imprime las Imagenes para Ordenar de Acuerdo a los Campos Vincula Solo El Orden
		function ImpOrd($campo)
		{	if ($this->Morden)
			     return  "<a href='#' onclick=\"ordenar('$campo asc');\"><img src='".$this->ImgOrd['A']."' title='Orden Ascendente' border='0' /></a><a href='#' onclick=\"ordenar('$campo desc');\"><img src='".$this->ImgOrd['D']."' title='Orden Descendente' border='0' /></a>";
		}

		//Funcion  Para Cambiar Valores de la Consulta Directa
		function ChaVal($campo,$base,$nuevo){
			$nbase=explode(",",$base);
			$nnuevo=explode(",",$nuevo);
			for ($i=0;$i<count($nbase);$i++){
				$campos=str_replace($nbase,$nnuevo,$campo);
			}	return $campos;
		}
		//Devuelve una Cadena Para Pasar Por Referencia a Otra Pagina Por GET
		function AsiVal($var,$val){
			$nvar=explode(",",$var);
			$nval=explode(",",$val);
			$cad="";
			$i=0;
			for ($i;$i<count($nvar);$i++)
			{
				$nomb=''; $valo='';
				if (is_numeric($nvar[$i]))	$nonb=$this->Campos[$nvar[$i]];
				else if (is_string($nvar[$i]))	$nomb=$nvar[$i];
				if (is_numeric($nval[$i]))	$valo=$this->fila[$nval[$i]];
				else if (is_string($nval[$i]))	$valo=$nval[$i];
				if ($cad==='')	$cad=$nomb."=".$valo;
				else $cad.="&".$nomb."=".$valo;
			}
			return $cad;
		}
        #Funcion Para Revisar Una Cadena si es Numerica Devuelve el Valor Correspondiente al Indice en el Registro Si es Caracter Retorna una Cadena
        function GetVal($String){
			$nvar=explode(",",$String);
			$cad="";
			$i=0;
			for ($i;$i<count($nvar);$i++)
			{
				$nonb=''; $valo='';
				if (is_numeric($nvar[$i])){	$nonb=$this->fila[$nvar[$i]]; }
				else if (is_string($nvar[$i]))	$nonb=$nvar[$i];
                $values[$i]=$nonb;
				//if ($cad==='')	$cad=$nomb."=".$valo;
			}
			return $values;
		}
		//Funcion Para Pasar Parametros a Una Funcion
		function FunVal($valcam,$valval){
			$nvalcam=explode(",",$valcam);
			$nvalval=explode(",",$valval);
			$cad='';	$cad1='';	$cad2='';
			$i=0;
			//echo count($nvalcam);
			for ($i;$i<count($nvalcam);$i++)
			{	$nomcam='';
				if (is_numeric($nvalcam[$i]))	$nomcam="'".$this->Campo[$nvalcam[$i]]."'";
				elseif (is_string($nvalcam[$i])) $nomcam="'".$nvalcam[$i]."'";
				if (empty($cad1))	$cad1=$nomcam;
				else	$cad1.=",".$nomcam;
			}
			$i=0;
			for ($i;$i<count($nvalval);$i++)
			{
				//echo "$nvalval[$i]";
				if (is_numeric($nvalval[$i]))	$nomval="'".$this->fila[$nvalval[$i]]."'";
				elseif (is_string($nvalval[$i])) $nomval="'".$nvalval[$i]."'";
				if ($cad2==='')	$cad2=$nomval;
				else	$cad2.=",".$nomval;
			}

			$cad=$cad1;
			if ($cad==='')	$cad.=$cad2;
			elseif (!empty($cad2))	$cad.=",".$cad2;
			return $cad;

		}
		//Crea la Paginacion al Final del Listado de Registros
		function paginacion($tabla,$limit){
			if (!empty($this->LFiltro)) $wfiltro=" ".$this->LFiltro;
			$sqlgen="select ".$this->Campo[0]." from ".$tabla.$wfiltro;
            //echo "gen".$sqlgen;
			$congen=$this->dbConsultar($sqlgen);
			if ($this->Error){ echo $this->Error."<br>".$this->sql; exit(); }
			$totreg=$this->dbRows($congen); # $this->rows($congen);

			$pags=$totreg/$limit[1];
			if ($pags>((int)$pags)){	$pags=((int) $pags)+1;	}
			if ($limit[0]==1){ $antl=1; } else{ $antl=$limit[0]-1;   }
			if ($limit[0]>=$pags-1){ $sigl=$pags; }else{ $sigl=$limit[0]+1;  }
			$Paginas="
		    <form id='form1' name='form1' method='get' action=''>
		      <table align='center' border='0' cellspacing='4' width='".$this->sizeTb."' class='Pages".$this->ClaseCSS."' cellpadding='0'>
			    <tr>
		          <td class='P".$this->ClaseCSS."'>Total [$totreg] Registros</td>
                  <td class='P".$this->ClaseCSS."'>Pagina <div id='pagactual".$this->ClaseCSS."' style='display:inline'>$limit[0]</div> de $pags</td>
		          <td class='P".$this->ClaseCSS."'>Registros Por Pagina: <input name='nro' class='Pages".$this->ClaseCSS."' type='text' id='nro' size='5' value='30' maxlength='5'></td>
		          <td valign='bottom' width='160' class='P".$this->ClaseCSS."'>
                    <div id='paginas'>
                    <a href='#' page='1'><img src='".$this->ImgPag['P']."' border='0' align='absmiddle' alt='Ir al Primero' title='Ir al Primero' width='26' height='21' /></a>
                    <a href='#' page='".$antl."'><img src='".$this->ImgPag['A']."' align='absmiddle' alt='Anterior' title='Anterior' width='20' height='21' border='0' /></a>
                    <input name='nropag' class='Pages".$this->ClaseCSS."' align='middle' type='text' id='nropag' size='3' maxlength='3' value='".$limit[0]."'  />
                    <a href='#' page='".$sigl."'><img src='".$this->ImgPag['S']."' alt='Siguiente' align='absmiddle' title='Siguiente' width='20' height='21' border='0' /></a>
                    <a href='#' page='".$pags."'><img src='".$this->ImgPag['U']."' alt='Ir al Ultimo' align='absmiddle' title='Ir al Ultimo' width='26' height='21' border='0' /></a>
                    </div>
                   </td>
        		</tr>
		      </table>
        	</form>";
			echo $Paginas;
		}

		//Crea el Formulario de Busquedas
		function buscar($campos,$titulos,$id){
			$campo=explode(",",CodeCombo($campos));
			$titulo=explode(",",$titulos);
            //name='form".$this->ClaseCSS."' id='form".$this->ClaseCSS."'
			$var="<form action='".$_SERVER['PHP_SELF']."' method='post' name='fBuscar' id='fBuscar'>
		      <table width='".$this->sizeTb."' class='Pages".$this->ClaseCSS."' align='center' border='0' cellspacing='2' cellpadding='2'>
    		    <tr><td width='200'>
                    <input name='fid' type='hidden' id='fid' value='$this->Forms'>
                    <input name='orden' type='hidden' id='orden' size='5' value='' maxlength='5'>
                    Buscar Por
            		<select name='campotb' size='1' id='campotb'>\n";
					for ($i=0;$i<count($campo);$i++){
					   if (!in_array($i,$id))
                        $var.=" <option value='".trim($campo[$i])."'>".trim($titulo[$i])."</option>\n";
					}
            	$var.="</select>
	          </td>
    	      <td width='100' valign='middle'><input name='valor' class='Pages".$this->ClaseCSS."' type='text' id='valor' size='25' maxlength='25'></td>
	          <td width='100' align='right' valign='top'>&nbsp;<button type='submit' name='B' id='B' > Buscar</button></td>
			  <td width='134' align='right' valign='top'>&nbsp;<button type='button' name='fTodo' id='fTodo'> Mostrar Todos</button></td>
    	    	</tr>
		      </table>
    	  	</form>";
			echo $var;

		}
	}

function CodeCombo($cadena){   return str_replace(".","___",$cadena);       }
function DecodeCombo($cadena){    return str_replace("___",".",$cadena);    }
?>
