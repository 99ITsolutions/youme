<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class ClientHelper extends Helper
{
    public function findhours($id)
    {
        $task_table = TableRegistry::get('task');
        $project_table = TableRegistry::get('project');
        $billing_table = TableRegistry::get('billing');
        $client_table = TableRegistry::get('client');
        $get_negative = $project_table->find()->select(['nlimit' , 'nallowed', 'cid'])->where([ 'md5(id)' => $id  ])->first();
        $getclient = $client_table->find()->select(['type' ])->where([ 'md5(id)' => $get_negative->cid  ])->first();
     
        if($getclient->type == 1){
            $query = $billing_table->find();        
            $retrieve_billing = $query->select([ 'hours' => $query->func()->sum('hours')  ])->where(['md5(pid)' => $id , 'deleted' => 0 ])->first() ;
            $count_billed = $task_table->find()->select(['billed' => 'SUM(billed)' ])->where([ 'project' => $id , 'status' => 4 ])->first();
    
           return  $hours  = (($retrieve_billing->hours == "" ? 0 : $retrieve_billing->hours) + ($get_negative->nlimit == "" ? 0 : $get_negative->nlimit)) - ($count_billed->billed == "" ? 0 : $count_billed->billed) ; 
           
    
        }
        elseif($getclient->type == 2){
            $query = $billing_table->find();        
            $retrieve_billing = $query->select([ 'hours' => $query->func()->sum('hours')  ])->where(['md5(cid)' =>  $get_negative->cid , 'deleted' => 0 ])->first() ;
            $count_billed = $task_table->find()->select(['billed' => 'SUM(billed)' ])->where([ 'client' =>  $get_negative->cid , 'status' => 4 ])->first();
            return  $hours  = $retrieve_billing->hours  - $count_billed->billed; 
    
    
        }

        // echo $id;
        // return  $id; 
        // Logic to create specially formatted link goes here...
    }
    public function showhours($id)
    {
        $task_table = TableRegistry::get('task');
        $project_table = TableRegistry::get('project');
        $billing_table = TableRegistry::get('billing');
        $client_table = TableRegistry::get('client');

        // $query = $billing_table->find();

        $get_negative = $project_table->find()->select(['nlimit' , 'nallowed', 'cid'])->where([ 'md5(id)' => $id  ])->first();
        $getclient = $client_table->find()->select(['type' ])->where([ 'md5(id)' => $get_negative->cid  ])->first();
     
        if($getclient->type == 1){
            $query = $billing_table->find();        
            $retrieve_billing = $query->select([ 'hours' => $query->func()->sum('hours')  ])->where(['md5(pid)' => $id , 'deleted' => 0 ])->first() ;
            $count_billed = $task_table->find()->select(['billed' => 'SUM(billed)' ])->where([ 'project' => $id , 'status' => 4 ])->first();
    
           return  $hours  = ($retrieve_billing->hours) - $count_billed->billed; 
           
    
        }
        elseif($getclient->type == 2){
            $query = $billing_table->find();        
            $retrieve_billing = $query->select([ 'hours' => $query->func()->sum('hours')  ])->where(['md5(cid)' =>  $get_negative->cid , 'deleted' => 0 ])->first() ;
            $count_billed = $task_table->find()->select(['billed' => 'SUM(billed)' ])->where([ 'client' =>  $get_negative->cid , 'status' => 4 ])->first();
            return  $hours  = $retrieve_billing->hours  - $count_billed->billed; 
    
    
        }

        

        
        // return  $hours  = $retrieve_billing->hours - $count_billed->billed; 
        // return  $id; 
        // Logic to create specially formatted link goes here...
    }
    public function updatenegative($tid)
    {
        $task_table = TableRegistry::get('task');
        $modified = strtotime('now');
        $task_table->query()->update()->set([ 'negative' => 0 , 'modified' => $modified ])->where(['id' =>  $tid ])->execute();
        return  ;
        // return  $id; 
        // Logic to create specially formatted link goes here...
    }
    public function updatepositive($tid)
    {
        $task_table = TableRegistry::get('task');
        $modified = strtotime('now');
        $color = "red";
        $task_table->query()->update()->set([ 'negative' => 1 , 'modified' => $modified ])->where(['id' =>  $tid ])->execute();
        return  $color;
        // return  $id; 
        // Logic to create specially formatted link goes here...
    }
}

?>