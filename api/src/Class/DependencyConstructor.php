<?php

class DependencyConstructor {

    public static function React($page){
        if(PROD || DEVELOP || DEBUG_MODE){
            $script = '<script type="module" src="../template/dist/'.$page.'/'.$page.'.js?v='.VERSION_NUM.'"></script>';
            $style = '<link rel="stylesheet" href="../template/dist/'.$page.'/'.$page.'.css?v='.VERSION_NUM.'">';
        }else{
            $script = '<script type="module" src="'.HOST_REACT.'/react/pages/'.$page.'/'.$page.'.jsx"></script>';
            $style = '';
        }

        return '<!-- React dependency -->' .$script . $style;
    }
}



?>