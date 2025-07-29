<?php

function getISODate()
{
   $now = new DateTime('now', new DateTimeZone('Europe/Rome'));
   $date = $now->format('Y-m-d');
   $time = $now->format('H:i:s');
   $dt = $date . 'T' . $time;
   return $dt;
}
 