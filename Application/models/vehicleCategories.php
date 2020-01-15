<?php

 class vehicleCategories extends DB\SQL\Mapper{

     public function __construct(DB\SQL $db)
     {
         parent::__construct($db, 'vehicleCategories');
     }

     public function create($data){
        try{
        
            $this->load(array('vehicleType = ?', $data['vehicleType']));

            $this->copyFrom($data);

            $this->save();  

         }catch(Exception $e){
             throw new Exception($e);
         }
     }

     public function delete($data){
        try{
  
            $this->load(array('vehicleType = ?', $data['vehicleType']));
  
             $this->copyFrom($data);
     
             $this->save();
        }catch(Exception $e){
  
         throw new Exception($e);
  
        }
     }

     public function readByVehicleType($vehicleType) {
        try{

            $query = "SELECT * FROM vehicleCategories WHERE vehicleType = '$vehicleType' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function VehicleCategoryGetAll($disabled) {
        try{

            $query = "SELECT * FROM vehicleCategories WHERE disabled = '$disabled' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }
 }
 ?>