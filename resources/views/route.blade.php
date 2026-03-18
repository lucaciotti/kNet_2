<?php

$response = \GoogleMaps::load('geocoding')
		->setParam (['address' =>'via dei martiri, 50, Rimini'])
 		->get();

dd($response);
// $routeParams = [
//     'origin' => [ ],
//     'destination' => [ /* ... destination details ... */ ],
//     'travelMode' => 'DRIVE',
//     // ... other Routes API parameters ...
// ];

// $responseArray = \GoogleMaps::load('routes') // Use 'routes' service
//     ->setParam($routeParams)
//     ->setFieldMask('routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline') // optional - used to specify fields to return 
//     ->fetch(); // Use fetch() for Routes API

// // $responseArray is already a PHP array
// if (!empty($responseArray['routes'])) {
//     // Process the route data
// } else {
//     // Handle errors or no routes found
// }
?>