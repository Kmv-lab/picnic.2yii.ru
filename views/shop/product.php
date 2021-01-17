<?php

Yii::$app->params['prod_name'] = $product->name;

?>

<div class="row product-page">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="image">
            <?= (isset($photos) && !empty($photos)) ? \app\widgets\PhotosView::widget(['photosNames' => $photos, 'photoSeoAlt' => $product->name, 'photoSeoTitle' => $product->name]) : ''?>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="info">
            <form method="post" class="ms2_form msoptionsprice-product">
                <!--<input type="hidden" name="id" value="176">
                <input type="hidden" name="count" value="1">
                <input type="hidden" name="options" value="[]">
                <input type="hidden" id="input_material" name="options[material]" value="Чугунный">
                <input type="hidden" id="input_kazan_country" name="options[kazan_country]" value="Узбекистан г. Наманган">
                <input type="hidden" id="input_kazan_material" name="options[kazan_material]" value="Чугун, крышка алюминий">
                <input type="hidden" id="input_kazan_krishka" name="options[kazan_krishka]" value="Да">
                <input type="hidden" id="input_kazan_person" name="options[kazan_person]" value="До 14">
                <input type="hidden" id="input_kazan_ves" name="options[kazan_ves]" value="6 кг.">
                <input type="hidden" id="input_kazan_d-outer" name="options[kazan_d-outer]" value="320">
                <input type="hidden" id="input_kazan_d-iner" name="options[kazan_d-iner]" value="295">
                <input type="hidden" id="input_kazan_gabarit" name="options[kazan_gabarit]" value="380">
                <input type="hidden" id="input_kazan_height" name="options[kazan_height]" value="140">
                <input type="hidden" id="input_kazan_t-sten" name="options[kazan_t-sten]" value="6">
                <input type="hidden" id="input_kazan_objom" name="options[kazan_objom]" value="10">-->


                <div id="variations">
                    <? foreach ($variations as $variation){?>
                        <div class="variation" id="variation-<?=$variation['id']?>">
                            <div class="prices">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="price">
                                            <span class=""><?=$variation['price']?></span> <span>руб.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="options">
                            </div>
                            <div class="infotable">
                                <table>
                                    <tbody>
                                    <? foreach ($variation['attr'] as $attribute){
                                        if($attribute['value']){?>
                                            <tr>
                                                <td><?=$attribute['name']?>:</td>
                                                <td id="attrId-<?=$attribute['id']?>" class="msoptionsprice-material msoptionsprice-176">
                                                    <?=$attribute['value']?> <?=$attribute['unit']?>
                                                </td>
                                            </tr>
                                        <?}
                                    }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?}?>
                </div>

                <div class="modifications" style="<?= count($variations)>1 ? '' : 'display:none'?>">
                    <?
                    $i = 0;
                    $firstVar = 0;
                    foreach ($variations as $variation){
                        $firstVar = !$firstVar ? $variation['id'] : $firstVar;
                        ?>
                        <div class="mod-item" data-price="<?=$variation['price']?>" data-options="">
                            <input id="var-<?=$variation['id']?>" type="radio" value="" name="mods" <?= $i == 0 ? "checked=\"checked\"" : ''?>>
                            <label class="input-parent" for="var-<?=$variation['id']?>"><?=$variation['name']?></label>
                        </div>
                        <?
                        $i++;
                    }?>
                </div>


                <div class="buttons">
                    <div class="row">
                        <div class="col-md-6">
                            <a  class="btn btn-light oneclick-link"><i class="fa fa-mouse-pointer"></i> Купить в 1 клик</a>
                        </div>
                        <div class="col-md-6">
                            <button data-product="<?=$product->id.'-'.$firstVar?>" name="ms2_action" value="cart/add" class="btn btn-bright product-put-basket"><i class = "fa fa-shopping-cart"></i> В корзину</button>
                        </div>
                    </div>
                </div>
            </form>
        </div><!--/.info-->
    </div>

    <div class="text col-xs-12">
        <p style="text-align: center;"><strong><?=$product->description?></strong></p>
    </div>
</div>