<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Menu\Controllers;

use Phalcon\Http\Response;
use Backend\Menu\Forms\MenuTypeForm;
use Core\BackendController;
use Core\Pagination;
use Core\Models\MenuItems;
use Core\Models\MenuTypes;
use Core\Models\MenuDetails;
use Core\Utilities\ArrayHelper;

class IndexController extends BackendController
{
    /**
     * View All Menu Type
     */
    public function indexAction()
    {
        // Add toolbar button
        $this->_toolbar->addNewButton();
        $this->_toolbar->addDeleteButton();

        // Add filter
        $this->addFilter('filter_order', 'menu_type_id', 'string');
        $this->addFilter('filter_order_dir', 'ASC', 'string');

        // Get all filter
        $this->getFilter();

        $conditions = [];

        $conditions[] = 'menu_type_id > 0';

        // Get all item
        $items = MenuTypes::find([
            'conditions' => implode(' AND ', $conditions),
            'order' => $this->_filter['filter_order'] . ' ' . $this->_filter['filter_order_dir'],
        ]);

        // Create pagination
        $this->view->setVar('_page', Pagination::getPaginationModel($items, $this->config->pagination->limit, $this->request->getQuery('page', 'int', 1)));

        // Set search value
        $this->view->setVar('_filter', $this->_filter);

        // Set column name, value
        $this->view->setVar('_pageLayout', [
            [
                'type' => 'check_all',
                'column' => 'menu_type_id'
            ],
            [
                'type' => 'index',
                'title' => '#'
            ],
            [
                'type' => 'link',
                'title' => 'm_menu_form_menu_type_name',
                'sort' => true,
                'column' => 'name',
                'link' => '/admin/menu/index/edit/',
                'access' => $this->acl->isAllowed('menu|index|edit')
            ],
            [
                'type' => 'date',
                'title' => 'created_at',
                'sort' => true,
                'column' => 'created_at'
            ],
            [
                'type' => 'date',
                'title' => 'updated_at',
                'sort' => true,
                'column' => 'updated_at'
            ],
            [
                'type' => 'id',
                'title' => 'id',
                'column' => 'menu_type_id'
            ]
        ]);
    }

    /**
     * Add menu type with menu item
     */
    public function newAction()
    {
        // Add Resource
        $this->_addResource();

        // Add toolbar button
        $this->_toolbar->addSaveButton();
        $this->_toolbar->addCancelButton('index');

        $menu_items = MenuItems::find();
        $form = new MenuTypeForm();

        if($this->request->isPost()) {
            $menuType = new MenuTypes();
            if($form->isValid($_POST, $menuType)) {
                $this->saveMenuDetails($menuType);
            } else {
                $this->flashSession->error('please_check_required_filed');
            }
        }

        $this->view->setVar('form', $form);
        $this->view->setVar('menu_items', $menu_items);
    }

    /**
     * Edit menu
     *
     * @param string $id id of menu type
     * @return \Phalcon\Http\ResponseInterface
     */
    public function editAction($id)
    {
        // Add Resource
        $this->_addResource();

        // Add toolbar button
        $this->_toolbar->addSaveButton();
        $this->_toolbar->addCancelButton('index');

        /**
         * @var MenuTypes $menuType
         */
        $menuType = MenuTypes::findFirst($id);

        $this->_getAllMenuItems($id);

        if($menuType) {
            $form = new MenuTypeForm($menuType);

            if($this->request->isPost()) {
                if($form->isValid($_POST, $menuType)) {
                    $this->saveMenuDetails($menuType);
                } else {
                    $this->flashSession->error('please_check_required_filed');
                }
            }

            $this->view->setVar('form', $form);
            $this->view->pick('index/new');
        } else {
            return $this->response->redirect('/admin/menu/');
        }
		
        return null;
    }

    /**
     * Delete multiple menu type and menu item of this menu type
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function deleteAction()
    {
        if($this->request->isPost()) {
            $ids = $this->request->getPost('ids');

            ArrayHelper::toInteger($ids);

            foreach($ids as $id) {
                /**
                 * @var MenuDetails[] $menu_details
                 */
                $menu_details = MenuDetails::find([
                    'menu_type_id = ?0',
                    'bind' => [$id]
                ]);
				
                foreach($menu_details as $detail) {
                    $detail->delete();
                }
				
                /**
                 * @var MenuTypes $menu_type
                 */
                $menu_type = MenuTypes::findFirst($id);
				
