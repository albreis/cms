<?php 
namespace albreis\cms\helpers;

class CMSHelper extends CMS  {
	//This CMSHelper class is for alias of CMS class
	
	
    //alias of echoSelect2Mult
    public function ES2M($values, $table, $id, $name) {
        return CMS::echoSelect2Mult($values, $table, $id, $name);
    }
}
