<?php

$router->get('subscription/list', 'subscription@view');
$router->post('subscription/add', 'subscription@add');
$router->post('subscription/remove', 'subscription@remove');