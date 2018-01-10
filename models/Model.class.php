<?php

require_once(__DIR__ . '/Database.class.php');

abstract class Model 
{
    protected static $database;

    protected function getDatabase() {
        self::$database = Database::instance();        
    }

    public static function all() 
    {
        self::$database = Database::instance();   

        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If called class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Prepare statement
        $table     = $called_class::$table;        
        $statement = self::$database->prepare("SELECT * FROM $table");

        // Execute statement
        $statement->execute();

        // Store result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($statement->rowCount() == 0) {
            // If there is no row selected

            return null;
        }

        $array = [];

        foreach ($result as $element) {
            // For each row selected

            $class = new $called_class(); // Create an instance
            self::fill($class, $element); // Fill object fields

            $array[] = $class; // Add object to array
        }

        return $array;
    }

    public static function find($id)
    {
        self::$database = Database::instance();   
        
        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If called class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Generate query
        $table = $called_class::$table;                
        $query = "SELECT * FROM $table WHERE id = :id";

        // Prepare statement
        $statement = self::$database->prepare($query);

        // Bind params        
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute statement
        $statement->execute();  

        // Store result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($statement->rowCount() == 0) {
            // If there is no row selected

            return null;
        }

        $class = new $called_class(); // Create an instance
        self::fill($class, $result[0]); // Fill object fields

        return $class;
    }

    public static function create(array $data)
    {
        self::$database = Database::instance();   

        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If called class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Generate query
        $table = $called_class::$table;             
        $query = "INSERT INTO $table (";

        // Add fields to query
        $is_first = true;
        foreach ($data as $key => $value) {
            if ($is_first) {
                // Is first element in list

                $query .= $key;
                $is_first = false;
            }
            else {
                // Is not first element in list
                $query .= ", $key";
            }
        }

        $query .= ") VALUES (";

        // Add params to query
        $is_first = true;
        foreach ($data as $key => $value) {
            if ($is_first) {
                // Is first element in list

                $query .= ":$key";
                $is_first = false;
            }
            else {
                // Is not first element in list
                $query .= ", :$key";
            }
        }

        $query .= ")"; 

        // Prepare statement
        $statement = self::$database->prepare($query);

        // Bind params
        foreach ($data as $key => &$value) {
            if (is_numeric($value)) {
                $statement->bindParam(":$key", intval($value), PDO::PARAM_INT);  
            }
            else if (gettype($value) == 'string') {
                $statement->bindParam(":$key", $value, PDO::PARAM_STR);   
            }
        } 

        try {
            // Execute statement
            $statement->execute();  

            $data['id'] = self::$database->lastInsertId();

            // Prepare to return the inserted object
            $class = new $called_class(); // Create an instance
            self::fill($class, $data); // Fill object fields

            return $class; 
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());

            return null;
        }
    }

    public static function where(array $conditions)
    {
        self::$database = Database::instance();   
        
        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If called class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Generate query
        $table = $called_class::$table;                
        $query = "SELECT * FROM $table WHERE ";

        if (!is_array($conditions[0])) {
            // Check if there is only one condition

            if (count($conditions) == 2) {
                // If there is no middle sign then add deafult (equal)

                $query .= $conditions[0] . " = :" . $conditions[0];
            }
            else if (count($conditions) == 3) {
                $query .= $conditions[0] . " " . $conditions[1] . " :" . $conditions[0];
            }
        }
        else {
            // More conditions

            foreach ($conditions as $key => $condition) {
                if (count($condition) == 2) {
                    // If there is no middle sign then add deafult (equal)
    
                    if ($key > 0) {
                        // This is not the first element

                        $query .= " AND " . $condition[0] . " = :" . $condition[0];
                    }
                    else {
                        // This is first element

                        $query .= $condition[0] . " = :" . $condition[0];                        
                    }
                }
                else if (count($condition) == 3) {
                    if ($key > 0) {
                        // This is not the first element

                        $query .= " AND " . $condition[0] . " " . $condition[1] . " :" . $condition[0];
                    }
                    else {
                        // This is first element                        

                        $query .= $condition[0] . " " . $condition[1] . " :" . $condition[0];                        
                    }
                }
            }
        }

        // Prepare statement
        $statement = self::$database->prepare($query);

        // Bind params
        if (!is_array($conditions[0])) {
            // One condition

            $number_of_fields = count($conditions);

            if (is_numeric($conditions[$number_of_fields - 1])) {
                $statement->bindParam(':' . $conditions[0], intval($conditions[$number_of_fields - 1]), PDO::PARAM_INT);
            }
            else if (gettype($conditions[$number_of_fields - 1]) == 'string') {
                $statement->bindParam(':' . $conditions[0], $conditions[$number_of_fields - 1], PDO::PARAM_STR);                    
            }
        }
        else {
            // Multiple conditions

            foreach ($conditions as $condition) {
                $number_of_fields = count($condition);
                
                if (is_numeric($condition[$number_of_fields - 1])) {
                    $statement->bindParam(':' . $condition[0], intval($condition[$number_of_fields - 1]), PDO::PARAM_INT);
                }
                else if (gettype($condition[$number_of_fields - 1]) == 'string') {
                    $statement->bindParam(':' . $condition[0], $condition[$number_of_fields - 1], PDO::PARAM_STR);                    
                }
            }
        }

        // Execute statement
        $statement->execute();  
        
        // Store result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($statement->rowCount() == 0) {
            // If there is no row selected

            return null;
        }

        $array = [];

        foreach ($result as $element) {
            // For each row selected

            $class = new $called_class(); // Create an instance
            self::fill($class, $element); // Fill object fields

            $array[] = $class; // Add object to array
        }

        return $array;
    }

    public static function whereFirst(array $conditions)
    {
        $array = self::where($conditions);

        if ($array) {
            // If array is not null 

            return $array[0]; // Return first object
        }

        return null;
    }

    public function delete() 
    {
        self::$database = Database::instance();   

        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If called class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Generate query
        $table = $called_class::$table;                
        $query = "DELETE FROM $table WHERE id = :id";

        try {
            // Prepare statement
            $statement = self::$database->prepare($query);

            // Bind params        
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute statement
            $statement->execute();  

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function save()
    {
        self::$database = Database::instance();   

        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If called class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Generate query
        $table = $called_class::$table;             
        $query = "UPDATE $table SET ";

        foreach ($called_class::$fields as $index => $field) {
            if ($field != 'id') {
                if ($index > 1) {
                    $query .= ', ' . $field . ' = :' . $field;
                }
                else {
                    $query .= $field . ' = :' . $field;            
                }
            }
        }

        $query .= ' WHERE id = ' . $this->id;

        // Prepare statement
        $statement = self::$database->prepare($query);

        // Bind params
        foreach ($called_class::$fields as $index => $field) {
            if ($field != 'id') {
                if (is_numeric($this->{$field})) {
                    $statement->bindParam(':' . $field, intval($this->{$field}), PDO::PARAM_INT);
                }
                else if (gettype($this->{$field}) == 'string') {
                    $statement->bindParam(':' . $field, $this->{$field}, PDO::PARAM_STR);                    
                }
            }
        }

        try {
            // Execute statement
            $statement->execute();  

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public static function deleteWhere(array $conditions) 
    {
        $array = self::where($conditions);

        foreach ($array as $element) {
            $element->delete();
        }
    }

    public function hasMany($class, $local_key, $foreign_key)
    {
        self::$database = Database::instance();   

        if (!isset($class::$table)) {
            // If class table is not set

            throw new Exception('Table name for class ' . $class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        $first_table  = $called_class::$table; 
        $second_table = $class::$table;

        // Generate query
        $query = "SELECT $second_table.* FROM $first_table INNER JOIN $second_table ON $first_table.$local_key = $second_table.$foreign_key WHERE $second_table.$foreign_key = :value";        

        // Prepare statement
        $statement = self::$database->prepare($query);     

        // Bind params
        if (is_numeric($this->$local_key)) {
            $statement->bindParam(':value', intval($this->$local_key), PDO::PARAM_INT);
        }
        else if (gettype($this->$local_key) == 'string') {
            $statement->bindParam(':value', $this->$local_key, PDO::PARAM_STR);                    
        }

        // Execute statement
        $statement->execute();  

        // Store result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($statement->rowCount() == 0) {
            // If there is no row selected

            return null;
        }

        $array = [];

        foreach ($result as $element) {
            // For each row selected

            $class = new $class(); // Create an instance
            self::fill($class, $element); // Fill object fields

            $array[] = $class; // Add object to array
        }
        
        return $array;
    }

    public function hasOne($class, $local_key, $foreign_key)
    {
        self::$database = Database::instance();   

        if (!isset($class::$table)) {
            // If class table is not set

            throw new Exception('Table name for class ' . $class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        // Get called class name
        $called_class = get_called_class();

        if (!isset($called_class::$table)) {
            // If class table is not set

            throw new Exception('Table name for class ' . $called_class . ' is not defined. Please add \'protected static $table\' in your class.');
        }

        $first_table  = $called_class::$table; 
        $second_table = $class::$table;

        // Generate query
        $query = "SELECT * FROM $second_table WHERE $second_table.$foreign_key = :value LIMIT 1";        

        // Prepare statement
        $statement = self::$database->prepare($query); 
        
        // Bind params
        if (is_numeric($this->$local_key)) {
            $statement->bindParam(':value', intval($this->$local_key), PDO::PARAM_INT);
        }
        else if (gettype($this->$local_key) == 'string') {
            $statement->bindParam(':value', $this->$local_key, PDO::PARAM_STR);                    
        }
        
        // Execute statement
        $statement->execute();  

        // Store result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($statement->rowCount() == 0) {
            // If there is no row selected

            return null;
        }

        $array = [];

        foreach ($result as $element) {
            // For each row selected

            $class = new $class(); // Create an instance
            self::fill($class, $element); // Fill object fields

            $array[] = $class; // Add object to array
        }

        return $array[0];
    }

    // Fill class fields with elements from data
    public static function fill(&$class, $data) 
    {
        foreach ($class::$fields as $field) {
            if (isset($data[$field])) {
                $class->{$field} = $data[$field];
            }
        }
    }
}