@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if(!$mails->isEmpty())
                    <div class="card">
                        <div class="card-header">All your threads</div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($mails as $mail)
                                    <li class="list-group-item">{{ $mail->target_email }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <br/>
                    <br/>
                @endif

                <div class="card">
                    <div class="card-header">
                        Add new email
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{ route('mail.store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="target_email">Target Email</label>
                                <input type="email" class="form-control" id="target_email" name="target_email">
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
