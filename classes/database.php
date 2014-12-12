<?php

// Database
abstract class Database
{
    // Build a database object
    //     null:  fetch all
    //     array: build from array
    //     int:   find with specified id
    public static function Factory($input = null)
    {
        // Get current class name
        $class_name = get_called_class();

        // Remove namespace
        $table_name = $class_name;
        if ($pos = strrpos($table_name, '\\'))
        {
            $table_name = substr($table_name, $pos+1);
        }
        
        // Construct table name
        $table_name = Database::Plouralise(strtolower($table_name));

        // If input is an array
        if (is_array($input))
        {
            // Build object from array
            $output = new $class_name($table_name);
            $output->Build($input);
        }
        else
        {
            // Output is array
            $output = array();

            // Construct query
            $query = 'SELECT * FROM '.$table_name;
            if ($input != null)
            {
                // Find with specified id
                $query .= ' WHERE id = '.Db::Instance()->Escape($input);
            }
            $query .= ';';

            // Execute query
            $res = Db::Instance()->Query($query);

            // Get results
            if ($res)
            {
                // Check number of results
                switch ($res->num_rows)
                {
                    // No results
                    case 0:
                        throw new Exception('Could not find in database');
                    // One result
                    case 1:
                        // Build single object
                        $row = $res->fetch_assoc();
                        $output = new $class_name($table_name);
                        $output->Build($row);
                        break;
                    // Many results
                    default:
                        // For each row
                        while($row = $res->fetch_assoc())
                        {
                            // Build single object
                            $object = new $class_name($table_name);
                            $object->Build($row);
                            $output[] = $object;
                        }
                        break;
                }
            }
            else
            {
                throw new Exception('Could not find in database');
            }
        }

        // Return output
        return $output;
    }

    // Table name
    protected $table_name = null;

    // Object data
    protected $data = array();

    // Construct
    protected function __construct($table_name)
    {
        $this->table_name = $table_name;
    }

    // Build from array
    protected function Build(array $data)
    {
        $this->data = $data;
    }

    // Check if loaded
    public function Loaded()
    {
        return count($this->data) > 0;
    }

    // Save object
    public function Save()
    {
        // If id is set
        if (!isset($this->data['id']) || !$this->data['id'])
        {
            // Insert object
            return $this->Insert();
        }
        else
        {
            // Update object
            return $this->Update();
        }
    }

    // Insert object
    protected function Insert()
    {
        // Build column list
        $columns = explode(array_keys(', ', $this->data));
        // Build value list
        $values = explode(array_values(', ', $this->data));
        // Construct query
        $query = 'INSERT INTO '.$this->table_name.' ('.$columns.') VALUES ('.$values.');';
        // Execute query
        return Db::Instance()->Query($query);
    }

    // Update object
    protected function Update()
    {
        // Build query parameters
        $updates = '';
        foreach ($this->data as $col => $val)
        {
            $updates .= '`'.$col.'` = "'.Db::Instance()->Escape($val).'", ';
        }
        $updates = substr($updates, 0, -2);
        // Construct query
        $query = 'UPDATE '.$this->table_name.' SET '.$updates.' WHERE id = '.Db::Instance()->Escape($this->data['id']).';';
        // Execute query
        return Db::Instance()->Query($query);
    }

    // Pplouralise a word
    protected static function Plouralise($string)
    {
        // If it ends in 'y'
        if (substr($string, -1, 1) == 'y')
        {
            // Remove 'y' and add 'ies'
            return substr($string, 0, -1).'ies';
        }
        else
        {
            // Append 's'
            return $string.'s';
        }
    }
    
    // Magic getter for column data
    public function __get($column_name)
    {
        if (array_key_exists($column_name, $this->data))
        {
            return $this->data[$column_name];
        }
        throw new Exception('invalid column name');
    }

    // Magic setter for column data
    public function __set($column_name, $value)
    {
        $this->data[$column_name] = $value;
    }
}
