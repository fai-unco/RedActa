@php( $issuerName = $document->issuer->description )
@php( $issuerId = $document->issuer->id )
@php( $documentTypeName = $document->documentType->description )
@php( $documentTypeId = $document->documentType->id )
@php( $name = $document->name )
@php( $number = $document->number )
@php( $destinatary = $document->destinatary )
@php( $subject = $document->subject )
@php( $months = ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"] )
@php( $issuePlace = $document->issue_place )
@php( $issueDate = strtotime($document->issue_date) )
@php( $issueDateStr = date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate)  )
@php( $body = json_decode($document->body) )
@php( $headers = [1 => "decanato" , 2 => "consejo_directivo"])

<!DOCTYPE html>
<html>
	<head>
		<title>{{$name}}</title>
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
						<img src="{{ asset('assets/images/headers/'.date('Y', $issueDate).'/'.$headers[$issuerId].'.png') }}">
						<hr>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
						<div class="subheader">
							{{mb_strtoupper($issuePlace, 'UTF-8')}}, {{$issueDateStr}} <br>
							{{mb_strtoupper($documentTypeName, 'UTF-8')}} {{mb_strtoupper($issuerName, 'UTF-8')}} FAIF N° {{sprintf('%03s', $number)}}<br>
							Ref: {{$subject}}
						</div>	
						<div class="destinatary-section">
                            {!! nl2br(e($destinatary)) !!}
						</div>
						<div class="body-section">
							{!! $body->cuerpo !!}
						</div>
						<div class="parting-phrase">Atentamente</div>
						<div class="signatures"></div>
					</td>
				</tr>
			</tbody>
			<tfoot class="document-footer">
				<tr>
					<td class="document-footer-cell">
						<div class="footer-content">
                            <hr>
							Buenos Aires 1400 (8300) – Neuquén — Tel. +54(0299)4490300 (int 650) — email: decanato@fi.uncoma.edu.ar
						</div>
					</td>
					<td class="document-footer-empty-cell"></td>
				</tr>
			</tfoot>
		</table>	
		<div class="footer">
			<div class="footer-content">
                <hr>
				Buenos Aires 1400 (8300) – Neuquén — Tel. +54(0299)4490300 (int 650) — email: decanato@fi.uncoma.edu.ar
			</div>
		</div>
	</body>		
</html>