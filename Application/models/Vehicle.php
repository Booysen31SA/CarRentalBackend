<?php

 class Vehicle extends DB\SQL\Mapper{

     public function __construct(DB\SQL $db)
     {
         parent::__construct($db, 'vehicle');
     }

     public function create($data){
        try{
            $this->load(array('vehNumber = ?', $data['vehNumber']));

            $this->copyFrom($data);
            $this->save();
        }catch(Exception $e){
            throw new Exception($e);
        }
     }

     public function delete($data){
        try{
            $this->load(array('vehNumber = ?', $data['vehNumber']));

            $this->copyFrom($data);
            $this->save();
        }catch(Exception $e){
            throw new Exception($e);
        }
     }

     public function searchByCategory($category){
        try{

            $query = "SELECT * FROM vehicle WHERE category = '$category' AND disabled = 0";

            $result = $this->db->exec($query);
            
            return $result;

        }catch(Exception $e){
          throw new Exception($e);
        }
     }

     public function searchByvehNumber($vehNumber){
        try{

            $query = "SELECT * FROM vehicle WHERE vehNumber = '$vehNumber' AND disabled = 0";

            $result = $this->db->exec($query);
            
            return $result;
            
        }catch(Exception $e){
          throw new Exception($e);
        }
     }

     public function getAllVehicles($disabled){
        try{

            $query = "SELECT * FROM vehicle WHERE disabled = '$disabled' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }
    }
?>