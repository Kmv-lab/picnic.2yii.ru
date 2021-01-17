<?php
/*
    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    Yii::$app->response->headers->add('Content-Type', 'text/xml');




    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

    foreach ($arrAliases as $url){
        echo "<url>
                        <loc>$url</loc>
                    </url>";
    }
    echo "</urlset>";
*/

use yii\helpers\Url;

Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
Yii::$app->response->headers->add('Content-Type', 'text/xml');

$baseUrl = Url::home(true);
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

echo "<url><loc>$baseUrl</loc></url>";
foreach ($arrAliases as $url){
    echo "<url><loc>$baseUrl$url</loc></url>";
}
echo "</urlset>";

?>