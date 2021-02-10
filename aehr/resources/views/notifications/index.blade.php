@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('title', 'Notifications')
@section('content2')
    <h2>Notifications</h2>
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
            <strong>Woaah an Error!</strong> {{ Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('response'))
        <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
            <strong>Success!</strong> {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row container mt-2">
        <div class="col-12">
            <div class="font-weight-bold">Unclosed Notification(s)</div>
            <a href="{{route('notifications.resolveAll')}}" class="btn btn-primary mx-2 mt-2 shadow" id="resolve">Resolve All Notification(s)</a>
            <div id="loading" style="position:fixed;top: 0;left: -15px; z-index: 999 !important; display: none;">
                <div class="container text-center">
                    <img style=" width: 100vw !important; height: 140vh !important;" src="{{asset('images/loading.gif')}}"/>
                    <div class="font-weight-bold">LOADING . . .</div>
                </div>
            </div>
            <div class="list-group m-2 shadow-sm" id="notification-body">
                @foreach ($data['unclosed'] as $unclosed)
                    <div class="list-group-item list-group-item-action">
                        <div class="row">
                            <div class="col-6">
                                <small class="font-weight-bold">
                                    Details
                                </small>
                                <div>{{ucfirst($unclosed->details)}}</div>
                            </div>
                            <div class="col-2">
                                <small class="font-weight-bold">
                                    Origin
                                </small>
                                <div>{{ucfirst($unclosed->origin)}}</div>
                            </div>
                            <div class="col-2">
                                <small class="font-weight-bold">
                                    Date Occured
                                </small>
                                <div>{{date_format(date_create($unclosed->created), 'Y-m-d', )}}</div>
                            </div>
                            <div class="col-2">
                                <div class="m-3"><a href="{{route('notification.resolve', $unclosed->id)}}" class="align-middle">Close Notification</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        function startLoad() {
            /*This is the loading gif, It will popup as soon as startLoad is called*/
            {{--$('#loading').html('<img src="{{asset('images/loading.gif')}}"/>');--}}
            $('#loading').show();
            $('#notification-body').hide();
        }
        /*This binds a click event to the refresh button*/
        $('#resolve').click(startLoad);
    </script>
@endsection
