<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminTypes extends Admin
{
/**
 * Create type
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
				if (isset($_POST['type_create']))
				{
					if ($this->isDemo())
					{
						$err = 7;
					} else {
					
						Object::import('Model', 'Type');
						$TypeModel = new TypeModel();						
						$id = $TypeModel->save($_POST);
						if ($id !== false && (int) $id > 0)
						{
							if (isset($_POST['i18n']))
							{
								Object::import('Model', 'I18n');
								$I18nModel = new I18nModel();
								$I18nModel->saveI18n($_POST['i18n'], $id, 'Type');
							}
							
							if (isset($_POST['extra_id']))
							{
								Object::import('Model', 'TypeExtra');
								$TypeExtraModel = new TypeExtraModel();
								Object::addLinked($TypeExtraModel->getTable(), 'type_id', 'extra_id', $id, $_POST['extra_id']);
							}
							
							if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name']))
							{
								Object::import('Component', 'SimpleImage');
								$SimpleImage = new SimpleImage();
								$SimpleImage->load($_FILES['image']['tmp_name']);
								
								$ext = $this->getFileExtension($_FILES['image']['name']);
								$path = UPLOAD_PATH . 'types/thumbs/' . $id . '.' . $ext;
								switch ($ext)
								{
									case 'gif':
										$image_type = IMAGETYPE_GIF;
										break;
									case 'png':
										$image_type = IMAGETYPE_PNG;
										break;
									case 'jpg':
									case 'jpeg':
									case 'pjepg':
									default:
										$image_type = IMAGETYPE_JPEG;
										break;
								}
								$SimpleImage->resize(185, 139);
								$SimpleImage->save($path, $image_type);
								$TypeModel->update(array('id' => $id, 'thumb_path' => $path));
							}
							$err = 1;
						} else {
							$err = 2;
						}
					}
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=$err");
				} else {
					Object::import('Model', array('Extra', 'I18n'));
					$ExtraModel = new ExtraModel();
					$I18nModel = new I18nModel();
					$ExtraModel->addJoin($ExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
					$this->tpl['extra_arr'] = $ExtraModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'adminTypes.js', 'path' => JS_PATH);
				}				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Delete type, support AJAX too
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
				
				Object::import('Model', 'Type');
				$TypeModel = new TypeModel();
					
				$arr = $TypeModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 1;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=8");
					}
				}
				
				if ($TypeModel->delete($id))
				{
					if (is_file($arr['thumb_path']))
					{
						@unlink($arr['thumb_path']);
					}
					Object::import('Model', array('TypeExtra', 'I18n'));
					$TypeExtraModel = new TypeExtraModel();
					$TypeExtraModel->delete(array('type_id' => $id));
					$I18nModel = new I18nModel();
					$I18nModel->delete(array('foreign_id' => $id, 'model' => 'Type'));
						
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=4");
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
 * List of types
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
				Object::import('Model', array('Type', 'CarType', 'I18n'));
				$TypeModel = new TypeModel();
				$CarTypeModel = new CarTypeModel();
				$I18nModel = new I18nModel();
				
				$opts = array();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $TypeModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;

				$TypeModel->addSubQuery($TypeModel->subqueries, sprintf("SELECT COUNT(*) FROM `%s` WHERE `type_id` = `t1`.`id` LIMIT 1", $CarTypeModel->getTable()), "cnt");
				$TypeModel->addJoin($TypeModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Type'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));
				$arr = $TypeModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 'i18n.content', 'direction' => 'asc')));
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = compact('pages', 'count', 'row_count');
									
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/flick/');
				
				$this->js[] = array('file' => 'adminTypes.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Update type
 *
 * @access public
 * @return void
 */
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				Object::import('Model', array('Type', 'TypeExtra', 'I18n'));
				$TypeModel = new TypeModel();
				$TypeExtraModel = new TypeExtraModel();
				$I18nModel = new I18nModel();
				
				if (isset($_POST['type_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=7");
					}
				
					$data = array();
					$TypeModel->update(array_merge($_POST, $data));
					
					$I18nModel->updateI18n($_POST['i18n'], $_POST['id'], 'Type');
					
					$TypeExtraModel->delete(array('type_id' => $_POST['id']));
					if (isset($_POST['extra_id']))
					{
						Object::addLinked($TypeExtraModel->getTable(), 'type_id', 'extra_id', $_POST['id'], $_POST['extra_id']);
					}
					
					if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name']))
					{
						Object::import('Component', 'SimpleImage');
						$SimpleImage = new SimpleImage();
						$SimpleImage->load($_FILES['image']['tmp_name']);
						
						$ext = $this->getFileExtension($_FILES['image']['name']);
						$path = UPLOAD_PATH . 'types/thumbs/' . $_POST['id'] . '.' . $ext;
						switch ($ext)
						{
							case 'gif':
								$image_type = IMAGETYPE_GIF;
								break;
							case 'png':
								$image_type = IMAGETYPE_PNG;
								break;
							case 'jpg':
							case 'jpeg':
							case 'pjepg':
							default:
								$image_type = IMAGETYPE_JPEG;
								break;
						}
						$SimpleImage->resize(185, 139);
						$SimpleImage->save($path, $image_type);
						$TypeModel->update(array('id' => $_POST['id'], 'thumb_path' => $path));
					}
					
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=5");

				} else {
					$arr = $TypeModel->get($_GET['id']);
					if (count($arr) === 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTypes&action=index&err=8");
					}
					$arr['i18n'] = $I18nModel->getI18n($arr['id'], 'Type');
					$this->tpl['arr'] = $arr;

					Object::import('Model', 'Extra');
					$ExtraModel = new ExtraModel();
					$ExtraModel->addJoin($ExtraModel->joins, $I18nModel->getTable(), 'i18n', array('i18n.foreign_id' => 't1.id', 'i18n.model' => "'Extra'", 'i18n.locale' => "'1'", 'i18n.field' => "'name'"), array('i18n.content.name'));					
					$this->tpl['extra_arr'] = $ExtraModel->getAll(array('col_name' => 'i18n.content', 'direction' => 'asc'));
					$this->tpl['linked_extras'] = Object::getLinked($TypeExtraModel->getTable(), 'type_id', 'extra_id', $arr['id']);
					
					$this->js[] = array('file' => 'jquery.i18n.js', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					$this->css[] = array('file' => 'jquery.i18n.css', 'path' => LIBS_PATH . 'jquery/plugins/i18n/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'adminTypes.js', 'path' => JS_PATH);
				}				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}