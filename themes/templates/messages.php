<?php 

if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} elseif($exception) {
    $message = [
        'type' => 'error',
        'message' => $exception->getMessage()
    ];
}

if($message) {
    echo "
        <script>
            $(function () {
                const app = new App();
                var message = " . json_encode($message) . ";
                app.showMessage(message.message, message.type);
            });
        </script>
    ";
}