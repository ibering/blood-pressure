<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Lib\Api\ApiPaginator;

class MeasurementsController extends AppController {

    public function index() {
        if ($this->isHtmlRequest()) {
            //Only ship html template
            return;
        }

        $user = $this->Authentication->getIdentity();

        $MeasurementsTable = TableRegistry::getTableLocator()->get('Measurements');

        $ApiPaginator = new ApiPaginator($this, $this->request);

        $entities = $MeasurementsTable->getMeasurementsIndex($ApiPaginator, $user->get('id'));
        $this->set('measurements', $entities);

        $this->viewBuilder()->setOption('serialize', ['measurements']);
    }

    public function add() {
        if ($this->isHtmlRequest()) {
            //Only ship html template
            return;
        }

        $user = $this->Authentication->getIdentity();

        $MeasurementsTable = TableRegistry::getTableLocator()->get('Measurements');
        $entity = $MeasurementsTable->newEntity($this->request->getData());
        $entity->set('user_id', $user->get('id'));

        $MeasurementsTable->save($entity);
        if ($entity->hasErrors()) {
            //This throws the body content away :(
            $this->response = $this->response->withStatus(400);
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('measurement', $entity);
        $this->viewBuilder()->setOption('serialize', ['measurement']);
    }

}
