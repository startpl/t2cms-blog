(function() {
'use strict';

$('#group-controls > button').click(function(){
    const selected = $("#blog-grid").yiiGridView("getSelectedRows");
    
    switch($(this).data('type')){
        case 'publish':
            changeStatus({id: selected, status: true});
            break;
        case 'delete':
            deletePost(selected);
            break;
    }
});

$('#blog-grid').change(function(event){
    if($(event.target).hasClass('change-status')){
        const target = $(event.target);
        let id   = target.attr('name').match(/status\[(\d+)\]/);
        let data = {'id': [id[1]], 'status': target.prop('checked')};
        
        changeStatus(data);
    }
    else if($(event.target).attr('name') == 'selection[]'){
        if($(event.target).prop('checked') && !$(event.target).closest('thead').length){
            $(event.target).parent().parent().addClass("danger");
        }
        else{
            $(event.target).parent().parent().removeClass("danger");
        }
        
        let keys = $("#blog-grid").yiiGridView("getSelectedRows");
        
        if(keys.length){
            $('#group-controls').show();
        }
        else{
            $('#group-controls').hide();
        }
    }
});

try{
$("#blog-grid .categories, #blog-grid .categories_wr").sortable({
    placeholder: "ui-state-highlight",
    forcePlaceholderSize: true,
    forceHelperSize: true,
    axis: "y",
    scrollSensitivity: 10,
    update: function( event, ui ) {
        saveSortable($(this).sortable('toArray',{attribute: 'data-id'}));
    }
});
$("#blog-grid .categories, #blog-grid .categories_wr").disableSelection();

$("#blog-grid .categories, #blog-grid .categories_wr").on( "sortstart", function( event, ui ) {
    ui.placeholder.height(ui.item.outerHeight());
} );
}
catch(error){
    console.log(error);
}

function deletePost(data)
{   
    if(!confirm(i18n.delete_confirm)){
        return false;
    }
    
    let formData = new FormData();
    formData.set('data', JSON.stringify(data));
    
    $.ajax({
        type: "POST",
        url: url.delete,
        data: formData,
        dataType: 'json',
        cache: false,
        processData: false,
        contentType: false,
        success: function(response){
        },
        error: function(response){
            console.error(response);
        }
    });
}

function changeStatus(data)
{   
    let formData = new FormData();
    formData.set('data', JSON.stringify(data));
    
    $.ajax({
        type: "POST",
        url: url.status,
        data: formData,
        dataType: 'json',
        cache: false,
        processData: false,
        contentType: false,
        success: function(response){
            if(data.status){
                for(let id of data.id){
                    $('#status_'+id).prop('checked', true);
                }
            }
        },
        error: function(response){
            console.error(response);
        }
    });
}

function saveSortable(sort){
    let data = new FormData();
    data.append('sort', JSON.stringify(sort));

    $.ajax({
        type: "POST",
        url: url.sort,
        data: data,
        dataType: 'json',
        cache: false,
        processData: false,
        contentType: false,
        success: function(response){
            console.log(response);
        },
        error: function(response){
            console.error(response);
        }
    });
}

$('#btn-save-top').click(function(){
    $('#btn-save-post').click();
});
    
$('#section_tabs > button').click(function(){
    let section = $('#'+$(this).data('section'));

    $('.blog-edit > form > .active.section').removeClass('active');
    section.addClass('active');

    $('#section_tabs > button.active').removeClass('active');
    $(this).addClass('active');
});

$('#field-name input').change(function(){
    if($('#field-title input').val() == ''){
        $('#field-title input').val($(this).val());
    }
        
    if($('#field-og_title input').val() == ''){
        $('#field-og_title input').val($(this).val());
    }
        
    if($('#field-url input').val() == ''){
        $('#field-url input').val(nameToUrl($(this).val()));
    }
});
        
$('#url-autofill').click(function(){
    let name = $('#field-name input').val();
        
    $('#field-url input').val(nameToUrl(name));
});

$('#blog-form').on("beforeValidate", function (event) {
    $('#form-errors').html('');
});

$('#blog-form').on("afterValidate", function (event, messages, errorAttributes) {
    console.log(errorAttributes);
    $('#section_tabs button').each(function(){
        let context = $(this);
        let fn = function(){
            if(context.hasClass('error') && !$('#'+context.data('section')).find('.has-error').length){
                context.removeClass('error');
            }
        };
        setTimeout(fn);
    });
    
   
    if(!errorAttributes.length){
        $('#form-errors').html('');
    }
    else{
        for(let attribute of errorAttributes){
            let section = $(attribute.input).closest('.section').attr('id');
            $('#section_tabs button[data-section="'+section+'"]').addClass('error');
        }
        $('#form-errors').html('<div class="alert-danger alert fade in"><i class="icon fa fa-ban"></i>'+error_message+'</div>');
    }
});

$("#blog-grid-dd").on("click", function(e){
    e.preventDefault()
    var keys = $("#blog-grid").yiiGridView("getSelectedRows");
    $.ajax({
      url: "'. \yii\helpers\Url::toRoute('delete') .'",
      type: "POST",
      data: {id: keys},
      success: function(){
         alert("yes")
      }
    })
});

function nameToUrl(name){
    let url = name.replace(/[\s]+/g, '-').toLowerCase();
    
    url = transliterate(url);
        
    return url.replace(/[^+a-z0-9\-\_]/g, '');
}
        
var a = {"Ё":"YO","Й":"I","Ц":"TS","У":"U","К":"K","Е":"E","Н":"N","Г":"G","Ш":"SH","Щ":"SCH","З":"Z","Х":"H","Ъ":"'","ё":"yo","й":"i","ц":"ts","у":"u","к":"k","е":"e","н":"n","г":"g","ш":"sh","щ":"sch","з":"z","х":"h","ъ":"'","Ф":"F","Ы":"I","В":"V","А":"a","П":"P","Р":"R","О":"O","Л":"L","Д":"D","Ж":"ZH","Э":"E","ф":"f","ы":"i","в":"v","а":"a","п":"p","р":"r","о":"o","л":"l","д":"d","ж":"zh","э":"e","Я":"Ya","Ч":"CH","С":"S","М":"M","И":"I","Т":"T","Ь":"'","Б":"B","Ю":"YU","я":"ya","ч":"ch","с":"s","м":"m","и":"i","т":"t","ь":"'","б":"b","ю":"yu"};

function transliterate(word){
  return word.split('').map(function (char) { 
    return a[char] || char; 
  }).join("");
}
})();