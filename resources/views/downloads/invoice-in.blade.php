<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
    </style>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 18cm;
            height: 29.7cm; 
            margin: 0 auto; 
            color: #001028;
            background: #FFFFFF; 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            font-family: Arial;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid  #5D6975;
            border-bottom: 1px solid  #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url("{{ public_path('images/dimension.png') }}");
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
            #company div {
            white-space: nowrap;        
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;        
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ public_path('images/footer-bar-logo.png') }}">
      </div>
      <h1>INVOICE {{ $transaction->transaction_id }}</h1>
      <div id="company" class="clearfix" style="margin-bottom: 15px;">
        <div style="margin-bottom: 15px;"><span>TRANSACTION ID: </span>{{ $transaction->transaction_id }}</div>
        <div><span>Company: </span> {{ config('app.company') }}</div>
        <div><span>Company Email: </span> {{ config('site_contact_email') }}</div>
      </div>
      <div id="project">
        <div><span>FROM: </span> {{ config('app.company') }}</div>
        <div><span>TO: </span> {{ $transaction->user->name }}</div>
        <div><span>ADDRESS: </span> {{ $transaction->user->address }}, {{ $transaction->user->state }} {{ $transaction->user->zip }}, {{ $transaction->user->country }}</div>
        <div><span>EMAIL: </span> <a href="mailto:{{ $transaction->user->email }}">{{ $transaction->user->email }}</a></div>
        <div><span>WITHDRAW DATE: </span> {{ \Carbon\Carbon::now()->parse($transaction->created_at)->format('M d, Y') }}</div>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="service">SERVICE</th>
            <th class="desc">DESCRIPTION</th>
            <th>ACCOUNT FEE</th>
            <th>GST FEE</th>
            <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
            <?php
              $currency_symbol = getCurrency(config('app.currency'))['symbol'];
              if(getCurrency(config('app.currency'))['short_code'] == 'INR') {
                $currency_symbol = '&#8377;';
              }
            ?>
            <tr>
                <td class="service">TuteBuddy Service</td>
                <td class="desc">{{ $transaction->orderItem->course->title }}</td>
                <td>{!! $currency_symbol . $transaction->account_fee !!}</td>
                <td>{!! $currency_symbol . $transaction->account_gst !!}</td>
                <td>{!! $currency_symbol . ( $transaction->account_fee + $transaction->account_gst ) !!}</td>
            </tr>
        </tbody>
      </table>
      <!-- <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
      </div> -->
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>