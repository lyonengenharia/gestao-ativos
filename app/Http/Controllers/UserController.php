<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
    /**
     * @var Adldap
     */
    protected $adldap;

    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays the all LDAP users.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return $request->all();
        //$users = Adldap::getDefaultProvider()->auth()->attempt('wfs', 'Html#2018');
        //$users = $this->adldap->getDefaultProvider()->search()->users()->get();
        //dd($users);
        //return view('users.index', compact('users'));
    }
}
