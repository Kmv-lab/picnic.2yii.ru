<?php


?>


<tr class="attr-elem" is-new="<?=$model ? 0 : 1?>" <?=$model ? 'id-in-db="'.$model->id.'"' : ''?>>
    <td class=""><input name="name-attr" type="text" class="" value="<?=$model ? $model->name : ''?>"></td>
    <td class=""><input name="unit-attr" type="text" class="" value="<?=$model ? $model->unit : ''?>"></td>
    <td class="">
        <label>
            <input name="for_filter-attr" type="checkbox" class="" <?=!$model ? '' : ( $model->for_filter ? 'checked' : '')?>>В фильтр
        </label>
        <button class="btn-save-attr btn-xs btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span></button>
        <button class="btn-delete-attr btn-xs btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
    </td>
</tr>
