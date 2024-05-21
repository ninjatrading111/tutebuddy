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

        #project span, #company span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
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
      <h1>INVOICE {{ $item->order->uuid }} {{ $item->id }}</h1>
      <div id="company" class="clearfix" style="padding-bottom: 15px;">
        <div><span>COMPANY: </span> {{ config('app.company') }}</div>
        <div><span>CONTACT:</span> <a href="">{{ config('site_contact_email') }}</a></div>
      </div>
      <div id="project">
        <div><span>COURSE:</span> {{ $item->course->title }} </div>
        <div><span>FROM:</span> {{ $item->course->teachers->first()->name }} (Instructor) </div>
        <div><span>TO:</span> {{ $item->order->user->name }}</div>
        <div><span>ADDRESS:</span> {{ $item->order->user->address }}, {{ $item->order->user->state }} {{ $item->order->user->zip }}, {{ $item->order->user->country }}</div>
        <div><span>EMAIL:</span> <a href="mailto:{{ $item->order->user->email }}">{{ $item->order->user->email }}</a></div>
        <div><span>DATE:</span> {{ \Carbon\Carbon::now()->parse($item->order->created_at)->format('M d, Y') }}</div>
        <div><span>ORDERID:</span>{{ $item->order->uuid }}</div>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="service">SERVICE</th>
            <th class="desc">DESCRIPTION</th>
            <th>PRICE</th>
            <th>GST</th>
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
                <td class="service">{{ $item->course->title }}</td>
                <td class="desc">{{ $item->course->short_description }}</td>
                <td class="unit">{!! $currency_symbol . $item->price !!}</td>
                <td>{!! $currency_symbol . $item->tax !!}</td>
                <td class="total">{!! $currency_symbol . ( $item->amount + $item->tax ) !!}</td>
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