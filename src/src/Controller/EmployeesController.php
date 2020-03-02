<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Routing\Router; // For url 
use Cake\Datasource\ConnectionManager; // For Databasese connection
use Cake\ORM\TableRegistry;
use Cake\Controller\Component\FlashComponent;
use  App\Controller\object;

class EmployeesController extends AppController
{
    public $paginate = ['limit'=>3];
    public function initialize() {
        parent::initialize();
        $this->url = Router::url("/", TRUE);
        $this->loadModel('employees');  
        $this->loadComponent('Paginator');
    }
    /**
     * Index method
     * @return \Cake\Http\Response|null
     */
    public function index() {
      $allData = $this->Employees->query('call cakepl.view_all_data()');
      $this->set('employees', $this->paginate($allData));
    }
    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employees->get($id, ['call cakepl.view_data($id)']);  
        $this->set('employee', $employee);
    }
    /**
     * Add method
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $employee = $this->Employees->newEntity();
        if($this->request->is('post')){
        
        $name = $this->request->data('name');
        $skills = $this->request->data('skills');
        $address = $this->request->data('address');

        $this->connection = ConnectionManager::get('default');     
        $addData = $this->connection->execute("call cakepl.add_data('$name', '$skills', '$address')");

        if ($addData){
                $this->Flash->success(__('The employee has been saved.'));
                   return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
               }
        $this->set(compact('employee'));
    }
    /**
     * Edit method
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employees->get($id, [ 'call cakepl.edit_data($id)', ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
           $name = $this->request->data('name');
           $skills = $this->request->data('skills');
           $address = $this->request->data('address');
           $this->connection = ConnectionManager::get('default');
           $updateData = $this->connection->execute("call cakepl.update_data($id, '$name', '$skills', '$address')");
        if ($updateData) {
                $this->Flash->success(__('The employee info has been Updated.'));
                return $this->redirect(['action' => 'index']);
            }
               $this->Flash->error(__('The employee could not be Update. Please, try again.'));
        }  
       $this->set(compact('employee'));
    }
    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->connection = ConnectionManager::get('default');
        $employee = $this->connection->execute("call cakepl.delete_data($id)");       
        if ($employee) {
            $this->Flash->success(__('The employee has been deleted.'));
        } else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}