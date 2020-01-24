@extends('layouts.print_layout')

@section('head')

<div class="table-responsive" style="padding:20px;">       
  <table class="table table-borderless" style="width: 100%;">
    <thead>
      <tr>
        <td rowspan="3" colspan="1" style="width: 20px; text-align: right;"><img src="{{ asset('images/logos/sss.svg.png') }}" alt="SSS Logo" width="100"></td>
      </tr>
      <tr>
        <th rowspan="1" style="text-align: left; font-size:35px;">R-5</th>
        <th colspan="1" style="text-align: center; font-size: 20px; padding-right: 100px;">Monthly Contribution Payment Breakdown</th>
        <td colspan="3" style="text-align: right;">Page No. 1</td>
      </tr>
        @isset($sql)
          @php
            $currdate = date('dd/mm/yy h:i:s A');
            $month = date('M');
            $year = date('Y');
          @endphp
          <tr>
            <th></th>
            <th colspan="1" style="text-align: center; font-size: 20px; padding-right: 100px;" class="mr-5">Calendar Month Ending</th>
            <td colspan="3" style="text-align: right;">{{ $currdate }}</td>
          </tr> 
          <tr>
            <td colspan="6" style="text-align: center; padding-left: 100px;">{{ $month }} {{ $year }}</td>
          </tr>
        @endif
      <tr>
        <td colspan="2">Registered Employer Name: <span style="font-weight: bold;">@isset($m99) {{ $m99[0]->comp_name }} @endif</span></td>
        <td style="text-align: right;">Employer SSS No.</td>
      </tr>
       <tr>
          <td colspan="2"><span style="visibility: hidden;">RERERER</span>Employee Address: <span style="font-weight: bold;">@isset($m99) {{ $m99[0]->comp_addr }} @endif </span></td>
       </tr>
    </thead>
    <tbody style="border: 2px solid #000;">
      <tr>
        <td></td>
        <td>Employee Name</td>
        <td>Employee</td>
        <td>Employer</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>SSS Number</td>
        <td>(Lastname, Firstname, Middlename)</td>
        <td>Contribution</td>
        <td>Contribution</td>
        <td>ECC</td>
        <td>TOTAL</td>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <td style="text-transform: uppercase; font-weight: bold; text-decoration: underline;">@isset($sql) {{ $sql[0]->sss }} @endif</td>
      </tr>
      @if(count($sql) > 0)
      @php
        $sum = 0;
        $sum2 = 0;
        $sum3 = 0;
        $fullname = $sql[0]->firstname . ' ' . $sql[0]->mi . ' ' . $sql[0]->lastname;
      @endphp
        @foreach($sql as $s)
          <tr>
            <td>APPLIED</td>
            <td>{{ $fullname }}</td>
            <td>{{ number_format(($s->sss_cont_b ?? null), 2, '.', ',') }} </td>
            <td>{{ number_format(($s->sss_cont_c ?? null), 2, '.', ',') }}</td>
            <td>{{ number_format(($s->sss_cont_d ?? null), 2, '.', ',') }}</td>
            @php
              $sum += ($s->sss_cont_b ?? null); //sums up all the employee contribution
              $sum2 +=  ($s->sss_cont_c ?? null); //sums up all the employer contribution
              $sum3 += ($s->sss_cont_d ?? null); // sums up all the ecc

              $add1 = ($s->sss_cont_b ?? null) + ($s->sss_cont_c ?? null) + ($s->cont_d ?? null) //addition of employee contribution + employer contribution
            @endphp
            <td>{{ number_format($add1, 2, '.', ',') }}</td>
          </tr>
        @endforeach
        @php
           $add2 = $sum + $sum2 + $sum3; //adds up the sum of employee contribution, employer contribution and ecc
        @endphp  
      <tr>
        <td colspan="2" style="font-weight: bold; text-align: right;">Department Total:</td>
        <td style="border-top: 2px solid">{{ number_format($sum, 2, '.', ',') }}</td>
        <td style="border-top: 2px solid">{{ number_format($sum2, 2, '.', ',') }}</td>
        <td style="border-top: 2px solid">{{ number_format($sum3, 2, '.', ',') }}</td>
        <td style="border-top: 2px solid">{{ number_format($add2, 2, '.', ',') }}</td>
      </tr>
      @else
      <h1>NO DATA</h1>
      @endif
    </tbody>
  </table>
</div>

@endsection

@section('script-body')
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript">
    PrintPage();
  </script>
@endsection
