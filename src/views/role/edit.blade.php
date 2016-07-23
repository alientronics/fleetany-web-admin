@extends('layouts.default')

@section('header')
	@if ($role->id)
	{{--*/ $operation = 'update' /*--}}
	<span class="mdl-layout-title">{{$role->name}}</span>
	@else
	{{--*/ $operation = 'create' /*--}}
	<span class="mdl-layout-title">{{Lang::get("admin.Role")}}</span>
	@endif
@stop

@section('content')

@permission($operation.'.role')

<div class="">
	<section class="demo-section demo-section--textfield demo-page--textfield mdl-upgraded">
		<div class="demo-preview-block">

@if (!$role->id)
{!! Form::open(['route' => 'role.store']) !!}
@else
{!! Form::model('$role', [
        'method'=>'PUT',
        'route' => ['role.update',$role->id]
    ]) !!}
@endif
		    
    
            <div id="name" class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('name')) is-invalid is-dirty @endif"" data-upgraded="eP">
            	{!!Form::text('name', $role->name, ['id' => 'name', 'class' => 'mdl-textfield__input'])!!}
            	{!!Form::label('name', Lang::get('admin.name'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label'])!!}
            	<span class="mdl-textfield__error">{{ $errors->first('name') }}</span>
            </div>
    
            <div id="div_slug" class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('slug')) is-invalid is-dirty @endif"" data-upgraded="eP">
            	{!!Form::text('slug', $role->slug, ['id' => 'slug', 'class' => 'mdl-textfield__input'])!!}
            	{!!Form::label('slug', Lang::get('admin.slug'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label'])!!}
            	<span class="mdl-textfield__error">{{ $errors->first('slug') }}</span>
            </div>
    
            <div id="div_description" class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('description')) is-invalid is-dirty @endif"" data-upgraded="eP">
            	{!!Form::text('description', $role->description, ['id' => 'description', 'class' => 'mdl-textfield__input'])!!}
            	{!!Form::label('description', Lang::get('admin.description'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label'])!!}
            	<span class="mdl-textfield__error">{{ $errors->first('description') }}</span>
            </div>
    
        	<div class="div_entry_permissions">
        		<div @if (empty($permissions)) style="display:none" @endif class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('permissions')) is-invalid is-dirty @endif"" data-upgraded="eP">
             		@foreach($permissions as $permission)
                 		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="permission{{$permission->id}}">
                          <input name="permissions[]" type="checkbox" id="permission{{$permission->id}}" class="mdl-checkbox__input" value={{$permission->id}}  @if(in_array($permission->id, $role_permissions)) checked @endif />
                          <span class="mdl-checkbox__label">{{$permission->name}}</span>
                        </label>
                    @endforeach
        			{!!Form::label('permissions', Lang::get('admin.Permissions'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label is-dirty'])!!}
        			<span class="mdl-textfield__error">{{ $errors->first('description') }}</span>
        		</div>
            </div>
            
			<div class="mdl-card__actions">
				<button type="submit" class="mdl-button mdl-color--primary mdl-color-text--accent-contrast mdl-js-button mdl-button--raised mdl-button--colored">
                  {{ Lang::get('general.Send') }} 
                </button>
			</div>
	
{!! Form::close() !!}

		</div>
	</section>
</div>

@else
<div class="alert alert-info">
	{{Lang::get("general.accessdenied")}}
</div>
@endpermission

@stop