<div class="Articulo" id="IdArticulo" >    <div class="TituloArticulo">Registro de Clasificados Gratis</div>
    <div class='SeparadorArticuloInterno'></div>
    <div class="ContenidoArticulo" id="IdContenidoArticulo">
       <form id="RegClasificado" name="RegClasificado" method="POST" enctype="multipart/form-data" action="pinternauta.php">
       <input type="hidden" id="idform" name="idform" value="RClasificado" />
        <div class="CampoCompleto">
            <div class="Etiqueta">Categoria: </div>
            <div class="CampoCorto">
            <?php
                echo $bd->dbComboSimple("select id, categoria from categorias", array(), "categoria", 0, array(1), null);
            ?>
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">Titulo: </div>
            <div class="CampoMedio">
                <input id="titulo" name="titulo" type="text" placeholder="Maximo 60 Caracteres" maxlength="60" size="60" />
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">Descripción: </div>
            <div class="CampoMedio">
                <input id="descripcion" name="descripcion" type="text" placeholder="Maximo 1200 Caracteres" maxlength="1200" size="90" />
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">Contacto: </div>
            <div class="CampoMedio">
                <input id="contacto" name="contacto" type="text" maxlength="150" placeholder="Maximo 150 Caracteres" size="90" />
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">País: </div>
            <div class="CampoMedio">
            <?php
                echo $bd->dbComboSimple("select id,pais from paises", array(), "CPais2", 0, array(1), null);
            ?>
            </div>
            <div class="Etiqueta">Estado: </div>
            <div class="CampoCorto">
            <?php
                echo $bd->dbComboSimple("select id,estado from estados where pais=?", array(null), "CEstado", 0, array(1), null);
            ?>
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">Id o Cedula: </div>
            <div class="CampoCorto">
                <input id="cedula" name="cedula" type="text" maxlength="60" size="60" />
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">Nombre: </div>
            <div class="CampoCorto">
                <input id="nombre" name="nombre" type="text"  size="35" />
            </div>
            <div class="Etiqueta">Apellido: </div>
            <div class="CampoCorto">
                <input id="apellido" name="apellido" type="text" size="35" />
            </div>
            <div class="Limpiador"></div>
        </div>
        <div class="CampoCompleto">
            <div class="Etiqueta">Correo Electronico: </div>
            <div class="CampoCorto">
                <input id="email" name="email" type="text"  size="35" />
            </div>
            <div class="Etiqueta">Teléfono: </div>
            <div class="CampoCorto">
                <input id="phone" name="phone" placeholder="####-#######" type="text" maxlength="24" size="35" />
            </div>
            <div class="Limpiador"></div>
        </div>

        <div class="FormFin">
            <input type="submit" id="Enviar" name="Enviar"  value="Registrar Clasificado"/>
        </div>
        </form>
       <div id="info"></div>
      <div class="Limpiador"></div>
    </div>
</div>
