<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
/**
 * tsDbLinker
 * 数据库的表间关联程序
 */
class tsDbLinker
{
    /**
     * 模型对象
     */
    private $model_obj = null;

    /**
     * 预准备的结果
     */
    private $prepare_result = null;

    /**
     * 运行的结果
     */
    private $run_result = null;

    /**
     * 可支持的关联方法
     */
    private $methods = array('find','findBy','findAll','run','create','delete','deleteByPk','update');
    /**
     * 是否启用全部关联
     */
    public $enabled = TRUE;
    /**
     * 函数式使用模型辅助类的输入函数
     */
    public function __input(& $obj, $args = null){
        $this->model_obj = $obj;
        return $this;
    }

    /**
     * 开发者可以通过tsDbLinker()->run($result)对已经返回的数据进行关联findAll查找
     * @param result    返回的数据
     */
    public function run($result = FALSE){
        if( FALSE == $result )return FALSE;
        $this->run_result = $result;
        return $this->__call('run', null);
    }

    /**
     * 魔术函数，支持多重函数式使用类的方法
     *
     * 在tsDbLinker类中，__call执行了tsApp继承类的相关操作，以及按关联的描述进行了对关联数据模型类的操作。
     */
    public function __call($func_name, $func_args){
        if( in_array( $func_name, $this->methods ) && FALSE != $this->enabled ){
            if( 'delete' == $func_name || 'deleteByPk' == $func_name )$maprecords = $this->prepare_delete($func_name, $func_args);
            if( null != $this->run_result ){
                $run_result = $this->run_result;
            }elseif( !$run_result = call_user_func_array(array($this->model_obj, $func_name), $func_args) ){
                if( 'update' != $func_name )return FALSE;
            }
            if( null != $this->model_obj->linker && is_array($this->model_obj->linker) ){
                foreach( $this->model_obj->linker as $linkey => $thelinker ){
                    if( !isset($thelinker['map']) )$thelinker['map'] = $linkey;
                    if( FALSE == $thelinker['enabled'] )continue;
                    $thelinker['type'] = strtolower($thelinker['type']);
                    if( 'find' == $func_name || 'findBy' == $func_name ){
                        $run_result[$thelinker['map']] = $this->do_select( $thelinker, $run_result );
                    }elseif( 'findAll' == $func_name || 'run' == $func_name ){
                        foreach( $run_result as $single_key => $single_result )
                            $run_result[$single_key][$thelinker['map']] = $this->do_select( $thelinker, $single_result );
                    }elseif( 'create' == $func_name ){
                        $this->do_create( $thelinker, $run_result, $func_args );
                    }elseif( 'update' == $func_name ){
                        $this->do_update( $thelinker, $func_args );
                    }elseif( 'delete' == $func_name || 'deleteByPk' == $func_name ){
                        $this->do_delete( $thelinker, $maprecords );
                    }
                }
            }
            return $run_result;
        }elseif(in_array($func_name, $GLOBALS['G_SP']["auto_load_model"])){
            return aac($func_name)->__input($this, $func_args);
        }else{
            return call_user_func_array(array($this->model_obj, $func_name), $func_args);
        }
    }

