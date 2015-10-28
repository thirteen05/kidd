<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminExtras extends Admin
{
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (isset($_POST['extra_create']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=7");
					}
					
					Object::import('Model', 'Extra');
					$ExtraModel = new ExtraModel();

					$id = $ExtraModel->save($_POST);
					if ($id !== false && (int) $id > 0)
					{
						if (isset($_POST['i18n']))
						{
							Object::import('Model', 'I18n');
							$I18nModel = new I18nModel();
							$I18nModel->saveI18n($_POST['i18n'], $id, 'Extra');
						}
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=1");
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=create&err=1");
					}
				}
				$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminExtras.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
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
				
				Object::import('Model', 'Extra');
				$ExtraModel = new ExtraModel();
					
				$arr = $ExtraModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 1;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=8");
					}
				}
				
				if ($ExtraModel->delete($id))
				{
					Object::import('Model', array('I18n', 'TypeExtra'));
					$I18nModel = new I18nModel();
					$TypeExtraModel = new TypeExtraModel();
					$I18nModel->delete(array('foreign_id' => $id, 'model' => 'Extra'));
					$TypeExtraModel->delete(array('extra_id' => $id));
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=4");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
		
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$opts = array();
				
				Object::import('Model', array('Extra', 'I18n'));
				$ExtraModel = new ExtraModel();
				$I18nModel = new I18nModel();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $ExtraModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$ExtraModel->addJoin($ExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
				$arr = $ExtraModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 'i18n.content', 'direction' => 'asc')));
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = compact('pages', 'row_count', 'count');

				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'adminExtras.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Extra', 'I18n'));
				$ExtraModel = new ExtraModel();
				$I18nModel = new I18nModel();
					
				if (isset($_POST['extra_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=7");
					}
					$ExtraModel->update($_POST);
					$I18nModel->updateI18n($_POST['i18n'], $_POST['id'], 'Extra');
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=5");
					
				} else {
					$arr = $ExtraModel->get($_GET['id']);
					if (count($arr) === 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminExtras&action=index&err=8");
					}
					$arr['i18n'] = $I18nModel->getI18n($arr['id'], 'Extra');
					$this->tpl['arr'] = $arr;
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
				}
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminExtras.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>