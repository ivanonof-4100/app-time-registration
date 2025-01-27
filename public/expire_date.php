<?php
const SESSION_COOKIE_LIFETIME =7200;

// Greenwich Mean Time (GMT)
$unixTimestamp = time() + SESSION_COOKIE_LIFETIME;
$cookieExpireDate = date(DateTimeInterface::COOKIE, $unixTimestamp);

// Show the calculated expire-date.
echo PHP_EOL . $cookieExpireDate .PHP_EOL;
