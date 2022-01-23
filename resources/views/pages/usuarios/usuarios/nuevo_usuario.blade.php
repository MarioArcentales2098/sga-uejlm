@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Agregar nuevo usuario')
@section('style-herramienta')@endsection
@section('content-herramienta')
    
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Agregar nuevo usuario</h4>            
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-6">    
                    <div class="form-group">
                        <label for="userName">User Name<span class="text-danger">*</span></label>
                        <input type="text" name="nick" parsley-trigger="change" required="" placeholder="Enter user name" class="form-control" id="userName">
                    </div>
                    <div class="form-group">
                        <label for="emailAddress">Email address<span class="text-danger">*</span></label>
                        <input type="email" name="email" parsley-trigger="change" required="" placeholder="Enter email" class="form-control" id="emailAddress">
                    </div>
                    <div class="form-group">
                        <label for="pass1">Password<span class="text-danger">*</span></label>
                        <input id="pass1" type="password" placeholder="Password" required="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="passWord2">Confirm Password <span class="text-danger">*</span></label>
                        <input data-parsley-equalto="#pass1" type="password" required="" placeholder="Password" class="form-control" id="passWord2">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <input id="remember-1" type="checkbox" data-parsley-multiple="remember-1">
                            <label for="remember-1"> Remember me </label>
                        </div>
                    </div>  
                </div>

                <div class="col-lg-6">                                       
                    <div class="form-group">
                        <label for="userName">User Name<span class="text-danger">*</span></label>
                        <input type="text" name="nick" parsley-trigger="change" required="" placeholder="Enter user name" class="form-control" id="userName">
                    </div>
                    <div class="form-group">
                        <label for="emailAddress">Email address<span class="text-danger">*</span></label>
                        <input type="email" name="email" parsley-trigger="change" required="" placeholder="Enter email" class="form-control" id="emailAddress">
                    </div>
                    <div class="form-group">
                        <label for="pass1">Password<span class="text-danger">*</span></label>
                        <input id="pass1" type="password" placeholder="Password" required="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="passWord2">Confirm Password <span class="text-danger">*</span></label>
                        <input data-parsley-equalto="#pass1" type="password" required="" placeholder="Password" class="form-control" id="passWord2">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <input id="remember-1" type="checkbox" data-parsley-multiple="remember-1">
                            <label for="remember-1"> Remember me </label>
                        </div>
                    </div>
                </div>         
                
                <div class="vol-lg-12">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-herramienta')

@endsection