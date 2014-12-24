<?php

/* 
 * Copyright (C) 2014 Daniel Henry
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License V2
 * as published by the Free Software Foundation; 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


class Default_fs extends Driver
{
    
    protected $driver_data;
    private $directories;
    private $doc_root;
    
    
    protected function init($args)
    {

        $this->add_directory("rw_root", "doc_root");
        $this->add_directory("config", "rw_root");
        $this->add_directory("drivers", "rw_root");
        $this->add_directory("libraries", "rw_root");
        $this->add_directory("modules", "rw_root");
        $this->add_directory("var", "rw_root");
          
    }
    
    private function add_directory($name, $parent="root")
    {
        
        if(!$this->directories[$name] = new Fs_dir($name, $parent))
        {
            return false;
        }
        
        return true;
    }
    
    public function get_path($directory)
    {

        if(!isset($this->directories[$directory]))
        {
            return false;
        }
        //build a path
        unset($path);

        $path[] = $this->directories[$directory]->get_name();

        $path_pointer = $this->directories[$directory]->get_parent();

        while ($path_pointer != "doc_root")
        {

            $path[] = $this->directories[$path_pointer]->get_name();
            $path_pointer = $this->directories[$path_pointer]->get_parent();

        }

        $path[] = $this->doc_root;

        //create the string

        $path = array_reverse($path);

        $path_string = "";

        foreach ($path as $dir)
        {
            $path_string .= $dir . "/";
        }

        return $path_string;
    }
    
    public function get_parent($dir)
    {
        if(!isset($this->directories[$dir]) || $dir == "doc_root")
        {
            return false;
        }
        
        return $this->directories[$dir]->get_parent();
    }
    
    protected function driver_data()
    {
        $this->driver_data['name'] = "Default Filesystem";
        $this->driver_data['type'] = "Filesystem";
        $this->driver_data['description'] = "The default filesystem for the Reweb framework";
        $this->driver_data['version'] = "0.0.1";
        $this->driver_data['author'] = "Daniel Henry";
        $this->driver_data['unique'] = "fs"; 
    }

    
    public function set_doc_root($doc_root)
    {
        if(substr($doc_root, -1) == '/') {
            $doc_root = substr($doc_root, 0, -1);
           }
        $this->doc_root = $doc_root;
        return true;
        
    }
    
    public function get_doc_root()
    {
        return $this->doc_root;
    }

}


class Fs_dir
{
    
    private $name; // name of the directory
    private $parent; // name of the parent
    
    public function __construct($name, $parent="root")
    {
        
        if($name == "" || $parent == "")
        {
            
            return false;
        }
        
        $this->name = $name;
        
        $this->parent = $parent;
        
        return true;
    }
    
    public function get_name()
    {
        
        return $this->name;
        
    }
    
    public function get_parent()
    {
        
        return $this->parent;
        
    }
}

?>