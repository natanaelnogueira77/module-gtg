<?php 

if(isset($_SESSION[SESS_MESSAGE])) {
    $message = $_SESSION[SESS_MESSAGE];
    unset($_SESSION[SESS_MESSAGE]);
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