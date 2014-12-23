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



//filesystem information
$rw_conf['fs']['driver'] = "Default_fs"; //the filesysytem driver to use
$rw_conf['fs']['doc_root'] = false; //override the DOCUMENT_ROOT


//database information
$rw_conf['db']['use_database'] = false; //'true' to load a database
$rw_conf['db']['driver'] = "";
$rw_conf['db']['server'] = "";
$rw_conf['db']['port'] = "";
$rw_conf['db']['username'] = "";
$rw_conf['db']['password'] = "";
$rw_conf['db']['db_name'] = "";