<?php

// Template
class Template
{
    // File path
    private $file;
    // Template data
    private $data;

    // Construct
    public function __construct($file, $data)
    {
        $this->file = $file;
        $this->data = $data;
    }

    // Render template
    public function Render()
    {
        // Check for template file
        if (!file_exists($this->file))
        {
            throw new Error\Http500('template file does not exist: '.$this->file);
        }

        // Copy all data to local variables
        foreach ($this->data as $key => $val)
        {
            // If variable is a template
            if (is_object($val) && get_class($val) == 'Template')
            {
                // Render sub-template
                $$key = $val->Render();
            }
            else
            {
                // Assign local variable
                $$key = $val;
            }
        }

        // Start output buffer
        ob_start();

        // Include template file
        require($this->file);

        // Get the contents of the buffer
        $content = ob_get_contents();

        // Clean the buffer
        ob_end_clean();

        // Return the rendered content
        return $content;
    }
}
