<?php


//БЛОК ФИЛЬТРАЦИИ
use app\widgets\Products; ?>

<div class="category-text">
    <?=$category->description;?>
</div>

<div class="row msearch2 category" id="category-<?=$category->id?>">
    <!--БЛОК ФИЛЬТРАЦИИ-->
    <? if($category->filter){?>
        <div class="col-md-3">

            <aside class="sidebar">
                <? if(Yii::$app->params['is_mobile']){?>
                <a role="button" class="filters-toggle" onclick="$('.filters').slideToggle();">
                    <span class="linktext">Фильтры</span>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </a>
                <div class="filters" style="display: none">
                <? }else{ ?>
                    <div class="filters">
                <?}?>

                    <form action="" method="post" id="filters">

                        <!--БЛОК ФИЛЬТРА ЦЕНЫ-->
                        <div class="filter-block slider-price">
                            <fieldset id="mse2_ms|price">
                                <h4 class="filter_title">Цена</h4>
                                <div class="filter-body">
                                    <div class="mse2_number_slider"></div>
                                    <div class="mse2_number_inputs row">
                                        <div class="form-group col-md-6">
                                            <label for="min-price">От
                                                <input type="text" name="min-price" id="min-price" value="<?=Yii::$app->params['cat_prices']['min']?>" class="form-control input-sm" />
                                            </label>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="max-price">До
                                                <input type="text" name="max-price" id="max-price" value="<?=Yii::$app->params['cat_prices']['max']?>" class="form-control input-sm" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <? foreach ($filterData as $idAttr => $attrData){?>
                            <div class="filter-block">
                                <fieldset id="<?="idCatVal".$idAttr?>" class="filter-group">
                                    <h4 class="filter_title"><?=$attrData['name']?></h4>
                                    <div class="filter-body">
                                        <? foreach ($attrData['values'] as $value => $count){?>
                                            <label for="<?="idCatVal".$idAttr."-".$value?>" class="">
                                                <input type="checkbox" name="<?="idCatVal".$idAttr."-".$value?>" id="<?="idCatVal".$idAttr."-".$value?>" value="<?=$value?>"  /><?=$value.$attrData['unit']?><sup><?=$count?></sup>
                                            </label><br/>
                                        <?}?>
                                    </div>
                                </fieldset>
                            </div>
                        <?}?>

                        <br/>
                        <button type="reset" class="btn btn-default hidden">Сбросить</button>
                        <button type="submit" class="btn btn-success pull-right hidden">Принять</button>
                        <div class="clearfix"></div>

                    </form>
                </div>
                <div class="info-blocks">
                    <div class="item">
                        <img src = "../files/img/infos/prvo.jpg" alt = "">
                    </div>
                </div>
            </aside>
        </div>
    <?}?>


    <!--БЛОК ТОВАРОВ-->
    <div class="col-md-<?= $category->filter ? 9 : 12?>">
        <div class="product-list">
            <div class="row">

                <? if(!Yii::$app->params['is_mobile']){?>
                    <div class="col-xs-12">
                        <div class="sort">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="ps-select" id="mse2_sort">
                                        <label>Сортировать: </label>
                                        <a data-sort-of="popular" data-method="ASC" class="active sort-button">По популярности</a>
                                        <a data-sort-of="price" data-method="DESC" class="sort-button">По цене</a>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <label>Показывать: </label>
                                    <select name="products-limit" id="prosucts-limit">
                                        <option value = "15" selected>15</option>
                                        <option value = "30" >30</option>
                                        <option value = "60" >60</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                <?}
                else{?>
                    <select style="display: none" name="products-limit" id="prosucts-limit">
                        <option value = "15" selected>15</option>
                        <option value = "30" >30</option>
                        <option value = "60" >60</option>
                    </select>
                <?}?>


                <div class="col-xs-12 products-block">

                    <?=Products::widget(['category' => $category->id, 'products' => Yii::$app->params['products']])?>
                </div>

                <div class="shampur-promo">
                    <img src = "../assets/img/shampur_promo.jpg" alt = "">
                </div>

                <div class="col-xs-12">
                    <nav aria-label="Page navigation" class = "text-center mse2_pagination">
                        <div class="pagination"><ul class="pagination"></ul></div>
                    </nav>
                </div>
            </div><!--/.row-->
        </div><!--/.product-list-->
    </div><!--/.right column-->
</div>