@extends('web.layouts.app')
@section('style')
@endsection 
@section('content')
<section class="sptb">
    <div class="container customerpage">
        <div class="row">
            <div class="single-page">
                <div class="col-lg-6 col-xl-4 col-md-6 d-block mx-auto">
                    <div class="ms-shadow p-4">
                        <form action="{{route('reset.password.post')}}" method="post" id="" class="card-body pb-2" tabindex="500">
                            @csrf
                            <h3 align="left">Reset Password</h3>
                            <p class="text-dark text-left pb-4">Set new password</p>
                            <div class="control-group form-group">
                                <div class="form-group">
                                    <label class="form-label text-dark">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Please enter new password" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="password-eye" data-id="password"><i class="fa fa-eye-slash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="error-message">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="control-group form-group">
                                <div class="form-group">
                                    <label class="form-label text-dark">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Please enter confirm new password" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="password-eye" data-id="password_confirmation"><i class="fa fa-eye-slash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="error-message">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="submit">
                                <input type="submit" class="btn btn-primary btn-block" value="Save Password">
                            </div>
                        </form>
                        <hr class="divider">
                        <div class="card-body pb-3 pt-3">
                            <div class="d-flex mb-0">
                                <div class="ms-auto font-weight-bold">
                                    <a href="{{route('login.get')}}" class="ms-default-color mx-1">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 

@section('script')

@endsection
