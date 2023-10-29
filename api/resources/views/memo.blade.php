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
		<link rel="stylesheet" href="{{ asset('assets/css/memo.css') }}">
	</head>
	<body>
		<table class="document-container">
			<thead class="document-header">
				<tr>
					<th>
						<img src="{{ env('STATIC_FILES_DIRECTORY').'/uploads/'.$document->heading->id.'.png' }}">
						<hr>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
                        <div class="subheader">
                            <div class="subheader-top">
                                <div class="memo-number">
                                    Memorandum {{$issuer->code}} N° {{sprintf('%03s', $document->number)}}
                                </div> 
                                {{date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate)}} 
                            </div>	
                            <div class="subheader-bottom">
                                <hr>
                                Dirigido a: {!! nl2br(e($document->destinatary)) !!} <br>
                                Producido por: {{$issuer->description}}
                                <hr>
                            </div>
                        </div>
                        <div class="subject">
                            ASUNTO: {{$document->subject}}
                        </div>
						<div class="body-section">
							{!! $body->cuerpo !!}
						</div>
						<div class="parting-phrase">Atentamente</div>
						<div class="signatures"></div>
					</td>
				</tr>
			</tbody>
        </table>
	</body>		
</html>