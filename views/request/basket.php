<?php
//vd($products);
?>

<?//=$product['photo']->DIRview().$product['photo']->getOneResolution('min').'/'.$product['photo']->name?>

<div class="content-block">
    <div id="msCart">
        <? if(!empty($products)){?>
            <div class="table-noresponsive">
                <table class="table">
                    <tbody>
                        <tr class="header">
                            <th class="image" style="text-align:left;">Фотография</th>
                            <th class="title">Наименование</th>
                            <th class="count">Количество</th>
                            <th class="price">Цена</th>
                            <th class="remove">Удалить</th>
                        </tr>

                        <?php
                        $fullPrice = 0;
                        $counts = 0;
                        foreach ($products as $product){?>
                            <tr class="product-in-basket" prod-id="<?=$product['prodId']?>" var-id="<?=$product['varId']?>">
                                <? if (isset($product['photo'])){?>
                                <td class="image" style="width:20%">
                                    <img width="250"
                                         loading="lazy"
                                         src="<?=$product['photo']->DIRview().$product['photo']->getOneResolution('min').'/'.$product['photo']->name?>"
                                         alt="<?=$product['name']?>"
                                         title="<?=$product['name']?>">
                                </td>
                                <?}?>
                                <td class="title" style="width:50%">
                                    <a href="<?='/shop/'.$product['aliases']['catAlias'].'/'.$product['aliases']['prodAlias']?>"><?=$product['name']?></a>
                                    <div class="cart-options">
                                        <? foreach ($product['attributes'] as $attribute){?>
                                            <p>
                                                <span><?=$attribute['name']?> :</span>
                                                <?=$attribute['value']?> <?=$attribute['unit']?>
                                            </p>
                                        <?}?>
                                    </div>
                                </td>
                                <td class="count" style="width:5%">
                                    <form method="post" class="ms2_form form-inline" role="form">
                                        <div class="form-group">
                                            <input type="number" name="count" value="<?=$product['count']?>" class="input-sm form-control">
                                            <span class="hidden-xs">шт.</span>
                                        </div>
                                    </form>
                                </td>
                                <td class="price" price-for-one="<?=$product['price']?>" style="width:20%">
                                    <span><?=$product['price']*$product['count']?></span> руб.
                                </td>
                                <td class="remove" style="width:5%">
                                    <form method="post" class="ms2_form">
                                        <input type="hidden" name="key" value="160ea96f11be64874793fb4ece7746a8">
                                        <button class="btn btn-default delete-product" type="submit" name="ms2_action" value="cart/remove">
                                            x
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?
                            $fullPrice += $product['price']*$product['count'];
                            $counts += $product['count'];
                        }?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-12 text-right">
                    <div class="itogo">
                        <b>Итого:</b> <span class="total_count"><?=$counts?></span> шт /
                        <span class="total_cost"><?=$fullPrice?></span>
                        руб.
                    </div>
                </div>
            </div>
        <?} else{?>
            Ваша корзина пуста
        <?}?>
    </div>
</div>

<? if(!empty($products)){?>
    <div class="content-block">
        <div class="order-thanks">
            После заполнения формы заказа с Вами свяжется наш менеджер для уточнения деталей оплаты и доставки. Спасибо!
        </div>
        <form class="form-horizontal basket-form">
            <div class="row">
                <div class="col-md-12">
                    <h4>Данные получателя:</h4>
                    <div class="form-group input-parent required">
                        <label class="col-md-4 control-label" for="phone">
                            <span class="required-star">*</span>Телефон</label>
                        <div class="col-sm-6">
                            <input type="text" id="phone" placeholder="Телефон" name="phone" value="" class="form-control input-phone" required="">
                        </div>
                    </div>
                    <div class="form-group input-parent">
                        <label class="col-md-4 control-label" for="receiver">
                            <span class="required-star">*</span>Получатель</label>
                        <div class="col-sm-6">
                            <input type="text" id="receiver" placeholder="Получатель" name="receiver" value="" class="form-control" required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="itogo-order text-right">
                <div class="thd">
                    <h3>Итого:<span class="total_cost"><?=$fullPrice?></span>руб.</h3>
                    <button type="submit" name="ms2_action" value="order/submit" class="btn btn-bright ms2_link">Сделать заказ!</button>
                </div>
            </div>
        </form>
    </div>
<?}?>