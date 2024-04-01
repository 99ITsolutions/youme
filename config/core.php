<?php
/*Configure::write('Session', array(
        'defaults' => 'php',
        'timeout' => 30, // The session will timeout after 30 minutes of inactivity
        'cookieTimeout' => 1440, // The session cookie will live for at most 24 hours, this does not effect session timeouts
        'checkAgent' => false,
        'autoRegenerate' => true, // causes the session expiration time to reset on each page load
    ));*/
Configure::write('debug', 0);

Configure::write('Session', array(
        'defaults' => 'php',
        'timeout' => 30, // The session will timeout after 30 minutes of inactivity
        'cookieTimeout' => 1440, // The session cookie will live for at most 24 hours, this does not effect session timeouts
        'checkAgent' => false,
        'autoRegenerate' => true, // causes the session expiration time to reset on each page load
    ));
?>