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

class MySQL_db extends Driver
{
    
    protected $driver_data;
    private $link;
    
    
    
    protected function init($args)
    {
             
    }
    
    protected function driver_data()
    {
        $this->driver_data['name'] = "Default MySQL Driver";
        $this->driver_data['type'] = "Database";
        $this->driver_data['description'] = "The default MySQL driver for the Reweb framework";
        $this->driver_data['version'] = "0.0.1";
        $this->driver_data['author'] = "Daniel Henry";
        $this->driver_data['unique'] = "db"; 
        
    }
    
    public function load($args)
    {
        //args should be formatted as the kernel $config['db'] array.
        try {
             $this->link = new PDO("mysql:host={$args['server']};dbname={$args['db_name']};charset=utf8", "{$args['username']}", "{$args['password']}");
        } catch(PDOException $ex) {
            echo "An Error occured!<br />"; //user friendly message
            echo $ex->getMessage();
        }
        
    }
    
    public function raw_query($query)
    {
        $data = $this->link->query($query);
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
