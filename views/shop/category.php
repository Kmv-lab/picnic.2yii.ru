<?php

//БЛОК ФИЛЬТРАЦИИ
use app\widgets\Products; ?>



<div class="col-md-9">
    <div class="product-list">
        <div class="row">

            <div class="col-xs-12">
                <div class="sort">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="ps-select" id="mse2_sort">
                                <label>Сортировать: </label>
                                <a href = "../index.html#" data-sort="ms_product|menuindex" data-dir="" data-default="asc" class="active">По популярности</a>
                                <a href = "../index.html#" data-sort="ms|price" data-dir="" data-default="desc">По цене</a>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <label>Показывать: </label>
                            <select name="mse_limit" id="mse2_limit">
                                <option value = "15" selected>15</option>
                                <option value = "30" >30</option>
                                <option value = "60" >60</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <?=Products::widget(['category' => $category->id])?>
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