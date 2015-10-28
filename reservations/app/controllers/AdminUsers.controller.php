<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminUsers extends Admin
{
/**
 * Create user
 *
 * @access public
 * @return void
 */
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$err = NULL;
				if (isset($_POST['user_create']))
				{
					if ($this->isDemo())
					{
						$err = 7;
					} else {
					
						Object::import('Model', 'User');
						$UserModel = new UserModel();
						$id = $UserModel->save($_POST);
						
						if ($id !== false && (int) $id > 0)
						{
							$err = 1;
						} else {
							$err = 2;
						}
					}
				}
				Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=$err");
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Delete user, support AJAX too
 *
 * @access public
 * @return void
 */
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (!$this->isMultiUser())
				{
					$this->tpl['status'] = 9;
					return;
				}
				
				if ($this->isDemo())
				{
					$_GET['err'] = 7;
					$this->index();
					return;
				}
				
				if ($this->isXHR())
				{
					$this->isAjax = true;
					$id = $_POST['id'];
				} else {
					$id = $_GET['id'];
				}
				
				Object::import('Model', 'User');
				$UserModel = new UserModel();
					
				$arr = $UserModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 1;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=8");
					}
				}
				
				if ($UserModel->delete($id))
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=4");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * List of users
 *
 * (non-PHPdoc)
 * @see app/controllers/Admin::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (!$this->isMultiUser())
				{
					$this->tpl['status'] = 9;
					return;
				}
				
				Object::import('Model', array('User', 'Role'));
				$UserModel = new UserModel();
				$RoleModel = new RoleModel();
				
				$opts = array();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $UserModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$UserModel->addJoin($UserModel->joins, $RoleModel->getTable(), 'TR', array('TR.id' => 't1.role_id'), array('TR.role'));
				$arr = $UserModel->getAll($opts);
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = array('pages' => $pages);
				$this->tpl['role_arr'] = $RoleModel->getAll();
									
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminUsers.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Set user 'status'
 *
 * @access public
 * @return void
 */
	function set()
	{
		$this->isAjax = true;

		if ($this->isXHR())
		{
			if ($this->isDemo())
			{
				$_GET['err'] = 7;
				$this->index();
				return;
			}
				
			Object::import('Model', 'User');
			$UserModel = new UserModel();
			
			$arr = $UserModel->get($_POST['id']);
			
			if (count($arr) > 0)
			{
				switch ($arr['status'])
				{
					case 'T':
						$sql_status = 'F';
						break;
					case 'F':
						$sql_status = 'T';
						break;
					default:
						return;
				}
				$UserModel->update(array('id' => $_POST['id'], 'status' => $sql_status));
			}
		}
		$this->index();
	}
/**
 * Update user
 *
 * @param int $id
 * @access public
 * @return void
 */
	function update($id=null)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (!$this->isMultiUser())
				{
					$this->tpl['status'] = 9;
					return;
				}
				
				Object::import('Model', 'User');
				$UserModel = new UserModel();
				
				if (isset($_POST['user_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=7");
					}
				
					$data = array();
					if (!empty($_POST['password']) && $_POST['password'] != 'password')
					{
						
					} else {
						unset($_POST['password']);
					}

					if ($UserModel->update(array_merge($_POST, $data)) && isset($_POST['password']))
					{
						$_SESSION[$this->default_user]['password'] = $_POST['password'];
					}
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=5");

				} else {
					$arr = $UserModel->get($id);
					if (count($arr) === 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminUsers&action=index&err=8");
					}
					$this->tpl['arr'] = $arr;
					
					Object::import('Model', 'Role');
					$RoleModel = new RoleModel();
					$this->tpl['role_arr'] = $RoleModel->getAll();
				}
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminUsers.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}