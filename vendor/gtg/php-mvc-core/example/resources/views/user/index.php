<?php 
    $this->layout("themes/main/_theme", [
        'title' => sprintf('User Page | %s', $appData['app_name'])
    ]);

    echo 'Logged user data: <br>';
    print_r($user);
    echo '<br><br> URL data: <br>';
    print_r($data);
?>