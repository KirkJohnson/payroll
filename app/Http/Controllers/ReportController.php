<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App;
use App\Http\Requests;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /*
     * index display all reports
     */
    public function index(){
      
        return view('report.reports', array( 'reports' => App\Report::all()));
    }
    
    /*
     * upload report form
     */
    public function upload (Request $request){
      
      //make sure file is set
      if( $request->hasFile('report') ) {
        
        //store file get path
        $file = $request->file('report');
        
        //get report id
        $report_id = $this->get_report_number_from_file($file);
        
        //check if report is alread stored
        if( $report_id !== FALSE && is_null(App\Report::find($report_id))) {
          
            //if not process file and store data
            $this->process_report_file($file, $report_id);
        } else {
          //redirect to home with error message
          return redirect('/')->with('warning','Report has already been uploaded');
        }
        
        
        //all was successful redirect to report view
        return redirect('/view_report/'.$report_id);
      }
      return redirect('/')->with("warning","File was not uploaded.  Please upload a file");
      
    }
    /*
     * view specific report
     * @input $request
     * @input $id of report to view
     * 
     */
    public function view( $id) {
        
        //grab hourse worked by report_id
        $hours = App\Hour::where("report_id", '=', $id)->orderBy('date','asc')->get();
        if($hours->count() > 0 ){
        
          //init array which will hold all data formatted for table
          $data = [ "report_id" => $id ];
          
          //put data into an  object
          $hours_worked = [];
          foreach($hours as $hour ) {
              //orgainze data like this
              /*
               * [ [strtotime] =>[
               *                        "emp_id" => hours_worked
               *                    ]
               * ]
               */
              //figure out if the lower part of pay period is the 1st or the last day
              $h_date = strtotime($hour->date);
              
              //get the time for the index of array
              $lower_time = ( date('j',$h_date) > 15 )  ? strtotime(date('m',$h_date)."/16/".date('Y', $h_date)) :
                                $lower_time = strtotime(date('m',$h_date)."/1/".date('Y', $h_date));
              $hours_worked[$lower_time] = (isset($hours_worked[$lower_time])) ? $hours_worked[$lower_time] : array();
              
              //add employee hours for pay period
              $hours_worked[$lower_time][$hour->employee_id] = (isset($hours_worked[$lower_time][$hour->employee_id])) ?
                        ($hours_worked[$lower_time][$hour->employee_id] + $hour->hours_worked ) : $hour->hours_worked;
          
          }
          
          //compute amount paid to each employee
          foreach( $hours_worked as $index => $emp_entry ){
                foreach( $emp_entry as $emp_id => $h_worked ){
                  $emp = App\Employee::find($emp_id);
                  $job = App\JobGroup::find(intval($emp->job_group_id));
                  $hours_worked[$index][$emp_id] = number_format((float)($h_worked * $job->wage) , 2, '.', '' );
                }
          }
          
          //sort array by key
          ksort($hours_worked);
          $data['hours_worked'] = $hours_worked;
          
           //go to view with view data
          return view('report.view', $data);
        } else {
            return redirect('/')->with('warning', 'Invalid report selected.');
        }
    }
    /*
     * process and store data from report file
     */
    private function process_report_file($file, $report_id) {
        
        //create report
        $report = App\Report::create(array('id'=>$report_id));
        $report = App\Report::find($report_id);
        
        //auto detect line endings
        ini_set("auto_detect_line_endings", true);
        
        $fp = fopen($file,'r');
        
        //header columns
        $date = 0;
        $hours_worked = 1;
        $emp_id = 2;
        $job_group = 3;
        
        while( ($line = fgetcsv($fp, 0, ',')) !== FALSE){
            if( $this->format_text($line[$date]) != 'date' && $this->format_text($line[$date]) !== 'report id') {
              //make sure job group exists
                $job = App\JobGroup::where('name','=',trim($line[$job_group]))->first();
                if( $job->count() == 0) {
                    //don't enter data for job that doesn't exists in database
                    continue;
                }
                //see if employee exists if not create one
                if( is_null( $employee = App\Employee::find(intval($line[$emp_id]))) ){
                  $employee  = new App\Employee();
                  $employee->id = intval($line[$emp_id]);
                  $employee->job_group_id = $job->id;
                  $employee->save();
                }
                
                //add hours for employee
               
                $hours = new App\Hour();
                $hours->report_id = $report->id;
                $hours->hours_worked = $line[$hours_worked];
                $hours->date = date('Y-m-d H:i:s', strtotime( str_replace('/','-',$line[$date])));
                $hours->employee_id = $employee->id;
                $hours->save();
            }
        }
        
    }
    /*
     * helper function for formatiing text for comparisons
     */
    private function format_text($text){
      return strtolower(trim($text));
    }
    /*
     * get a report number from a file
     * @input $file - path 
     * 
     * @return $report_id or FALSE
     */
    private function get_report_number_from_file($file) {
        
        //auto detect line endings
        ini_set("auto_detect_line_endings", true);
        
        $fp = fopen($file, 'r');
        
        $report_id = FALSE;
        
        //find report number
        while( ($line = fgetcsv($fp, 0, ',')) !== FALSE){
          if( trim(strtolower($line[0])) == 'report id') {
                $report_id = $line[1];
                break;
          }
        }
        fclose($fp);
        
        //return result
        return $report_id;
    }
}
