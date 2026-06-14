<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';

class NotificationServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server Notifikasi Real-Time Berjalan di port 8080...\n";
    }
// Seperti pintu masuk. Saat aplikasi terhubung ke server, server mencatat koneksi tersebut agar bisa mengirim pesan ke aplikasi kapan saja nanti.
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Koneksi baru masuk: ({$conn->resourceId})\n";
    }
// real time server bakalan meneriman pesan dari aplikasi (misalnya saat task baru dibuat atau diupdate), lalu langsung mengirimkan pesan itu ke semua perangkat yang terhubung, termasuk emulator/layar yang sedang kamu pakai. Jadi, setiap kali ada perubahan pada task, semua perangkat akan mendapatkan notifikasi secara real-time tanpa perlu refresh.
    public function onMessage(ConnectionInterface $from, $msg) {
        // Broadcast ke SEMUA perangkat (termasuk emulator/layar yang sedang kamu pakai)
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }
// efesiensi sat aplikasi di tutup agar mengatasi kebocoran memori server 
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Koneksi terputus: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = Ratchet\Server\IoServer::factory(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new NotificationServer()
        )
    ),
    8080
);

$server->run();
?>