    /**
     * 私有函数，辅助删除数据操作
     * @param func_name    需要执行的函数名称
     * @param func_args    函数的参数
     */
    private function prepare_delete($func_name, $func_args)
    {
        if('deleteByPk'==$func_name){
            return $this->model_obj->findAll(array($this->model_obj->pk=>$func_args[0]));
        }else{
            return $this->model_obj->findAll($func_args[0]);
        }
    }
    /**
     * 私有函数，进行关联删除数据操作
     * @param thelinker    关联的描述
     * @param maprecords    对应的记录
     */
    private function do_delete( $thelinker, $maprecords ){
        if( FALSE == $maprecords )return FALSE;
        foreach( $maprecords as $singlerecord ){
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]) + $thelinker['condition'];
                }else{
                    $fcondition = "{$thelinker['fkey']} = '{$singlerecord[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]);
            }
            $returns = aac($thelinker['fclass'])->delete($fcondition);
        }
        return $returns;
    }
    /**
     * 私有函数，进行关联更新数据操作
     * @param thelinker    关联的描述
     * @param func_args    进行操作的参数
     */
    private function do_update( $thelinker, $func_args ){
        if( !is_array($func_args[1][$thelinker['map']]) )return FALSE;
        if( !$maprecords = $this->model_obj->findAll($func_args[0]))return FALSE;
        foreach( $maprecords as $singlerecord ){
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]) + $thelinker['condition'];
                }else{
                    $fcondition = "{$thelinker['fkey']} = '{$singlerecord[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]);
            }
            $returns = aac($thelinker['fclass'])->update($fcondition, $func_args[1][$thelinker['map']]);
        }
        return $returns;
    }
    /**
     * 私有函数，进行关联新增数据操作
     * @param thelinker    关联的描述
     * @param newid    主表新增记录后的关联ID
     * @param func_args    进行操作的参数
     */
    private function do_create( $thelinker, $newid, $func_args ){
        if( !is_array($func_args[0][$thelinker['map']]) )return FALSE;
        if('hasone'==$thelinker['type']){
            $newrows = $func_args[0][$thelinker['map']];
            $newrows[$thelinker['fkey']] = $newid;
            return aac($thelinker['fclass'])->create($newrows);
        }elseif('hasmany'==$thelinker['type']){
            if(array_key_exists(0,$func_args[0][$thelinker['map']])){ // 多个新增
                foreach($func_args[0][$thelinker['map']] as $singlerows){
                    $newrows = $singlerows;
                    $newrows[$thelinker['fkey']] = $newid;
                    $returns = aac($thelinker['fclass'])->create($newrows);
                }
                return $returns;
            }else{ // 单个新增
                $newrows = $func_args[0][$thelinker['map']];
                $newrows[$thelinker['fkey']] = $newid;
                return aac($thelinker['fclass'])->create($newrows);
            }
        }
    }
    /**
     * 私有函数，进行关联查找数据操作
     * @param thelinker    关联的描述
     * @param run_result    主表执行查找后返回的结果
     */
    private function do_select( $thelinker, $run_result ){
        if(empty($thelinker['mapkey']))$thelinker['mapkey'] = $this->model_obj->pk;
        if( 'manytomany' == $thelinker['type'] ){
            $do_func = 'findAll';
            $midcondition = array($thelinker['mapkey']=>$run_result[$thelinker['mapkey']]);
            if( !$midresult = aac($thelinker['midclass'])->findAll($midcondition,null,$thelinker['fkey']) )return FALSE;
            $tmpkeys = array();foreach( $midresult as $val )$tmpkeys[] = "'".$val[$thelinker['fkey']]."'";
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = "{$thelinker['fkey']} in (".join(',',$tmpkeys).")";
                    foreach( $thelinker['condition'] as $tmpkey => $tmpvalue )$fcondition .= " AND {$tmpkey} = '{$tmpvalue}'";
                }else{
                    $fcondition = "{$thelinker['fkey']} in (".join(',',$tmpkeys).") AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = "{$thelinker['fkey']} in (".join(',',$tmpkeys).")";
            }
        }else{
            $do_func = ( 'hasone' == $thelinker['type'] ) ? 'find' : 'findAll';
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = array($thelinker['fkey']=>$run_result[$thelinker['mapkey']]) + $thelinker['condition'];
                }else{
                    $fcondition = "{$thelinker['fkey']} = '{$run_result[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = array($thelinker['fkey']=>$run_result[$thelinker['mapkey']]);
            }
        }
        if(TRUE == $thelinker['countonly'])$do_func = "findCount";
        return aac($thelinker['fclass'])->$do_func($fcondition, $thelinker['sort'], $thelinker['field'], $thelinker['limit'] );
    }
}
