<?php

?>


<? if($products){?>
    <div id="mse2_results" class="row row-hr row-hr-half">

        <? foreach ($products as $id => $product){
            $alias = '/shop/'.$category->alias.'/'.$product['alias'];
            ?>
            <div class="<?= $prodInRow == 3 ? "col-md-4 col-sm-6 col-xs-12" : "col-md-3 col-sm-6 col-xs-12"?>" id="<?='prod'.$id?>">
                <div class="product ms2_product">
                    <form method="post" class="ms2_form">
                        <? if(!empty($product['photo'])){?>
                            <div class="product-image">
                                <a href="<?=$alias?>">
                                    <img loading="lazy"
                                         src="<?=$product['photo']->DIRview().$product['photo']->getOneResolution("250x180").'/'.$product['photo']->name?>"
                                         alt="<?=$product['prod_name']?>"
                                         title="<?=$product['prod_name']?>">
                                </a>
                            </div>
                        <?}?>
                        <div class="product-info">
                            <h3><a href="<?=$alias?>"><?=$product['prod_name']?></a></h3>
                            <div class="prices">
                                <div class="price"> <?=$product['min_price']?> руб.</div>
                            </div>
                        </div>

                        <div class="button">
                            <button  data-product="<?=$id?>-" name="ms2_action" class="btn-buy btn-left product-put-basket"><i class = "fa fa-shopping-cart"></i> В корзину</button>
                            <a href = "<?=$alias?>" class = "btn-buy btn-right">Подробнее</a>
                        </div>
                    </form>
                </div>
            </div>
        <?}?>

    </div>
<?}?>