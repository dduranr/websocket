<?php
    date_default_timezone_set('America/Mexico_City');
?>
<!DOCTYPE html>
<html>
<head>
    <title>WebSocket - Cambiar DOM según dato en BD</title>
    <link href="data:image/x-icon;base64,AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAgAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAA/4QAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAERERERERERERAAAAAAAAERAQAAAAAAEBEAEAAAAAEAEQABAAAAEAARAAAQAAEAABEAAAEAEAAAEQAAABEAAAARAAAAEQAAABEAAAEAEAAAEQAAEAABAAARAAEAAAAQABEAEAAAAAEAEQEAAAAAABAREAAAAAAAAREREREREREREAAAAAP/wAAF/6AABv9gAAd+4AAHveAAB9vgAAfn4AAH5+AAB9vgAAe94AAHfuAABv9gAAX/oAAD/8AAAAAAAA" rel="icon" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js" type="text/javascript"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center">WebSocket - Cambiar DOM según info en BD</h2>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-success" role="alert">
                    <h3>Objetivo</h3>
                    <p>La idea es que el cliente revise cada <i>n</i> segundos la BD, con el fin de averiguar si justo ahora es momento de activar/desactivar el video en el frontend.</p>
                </div>
            </div>
            <div class="col-md-8">
                <div id="wrapper-video"></div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    /*
        DETALLES del funcionamiento
        ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
            1. La mecánica la copié del proyecto que armé para departamento.tv. Ahí la idea era que el cliente pudiera configurar en el backend WP qué días de la semana iba a mostrarse el video (lunes, martes, etc) y en qué horarios. Es decir, no había fechas exactas, por ejemplo: mostrarse el viernes 8 de enero de 2021 a la 1:19pm. Las fechas se asignaban más bien a modo de horario escolar. Por esta razón en el backend de departamento.tv puse un campo para configurar los días de la semana, y también uno donde se indicaba si la hora de fin correspondía o no con el día siguiente respecto de la hora de inicio. Se me olvidó esta mecánica mientras armaba el presente proyecto con websockets, así que este proyecto sí maneja fechas explícitas (por ejemplo: mostrar video el viernes 8 de enero de 2021 a la 1:19pm). Lo cual hace innecesario tener información acerca de los días de la semana en que debe activarse el video; sin embargo, dejé tal campo, el cual no sirve de mucho. Pero al final de cuentas ya tengo 2 formas distintas de mostrar un determinado contenido en el frontend de acuerdo a info en la BD, es decir, con fechas exactas (este proyecto) y con fechas a modo de horario (departamento.tv).
    */

    $(document).ready(function(){

        // Creamos una nueva conexión websocket
        let conn = new WebSocket("ws://localhost:8080/");


        // --> El evento websocket ONOPEN: El servidor responde a la solicitud de conexión de WebSocket. Indica que se ha establecido la conexión.
        conn.onopen = function(e) {
            console.log('Nueva conexión establecida (WebSocket Ratchet)');
            // Enviamos una petición al servidor cada 5 seg. ¿Pero qué archivo PHP se encarga de realizar el trabajo en el servidor? En server.php (que es el archivo que se ejecuta en la consola del sitema operativo: php server.php para levantar la conexión del WebSocket) se instancia el objeto de la clase PHP encargada de hacer el trabajo en el servidor, en este caso es la clase Fechas... Es decir, el archivo en cuestión es Fechas.php
            setInterval(function(){
                conn.send('getFechas');
            }, 5000);
        };


        // --> El evento websocket ONMESSAGE:
        conn.onmessage = function(e) {
            let fechaInicio          = null;
            let fechaFin             = null;
            let diasDeTransmision    = null; // El 1er día de la semana es domingo y corresponde con el 0
            let json                 = JSON.parse(e.data); // Recuperamos los datos recibidos del servidor
            let objetosTotalesEnJSON = Object.keys(json).length;

            // Si recibimos los 3 datos obligatorios del servidor (fecha inicio, fin y los días de transmisión)
            if (typeof(objetosTotalesEnJSON)==='number' && objetosTotalesEnJSON===3) {

                // Recuperamos los datos recibidos del servidor
                for(let key in json) {
                         if (key==='fecha1') fechaInicio = json[key];
                    else if (key==='fecha2') fechaFin = json[key];
                    else if (key==='dias')   diasDeTransmision = json[key];
                }

                // Declaramos algunas variables a utilizar
                let diaDeHoy               = null
                let ahorita                = null
                let diasDeTransmisionARRAY = null;
                let startDate              = null;
                let endDate                = null;
                let estamosEnRango         = null;
                let ARRAYdiasDeTransmision = [];

                const formatoDeFecha   = 'YYYY-MM-DD HH:mm:ss'
                diaDeHoy               = <?php echo date('w'); ?>;
                ahorita                = moment(moment().format(), formatoDeFecha);
                diasDeTransmisionARRAY = diasDeTransmision.split(',');

                // Metemos en ARRAYdiasDeTransmision los días en que debe activarse el video
                for(let dia in diasDeTransmisionARRAY) {
                    let diaAinsertar = diasDeTransmisionARRAY[dia];
                    ARRAYdiasDeTransmision.push(parseInt(diaAinsertar));
                }

                // Si hoy es un día en que debe activarse el video en el frontend
                if(jQuery.inArray(diaDeHoy, ARRAYdiasDeTransmision) !== -1) {
                    console.log('%c==> Hoy <?php echo date("l"); ?> SÍ es un día de transmisión', 'background: #000; color: #0f0');

                    var alfa = new Date(fechaInicio);
                    var beta = new Date(fechaFin);

                    startDate = moment(alfa).format(formatoDeFecha);
                    endDate = moment(beta).format(formatoDeFecha);
                    estamosEnRango = ahorita.isBetween(startDate, endDate);
                    console.log('El rango es: '+startDate+' - '+endDate+'.');

                    // Mostramos/ocultamos el iFrame streaming si las fechas programadas coinciden
                    if (estamosEnRango === true) {
                        console.log('%cY es hora ('+moment().format()+') de que esté activo.', 'color: green');
                        jQueryShowVideo();
                    }
                    else{
                        console.log('%cPero no es hora ('+moment().format()+') de que esté activo.', 'color: red');
                        jQueryHideVideo();
                    }
                }
                else {
                    console.log('==> Hoy <?php echo date("l"); ?> NO es un día de transmisión', 'background: #000; color: #f00');
                }

                // Función encargada de mostrar el video en el frontend
                function jQueryShowVideo() {
                    if(jQuery('#video','div').length===0) {
                        console.log('El video NO está en el frontend, así que lo cargamos');
                        let iframeHTML = '<div id="video"><iframe width="360" height="200" src="https://www.youtube.com/embed/K8rpo9e7tvg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                        jQuery('#wrapper-video').html(iframeHTML);
                    }
                }
                // Función encargada de ocultar el video en el frontend
                function jQueryHideVideo() {
                    if(jQuery('#video','div').length) {
                        console.log('Quitamos el video');
                        jQuery('#video').fadeOut(1000, function(){jQuery(this).remove();});
                    }
                }
            }
            else {
                console.log('El front no está recibiendo la info completa de la BD');
            }
        };

        // --> El evento websocket ONCLOSE:
        conn.onclose = function(e) {
            console.log('Conexión websocket cerrada!');
        };
    })
</script>
</html>