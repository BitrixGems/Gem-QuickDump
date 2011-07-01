<?php
class BG_IBlockElement_Dumper extends BG_QuickDumper {
	
	public function checkRequirements(){
		return CModule::IncludeModule('iblock');
	}
		
	public function dump( $iElementID ){
		$oElement = CIBlockElement::GetByID( $iElementID )->GetNextElement();
		if( !$oElement ) return 'Элемент не найден!';
		$aElement = $oElement->GetFields();
		$aElement['PROPERTIES'] = $oElement->GetProperties();
		ob_start();
		var_dump( $aElement );
		$sResult = ob_get_contents();
		ob_end_clean();
		return $sResult;
	}
	
}
?>