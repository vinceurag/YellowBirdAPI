<?php $user = json_decode(
    file_get_contents('http://admin:1234@localhost/devspace/restserver/index.php/api/example/users/id/1/')
);

echo $user->name;
