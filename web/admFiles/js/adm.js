
$(document).ready(function() {
    CKFinder.setupCKEditor( null, '/admFiles/ckfinder/' );
    // CodeMirror

    $('.deleteItem').click(function(){
        if (confirm('Точно удалить?'))
            return true;
        else
            return false;
    });


    function refreshPhoto(id) {
        let parent = $('div.block-img-prod[data-id='+id+']');
        $(parent).find('img').each(function () {
            let imgSrc = $(this).attr('src')+'1';
            let imgClass = 'img-thumbnail img-responsive';
            let imgData = $(this).attr('data-ratio');
            let imgParent = $(this).parent();

            let newImg = '<img class="'+imgClass+'" src="'+imgSrc+'" alt="" data-ratio="'+imgData+'">';

            console.log(newImg);

            $(this).remove();

            $(imgParent).prepend(newImg);

            return true;
        });
    }

    function textAreaToCodeMirror() {
        if($('textarea').is('.codemirror'))
        {
            $(function(){
                var tAreas = document.querySelectorAll('.codemirror');
                for (var i = 0; i < tAreas.length; i++) {
                    CodeMirror.fromTextArea(tAreas[i], {
                        //tabMode: 'indent',
                        matchTags: {bothTags: true},
                        lineNumbers: true,               // показывать номера строк
                        matchBrackets: true,             // подсвечивать парные скобки
                        autoCloseTags: true,
                        styleActiveLine: true,
                        indentUnit: 4,                    // размер табуляции
                        theme: 'eclipse',
                        matchBrackets: true,
                        lineWrapping: true });
                }
            });
        }
    }

    var res;

    $(document).on('click', '.btn-danger', function(e){
        res = confirm('Вы уверены?');

        if(res) return true;

        return false;
    });

    textAreaToCodeMirror();

    $('.btn-delete-block').click(function(event){
        if (confirm('Точно удалить?')){
            console.log("Удаление перед аякса");
            return true;
        }

        else{
            event.preventDefault();
            console.log("Отмена удаление перед аякса");
        }

    });

    $(document).ajaxComplete(function() {

        $( ".ajax-form" ).each(function() {
            $(this).removeAttr('action');
        });

        changeBackgroundColor();
        textAreaToCodeMirror();
        if ($('#main_page-content_wysiwyg').length){
            CKEDITOR.replace("main_page-content_wysiwyg");
        }
        if ($('textarea.ckeditor').length){
            $('textarea.ckeditor').each( function(){
                CKEDITOR.replace($(this).attr('id'));
            });
        }
        $('input:checkbox').on('change', function(){
            $(this).parents(".elem-adm-block").toggleClass('is-active');
        });
        $('.btn-delete-block').click(function(event){
            if (confirm('Точно удалить?')){
                console.log("Удаление после аякса");
                return true;
            }
            else{
                console.log("Отмена удаление после аякса");
                event.preventDefault();
            }

        });
    });

    changeBackgroundColor();

    function changeBackgroundColor(){
        $.each($('input:checkbox'), function(){
            if($(this).is(':checked')){
                $(this).parents(".elem-adm-block").toggleClass('is-active');
            }
        });
    }

    $('.new-elem-exc').click( function (){
        $('.new-elem-craete').animate({'height': 'toggle'});
    });

    $('input:checkbox').on('change', function(){
        $(this).parents(".elem-adm-block").toggleClass('is-active');
    });

    function datepickerAjax(wrapper){ //---------------------функция для добавления календаря новым полям
        if(wrapper.find( '.datepicker' ).length > 0){

            wrapper.find( '.datepicker' ).datepicker({
                //	showOn:          'button',
                //	buttonImage:     '/css/calendar.png',
                //	buttonImageOnly: false,
                dateFormat:      'dd.mm.yy'
            });

            $.datepicker.regional['ru'] = {
                closeText: 'Закрыть',
                prevText: '&#x3c;Пред',
                nextText: 'След&#x3e;',
                currentText: 'Сегодня',
                monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                    'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                    'Июл','Авг','Сен','Окт','Ноя','Дек'],
                dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
                dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
                dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                isRTL: false
            };
            $.datepicker.setDefaults( $.datepicker.regional[ 'ru' ] );
        }
    }

    datepickerAjax($('body'));

    //---------------------------------------------------------- Дерево разрешений
    if($('div').is('#resolution-treeview')){
        var wrap_resolution = $('#resolution-treeview');
        wrap_resolution.jstree({"core": {
                "themes":{
                    "icons":false,
                    "variant" : "large",
                },
            }});
        wrap_resolution.on('click', '.add_form', function(event){
            $(this).after('<div class="resolution_input">'+
                '<label class="control-label" for="width_'+$(this).data('name')+'">Ширина</label>'+
                '<input class="form-control input-sm width" name="width_'+$(this).data('name')+'" id="width_'+$(this).data('name')+'" type="text" maxlength="4" value="0">'+
                '</div>'+
                '<div class="resolution_input">'+
                '<label class="control-label" for="height_'+$(this).data('name')+'">Длина</label>'+
                '<input class="form-control input-sm height" name="height_'+$(this).data('name')+'" id="heigh_'+$(this).data('name')+'t" type="text" maxlength="4" value="0">'+
                '</div>');
            $(this).removeClass('add_form').addClass('add_resolution');
            event.preventDefault();
            return false;
        });
        wrap_resolution.on('click', '.add_resolution', function(event){
            var parent = $(this).parent();
            var width = parent.find('.width');
            var height = parent.find('.height');
            if(width.val() !== '0' || height.val() !== '0'){
                $.ajax({
                    type: "GET",
                    url: "../add/",
                    dataType: "html",
                    data: 'folder='+$(this).data('name')+'&width='+width.val()+'&height='+height.val(),
                    success: function(html){
                        if (html === "ok"){
                            window.location.href = window.location.href;
                        }else{
                            width.attr('style','border-color: #CD0000;');
                            height.attr('style','border-color: #CD0000;');
                        }
                    }
                });
            }
            event.preventDefault();
            return false;
        });


        wrap_resolution.on('click', 'a.deleteItem', function(){
            document.location.href = $(this).attr('href');
        });
        wrap_resolution.on("change keyup input", 'input', function(){
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9]/g, '');

            }
        });
    }

    //---------------------------------------------------------- Дерево и сортировка страниц
    if($('div').is('#menu-treeview')){
        $('#menu-treeview').jstree({"core": {
                "themes":{
                    "icons":false,
                    "variant" : "large",
                },
            }});
        $( "#menu-treeview ul" ).sortable({
            stop: function(event, ui) {
                data = $( this ).sortable("serialize");
                $.ajax({
                    type: "POST",
                    url: "../sort/",
                    dataType: "json",
                    data: data
                });
            }
        });
        $('#menu-treeview').on('click', 'a', function(){
            if($(this).hasClass('deleteItem')){
                if (confirm('Точно удалить?'))
                    document.location.href = $(this).attr('href');
                else
                    return false;
            }
            document.location.href = $(this).attr('href');
        });
    }

    //---------------------------------------------------------- Шаблончики
    $('.showTpls').click(function(event){
        event.preventDefault();
        var ckId = $(this).attr('ckId');
        var tpls = $('.tpls[ckId="'+ckId+'"]');
        tpls.hasClass('show')    ?   tpls.removeClass('show')    :  tpls.addClass('show');
        $('.snippets[ckId="'+ckId+'"]').removeClass('show');
        $('.galleries[ckId="'+ckId+'"]').removeClass('show');
        return false;
    });

    $('.TplLink').click(function(event){
        event.preventDefault();
        var ckId = $(this).attr('ckId');

        var id = $(this).attr('rel');
        var instance = CKEDITOR.instances[ckId];
        instance.insertHtml($('.TplContent[rel='+id+']').html());
    });
    //---------------------------------------------------------- Сниппеты
    $('.showSnippets').click(function(event){
        event.preventDefault();
        var ckId = $(this).attr('ckId');
        var snippets = $('.snippets[ckId="'+ckId+'"]');
        $('.tpls[ckId="'+ckId+'"]').removeClass('show');
        snippets.hasClass('show')    ?   snippets.removeClass('show') :   snippets.addClass('show');
        $('.galleries[ckId="'+ckId+'"]').removeClass('show');
    });

    $('.snipLink').click(function(event){
        event.preventDefault();
        var ckId = $(this).attr('ckId');
        var snippet = $(this).find('span').html();
        var instance = CKEDITOR.instances[ckId];
        instance.insertHtml(snippet);
    });
    //---------------------------------------------------------- Галереи
    $('.showGalleries').click(function(event){
        event.preventDefault();
        var ckId = $(this).attr('ckId');
        var galleries = $('.galleries[ckId="'+ckId+'"]');
        $('.tpls[ckId="'+ckId+'"]').removeClass('show');
        $('.snippets[ckId="'+ckId+'"]').removeClass('show');
        galleries.hasClass('show')   ?   galleries.removeClass('show')    :   galleries.addClass('show');
    });

    $('.galLink').click(function(event){
        event.preventDefault();

        ckId = $(this).attr('ckId');

        gallery = $(this).find('span').html();
        instance = CKEDITOR.instances[ckId];

        instance.insertHtml(gallery);
    });

    //---------------------------------------------------------- Блоки
    $('.seoLink').click(function(){
        var block = $('.seoBlock');
        if(block.hasClass('show'))
            block.removeClass('show');
        else
            block.addClass('show');
        event.preventDefault();
        return false;
    });

    //---------------------------------------------------------- Транслит алиаса
    $('.translit_source').focusout(function(){
        if ($('.translit_dest').val() == '')
        {
            //new RegExp("[^a-zа-я0-9\_\—]");
            str = $(this).val();
            str = encodeURIComponent(str);

            $.ajax({
                url: "/admi/default/ajaxtranslite/",
                cache: false,
                data: "str="+str,
                success: function(html){
                    console.log('html='+html);
                    $('.translit_dest').val(html);
                }
            });
        }
    });
    //---------------------------------------------------------- Дубляж в поле название в меню
    $('.translit_source').change(function(){
        if ($('#page-page_menu_name').val() == '')
            $('#page-page_menu_name').val($(this).val());
    })

    if($('div').is('#dialog-thumb')){
        var jcrop_api;
        var id;
        function showCoords(c)
        {
            $('#x1').val(c.x);
            $('#y1').val(c.y);
            $('#x2').val(c.x2);
            $('#y2').val(c.y2);
        };
        // фукнция Автоматической упаковки формы любой сложности
        function getRequestBody(oForm) {

            var aParams = new Array();
            var keys = Object.keys(oForm);
            i = 0;
            $.each(oForm, function(){
                var sParam = encodeURIComponent(keys[i]);
                sParam += "=";
                sParam += encodeURIComponent(oForm[keys[i]]);
                aParams.push(sParam);
                i++;
            });
            console.log(aParams);
            return aParams.join("&");
        }
        $('.reset-thumb').click(function(event){
            var $this = $(this);
            $.ajax({
                type: "GET",
                url: $this.attr('href'),
                dataType: "json",
                success: function(html){
                    if (html = 'ok'){
                        window.location.href = window.location.href;
                    }
                }
            });
            event.preventDefault();
            return false;
        });

        //Отправка значений новой миниатюры аяксом на контроллер
        $('#dialog-thumb').on('click','#thumb-ready',function(event){
            x1 = $('#x1').val();
            y1 = $('#y1').val();
            x2 = $('#x2').val();
            y2 = $('#y2').val();

            if (x1 == '' || y1 == '' || x2 == '' || y2 == '' )
            {
                alert('Вы не выбрали область для миниатюры')//console.log(x1+' '+y1+' '+x2+' '+y2);
                event.preventDefault();
                return false;
            }
            //console.log('id='+id+'&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2);
            arrPost = {
                id: id,
                x1: x1,
                y1: y1,
                x2: x2,
                y2: y2,
                r: $('#Photo_ratio_'+id).val(),
            };
            if($('select').is('.is_main_excursion_photo')) {
                arrPost['is_main_excursion_photo'] = true;
            }
            data = getRequestBody(arrPost);

            $.ajax({
                type: "POST",
                url: $( "#dialog-thumb").attr('data-url'),
                dataType: "json",
                data: data,
                //data: 'id='+id+'&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2+'&r='+$('#Photo_ratio').val(),
                success: function(html){
                    if (html = 'ok')
                    {

                        $( "#dialog-thumb" ).modal('hide');

                        if(typeof arrPost.is_main_excursion_photo!== "undefined"){
                            let img = $('img.img-thumbnail');
                            img.attr("src", img.attr("src").split("?")[0] + "?" + Math.random());
                        }
                        else if((arrPost.r != 0) && (typeof arrPost.is_main_excursion_photo === "undefined")){
                            var img = $('[data-id = '+id+']').parents('.image_thumb').find('[data-ratio = '+arrPost.r+']');
                            img.attr("src", img.attr("src").split("?")[0] + "?" + Math.random());

                        }
                        else{
                            refreshPhoto(id);
                            //window.location.href = window.location.href;
                        }

                    }
                }
            });
            return false;
        });


      /*  $( "#dialog-thumb" ).dialog({
            autoOpen: false,
            width: 1200,
            height: 800,
            modal: true,
            //open: function( event, ui ){$("body").css({"overflow":"hidden"});},
            //beforeClose: function( event, ui ) {$("body").css({"overflow":"auto"});}
        }) */

        $('.show-dialog-thumb').click(function(){


            id = $(this).attr('data-id');

            if($('.image_thumb').length > 1){
                let idImage = $(this).attr('data-id');
                id = idImage;
                let dataUrl = $('#dialog-thumb').attr('data-url');

                $('#dialog-thumb').attr('data-url', dataUrl.replace(/id=([0-9]{1,4})&name/, 'id='+idImage+'&name'));

            }

            file_name = $(this).data('file_name');
            path_to_big = $( "#dialog-thumb").attr('data-big');
            html = ''+
                '<img id="target" src = "'+path_to_big+file_name+'" /></br>'+
                '<div style="display:none;">'+
                '    <input type="text" value="" id="x1" name="x1" />'+
                '    <input type="text" value="" id="y1" name="y1" />'+
                '    <input type="text" value="" id="x2" name="x2" />'+
                '    <input type="text" value="" id="y2" name="y2" />'+
                '</div>'+
                '';
            $("#dialog-thumb .modal-body").html(html);
            /*
            img = $('#dialog-thumb img');
            // Дожидаемся загрузки изображения браузером.
            img.load(function(){
                console.log('w='+img.width());
                console.log('h='+img.height());
            return false;

            })*/
            ratio = $('#Photo_ratio_'+id).val();
            if(ratio == '0'){
                ratio = '1200x800';
            }
            ratio = ratio.split('x');
            ratiowid = ratio[0];
            ratiohei = ratio[1]
            $("#dialog-thumb").modal('toggle');
            $('#target').bind("load",function(){
                $('#target').Jcrop({
                    //bgColor: 'red',
                    onChange:   showCoords,
                    onSelect:   showCoords,
                    aspectRatio: ratiowid/ratiohei,
                    boxWidth: 868,
                    boxHeight: 868,
                    trueSize: [
                        document.getElementById('target').naturalWidth,
                        document.getElementById('target').naturalHeight
                    ]
                },function(){
                    jcrop_api = this;
                });//document.getElementById('img12').naturalHeight
            });

            return false;
        })
    }

    $('.select-type').each(function (ss) {
        let strClasses =  $(this).attr('class');
        let num = strClasses.replace('select-type', '').replace('select-type', '').replace('-', '');
        num = parseInt(num);
        let val = $(this).val();
        if(val == 0){// текстовый отзыв
            $('.comment-type-'+num).show();
        }else {// видео отзыв
            $('.comment-type-'+num).hide();
        }
    });

    $( ".select-type" ).change(function() {
        let strClasses =  $(this).attr('class');
        let num = strClasses.replace('select-type', '').replace('select-type', '').replace('-', '');
        num = parseInt(num);
        let val = $(this).val();
        if(val == 0){// текстовый отзыв
            $('.comment-type-'+num).show(400);
        }else {// видео отзыв
            $('.comment-type-'+num).hide(400);
        }
    });

    if($('a.elem-error-validate').length){
        setTimeout(function() {
            $("html,body").animate({scrollTop: $('a.elem-error-validate').offset().top}, 1000);
        }, 600);
    }

    function addEmptyElemAttr(){
        $.ajax({
            url: "/admi/category/ajaxaddattribute",
            success: function(html){
                $(".attributes-elems-block").append(html);
            }

        });
    }

    //---------------------------------------------------------- Проставляет нужные id элементам атрибутов в категориях
    var lastElem;
    function giveIdToElemAttr(responseFromServer){

        lastElem.attr('id-in-db', responseFromServer.id);
    }

    //---------------------------------------------------------- сохранение Атрибутов Категории(отправка их на сервер
    $(document).on('click', '.btn-save-attr', function(e){
        let parent = $(this).parents('.attr-elem');
        lastElem = parent;

        let name = parent.find('input[name ="name-attr"]');
        let unit = parent.find('input[name ="unit-attr"]');
        let forFilter = parent.find('input[name ="for_filter-attr"]').is(':checked') ? 1 : 0;
        let idCat = $('.attributes').attr('data-id-category');
        let isNew = parent.attr('is-new');
        let id = parent.attr('id-in-db');

        unit.removeClass('error');
        name.removeClass('error');

        if(name.val() && idCat){
            parent.attr('is-new', '0');
            if(isNew && id){
                $.ajax({
                    url: "/admi/category/ajaxsaveattr",
                    data: "name="+name.val()+"&unit="+unit.val()+"&forFilter="+forFilter+"&idCat="+idCat+"&isNew="+isNew+"&id="+id,
                    success: function(json){
                        console.log(json);
                        let result = JSON.parse(json);
                        giveIdToElemAttr(result);

                    }
                });
            }else {
                addEmptyElemAttr();

                $.ajax({
                    url: "/admi/category/ajaxsaveattr",
                    data: "name="+name.val()+"&unit="+unit.val()+"&forFilter="+forFilter+"&idCat="+idCat+"&isNew="+isNew,
                    success: function(json){
                        let result = JSON.parse(json);
                        giveIdToElemAttr(result);
                    }
                });
            }

        }else {
            if(!name.val()){
                name.addClass('error');
                //name error
            }
        }

    });

    //---------------------------------------------------------- удаление атрибута категории
    $(document).on('click', '.btn-delete-attr', function(e){

        let parent = $(this).parents('.attr-elem');
        let id = parent.attr('id-in-db');

        if(parent.attr('is-new') == '0'){
            $.ajax({
                url: "/admi/category/ajaxdeleteattr",
                data: "id="+id,
                success: function(json){

                }
            });
            $(this).parents('.attr-elem').remove();
        }
    });

    //---------------------------------------------------------- Сохранение вариации товара и атрибутов этой вариации
    $(document).on('click', '.btn-save-var', function(e){
        let
            parent = $(this).parents('.block-variation'),
            data = {},
            errors = [];

        let
            name = parent.find('input[name ="name-var"]'),
            price = parent.find('input[name ="price-var"]');

        lastElem = parent;

        data['name'] = parent.find('input[name ="name-var"]').val();
        data['price'] = parent.find('input[name ="price-var"]').val();
        data['isActive'] = parent.find('input[type ="checkbox"]').is(':checked');
        data['prodId'] = parent.parents(".variations").attr('data-id-prod');
        data['variationsId'] = parent.attr('id-var');
        data.attr = {};

        name.removeClass('error');
        price.removeClass('error');

        parent.find('.attributes-elems>div>input').each(function () {
            let valId = $(this).attr('id-prod-var-attr');
            data['attr'][$(this).attr('name')] = {
                'value': $(this).val(),
                'valueID': valId
            };
        });

        if(data['name'] && data['price']){
            var requestData = JSON.stringify(data);
        }else{
            if(!data['name']) errors.push(name);
            if(!data['price']) errors.push(price);
        }

        if(errors && errors.length !== 0){
            errors.forEach(function(item, i, arr) {
                item.addClass('error');
            });
            return false;
        }

        $.ajax({
            url: "/admi/products/ajaxsavevar",
            data: "data="+requestData,
            success: function(json){
                let result = JSON.parse(json);
                if(result['errors']){
                    addErrorText(result, lastElem);
                }else{
                    setIdAttrVarProd(result, lastElem);
                }
            }
        });
    });


    //---------------------------------------------------------- Добавление текста ощибок
    function addErrorText(returns, elem) {

        if(returns['errors']['name']){
            $(elem).find('input[name ="name-var"]').parents('div.col-xs-5').find('span.error-text').text(returns['errors']['name'][0]);
        }

    }

    //---------------------------------------------------------- добавление id атрибутам вариации товара
    function setIdAttrVarProd(returns, elem){

        $(elem).find('.error-text').text('');

        $(elem).find('.attributes-elems>div>input').each(function () {

            $(this).attr('id-prod-var-attr', returns['attr'][$(this).attr('name')]);
        });

        setDataForElemOfVariation(returns, elem);

    }

    //---------------------------------------------------------- добавление информации о вариации товара на страницу
    function setDataForElemOfVariation(returns, elem){

        $(elem).attr('is-new', 0);
        $(elem).attr('id-var', returns['id_var']);
    }

    //---------------------------------------------------------- удаление вариации
    $(document).on('click', '.btn-delete-var', function(e){
        if(res){
            //$(this).parents('.block-variation').attr('id-var');

            $.ajax({
                url: "/admi/products/ajaxdeletevar",
                data: "varId="+$(this).parents('.block-variation').attr('id-var'),
                success: function(html){
                    console.log(html);
                    //$(".attributes-elems-block").append(html);
                }
            });

            $(this).parents('.block-variation').remove();
        }
    });

    //---------------------------------------------------------- добавление пустой вариации для заполнения
    $(document).on('click', '.add-empty-var', function(e){
        let catId = $('.variations').attr('data-id-category');
        $.ajax({
            url: "/admi/products/ajaxgetemptyvar",
            data: "categoryId="+catId,
            success: function(html){
                console.log(html);
                $(".attributes-elems-block").append(html);
            }
        });
    })

    //---------------------------------------------------------- Скрытие всплывающего пояснения
    if($('.alert-temporary').length){
        setTimeout(function () {
            $('.alert-temporary').hide(500);
        }, 12000);
    }

    //--------------------------------------------------------------Подсчёт символов в строке.
    $('.panel-body-seo>div input, .panel-body-seo>div textarea').keyup(function(){
        let maxCount = parseInt($(this).attr('max-count'));
        let parent = $(this).parents('.seo-options');
        let remainingChars = maxCount - $(this).val().length;
        if(remainingChars <= 0 ){
            let newVal = $(this).val().substring(0, maxCount);
            $(this).val(newVal);
        }
        remainingChars = maxCount - $(this).val().length;
        $(parent).find('span.badge').html(remainingChars);
    });

});
