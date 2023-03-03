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
			<h1 style="font-size: 20px; margin: 0;">Biodata </h1>
		</td>
	</tr>

	<tr>
		<td colspan="2" style="padding: 10px; line-height: 1.5;">

			<h3 style="font-size: 14px; margin: 0;">Dear {{ $data['name'] }}
			</h3>
            <p style="font-size: 13px;">
				Terimakasih telah melakukan registrasi di website kami
				<br/>
			</p>
			<div style="border-top: 1px dashed #ddd; border-bottom:1px dashed #ddd">
				<table style="width: 100%; border-spacing: 0; background:#f2f2f2;">
					<tr>
						<td width="50%" style="font-size: 13px; padding: 25px; line-height: 1.5; border-right:1px dashed #ddd; border-left:1px dashed #ddd">
                            <div>
								<i class="fa fa-file" style="float:left ;font-size:25px; margin-right:10px; margin-top:8px; color:#196A39">
								</i>
								<b style="color:#828180;">Email</b> <b> <br>  &nbsp;&nbsp;&nbsp;{{ $data['email'] }} </b>
							</div>
                            <div>
								<i class="fa fa-file" style="float:left ;font-size:25px; margin-right:10px; margin-top:8px; color:#196A39">
								</i>
								<b style="color:#828180;">Password</b> <b> <br>  &nbsp;&nbsp;&nbsp;{{ $data['password'] }} </b>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<p style="font-size: 15px;">Thanks,</p>
			<span style="font-size: 13px;"><b>Enesis Integrated System</b></span>

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
			copyright 2020.
		</td>
	</tr>
</table>

</body>
</html>
