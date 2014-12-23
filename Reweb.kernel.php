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
    
    private $fs;  //contains reference to the filesystem driver object
    private $drivers; //contains all of the driver objects
    private $config;
    
    
    
    public function __construct($config = false) {
        
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
       
       
       
        //check for a filesystem driver
       if($this->config['fs']['driver'] == "Default_fs")
       {
           //load the default filesystem driver
           
           if(!require_once($_SERVER['DOCUMENT_ROOT'] . '/Reweb/drivers/fs.driver.php'))
           {
               return false;
           }
           
           //set the driver instance
           $this->drivers['fs'] = new Default_fs();
           //create a reference to the fs driver in the kernel `fs` attribute
           $this->fs =& $this->drivers['fs'];
                      
           return true;
       }
        

       
    }
    
    public function driver_check($driver)
    {

        

        return true;

    }


    public function driver_load($driver, $check=true)
    {
        if($check)
        {
            if(!$this->check_driver($driver))
            {
                return false;
            }
        }
        
        
        
        return true;
    }
}

abstract class Driver
{
    
    protected $driver_data;
    
    public function __construct($args = 0) {
       
        $this->driver_data();
        
        $this->init($args);
    }
    
    
    //sets the driver data such as name, author and versoin
    abstract protected function driver_data();
    
    abstract protected function init($args);
    
    public function get_driver_data()
    {
        return $this->driver_data;
    }
    
}
