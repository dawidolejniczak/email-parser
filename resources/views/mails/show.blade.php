@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">All emails in thread with {{ $mails[0]->target_email }}</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Content</th>
                                <th scope="col">Sent at</th>
                                <th scope="col">Sent from you / to you</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mails as $mail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{!! $mail->content !!}</td>
                                    <td>{{ $mail->created_at }}</td>
                                    <td>
                                        @if($mail->is_sent)
                                            Sent from you
                                        @else
                                            Sent to you
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <br/>
                <br/>

                <div class="card">
                    <div class="card-header">
                        Add new email
                    </div>
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{ route('mails.store') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="target_email" value="{{ $mails[0]->target_email }}">
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
