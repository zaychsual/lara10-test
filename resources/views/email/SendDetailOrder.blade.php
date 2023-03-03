<!DOCTYPE html>
<html>
<head>
	<title>TITLE</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="margin: 20px 0; padding: 20px; background: #f2f2f2; font-family: Arial;">

<table style="width: 85%; height: auto; margin: 20px auto !important; background: #fff; border-spacing: 0; border: 0;">
	<tr>
		<td colspan="2" style="padding: 10px; line-height: 1.5; background: #196A39; text-align: center; color: #fff; border-bottom: 3px solid #ddd;">
			<h1 style="font-size: 20px; margin: 0;">Notifikasi Detail Order </h1>
		</td>
	</tr>

	<tr>
		<td colspan="2" style="padding: 10px; line-height: 1.5;">
			<h3 style="font-size: 14px; margin: 0;">Dear {{ $data['customer']['name'] }}
			</h3>
            <p style="font-size: 13px;">
				Terima Kasih Telah Melakukan pembelian di toko kami, dengan detail
			</p>
			<div style="border-top: 1px dashed #ddd; border-bottom:1px dashed #ddd">
				<table style="width: 100%; background:#f2f2f2;" border=1>
					<thead>
						<tr>
							<th style="font-size: 13px; padding: 10px; line-height: 1.5; border-right:1px dashed #ddd; border-left:1px dashed #ddd">
								<div>
									Produk
								</div>
							</th>
							<th style="font-size: 13px; padding: 10px; line-height: 1.5; border-right:1px dashed #ddd; border-left:1px dashed #ddd">
								<div>
									QTY
								</div>
							</th>
							<th style="font-size: 13px; padding: 10px; line-height: 1.5; border-right:1px dashed #ddd; border-left:1px dashed #ddd">
								<div>
									Price
								</div>
							</th>
							<th style="font-size: 13px; padding: 10px; line-height: 1.5; border-right:1px dashed #ddd; border-left:1px dashed #ddd">
								<div>
									Total Price
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data['details'] as $rows)
							<tr>
								<td>{{ $rows['product']['title'] }}</td>
								<td>{{ formatIdr($rows['product']['price']) }}</td>
								<td>{{ $rows['qty'] }}</td>
								<td>{{ formatIdr($rows['total_price']) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
            @php
                $dataApprove = [
                    'id'         => $data['id'],
                    'status'   => 'approved',
                    'cust'  => $data['customer_id'],
                ];

                $dataReject = [
                    'id'          => $data['id'],
                    'status'    => 'cancel',
                    'cust'   => $data['customer_id'],
                ];
                $approvalHash = base64_encode(json_encode($dataApprove));
                $cancelHash   = base64_encode(json_encode($dataReject));
            @endphp
			<p style="font-size: 15px;">Thanks,</p>
			<span style="font-size: 13px;"><b>Enesis Integrated System</b></span>

		</td>
    </tr>
    <tr>
        <td style="text-align: center">
            <a href="{{url('approval')}}/{{ $approvalHash }} "
               style="background-color:#196a39;/*! border:1px solid #eb7035; */border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:30px;text-align:center;text-decoration:none;width:100px;">Approve</a>
            <a href="{{url('approval')}}/{{ $cancelHash }} "
               style="background-color:red;/*! border:1px solid #eb7035; */border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:30px;text-align:center;text-decoration:none;width:100px;">Cancel</a>
        </td>
    </tr>
    {{-- <tr>
        <td>

					<a href="https://eis.enesis.com/login/candidate"> Click For Detail </a>
        </td>
    </tr> --}}
    <tr>
	</tr>
	<tr>
		<td colspan="2" style="padding: 10px; line-height: 1.5; font-size: 12px; background:#196A39; text-align: center; color:#fff;">
			copyright {{date('Y')}}.
		</td>
	</tr>
</table>

</body>
</html>
