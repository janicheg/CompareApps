<?php
include 'CompareApps.php';

/*$links = [
    'https://itunes.apple.com/ru/app/skype-dla-iphone/id304878510?mt=8',
    'https://play.google.com/store/apps/details?id=com.skype.raider',
    'https://itunes.apple.com/ru/app/facebook/id284882215?l=ru&mt=8&ign-mpt=uo%3D2',
    'https://play.google.com/store/apps/details?id=com.facebook.katana',
    'https://play.google.com/store/apps/details?id=com.vkontakte.android',
];*/
$links = $_POST['links'];

$compare = new CompareApps();
$clones = $compare->compare($links);
?>

<html>
<head>
    <style>
        td {
            border: 1px solid black;
            padding: 2px;
            margin: 2px;
        }
    </style>
</head>
<body>
<h1>Идентичные приложения</h1>
<table>
    <thead>
        <tr>
            <td>#</td>
            <td>iTunes</td>
            <td>Google Play</td>
        </tr>
    </thead>
    <tbody>
    <?foreach($clones as $key => $clone):?>
        <tr>
            <td><?=$key+1?></td>
            <td><?=$clone['links']['itunes']?></td>
            <td><?=$clone['links']['google']?></td>
        </tr>
    <?endforeach?>
    </tbody>
</table>
</body>
</html>
