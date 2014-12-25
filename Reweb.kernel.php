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

/**
 * 
 * Reweb.kernel.php
 * The classes that make up the main kernel of Reweb
 * 
 */


class Reweb
{
    
    private $kernel_data; //contains static kernel information
    
    public $fs;  //contains reference to the filesystem driver object
    public $db;  //contains a reference to the database object
    public $drivers; //contains all of the driver objects
    private $config;  //kernel configuration
    
    public $library;  //contains the kernel_library object
    
    public $module; //contains the kernel_module object
    public $mod;    //Shorthand reference to $this->module
    
    public function __construct($config = false) {
        
        //set kernel data
        $this->kernel_data['version'] = "0.0.1";
        
        if(!$config)
        {
            //load the configuration file at the default location
            
            $config = "{$_SERVER['DOCUMENT_ROOT']}/Reweb/config/kernel.conf.php";
            
        }
        
        //load the configuratoin file
        if(!file_exists($config))
        {
            return false;
        }
        
        require_once($config);
        $this->config = $rw_conf;
              
        //check for doc_root override
        if($this->config['fs']['doc_root'])
        {
            $doc_root = $this->config['doc_root'];
        }else{
            $doc_root = $_SERVER['DOCUMENT_ROOT'];
        }
        
        //check for a filesystem driver
       if($this->config['fs']['driver'] == "Default_fs")
       {

           //load the default filesystem driver
           
           if(!require_once($doc_root . '/Reweb/drivers/fs.driver.php'))
           {
               return false;
           }
           
           //set the driver instance
           $this->drivers['fs'] = new Default_fs($this);
           //create a reference to the fs driver in the kernel `fs` attribute
           $this->fs =& $this->drivers['fs'];
                      
       }else{
           
           //load the alternative filesystem driver
           
           if(!require_once($doc_root . $this->config['fs']['driver_path']))
           {
               return false;
           }
           
           //set the driver instance
           $this->drivers['fs'] = new $this->config['fs']['driver']($this);
           //create a reference to the fs driver in the kernel `fs` attribute
           $this->fs =& $this->drivers['fs'];
           
       }
       
       $this->fs->set_doc_root($doc_root);
       
       //check for database autoload
       
       if($this->config['db']['use_database'])
       {
           if(!$this->driver_load($this->config['db']['driver']))
           {
               //unable to load the driver, error out.
               
               
               return false;
           }
       }

       //create kernel_module object and shorthand reference.
       
       $this->module = new Kernel_Module($this);
       $this->mod = &$this->module;
       return true;
    }
    
    public function driver_check($driver)
    {
        $path = "{$this->fs->get_path("doc_root")}/{$this->fs->get_path("rw_root")}/{$this->fs->get_path("drivers")}/$driver.driver.php";
        
        if(!file_exists($path))
        {
            return false;
            
        }
        
        if(!require_once($path))
        {
            return false;
        }
        
        if(!$temp_driver = new $driver($this))
        {
            return false;
        }

        return $temp_driver->get_driver_data();

    }

    public function driver_load($driver, $args = 0, $check=true)
    {
        //check flag is set, so check the driver.
        if($check)
        {
            if(!$this->driver_check($driver))
            {
                return false;
            }
        }
        
        //build a path
        $path = "{$this->fs->get_path("doc_root")}/{$this->fs->get_path("rw_root")}/{$this->fs->get_path("drivers")}/$driver.driver.php";
        
        //include the class and create an object
        require_once($path);
        $this->drivers[$driver] = new $driver($this, $args);
        
        //check for a unique flag
        $driver_data = $this->drivers[$driver]->get_driver_data();    
        if($driver_data['unique'])
        {
            //if unique is set, create a root level instance
            $unique = $driver_data['unique'];
            $this->$unique =& $this->drivers[$driver];
        }
        
        
        return true;
    }
    
    public function get_config_block($block)
    {
        
        if(isset($this->config[$block]))
        {
            return $this->config[$block];
        }
        
        return false;
    }
}



abstract class Driver
{
    
    protected $driver_data;
    protected $Rw;  //kernel instance
    
    public function __construct(&$kernel, $args = 0) 
    {
        
        $this->driver_data();
        
        $this->Rw = &$kernel;
        
        if(!$this->init($args))
        {
            return false;
        }
    }
    
    //sets the driver data such as name, author and versoin
    abstract protected function driver_data();
    
    abstract protected function init($args);
    
    public function get_driver_data()
    {
        return $this->driver_data;
    }
    
}

abstract class Library
{
    
    private $library_data;
    
    public function __construct($args = 0)
    {
        
        $this->library_data();
        
        $this->init($args);
        
    }
    
    
    abstract protected function library_data();
    
    abstract protected function init($args);
    
    public function get_library_data()
    {
        
        return $this->library_data;
        
    }
}

abstract class Module
{
    
    private $module_data;
    
    public function __construct($args = 0)
    {
        
        $this->module_data();
        
        $this->init($args);
        
    }
    
    
    abstract protected function module_data();
    
    abstract protected function init($args);
    
    public function get_module_data()
    {
        
        return $this->module_data;
        
    }
}

class Kernel_Module
{
    
    private $Rw; //kernel reference
    private $loaded; //loaded modules
    
    public function __construct(&$kernel)
    {
        $this->Rw = $kernel;
        
        //check for required modules and load them
        
    }
    
    
    public function check($module)
    {
        
        $path = "{$this->Rw->fs->get_path("modules")}$module/$module.module.php";
        
        if(!file_exists($path))
        {
            return false;
        }
        
        if(!require_once($path))
        {
            
            return false;
        }
        
        if(!$test = new $module())
        {
            return false;
        }
        
        return true;
    }
    
    
    //load a module, if successful returns a reference to that module
    public function &load($module, $check = true)
    {
        
        //check the module if possible
        if($check)
        {
            if(!$this->check($module))
            {
                return false;
            }
        }
        
        //create instance of module class
        $path = "{$this->Rw->fs->get_path("modules")}$module/$module.module.php";
        require_once($path);
        $this->loaded[$module] = new $module();
        
        return $this->loaded[$module];
    }    
    
    //check to see that a module is loaded into the kernel
    public function is_loaded($module)
    {
        if(!isset($this->loaded[$module]))
        {
            echo "false";
            return false;
        }
        
        return true;
    }
    
    public function &get_module($module, $check = true)
    {
        if($check)
        {
            if($this->is_loaded($module))
            {
                return $this->loaded[$module];
            }
            return false;
        }
        
        return $this->loaded[$module];
    }
}

abstract class Kernel_Library
{
    
    
    
}