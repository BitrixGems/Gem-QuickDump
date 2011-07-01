<?php
/**
 * Подключаем jQuery :)
 *
 * @author		Vladimir Savenkov <iVariable@gmail.com>
 *
 */
class BitrixGem_QuickDump extends BaseBitrixGem{

	protected $aGemInfo = array(
		'GEM'			=> 'QuickDump',
		'AUTHOR'		=> 'Владимир Савенков',
		'AUTHOR_LINK'	=> 'http://bitrixgems.ru',
		'DATE'			=> '26.04.2011',
		'VERSION'		=> '0.1',
		'NAME' 			=> 'QuickDump',
		'DESCRIPTION' 	=> "Оболочка для вывода дампа необходимой сущности Битрикс в административной части сайта",
		'DESCRIPTION_FULL' => '',
		'CHANGELOG'		=> 'Релизная версия',
		'REQUIREMENTS'	=> '',
	);
	
	protected $aLoadedDumpers = array();
	
	public function initGem(){
		if( defined( 'ADMIN_SECTION' ) ){
			AddEventHandler(
					'main',
					'OnProlog',
					array( $this , 'initDumper')
			);
		}
	}
	
	public function processAjaxRequest( $aOptions ){
		if( $aOptions['type'] == 'undefined' ) $aOptions['type'] = 'IBlockElement';
		if( !isset( $this->aLoadedDumpers[ $aOptions['type'] ] ) ) return 'Дампер "'.$aOptions['type'].'" не найден!';
		$sResult = '<h2>'.$aOptions['type'].': '.$aOptions['id'].'</h2>';
		$sResult .= $this->aLoadedDumpers[ $aOptions['type'] ]->dump( $aOptions['id'] );
		return $sResult;
	}
	
	public function initDumper(){
		require_once( 'lib/general/BG_QuickDumper.class.php' );
		$this->loadDumpers();
		global $APPLICATION;
		$APPLICATION->AddHeadScript('/bitrix/js/iv.bitrixgems/QuickDump/quickDump.gem.js');
		$APPLICATION->AddHeadScript('/bitrix/js/iv.bitrixgems/QuickDump/shortcut.js');
		
		$APPLICATION->AddHeadString(
				'<style type="text/css">
				.bitrixgems_quickDump { position:absolute !important; }
				.bitrixgems_quickDump .bitrixgems_quickDump_inner { width:800px !important;}
				</style>
				<script type="text/javascript">
				/*BX_DEBUG_INFO*/
				if( typeof jQuery != "undefined" ){
					jQuery(function(){
						window.quickDumpers = '.json_encode( array_keys( $this->aLoadedDumpers ) ).';
						jQuery("#bx-panel-admin-toolbar-inner").append(\'<span class="bx-panel-admin-button-separator"></span><a class="bx-panel-admin-button bitrixgems_quickDump_trigger" hidefocus="true" href="#"><span class="bx-panel-admin-button-text">Dump</span></a>\');
						jQuery(".bitrixgems_quickDump_trigger").click(function(){
							BitrixGem_quickDump_toggleSwitch(this);
							jQuery(".bitrixgems_quickDump_search").focus();
						});
						shortcut.add(
							"Shift+D",
							function(){
								jQuery(".bitrixgems_quickDump_trigger").click();
							},
							{disable_in_input: true}
						);
					})
				}
				</script>
				'
		);
		CAjax::Init(); //для jsAjaxUtil
	}
	
	protected function loadDumpers(){
		$aFoundDumpers = glob( dirname(__FILE__).'/lib/dumpers/BG_*_Dumper.class.php' );
		foreach( $aFoundDumpers as $sDumperFile ){
			$sClassName = str_replace( '.class.php','', basename($sDumperFile) );
			$sDumperName = substr( substr( $sClassName, 3 ), 0, -7);
			include_once( $sDumperFile );
			if( !class_exists( $sClassName ) ) continue;
			$oDumper = new $sClassName();
			if( !is_a( $oDumper, 'BG_QuickDumper' ) ) continue;
			if( !$oDumper->checkRequirements() ) continue;
			$this->aLoadedDumpers[ $sDumperName ] = $oDumper;
		}
	}
	
}