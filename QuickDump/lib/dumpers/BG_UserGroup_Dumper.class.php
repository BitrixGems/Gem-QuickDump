<?php
class BG_UserGroup_Dumper extends BG_QuickDumper {
	
	public function checkRequirements(){
		return true;
	}
		
	public function dump( $iElementID ){
		$aElement = CGroup::GetByID( $iElementID )->Fetch();
		if( !$aElement ) return 'Элемент не найден!';
		ob_start();
		var_dump( $aElement );
		$sResult = ob_get_contents();
		ob_end_clean();
		return $sResult;
	}
	
}
?>