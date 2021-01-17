<?php

?>

<div class="block-variation container" is-new="<?=$model ? '0' : '1'?>" id-var="<?=$model ? $model['id'] : ''?>">
    <div class="row">
        <div class="col-xs-5">
            <label for="name-var">Название вариации</label>
            <input type="text" name="name-var" value="<?=$model ? $model['name'] : ''?>">
            <span class="error-text name-error"></span>
        </div>
        <div class="col-xs-5">
            <label for="price-var">Цена</label>
            <input type="number" name="price-var" value="<?=$model ? $model['price'] : ''?>">
        </div>
        <div class="col-xs-2">
            <label for="is-active-var">ВКЛ\ВЫКЛ</label>
            <input class="qiiq" type="checkbox" name="is-active-var" <?=!$model ? '' : ( $model['is_active'] ? 'checked' : '')?>>
        </div>
    </div>

    <div class="attributes-elems row">
        <?php
        if($model){
            foreach ($model['attr'] as $attribute){?>
                <div class="col-xs-4">
                    <label for="attr-<?=$attribute['id_attr']?>"><?=$attribute['name']?></label>
                    <input
                            id-prod-var-attr="<?=$attribute['id_val']?>"
                            type="text"
                            value="<?=$attribute['value']?>"
                            name="attr-<?=$attribute['id_attr']?>"
                    ><?=$attribute['unit']?>
                </div>
            <?}
        }elseif($attributes){
            foreach ($attributes as $attribute){?>
                <div class="col-xs-4">
                    <label for="attr-<?=$attribute->id?>"><?=$attribute->name?></label>
                    <input type="text" name="attr-<?=$attribute->id?>"><?=$attribute->unit?>
                </div>
            <?}
        }?>
    </div>

    <div class="row buttons">
        <div class="col-xs-2 col-xs-offset-5">
            <button class="btn-save-var btn-xs btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span></button>
            <button class="btn-delete-var btn-xs btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
        </div>
    </div>
    <!--<div class="attributes-variation-product">

    </div>-->
</div>
