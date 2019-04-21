<?php

namespace App\Console\Commands;

use App\Session;
use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\App;
use Ratchet\Server\EchoServer;
use App\Http\Controllers\MyChatController;

class RunWebsocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:ws';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Session::query()->update(['chat_resource_id' => null]);
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new MyChatController()
                )
            ),
            8080
        );
        $server->run();

        // $server = new App('localhost', 8080);
        // $server->route('/chat', new MyChatController, array('*'));
        // $server->route('/echo', new EchoServer, array('*'));
        // $server->run();
    }
}
