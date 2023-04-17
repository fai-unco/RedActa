@php( $issuerName = $document->issuer->description )
@php( $issuerId = $document->issuer->id )
@php( $documentTypeName = $document->documentType->description )
@php( $documentTypeId = $document->documentType->id )
@php( $name = $document->name )
@php( $number = $document->number )
@php( $months = ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"] )
@php( $issuePlace = $document->issue_place )
@php( $issueDate = strtotime($document->issue_date) )
@php( $issueDateStr = date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate)  )
@php( $adReferendum = $document->ad_referendum )
@php( $body = json_decode($document->body) )
@php( $headers = [1 => "decanato" , 2 => "consejo_directivo"])
@php( $documentTypeAction = [1 => "RESUELVE" , 2 => "DECLARA", 3 => "DISPONE"])
@php( $issuerInArticlesSectionBeggining = [1 => "DECANO" , 2 => "CONSEJO DIRECTIVO"])
@php( $decanatoData = ["Lic. Guillermo Grosso", "Decano"])

<!DOCTYPE html>
<html>
	<head>
		<title>{{$name}}</title>
    	<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://latex.now.sh/style.css">
		<link rel="stylesheet" href="{{ asset('assets/css/document.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/css/res-dec-disp.css') }}">
	</head>
	<body>
		<table class="document-container">
			<thead class="document-header">
				<tr>
					<th>
						<div class="top-header">
							<img src="{{ asset('assets/images/headers/'.date('Y', $issueDate).'/'.$headers[$issuerId].'.png') }}">
						</div>
						<div class="subheader">
							{{mb_strtoupper($documentTypeName, 'UTF-8')}} {{mb_strtoupper($issuerName, 'UTF-8')}} FAIF N° {{sprintf('%03s', $number)}}<br>
							{{mb_strtoupper($issuePlace, 'UTF-8')}}, {{$issueDateStr}}
						</div>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
						<div class="visto-section indented">
							<b class="visto-section-beggining">VISTO</b>, {!! $body->visto !!}
						</div>	
						<div class="considerando-section indented">
							<b>CONSIDERANDO:</b>
							@foreach($body->considerando as $considerando_item)
								{!! $considerando_item !!} 
							@endforeach
							<p>Por ello</p>
						</div>
						<div class="articulos-section">
							<div class="articulos-section-beggining">
								<b> 
									<div class="no-wrap">
										EL 
										{{ $issuerInArticlesSectionBeggining[$issuerId] }}
										DE LA FACULTAD DE INFORMÁTICA
									</div>
									DE LA UNIVERSIDAD NACIONAL DEL COMAHUE<br> 
									@if($adReferendum)
										AD REFERENDUM DEL CONSEJO DIRECTIVO <br>
									@endif	
									{{ $documentTypeAction[$documentTypeId] }}:
								</b>
							</div>
							@foreach($body->articulos as $articulo)
								<div class="articulo">
									<b class="articulo-number no-wrap">
										ARTÍCULO {{ $loop->index + 1}}°:
									</b>
									<div class="articulo-content">
										{!! $articulo !!} 									
									</div>
								</div>
							@endforeach
						</div>
						@if(!$isCopy)
							<div class="signatures"></div>
						@endif
					</td>
				</tr>
			</tbody>
			@if($isCopy)
				<tfoot class="document-footer">
					<tr>
						<td class="document-footer-cell">
							<p>ES COPIA FIEL</p>
							<div style="display: flex; flex-flow: row-reverse;">
								<p style="text-align: center">
									Fdo. $decanatoData[0] <br>
									$decanatoData[1]
								</p>
							</div>
						</td>
						<td class="document-footer-empty-cell"></td>
					</tr>
				</tfoot>
			@endif
		</table>	
		@if($isCopy)
			<div class="footer">
				<p>ES COPIA FIEL</p>
				<div style="display: flex; flex-flow: row-reverse;">
					<p style="text-align: center">
						Fdo. $decanatoData[0] <br>
						$decanatoData[1]
					</p>
				</div>
			</div>
		@endif
	</body>		
</html>