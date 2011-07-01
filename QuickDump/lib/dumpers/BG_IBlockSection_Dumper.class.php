<?php
class BG_IBlockSection_Dumper extends BG_QuickDumper {
	
	public function checkRequirements(){
		return CModule::IncludeModule('iblock');
	}
		
	public function dump( $iElementID ){
		$aElement = CIBlockSection::GetByID( $iElementID )->Fetch();
		if( !$aElement ) return 'Элемент не найден!';
		ob_start();
		var_dump( $aElement );
		$sResult = ob_get_contents();
		ob_end_clean();
		return $sResult;
	}
	
}
?>