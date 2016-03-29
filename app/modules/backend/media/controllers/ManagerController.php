<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Media\Controllers;

use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Core\Utilities\MediaUpload;
use Core\BackendController;
use Core\Models\Medias;

/**
 * Class ManagerController
 *
 * @package Backend\Media\Controllers
 */
class ManagerController extends BackendController
{
    public function indexAction()
    {
		$this->view->setVar('medias', Medias::find());
    }

    public function newAction()
    {
        $this->view->setVar('max_file_upload', (int)ini_get("upload_max_filesize"));
    }

    public function uploadImageAction()
    {
        if($this->request->isAjax()) {
            if($files = $this->request->getUploadedFiles()) {
                $response = (new MediaUpload($files[0]))->response;
                if($response['code'] == 0){
                    // $this->response->setStatusCode(200, $response['msg']);
                } else {
                    $this->response->setStatusCode(406, $response['msg']);
                }
                $this->view->disableLevel(View::LEVEL_NO_RENDER);
            }
        }
    }
	
	public function deleteAction() {
		$response = new Response();
		$response->setHeader("Content-Type", "application/json");
		$content = ['status' => 0];
		
		$media_id = (int)$this->request->get('media_id');
		if($media = Medias::findFirst($media_id)) {
			$media_path = ROOT_PATH . '/public' . $media->src;
			if(file_exists($media_path)) {
				unlink($media_path);
			}
			
			if($media->delete()) {
				$content['status'] = 1;
			}
		}
		
		$response->setJsonContent($content);
        return $response;
	}
}