<?php
/**
 * __      ___    _    ___                 _          
 * \ \    / (_)__| |_ | __|__ _  _ _ _  __| |_ _ _  _ 
 *  \ \/\/ /| (_-< ' \| _/ _ \ || | ' \/ _` | '_| || |
 *   \_/\_/ |_/__/_||_|_|\___/\_,_|_||_\__,_|_|  \_, |
 *                                               |__/
 *                                                                               
 * Created by : bngreer
 * Date: 1/4/13
 * Time: 4:59 PM       
 * Copyright 2013 The WishFoundry / Ben Greer
 * 
 * 
 */

return [
    [
        'id' => 1,
        'username' => 'admin',
        'email' => 'admin@email.com',
        'password' => Hash::make('password'),
        'created_at'    => new DateTime,
        'updated_at'    => new DateTime,

    ],
];