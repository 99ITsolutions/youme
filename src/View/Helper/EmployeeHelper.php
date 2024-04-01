<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class EmployeeHelper extends Helper
{
    public function getattendance($id,$day)
    {
        // $task_table = TableRegistry::get('task');
        $attendance_table = TableRegistry::get('emp_attendance');

        $users_attendance_details = $attendance_table->find()->select([ 'id' , 'userid' , 'login' , 'logout' , 'day'  ])->where([ 'day' => $day , 'userid' => $id  ])->toArray();   

        return $users_attendance_details ;
        // echo $id;
        // return  $id; 
        // Logic to create specially formatted link goes here...
    }
    public function getspent($id,$day)
    {
        $task_table = TableRegistry::get('task');
        $timelimitlower = $day;
        $timelimitupper = $timelimitlower + 86400 ;
        
        $get_spent =  $task_table->find()->select(['spent' => 'SUM(spent)' ])->where([ 'completedtime >=' =>  $timelimitlower , 'assigned' => md5($id)  , 'completedtime <=' =>  $timelimitupper  ])->first();

        return $get_spent->spent;     
    }

    public function getbilled($id,$day)
    {
        $task_table = TableRegistry::get('task');
        $timelimitlower = $day;
        $timelimitupper = $timelimitlower + 86400 ;
        
        $get_billed =  $task_table->find()->select(['billed' => 'SUM(billed)' ])->where([ 'billedtime >=' =>  $timelimitlower , 'assigned' => md5($id)  , 'billedtime <=' =>  $timelimitupper  ])->first();

        return $get_billed->billed;     
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