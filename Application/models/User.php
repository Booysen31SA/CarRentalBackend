<?php

 class User extends DB\SQL\Mapper{

     public function __construct(DB\SQL $db)
     {
         parent::__construct($db, 'user');
     }

     public function create($data){
       
        try{
            $this->load(array('Username = ?', $data['Username']));

            $this->copyFrom($data);
            $this->save();
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    public function delete($data){
        try{
 
            $this->load(array('Username = ?', $data['Username']));
 
             $this->copyFrom($data);
     
             $this->save();
        }catch(Exception $e){
 
         throw new Exception($e);
 
        }
     }

     public function getByUsername($username){
        try{

            $query = "SELECT * FROM user WHERE Username = '$username' AND disabled = 0";

            $result = $this->db->exec($query);
            
            return $result;
        }catch(Exception $e){
          throw new Exception($e);
        }
     }

     public function getByCell($cell){
        try{

            $query = "SELECT * FROM user WHERE Cell_Number = '$cell' AND disabled = 0";

            $result = $this->db->exec($query);
            
            return $result;
        }catch(Exception $e){
          throw new Exception($e);
        }
     }

     public function Get_PendingList(){
        try{
            $query = "CALL Get_PendingList()";
      
            $result = $this->db->exec($query);
      
            return $result;
            
               }catch(Exception $e){
                  throw new Exception($e);
               }
    }
 }
?>