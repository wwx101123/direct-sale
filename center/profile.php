<?php
/**
 * 个人信息
 * @author 王仁欢
 * @email wrh4285@163.com
 * @date 2015-08-11
 * @version 1.0.0
 */

include 'library/init.inc.php';
back_base_init();

$template = 'profile/';
assign('subTitle', '个人信息');

$action = 'info|passwd';
$operation = 'info|passwd';

$act = check_action($action, getGET('act'));
$act = ( $act == '' ) ? 'info' : $act;

$opera = check_action($operation, getPOST('opera'));

//=========================================================================

//编辑个人信息
if( 'info' == $opera ) {
    if( !check_purview('pur_passwd_edit', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $name = trim(getPOST('screenName'));
    $email = trim(getPOST('email'));
    $sex = trim(getPost('sex'));

    if( '' == $name ) {
        show_system_message('昵称不能为空', array());
        exit;
    } else {
        $name = $db->escape(htmlspecialchars($name));
    }

    if( '' == $email ) {
        show_system_message('邮箱不能为空', array());
        exit;
    } else {
        $pattern = '/^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/';
        if( !preg_match($pattern, $email) ) {
            show_system_message('邮箱格式不正确', array());
            exit;
        }

        $email = $db->escape(htmlspecialchars($email));
    }

    $sex = ( 'F' == $sex ) ? $sex : 'M';

    $data = array(
        'name' => $name,
        'email' => $email,
        'sex' => $sex,
    );
    $table = 'admin';
    $where = '`account` = \''.$_SESSION['account'].'\'';
    $order = '';
    $limit = '1';

    if( $db->autoUpdate($table, $data, $where, $order, $limit) ) {

        $_SESSION['name'] = $name;

        $links = array(
            array('link' => '个人信息', 'alt' => 'profile.php?act=info'),
            array('link' => '修改密码', 'alt' => 'profile.php?act=passwd'),
        );
        show_system_message('修改个人信息成功', $links);
        exit;
    } else {
        show_system_message('系统繁忙，请稍后重试', array());
        exit;
    }


}

//修改密码
if( 'passwd' == $opera ) {
    if( !check_purview('pur_passwd_edit', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $old_password = trim(getPOST('old-password'));
    $new_password = trim(getPOST('new-password'));
    $confirm_password = trim(getPOST('confirm-password'));

    if( '' == $old_password ) {
        show_system_message('原密码不能为空', array());
        exit;
    } else {
        $old_password = md5($old_password.PASSWORD_END);
    }

    if( '' == $new_password ) {
        show_system_message('新密码不能为空', array());
        exit;
    }

    if( $confirm_password != $new_password ) {
        show_system_message('两次输入的密码不一致', array());
        exit;
    }

    $get_admin = 'select `password` from '.$db->table('admin').' where account = \''.$_SESSION['account'].'\' limit 1';
    $admin = $db->fetchRow($get_admin);

    if( empty($admin) ) {
        $links = array(
            array('link' => 'index.php?act=logout', 'alt' => '注销')
        );
        show_system_message('当前登陆用户异常,强制注销', $links);
        exit;
    }

    if( $old_password != $admin['password'] ) {
        show_system_message('原密码不正确', array());
        exit;
    }

    $new_password = md5($new_password.PASSWORD_END);

    $update_admin = 'update '.$db->table('admin').' set password = \''.$new_password.'\'';
    $update_admin .= ' where account = \''.$_SESSION['account'].'\' limit 1';

    if( $db->update($update_admin) ) {
        $links = array(
        );
        show_system_message('修改密码成功', $links);
        exit;
    } else {
        show_system_message('系统繁忙，请稍后重试', array());
        exit;
    }

}


//=========================================================================

//编辑个人信息
if( 'info' == $act ) {
    if( !check_purview('pur_passwd_edit', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }

    $get_admin = 'select `email`,`name`,`sex` from '.$db->table('admin').' where account = \''.$_SESSION['account'].'\' limit 1';
    $admin = $db->fetchRow($get_admin);

    if( empty($admin) ) {
        $links = array(
            array('link' => 'index.php?act=logout', 'alt' => '注销')
        );
        show_system_message('当前登陆用户异常,强制注销', $links);
        exit;
    }

    assign('admin', $admin);
}

//修改密码
if( 'passwd' == $act ) {
    if( !check_purview('pur_passwd_edit', $_SESSION['purview']) ) {
        show_system_message('权限不足', array());
        exit;
    }
}


$template .= $act.'.phtml';
$smarty->display($template);