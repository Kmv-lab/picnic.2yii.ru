<?php

?>

<div class="section catalog">
    <div class="section-title">Наша продукция</div>
    <div class="container">
        <div class="row row-hr row-hr-full">
            <?
            if (isset($categories)) {
                foreach ($categories as $category) { ?>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href="<?= '/shop/' . $category['alias'] ?>" class="block-link"
                           title="<?= $category['short_desc'] ?>">
                            <div class="cat-item"
                                 style="background-image:url(<?= $category->DIRview() . $category->getOneResolution('min') . '/' . $category->img ?>);">
                                <div class="overlay">
                                    <svg width="370.0000000000001" height="220" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:svg="http://www.w3.org/2000/svg">
                                        <g>
                                            <path stroke="#000000" id="svg_22"
                                                  d="m-6.33333,-1.33333l1.34082,246l254.75491,0c0,0 -209.16718,-160 -1.34082,-246c207.82636,-86 -229.27942,-40 -229.27942,-40c0,0 -25.47549,40 -25.47549,40z"
                                                  fill-opacity="0.85"
                                                  stroke-dasharray="null" stroke-width="0" fill="#000000"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="cat-inner">
                                    <h2><?= $category['name'] ?></h2>
                                    <p class="introtext"><?= $category['short_desc'] ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?
                }
            }
            ?>

        </div><!--/.row-->
    </div><!--/.container-->
</div>
