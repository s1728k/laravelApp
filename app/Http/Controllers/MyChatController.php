<?php

namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use App\Http\Controllers\Controller;
use App\Tourist;
use App\Tourguide;
use App\Tourcompany;
class MyChatController extends Controller implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        // $this->clients = new \SplObjectStorage;
        $this->clients = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        // $this->clients->attach($conn);
        $this->clients[$conn->resourceId] = $conn;
        $this->clients[$conn->resourceId]->send($conn->resourceId);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $colors=array("red", "maroon", "yellow", "olive", "lime", "green", "aqua", "teal", "blue", "navy", "fuchsia", "purple", "grey", "silver", "black", "white");
        $msgObj = json_decode($msg);
        $fromTable = 'App\\'.ucwords(rtrim($msgObj->fromRtype,'s'));
        $fromRecord = $fromTable::findOrFail($msgObj->from);
        $msgObj->fromName = $fromRecord->name;
        foreach ($this->clients as $client) {
            if ($fromRecord->chat_resource_id == $client->resourceId) {
                $msgObj->color = "red";
                $client->send(json_encode($msgObj));
            }
        }
        foreach ($msgObj->to as $key => $to) {
            $toTable = 'App\\'.ucwords(rtrim($to->rtype,'s'));
            $toRecord = $toTable::findOrFail($to->rid);
            if($toRecord->chat_resource_id == $fromRecord->chat_resource_id){continue;}
            foreach ($this->clients as $client) {
                if($toRecord->chat_resource_id == $client->resourceId){
                    $msgObj->color = $colors[($key+1) % 16];
                    $client->send(json_encode($msgObj));
                }
            }
        }
    }

    // public function onMessage(ConnectionInterface $from, $msg) {
    //     $msgObj = json_decode($msg);
    //     foreach ($this->clients as $client) {
    //         if ($msgObj->to == $client->resourceId || $msgObj->from == $client->resourceId) {
    //             $client->send($msg);
    //         }
    //     }
    // }

    public function onClose(ConnectionInterface $conn) {
        
        // $this->clients->detach($conn);
        $tourist = Tourist::where('chat_resource_id', $conn->resourceId)->first();
        $tourguide = Tourguide::where('chat_resource_id', $conn->resourceId)->first();
        $tourcompany = Tourcompany::where('chat_resource_id', $conn->resourceId)->first();

        if (!empty($tourist)){
            $tourist->online_status = '';
            $tourist->chat_resource_id = 0;
            $tourist->save();
        }else if (!empty($tourguide)){
            $tourguide->online_status = '';
            $tourguide->chat_resource_id = 0;
            $tourguide->save();
        }else if (!empty($tourcompany)){
            $tourcompany->online_status = '';
            $tourcompany->chat_resource_id = 0;
            $tourcompany->save();
        }
        unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}