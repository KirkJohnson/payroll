@extends('layouts.app')

@section('content')
<home-slider></home-slider>

<div class='col-md-12'>
  <a class='btn btn-primary' href='@php echo route("reports"); @endphp'><i class="fas fa-long-arrow-alt-left"></i> Report List</a>
</div>
<div class="row container justify-content-center">
    
     <div class="col-md-8">
             <div class="card">
                 <div class="card-body">
                      <h1 class="card-title">Report: {{$report_id}}</h1>

                      @if(count($hours_worked) > 0)
                          <table class='table'>
                              <thead>
                                  <tr>
                                      <th>Pay Period</th>
                                      <th>Employee Id</th>
                                      <th>Amount Paid</th>
                                  </tr>
                              </thead>
                              <tbody>
                              @foreach( $hours_worked as $time => $employees)
                              @php
                                 $start_date = date('d/m/Y', $time );
                                 $end_date = (intval(date('d', $time)) == 1) ? '16/'.date('m', $time).'/'.date('Y',$time) :
                                                                          date('t/m/Y', $time);  
                                 $pay_period = $start_date." - ".$end_date;
                              @endphp
                                 @foreach( $employees as $emp_id => $amount_paid )
                                     <tr>
                                         <td>{{$pay_period}}</td>
                                         <td>{{$emp_id}}</td>
                                         <td>{{$amount_paid}}</td>
                                     </tr>
                                 @endforeach

                              @endforeach
                              </tbody>
                         </table>
                      @else 
                      <p>No data in report</p>
                      @endif
               </div>
         </div> 
    </div>
</div>
@endsection
