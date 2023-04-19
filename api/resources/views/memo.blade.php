@php( $issuerName = $document->issuer->description )
@php( $issuerId = $document->issuer->id )
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
@php( $producedBy = [1 => "Despacho Decano FAI" , 2 => "Consejo Directivo"])

<!DOCTYPE html>
<html>
	<head>
		<title>{{$name}}</title>
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
						<img src="{{ asset('assets/images/headers/'.date('Y', $issueDate).'/'.$headers[$issuerId].'.png') }}">
						<hr>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
                        <div class="subheader">
                            <div class="subheader-top">
                                <div class="subheader-top-content">
                                    Memorandum {{$issuerName}} FAI N° {{sprintf('%03s', $number)}} <br>
                                    {{$issueDateStr}} 
                                </div>
                            </div>	
                            <div class="subheader-bottom">
                                <hr>
                                <div class="subheader-bottom-content">
                                    Dirigido a: {!! nl2br(e($destinatary)) !!} <br>
                                    Producido por: {{$producedBy[$issuerId]}}
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="subject">
                            Ref: {{$subject}}
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