<?php

$resolution = '200x134/'; ?>


<div class="owl-carousel">

    <?php

    $rand = rand ( 0, time());
    foreach ($photos AS $photo){
        echo '<div>
                <a href="'.$photo->DIRview().'original/'.$photo['name'].'" data-fancybox="gallery'.$rand.'" class="docs-slider__item js-gallery" >
                    <img 
                        loading="lazy" 
                        src="'.$photo->DIRview().$photo->getOneResolution('max').'/'.$photo['name'].'" 
                        alt="'.$alt.'"
                        title="'.$title.'"
                    >
                </a>
              </div>';
    }
    ?>
</div>