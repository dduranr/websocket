<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use \PDO;
class Fechas implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo 'Servidor Ratchet iniciado...';
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    // FROM es un objeto, y MSG es la cadena que viene de conn.send();
    public function onMessage(ConnectionInterface $from, $msg) {
        try {
            $fechas = array();
            $db_attrs = array(PDO::ATTR_PERSISTENT => true);
            $dsn = 'mysql:host=localhost;dbname=websocket;charset=utf8';
            $pdo = new PDO($dsn,'root','root',$db_attrs);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('SELECT * FROM conf WHERE id=? ORDER BY id ASC LIMIT 1');
            if ($stmt->execute([1])) {
                if($stmt->rowCount() === 1) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $fechas['fecha1'] = $row['fecha_inicio'];
                    $fechas['fecha2'] = $row['fecha_final'];
                    $fechas['dias'] = $row['dias_de_transmision'];
                }
            }
        }
        catch (Exception $e) {echo 'Excepción general: '.$e->getMessage();}
        catch (PDOException $e) {echo 'Excepción PDO: '.$e->getMessage();}


        // Justo aquí devuelvo al cliente el jSON con las fechas obtenidas de la BD
        if ($msg === 'getFechas') {
            $from->send(json_encode($fechas));
        }
        // Para devolver los mensajes en consola a todos los navegadores:
        // foreach ($this->clients as $client) {
        //     if ($msg === 'getFechas') {
        //         $client->send(json_encode($fechas));
        //     }
        // }


    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}