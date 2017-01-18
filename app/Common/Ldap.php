<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 17/01/2017
 * Time: 15:35
 */

namespace App\Common;


class Ldap
{
    private $ldap;
    private $conn;
    private $bind;

    public function __construct()
    {
        $this->ldap['user'] = env('LDAP_USER');
        $this->ldap['pass'] = env('LDAP_PASS');
        $this->ldap['host'] = env('LDAP_HOST');
        $this->ldap['port'] = env('LDAP_PORT');
        $this->ldap['dn'] = 'uid=' . $this->ldap['user'] . ',ou=people,dc=' . env('LDAP_DOMAIN') . ',dc=com,dc=br';
        $ldap['base'] = '';
        $this->conn = ldap_connect(   $this->ldap['host'], $this->ldap['port']);
        ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $this->bind = ldap_bind($this->conn,$this->ldap['dn'],$this->ldap['pass']);

    }
    public function search($email){
        $result = ldap_search($this->conn, $this->ldap, "(mail=$email)") or die ("Error in search query: " . ldap_error($this->conn));
        $data = ldap_get_entries($this->conn, $result);
        return $data;
    }

}