<?php
/**
 * 栏目管理
 * @author 王仁欢
 * @email wrh4285@163.com
 * @date 2015-08-06
 * @version 1.0.0
 */

include 'library/init.inc.php';
back_base_init();

$template = 'section/';
assign('subTitle', '栏目管理');

$action = 'edit|add|view|delete';
$operation = 'edit|add';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'view' : $act;

$opera = check_action($operation, getPOST('opera'));
//===========================================================================

//添加栏目
if( 'add' == $opera ) {
    if( !check_purview('pur_section_add', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $name = trim(getPOST('articleCatName'));
    $parent_id = getPOST('parentId');
    $keywords = trim(getPOST('keywords'));
    $description = trim(getPOST('description'));
    $order_view = intval(getPOST('order_view'));
    $path = '';
    $original = trim(getPOST('img'));
    $thumb = '';


    if('' == $name) {
        show_system_message('栏目名称不能为空', array());
        exit;
    } else {
        $name = $db->escape(htmlspecialchars($name));
    }

    $keywords = $db->escape(htmlspecialchars($keywords));
    $description = $db->escape(htmlspecialchars($description));

    /*
    if('' == $keywords) {
        show_system_message('出于SEO考虑，请务必填写关键词', array());
        exit;
    } else {
        $keywords = $db->escape(htmlspecialchars($keywords));
    }

    if('' == $description) {
        show_system_message('出于SEO考虑，请务必填写简要介绍', array());
        exit;
    } else {
        $description = $db->escape(htmlspecialchars($description));
    }
    */

    if( '' == $original ) {

    } else {
        $original = $db->escape(htmlspecialchars($img));
        if( file_exists('../'.$original) ) {
            $thumb = str_replace('image', 'thumb', $original);
        } else {
            $thumb = '';
        }

    }

    $parent_id = intval($parent_id);
    if( 0 > $parent_id ) {
        show_system_message('参数错误', array());
        exit;
    }

    $data = array(
        'section_name' => $name,
        'parent_id' => $parent_id,
        'path' => '',
        'keywords' => $keywords,
        'description' => $description,
        'order_view' => $order_view,
        'thumb' => $thumb,
        'original' => $original,
    );

    if( $db->autoInsert('section', array($data)) ) {
        $id = $db->get_last_id();
        $path = $id.',';
        if( 0 < $parent_id ) {
            $get_parent_path = 'select `path` from `'.DB_PREFIX.'section` where `id`='.$parent_id;
            if( $parent_path = $db->fetchRow($get_parent_path) ) {
                $path = $parent_path['path'].','.$path;
            }
        }

        $update_section = 'update `'.DB_PREFIX.'section` set `path`=\''.$path.'\' where `id`='.$id.' limit 1';
        if( $db->update($update_section) ) {
            $links = array(
                array('alt'=>'查看栏目', 'link'=>'section.php?act=list'),
                array('alt'=>'继续添加栏目', 'link'=>'section.php?act=add')
            );
            show_system_message('添加栏目成功', $links);
            exit;
        } else {
            show_system_message('更新栏目失败', array());
            exit;
        }
    } else {
        show_system_message('系统繁忙，请稍后再试', array());
        exit;
    }

}

//编辑栏目
if( 'edit' == $opera ) {

    if( !check_purview('pur_section_edit', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }
    $id = getPOST('id');
    $id = intval($id);

    if( 0 >= $id ) {
        show_system_message('参数错误', array());
        exit;
    }

    $get_section = 'select * from `'.DB_PREFIX.'section` where `id`='.$id.' limit 1';
    $section = $db->fetchRow($get_section);

    if( empty($section) ) {
        show_system_message('栏目不存在', array());
        exit;
    }

    $name = trim(getPOST('articleCatName'));
    $parent_id = getPOST('parentId');
    $keywords = trim(getPOST('keywords'));
    $description = trim(getPOST('description'));
    $order_view = intval(getPOST('order_view'));
    $path = '';
    $original = trim(getPOST('img'));
    $thumb = '';


    if('' == $name) {
        show_system_message('栏目名称不能为空', array());
        exit;
    } else {
        $name = $db->escape(htmlspecialchars($name));
    }

    $keywords = $db->escape(htmlspecialchars($keywords));
    $description = $db->escape(htmlspecialchars($description));
    /*
    if('' == $keywords) {
        show_system_message('出于SEO考虑，请务必填写关键词', array());
        exit;
    } else {
        $keywords = $db->escape(htmlspecialchars($keywords));
    }

    if('' == $description) {
        show_system_message('出于SEO考虑，请务必填写简要介绍', array());
        exit;
    } else {
        $description = $db->escape(htmlspecialchars($description));
    }
    */

    if( '' == $original ) {

    } else {
        $original = $db->escape(htmlspecialchars($original));
        if( file_exists('../'.$original) ) {
            $thumb = str_replace('image', 'thumb', $original);
        } else {
            $thumb = '';
        }

    }

    $parent_id = intval($parent_id);
    if( 0 > $parent_id ) {
        show_system_message('参数错误', array());
        exit;
    }

    if( 0 < $parent_id ) {
        $get_parent_path = 'select `path` from `'.DB_PREFIX.'section` where `id`='.$parent_id;
        if( $parent_path = $db->fetchRow($get_parent_path) ) {
            $path = $parent_path['path'].','.$id.',';
        }
    }



    $data = array(
        'section_name' => $name,
        'parent_id' => $parent_id,
        'path' => $path,
        'keywords' => $keywords,
        'description' => $description,
        'order_view' => $order_view,
        'thumb' => $thumb,
        'original' => $original,
    );

    foreach( $data as $key => $value ) {
        if( $value == '' ) {
            unset($data[$key]);
        }
    }

    $where = 'id = '.$id;
    $order = '';
    $limit = '1';

    if( $db->autoUpdate('section', $data, $where, $order, $limit) ) {
        show_system_message('栏目更新成功', array());
        exit;
    } else {
        show_system_message('系统繁忙，请稍后重试', array());
        exit;
    }


}



//===========================================================================

//栏目列表
if( 'view' == $act ) {

    if( !check_purview('pur_section_view', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $get_section_list = 'select * from '.$db->table('section').' where 1  order by `order_view` ASC,`path` ASC';
    $section_list = $db->fetchAll($get_section_list);

    if($section_list) {
        foreach ($section_list as $key => $section) {
            $count = count(explode(',', $section['path']));
            if ($count > 1) {
                $temp = '|--' . $section['section_name'];
                while ($count--) {
                    $temp = '&nbsp;&nbsp;' . $temp;
                }

                $section['section_name'] = $temp;
                $section_list[$key] = $section;
            }
        }
    }

    assign('sectionList', $section_list);
}

//添加栏目
if( 'add' == $act ) {

    if( !check_purview('pur_section_add', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $get_section_list = 'select * from '.$db->table('section').' where 1 order by `path` ASC';
    $section_list = $db->fetchAll($get_section_list);

    if($section_list) {
        foreach ($section_list as $key => $section) {
            $count = count(explode(',', $section['path']));
            if ($count > 1) {
                $temp = '|--' . $section['section_name'];
                while ($count--) {
                    $temp = '&nbsp;&nbsp;' . $temp;
                }

                $section['section_name'] = $temp;
                $section_list[$key] = $section;
            }
        }
    }

    assign('sectionList', $section_list);

    $get_configs = 'select * from '.$db->table('sysconf').' where 1';
    $configs = $db->fetchAll($get_configs);

    foreach( $configs as $config ) {
        $target[$config['key']] = $config['value'];
    }

    assign('configs', $target);
}

//编辑栏目
if( 'edit' == $act ) {
    if( !check_purview('pur_section_edit', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }
    $id = getGET('id');
    $id = intval($id);

    if( 0 >= $id ) {
        show_system_message('参数错误', array());
        exit;
    }

    $get_section = 'select * from `'.DB_PREFIX.'section` where `id`='.$id.' limit 1';
    $section = $db->fetchRow($get_section);

    if( empty($section) ) {
        show_system_message('栏目不存在', array());
        exit;
    }

    assign('section', $section);

    $get_section_list = 'select * from '.$db->table('section').' where `id` <> '.$id.'  order by `path` ASC';
    $section_list = $db->fetchAll($get_section_list);

    if($section_list) {
        foreach ($section_list as $key => $section) {
            $count = count(explode(',', $section['path']));
            if ($count > 1) {
                $temp = '|--' . $section['section_name'];
                while ($count--) {
                    $temp = '&nbsp;&nbsp;' . $temp;
                }

                $section['section_name'] = $temp;
                $section_list[$key] = $section;
            }
        }
    }

    assign('sectionList', $section_list);

    $get_configs = 'select * from '.$db->table('sysconf').' where 1';
    $configs = $db->fetchAll($get_configs);

    foreach( $configs as $config ) {
        $target[$config['key']] = $config['value'];
    }

    assign('configs', $target);

}


//删除栏目
if( 'delete' == $act ) {
    if( !check_purview('pur_section_del', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $id = getGET('id');
    $id = intval($id);

    if(0 >= $id) {
        show_system_message('参数错误', array());
        exit;
    }

    $check_section = 'select `id` from `'.DB_PREFIX.'section` where `parent_id`='.$id;
    $section = $db->fetchAll($check_section);
    if($section) {
        show_system_message('当前栏目下有子栏目，请先删除或移走子栏目', array());
        exit;
    }

    if( $id == 1 ) {
        show_system_message('系统保留分类，不允许删除', array());
        exit;
    }

    $delete_section = 'delete from `'.DB_PREFIX.'section` where `id`='.$id.' limit 1';
    if($db->delete($delete_section)) {
        show_system_message('删除栏目成功', array());
        exit;
    } else {
        show_system_message('系统繁忙，请稍后再试', array());
        exit;
    }
}


$template .= $act.'.phtml';
$smarty->display($template);
