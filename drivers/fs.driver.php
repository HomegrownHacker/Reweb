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
    private $fs;
    
    
    
    protected function init($args)
    {
             
        $this->fs['doc_root'] = $_SERVER['DOCUMENT_ROOT'];
        $this->fs['rw_root'] = "Reweb";
        $this->fs['config'] = "config";
        $this->fs['drivers'] = "drivers";
        $this->fs['libraries'] = "libraries";
        $this->fs['modules'] = "modules";
        $this->fs['var'] = "var";
    }
    
    protected function driver_data()
    {
        $this->driver_data['name'] = "Default Filesystem";
        $this->driver_data['type'] = "Filesystem";
        $this->driver_data['description'] = "The default filesystem for the Reweb framework";
        $this->driver_data['version'] = "0.0.1";
        $this->driver_data['author'] = "Daniel Henry";
        $this->driver_data['unique'] = true; 
    }
    
    public function get_path($index)
    {
        if(isset($this->fs[$index]))
        {
            return $this->fs[$index];
        }
        
        return false;

    }
}

?>