                if($menu_type->delete()) {
                    $this->flashSession->success($menu_type->name . ' deleted successful');
                } else {
                    $this->flashSession->error($menu_type->name . ' deleted fail');
                }
            }
        }
		
        return $this->response->redirect('/admin/menu/');
    }

    /**
     * Get menu items
     *
     * @return \Phalcon\Http\Response
     * REST service return menu item information
     */
    public function getMenuItemsAction()
    {
        $response = new Response();
        $response->setHeader('Content-Type', 'application/json');
		
        /**
         * @var MenuItems[] $menuItems
         */
        $menuItems = MenuItems::find([
            'id in (' . $this->request->getPost('ids') . ')'
        ]);
		
        if(count($menuItems) > 0 && method_exists($menuItems, 'toArray')) {
            $response->setJsonContent($menuItems->toArray());
        } else {
            $response->setJsonContent([]);
        }

        return $response;
    }

    /**
     * Add child menu
     *
     * @param int $type_id
     * @param int $id
     * @param array $children
     * @return bool
     */
    private function addMenuChild($type_id, $id, $children)
    {
        $index = 1;
        foreach($children as $value) {
            $menuDetail = new MenuDetails();
            $menuDetail->menu_type_id = $type_id;
            $menuDetail->menu_item_id = $value->id;
            $menuDetail->parent_id = $id;
            $menuDetail->ordering = $index++;
            if(!$menuDetail->save()) {
                foreach($menuDetail->getMessages() as $mgs) {
                    $this->flashSession->error($mgs);
                }
                return false;
            } else {
                if($value->children) {
                    if(!$this->addMenuChild($type_id, $value->id, $value->children)) {
                        return false;
                    }
                }
            }
        }
		
        return true;
    }

    /**
     * Save menu detail
     *
     * @param MenuTypes $menuType
     * @return \Phalcon\Http\ResponseInterface
     */
    private function saveMenuDetails($menuType)
    {
        $this->db->begin();
        if(!$menuType->save()) {
            foreach($menuType->getMessages() as $mgs) {
                $this->flashSession->error($mgs);
            }
            $this->db->rollBack();
        } else {
            // Delete all menu item
			
            /**
             * @var MenuDetails[] $menuDetails
             */
            $menuDetails = MenuDetails::find([
                'menu_type_id = ?0',
                'bind' => [$menuType->menu_type_id]
            ]);
			
            foreach($menuDetails as $it) {
                $it->delete();
            }

            $d_array = $this->request->getPost('nestable-output');

            $result = json_decode($d_array);
            if(count($result) > 0) {
                $index = 1;
                foreach($result as $value) {
                    $menuDetail = new MenuDetails();
                    $menuDetail->menu_type_id = $menuType->menu_type_id;
                    $menuDetail->menu_item_id = $value->id;
                    $menuDetail->parent_id = 0;
                    $menuDetail->ordering = $index++;
                    if(!$menuDetail->save()) {
                        $this->db->rollBack();
                        foreach($menuDetail->getMessages() as $mgs) {
                            $this->flashSession->error($mgs);
                        }
                        return null;
                    } else {
                        if(!empty($value->children)) {
                            if(!$this->addMenuChild($menuType->menu_type_id, $value->id, $value->children)) {
                                $this->db->rollBack();
                                return null;
                            }
                        }
                    }
                }
            }
			
            $this->flashSession->success($menuType->name . ' was updated successfully');
            $this->db->commit();
            $this->view->disable();

            return $this->response->redirect('/admin/menu/');
        }
		
        return null;
    }

    /**
     * Get all menu items
     *
     * @param int $id
     */
    private function _getAllMenuItems($id)
    {
        $items = MenuItems::find([
            'order' => 'menu_item_id asc'
        ]);
        $dicMenus = [];
        foreach($items as $it) {
            $dicMenus[$it->menu_item_id] = $it->name;
        }
        $this->view->setVar('dic_menus', $dicMenus);

        /**
         * @var MenuDetails[] $menuDetails Get all menu detail
         */
        $menuDetails = MenuDetails::find([
            'menu_type_id=?0',
            'order' => 'parent_id asc',
            'bind' => [$id]
        ]);

        $newArr = [];
        $ids = [];
        $detailMenu = [];

        if(count($menuDetails)) {
            foreach($menuDetails as $it) {
                $newArr[] = $it;
                $ids[] = $it->menu_item_id;
            }

            foreach($menuDetails as $it) {
                if($it->parent_id < 1) {
                    $it->children = $it->getChildren($newArr);
                    $detailMenu[] = $it;
                }
            }
        }
		
        $conditions = '';
        if(count($ids)) {
            $conditions = 'menu_item_id NOT IN (' . implode(',', $ids) . ')';
        }
        $menu_items = MenuItems::find([
            'conditions' => $conditions
        ]);

        $this->view->setVar('menu_items', $menu_items);
        $this->view->setVar('menu_details', $detailMenu);
    }

    /**
     * Add CSS / JS
     */
    private function _addResource()
    {
        // Add Resource
        $this->assets->collection('css_header')
            ->addCss('/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css');

        $this->assets->collection('js_footer')
            ->addJs('/plugins/nestable/jquery.nestable.js')
            ->addJs('/plugins/nestable/ui-nestable.js')
            ->addJs('/plugins/bootstrap-modal/js/bootstrap-modal.js')
            ->addJs('/plugins/bootstrap-modal/js/bootstrap-modalmanager.js')
            ->addJs('/templates/backend/default/AdminLTE/js/ui-modals.js');
    }
}
