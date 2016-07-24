{{ Form::open(['url' => 'permissions/create']) }}

<dialog id="permissioncreate-dialog" class="mdl-dialog">
	<h1 class="mdl-dialog__title mdl-color-text--grey-600">{{Lang::get('admin.createpermission')}}</h1>
    <div class="mdl-dialog__content">
        
    	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label @if ($errors->has('permissiondialog_inherit_id')) is-invalid is-dirty @endif"">
            {!!Form::select('permissiondialog_inherit_id', $permissiondialog['permissions'], 0, ['id' => 'permissiondialog_inherit_id', 'class' => 'mdl-textfield__input'])!!}
    		{!!Form::label('permissiondialog_inherit_id', Lang::get('admin.inherit'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label is-dirty'])!!}
        	<span class="mdl-textfield__error">{{ $errors->first('permissiondialog_inherit_id') }}</span>
        </div>                        
    	
    	<div class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('permissiondialog_name')) is-invalid is-dirty @endif"" data-upgraded="eP">
     		{!!Form::text('permissiondialog_name', "", ['id' => 'permissiondialog_name', 'class' => 'mdl-textfield__input'])!!}
    		{!!Form::label('permissiondialog_name', Lang::get('admin.name'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label is-dirty'])!!}
    		<span class="mdl-textfield__error">{{ $errors->first('permissiondialog_name') }}</span>
    	</div>                         
    	
    	<div class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('permissiondialog_slug')) is-invalid is-dirty @endif"" data-upgraded="eP">
     		{!!Form::text('permissiondialog_slug', "", ['id' => 'permissiondialog_slug', 'class' => 'mdl-textfield__input'])!!}
    		{!!Form::label('permissiondialog_slug', Lang::get('admin.slug'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label is-dirty'])!!}
    		<span class="mdl-textfield__error">{{ $errors->first('permissiondialog_slug') }}</span>
    	</div>                         
    	
    	<div class="mdl-textfield mdl-js-textfield is-upgraded is-focused mdl-textfield--floating-label @if ($errors->has('permissiondialog_description')) is-invalid is-dirty @endif"" data-upgraded="eP">
     		{!!Form::text('permissiondialog_description', "", ['id' => 'permissiondialog_description', 'class' => 'mdl-textfield__input'])!!}
    		{!!Form::label('permissiondialog_description', Lang::get('admin.description'), ['class' => 'mdl-color-text--primary-contrast mdl-textfield__label is-dirty'])!!}
    		<span class="mdl-textfield__error">{{ $errors->first('permissiondialog_description') }}</span>
    	</div>  
    	
    	{!!Form::hidden('permissiondialog_role_id', $permissiondialog['role_id'], ['id' => 'permissiondialog_role_id'])!!}
		
    </div>
    <div class="mdl-dialog__actions mdl-dialog__actions--full-width">
      <button type="submit" class="mdl-button create-permission">{{Lang::get('general.Submit')}}</button>
      <button type="button" class="mdl-button close-permission">{{Lang::get('general.Close')}}</button>
    </div>
</dialog>

{!! Form::close() !!}

<script type="text/javascript">
window.onload=function(){
    if($("#permissioncreate-dialog").length > 0) {
		var dialogPermissionCreate = document.querySelector('#permissioncreate-dialog');
		var dialogPermissionCreateAddButton = "";
		if (! dialogPermissionCreate.showModal) {
			dialogPolyfill.registerDialog(dialogPermissionCreate);
		}
	}
		
	$("#permission-add").click(function(event){
		event.preventDefault();
		dialogPermissionCreateAddButton = $(this);
		dialogPermissionCreate.showModal();
	});
	
    $('.close-permission').click(function() {
    	dialogPermissionCreate.close();
    });
}    
</script>	