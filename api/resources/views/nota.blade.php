@php( $issuer = $document->issuer )
@php( $months = ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"] )
@php( $issueDate = strtotime($document->issue_date) )
@php( $body = json_decode($document->body) )
<!DOCTYPE html>
<html>
	<head>
		<title>{{$document->name}}</title>
    	<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://latex.now.sh/style.css">
		<link rel="stylesheet" href="{{ asset('assets/css/document.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/css/nota.css') }}">
	</head>
	<body>
		<table class="document-container">
			<thead class="document-header">
				<tr>
					<th>
						<img src="{{ env('STATIC_FILES_DIRECTORY').'/uploads/'.$document->heading->file->id.'.png' }}">
						<hr>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
						<div class="subheader">
							{{mb_strtoupper($issuer->city, 'UTF-8')}},
							@if($document->issue_date)
								{{date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate)}}
							@else
								@for($i = 0; $i < 9; $i++)
									&nbsp;
								@endfor
							@endif
							<br>
							{{mb_strtoupper($document->documentType->description, 'UTF-8')}} {{mb_strtoupper($issuer->code, 'UTF-8')}} N° 
							@if($document->number)
								{{sprintf('%03s', $document->number)}}
							@else
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							@endif
							<br>
							Ref: {{$document->subject}}
						</div>	
						<div class="destinatary-section">
                            {!! nl2br(e($document->destinatary)) !!}
						</div>
						<div class="body-section">
							{!! $body->cuerpo !!}
						</div>
						<div class="parting-phrase">Atentamente</div>
						<div class="signatures-container">
							@foreach($document->signatures as $signature)
								<div class="stamp">
									{!! $signature->stamp->content !!}	
								</div>								
							@endforeach
						</div>
						<div class="anexos-section">
							@php(chdir(env('STATIC_FILES_DIRECTORY').'/uploads'))							
							@foreach($anexos as $key=>$anexo)
								<div class="anexo-content">
									<p><b>ANEXO {{$hasAnexoUnico ? 'ÚNICO' : $intToRomanNumbers[$key+1]}}</b></p>
									@php($numberOfPages = (int)shell_exec('set -- '.$anexo->file->id.'-* ; echo "$#"'))
									@for($i = 1; $i <= $numberOfPages; $i++)
										<img src="{{ env('STATIC_FILES_DIRECTORY').'/uploads/'.$anexo->file->id.'-'.$i.'.png'}}" class="anexo-img">
									@endfor
								</div>
							@endforeach
						</div>
					</td>
				</tr>
			</tbody>
			<tfoot class="document-footer">
				<tr>
					<td class="document-footer-cell">
						<div class="footer-content">
                            <hr>
							{{$issuer->address}} 
							{{$issuer->postal_code ? ' ('.$issuer->postal_code.')' : ''}}
							, {{$issuer->city}} 
							{{$issuer->province ? ', '.$issuer->province : ''}} 
							{{$issuer->phone ? ' — Tel. '.$issuer->phone : ''}}
							{{$issuer->email ? ' — Email: '.$issuer->email : ''}}
							{{$issuer->website_url ? ' — Sitio web: '.$issuer->website_url : ''}}
					</td>
					<td class="document-footer-empty-cell"></td>
				</tr>
			</tfoot>
		</table>	
		<div class="footer">
			<div class="footer-content">
                <hr>
				{{$issuer->address}} 
				{{$issuer->postal_code ? ' ('.$issuer->postal_code.')' : ''}}
				, {{$issuer->city}} 
				{{$issuer->province ? ', '.$issuer->province : ''}} 
				{{$issuer->phone ? ' — Tel. '.$issuer->phone : ''}}
				{{$issuer->email ? ' — Email: '.$issuer->email : ''}}
				{{$issuer->website_url ? ' — Sitio web: '.$issuer->website_url : ''}}			
			</div>
		</div>
	</body>		
</html>