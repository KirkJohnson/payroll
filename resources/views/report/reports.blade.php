@extends('layouts.app')

@section('content')
<home-slider></home-slider>
<div class="row justify-content-center container-fluid">
    
    <div class="col-md-5">
        <div id="upload_report" class="card">
            <div class="card-body">
                <h1 class="card-title">Upload New Report</h1>
               <form id='report_form' class='form-horizontal' method="POST" action="@php echo route('upload'); @endphp" enctype='multipart/form-data'>
                   {{ csrf_field() }}
                   <div class="form-group">
                     <input type='file' name='report' id='report' />
                     <button type='submit' class='btn btn-primary'>Upload Report <i class="fas fa-upload"></i></button>
                   </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row container-fluid justify-content-center mt-3">
    @if($reports->count() > 0 )
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
              <h1 class="card-title">Reports</h1>
              <table class='table'>
                  <thead>
                  <th>Report Id</th>
                  <th>Actions</th>
                  </thead>
                  <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>@php echo $report->id;@endphp</td>
                        <td><a titl="view report" href='@php echo route("view_report",["id"=>$report->id]);@endphp'><i class="far fa-eye"></i></a></td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
            </div>
        </div>
    </div>
    @else
        <div class="col-md-8">
            <p>No reports uploaded yet</p>
        </div>
    @endif
</div>
@endsection
