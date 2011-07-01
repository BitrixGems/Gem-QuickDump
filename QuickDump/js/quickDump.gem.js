function ShowAjaxWaitAnimation(selector){
    var elem = $(selector).get(0);
    if(!elem) {
	return;
    }
    jsAjaxUtil.ShowLocalWaitWindow(elem.id, elem, true);
}

function HideAjaxWaitAnimation(selector){
    var elem = $(selector).get(0);
    if(!elem) {
	return;
    }
    jsAjaxUtil.CloseLocalWaitWindow(elem.id, elem);
}

function BitrixGem_quickDump_toggleSwitch( elem ){
	if( $('.bitrixgems_quickDump').length > 0 ){
		$('.bitrixgems_quickDump').remove();
	}else{
		var sDumpersSelector = '';
		for( i in window.quickDumpers ){
			if( window.quickDumpers.hasOwnProperty( i ) ){
				sDumpersSelector += '<input id="bg_dump_type_selector_'+window.quickDumpers[i]+'" type="radio" name="type" class="i-dump-type" value="'+window.quickDumpers[i]+'" /> <label for="bg_dump_type_selector_'+window.quickDumpers[i]+'">'+window.quickDumpers[i]+'</label> ';
			}
		}
		var offset = $(elem).offset();
		$('body').after(
			'<div class="bx-core-window bx-core-dialog bitrixgems_quickDump" style="left: '+400+'px !important; top: '+(offset.top+30)+'px !important ;">\
				<form method="get" action="" id="quick-search-form">\
				<div class="dialog-center"><div class="bitrixgems_quickDump_inner bx-core-dialog-content"><div class="bx-core-dialog-head"><div class="bx-core-dialog-head-content head-block">\
					Идентификатор элемента: <input type="text" name="bitrixgems_quickDump_search" class="bitrixgems_quickDump_search"><input type="submit" class="bitrixgems_quickDump_search_search" value="Dump!"/>\
					<br />'+sDumpersSelector+'<br /><br />По умолчанию - "IBlockElement"\
				</div></div>\
				<div class=" bitrixgems_quickDump_result bx-core-dialog-content"></div>\
				</div></div>\
				<div class="dialog-head"><div class="l"><div class="r"><div class="c"><span>Получить дамп сущности</span></div></div></div></div>\
				<div class="dialog-head-icons"><a class="bx-icon-close" title="Закрыть" onclick="$(\'.bitrixgems_quickDump\').remove();"></a></div>\
				<div class="dialog-foot"><div class="l"><div class="r"><div class="c"><img height="1" border="0" width="90%" style="position: absolute; top: 0pt; left: 0pt;" src="/bitrix/js/main/core/images/line.png"></div></div></div></div>\
				</form>\
			</div>'
		);
		$('#quick-search-form').submit(function(e){
			e.preventDefault();
			e.stopPropagation();
			var id = $('.bitrixgems_quickDump_search').val();
			if( $.trim( id ) == '' ) return;
			$('.bitrixgems_quickDump_result').hide();
			ShowAjaxWaitAnimation('.bitrixgems_quickDump');
			$('.bitrixgems_quickDump_result')
			    .load(
				'/bitrix/admin/bitrixgems_simpleresponder.php?gem=QuickDump&AJAXREQUEST=Y&id=' + encodeURI(id)+'&type='+encodeURI( $('.i-dump-type:checked').val() ),
				function(){
				    HideAjaxWaitAnimation('.bitrixgems_quickDump');
				    $(".bitrixgems_quickDump_result").show()
				}
			    );
		})

	}

}
