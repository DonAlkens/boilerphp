<?php 

namespace App\Core\Factory\Socket\Interfaces;

use Exception;

interface WebSocketEventsInterface {

    /**
     * Triggers when client connects to the socket server
     *  
     * @param connection 
     */
    public function onOpen($connection);
    

    /**
     * Triggers when a client send a message to the socket server
     *  
     * @param connection 
     */
    function onMessage($connection, $mesaage);


    /**
     * Triggers when a server send a message to a connected client
     *  
     * @param connection 
     */    
    function onSend($connection, $mesaage);


    /**
     * Triggers when a connection is closed
     *  
     * @param connection 
     */
    function onClose($connection);


    /**
     * Triggers when a error occurrs on a connection
     *  
     * @param connection 
     */
    function onError($connection, Exception $ex);

}