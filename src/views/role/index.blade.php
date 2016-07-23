@extends('layouts.default')

@section('header')
      
      <span class="mdl-layout-title">{{Lang::get("admin.Roles")}}</span>

@stop

@include('role.filter')

@section('content')

<div class="mdl-grid demo-content">

	@include('includes.gridview', [
    	'registers' => $roles,
    	'gridview' => [
    		'pageActive' => 'role',
         	'sortFilters' => [
                ["class" => "mdl-cell--4-col", "name" => "name", "lang" => "admin.Role"], 
                ["class" => "mdl-cell--hide-phone mdl-cell--hide-tablet mdl-cell--6-col", "name" => "description", "lang" => "admin.Description"], 
    		] 
    	]
    ])
     
</div>

@stop