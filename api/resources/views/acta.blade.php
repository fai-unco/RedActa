@php( $issuer = $document->issuer )
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
		<link rel="stylesheet" href="{{ asset('assets/css/acta.css') }}">
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
                            <p>ACTA {{$issuer->code}} N° {{sprintf('%03s', $document->number)}}/{{substr(date('Y', $issueDate), -2)}}</p>
                            <p>{{$document->subject}}</p>
                        </div>
						<div class="body-section">
							{!! $body->cuerpo !!}
						</div>
						<div class="signatures-container">
							@foreach($document->signatures as $signature)
								<div class="stamp">
									{!! $signature->stamp->content !!}	
								</div>								
							@endforeach
						</div>
						@if($blankPageAtEnd)
							<div class="page-break"></div>
						@endif
						<div class="anexos-section">
							@foreach($anexos as $key=>$anexo)
								<div class="anexo-content">
									<p><b>ANEXO {{$hasAnexoUnico ? 'ÚNICO' : $intToRomanNumbers[$key+1]}}</b></p>
									@php($files = glob(env('STATIC_FILES_DIRECTORY').'/uploads/'.$anexo->file->id.'[-,.]*'))
									@foreach($files as $file)
										<img src="{{ $file }}" class="anexo-img">
									@endforeach
								</div>
							@endforeach
						</div>
					</td>
				</tr>
			</tbody>
        </table>
	</body>		
</html>